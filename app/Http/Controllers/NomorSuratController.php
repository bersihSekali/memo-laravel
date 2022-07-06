<?php

namespace App\Http\Controllers;

use App\Models\BidangCabang;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Penomoran;
use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\User;
use App\Models\SatuanKerja;
use App\Models\TujuanDepartemen;
use App\Models\TujuanKantorCabang;
use App\Models\TujuanSatuanKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class NomorSuratController extends Controller
{
    public function __construct()
    {
        $this->suratKeluar = new SuratKeluar;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // data user
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        //cek user        
        if ($user->levelTable->golongan == 7) {
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('draft', 0)
                ->latest()->get();
        } elseif ($user->levelTable->golongan <= 6) {
            $mails = SuratKeluar::where('departemen_asal', $user->departemen)
                ->orWhere('internal', 2)
                ->where('draft', 0)
                ->latest()->get();
        }

        //untuk cek all flag, Untuk view column tujuan 
        $tujuan = $this->suratKeluar->columnTujuan($user);

        $datas = [
            'title' => 'Daftar Semua Surat',
            'datas' => $mails,
            'tujuanDepartemens' => $tujuan['tujuanDepartemen'],
            'tujuanSatkers' => $tujuan['tujuanSatker'],
            'tujuanCabangs' => $tujuan['tujuanCabangs'],
            'seluruhDepartemenMemoIds' => $tujuan['seluruhDepartemenMemoId'],
            'seluruhSatkerMemoIds' => $tujuan['seluruhSatkerMemoId'],
            'seluruhCabangMemoIds' => $tujuan['seluruhCabangMemoId'],
            'users' => $user
        ];

        return view('nomorSurat.index', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $pengganti = User::all();

        $departemen = Departemen::where('satuan_kerja', $user->satuan_kerja)->get();
        $satuanKerja = SatuanKerja::where('id', '!=', 1)
            ->where('satuan_kerja', '!=', 'CABANG JABODETABEK')
            ->where('satuan_kerja', '!=', 'CABANG NON JABODETABEK')->get();
        $cabang = Cabang::where('id', '!=', 1)->get();

        $datas = [
            'title' => 'Tambah Surat',
            'satuanKerjas' => $satuanKerja,
            'departemens' => $departemen,
            'cabangs' => $cabang,
            'users' => $user,
            'penggantis' => $pengganti
        ];

        return view('nomorSurat.create', $datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($_POST['simpan'])) {
            $id = Auth::id();
            $user = User::find($id);

            $satuanKerja = SatuanKerja::where('satuan_kerja', '!=', 'CABANG JABODETABEK')
                ->where('satuan_kerja', '!=', 'CABANG NON JABODETABEK')->get();
            $departemenInternal = Departemen::where('satuan_kerja', 2)->get();
            $cabang = Cabang::all();

            $nomorTersedia = Penomoran::latest()->pluck('nomor_surat')->toArray();
            $request->flash();

            $validated = $request->validate([
                'created_by' => 'required',
                'nomor_surat' => 'required|unique:surat_keluars',
                'satuan_kerja_asal' => 'required',
                'perihal' => 'required',
                'lampiran' => 'mimes:pdf',
                'kriteria' => 'required',
                'isi' => 'required',
            ]);
            $validated['departemen_asal'] = $request->departemen_asal;
            $validated['otor2_by'] = $request->tunjuk_otor2_by;
            $validated['otor1_by'] = $request->tunjuk_otor1_by;
            $validated['internal'] = $request->tipe_surat;
            $validated['draft'] = 0;

            if (!in_array($validated['nomor_surat'], $nomorTersedia)) {
                return redirect('/nomorSurat/create')->with('error', 'Pembuatan surat gagal, harap ambil nomor terlebih dahulu');
            }

            $tujuanUnitKerja = $request->tujuan_unit_kerja;
            $tujuanKantorCabang = $request->tujuan_kantor_cabang;
            $tujuanInternal = $request->tujuan_internal;

            // get file and store
            if ($request->file('lampiran')) {
                $file = $request->file('lampiran');
                $originalFileName = $file->getClientOriginalName();
                $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
                $fileName = date("YmdHis") . '_' . $fileName;
                $validated['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
            }

            $create = SuratKeluar::create($validated);
            // Return gagal simpan
            if (!$create) {
                return redirect('/nomorSurat/create')->with('error', 'Pembuatan surat gagal');
            }

            $idSurat = $create->id;

            // Update audit trail
            $audit = [
                'users' => $user->id,
                'aktifitas' => config('constants.CREATE'),
                'deskripsi' => $idSurat
            ];
            storeAudit($audit);

            // Seluruh tujuan internal
            if ($tujuanInternal[0] == 'internal') {
                foreach ($departemenInternal as $item) {
                    if ($item->id != $user->departemen) {
                        TujuanDepartemen::create([
                            'memo_id' => $idSurat,
                            'departemen_id' => $item->id,
                            'all_flag' => 1
                        ]);
                    }
                }
            } else {
                if ($tujuanInternal != null) {
                    foreach ($tujuanInternal as $item) {
                        TujuanDepartemen::create([
                            'memo_id' => $idSurat,
                            'departemen_id' => $item,
                            'all_flag' => 0
                        ]);
                    }
                }
            }

            //TujuanCabang
            if ($tujuanKantorCabang[0] == 'kantor_cabang') {
                foreach ($cabang as $item) {
                    if ($item->id != $user->cabang) {
                        TujuanKantorCabang::create([
                            'memo_id' => $idSurat,
                            'cabang_id' => $item->id,
                            'all_flag' => 1
                        ]);
                    }
                }
            } else {
                if ($tujuanKantorCabang != null)
                    foreach ($tujuanKantorCabang as $item) {
                        TujuanKantorCabang::create([
                            'memo_id' => $idSurat,
                            'cabang_id' => $item,
                            'all_flag' => 0
                        ]);
                    }
            }
            // Tujuan unit kerja
            if ($tujuanUnitKerja[0] == 'unit_kerja') {
                foreach ($satuanKerja as $item) {
                    if ($item->id != $user->satuan_kerja) {
                        TujuanSatuanKerja::create([
                            'memo_id' => $idSurat,
                            'satuan_kerja_id' => $item->id,
                            'all_flag' => 1
                        ]);
                    }
                }
            } else {
                if ($tujuanUnitKerja != null)
                    foreach ($tujuanUnitKerja as $item) {
                        TujuanSatuanKerja::create([
                            'memo_id' => $idSurat,
                            'satuan_kerja_id' => $item,
                            'all_flag' => 0
                        ]);
                    }
            }

            return redirect('/nomorSurat')->with('success', 'Pembuatan surat berhasil');
        } elseif (isset($_POST['lihat'])) {
            $satuanKerja = SatuanKerja::where('satuan_kerja', '!=', 'CABANG JABODETABEK')
                ->where('satuan_kerja', '!=', 'CABANG NON JABODETABEK')->get();
            $departemenInternal = Departemen::where('satuan_kerja', 2)->get();
            $cabang = Cabang::all();

            $validated = $request->validate([
                'created_by' => 'required',
                'nomor_surat' => 'required|unique:surat_keluars',
                'satuan_kerja_asal' => 'required',
                'perihal' => 'required',
                'lampiran' => 'mimes:pdf',
                'kriteria' => 'required',
            ]);
            $validated['departemen_asal'] = $request->departemen_asal;
            $validated['tujuan_unit_kerja'] = $request->tujuan_unit_kerja;
            $validated['tujuan_kantor_cabang'] = $request->tujuan_kantor_cabang;
            $validated['tujuan_internal'] = $request->tujuan_internal;
            $validated['otor2_by'] = $request->tunjuk_otor2_by;
            $validated['otor1_by'] = $request->tunjuk_otor1_by;
            $validated['isi'] = $request->editordata;
            $validated['internal'] = $request->tipe_surat;
            $validated['draft'] = 0;

            if ($validated['tujuan_unit_kerja'] == 'unit_kerja') {
                $tujuanSatker = ['Segenap Unit Kerja Kantor Pusat'];
            } else {
                $tujuanSatker = $satuanKerja->whereIn('id', $validated['tujuan_unit_kerja'])->pluck('satuan_kerja')->toArray();
            }

            if ($validated['tujuan_kantor_cabang'] == 'kantor_cabang') {
                $tujuanCabang = ['Segenap Kantor Layanan'];
            } else {
                $tujuanCabang = $cabang->whereIn('id', $validated['tujuan_kantor_cabang'])->pluck('cabang')->toArray();
            }

            if ($validated['tujuan_internal'] == 'kantor_cabang') {
                $tujuanDepartemen = ['Seluruh Departemen di SKTILOG'];
            } else {
                $tujuanDepartemen = $departemenInternal->whereIn('id', $validated['tujuan_internal'])->pluck('departemen')->toArray();
            }

            if ($validated['internal'] == 1) {
                $dari = $departemenInternal->find($validated['departemen_asal'])->departemen;
            } elseif ($validated['satuan_kerja_asal']) {
                $dari = $satuanKerja->find($validated['satuan_kerja_asal'])->satuan_kerja;
            } elseif ($validated['cabang_asal']) {
                $dari = 'Cabang' . ' ' . $cabang->find($validated['cabang_asal'])->cabang;
            }

            $ttd1 = User::find($validated['otor1_by']);
            $ttd2 = User::find($validated['otor2_by']);
            $jabatanTtd1 = User::find($validated['otor1_by'])->levelTable['jabatan'];
            $jabatanTtd2 = User::find($validated['otor2_by'])->levelTable['jabatan'];

            $pdf = PDF::loadView('preview/preview', [
                'title' => 'Pratinjau',
                'requests' => $validated,
                'tujuanSatkers' => $tujuanSatker,
                'tujuanCabangs' => $tujuanCabang,
                'tujuanDepartemens' => $tujuanDepartemen,
                'dari' => $dari,
                'ttd1' => $ttd1,
                'ttd2' => $ttd2,
                'jabatanTtd1' => $jabatanTtd1,
                'jabatanTtd2' => $jabatanTtd2
            ])->setPaper('a4', 'portrait');

            $pdf->output();
            $dompdf = $pdf->getDomPDF();

            $canvas = $dompdf->get_canvas();

            $cpdf = $canvas->get_page_count();

            $canvas->page_text(550, 800, "{PAGE_NUM}/{PAGE_COUNT}", null, 10, array(0, 0, 0));

            return $pdf->stream();
        } elseif (isset($_POST['draft'])) {
            $id = Auth::id();
            $user = User::find($id);

            $satuanKerja = SatuanKerja::where('satuan_kerja', '!=', 'CABANG JABODETABEK')
                ->where('satuan_kerja', '!=', 'CABANG NON JABODETABEK')->get();
            $departemenInternal = Departemen::where('satuan_kerja', 2)->get();
            $cabang = Cabang::all();

            $nomorTersedia = Penomoran::latest()->pluck('nomor_surat')->toArray();
            $request->flash();

            $validated = $request->validate([
                'created_by' => 'required',
                'nomor_surat' => 'required|unique:surat_keluars',
                'satuan_kerja_asal' => 'required',
                'perihal' => 'required',
                'lampiran' => 'mimes:pdf',
                'kriteria' => 'required',
            ]);
            $validated['departemen_asal'] = $request->departemen_asal;
            $validated['tujuan_unit_kerja'] = $request->tujuan_unit_kerja;
            $validated['tujuan_kantor_cabang'] = $request->tujuan_kantor_cabang;
            $validated['tujuan_internal'] = $request->tujuan_internal;
            $validated['otor2_by'] = $request->tunjuk_otor2_by;
            $validated['otor1_by'] = $request->tunjuk_otor1_by;
            $validated['isi'] = $request->editordata;
            $validated['internal'] = $request->tipe_surat;
            $validated['draft'] = 1;

            if (!in_array($validated['nomor_surat'], $nomorTersedia)) {
                return redirect('/nomorSurat/create')->with('error', 'Pembuatan surat gagal, harap ambil nomor terlebih dahulu');
            }

            $tujuanUnitKerja = $request->tujuan_unit_kerja;
            $tujuanKantorCabang = $request->tujuan_kantor_cabang;
            $tujuanInternal = $request->tujuan_internal;

            // get file and store
            if ($request->file('lampiran')) {
                $file = $request->file('lampiran');
                $originalFileName = $file->getClientOriginalName();
                $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
                $fileName = date("YmdHis") . '_' . $fileName;
                $validated['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
            }

            $create = SuratKeluar::create($validated);
            // Return gagal simpan
            if (!$create) {
                return redirect('/nomorSurat/create')->with('error', 'Pembuatan surat gagal');
            }

            $idSurat = $create->id;

            // Update audit trail
            $audit = [
                'users' => $user->id,
                'aktifitas' => config('constants.CREATE'),
                'deskripsi' => $idSurat
            ];
            storeAudit($audit);

            // Seluruh tujuan internal
            if ($tujuanInternal[0] == 'internal') {
                foreach ($departemenInternal as $item) {
                    if ($item->id != $user->departemen) {
                        TujuanDepartemen::create([
                            'memo_id' => $idSurat,
                            'departemen_id' => $item->id,
                            'all_flag' => 1
                        ]);
                    }
                }
            } else {
                if ($tujuanInternal != null) {
                    foreach ($tujuanInternal as $item) {
                        TujuanDepartemen::create([
                            'memo_id' => $idSurat,
                            'departemen_id' => $item,
                            'all_flag' => 0
                        ]);
                    }
                }
            }

            //TujuanCabang
            if ($tujuanKantorCabang[0] == 'kantor_cabang') {
                foreach ($cabang as $item) {
                    if ($item->id != $user->cabang) {
                        TujuanKantorCabang::create([
                            'memo_id' => $idSurat,
                            'cabang_id' => $item->id,
                            'all_flag' => 1
                        ]);
                    }
                }
            } else {
                if ($tujuanKantorCabang != null)
                    foreach ($tujuanKantorCabang as $item) {
                        TujuanKantorCabang::create([
                            'memo_id' => $idSurat,
                            'cabang_id' => $item,
                            'all_flag' => 0
                        ]);
                    }
            }
            // Tujuan unit kerja
            if ($tujuanUnitKerja[0] == 'unit_kerja') {
                foreach ($satuanKerja as $item) {
                    if ($item->id != $user->satuan_kerja) {
                        TujuanSatuanKerja::create([
                            'memo_id' => $idSurat,
                            'satuan_kerja_id' => $item->id,
                            'all_flag' => 1
                        ]);
                    }
                }
            } else {
                if ($tujuanUnitKerja != null)
                    foreach ($tujuanUnitKerja as $item) {
                        TujuanSatuanKerja::create([
                            'memo_id' => $idSurat,
                            'satuan_kerja_id' => $item,
                            'all_flag' => 0
                        ]);
                    }
            }

            return redirect('/draft')->with('success', 'Draft berhasil disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mails = SuratKeluar::find($id);
        $datas = [
            'title' => 'Detil Surat',
            'datas' => $mails
        ];

        return view('nomorSurat.index', $datas);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) // Soft Delete
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);
        $update[] = $datas['deleted_by'] = $user->name;

        Storage::delete($datas->lampiran);

        $datas['no_urut'] = 0;
        array_push($update, $datas['no_urut']);

        // Update audit trail
        $audit = [
            'users' => $user->id,
            'aktifitas' => config('constants.DELETE'),
            'deskripsi' => $datas['id']
        ];
        storeAudit($audit);

        $datas->update($update);

        $datas->delete();

        return redirect('/nomorSurat')->with('success', 'Surat berhasil dihapus');
    }

    public function getSatuanKerja(Request $request)
    {
        echo $skid = $request->post('skid');
        $departemen = DB::table('departemens')->where('satuan_kerja', $skid)->get();
        $html = '<option value=""> ---- </option>';
        foreach ($departemen as $key) {
            $html .= '<option value="' . $key->id . '">' . $key->departemen . '</option>';
        }
        echo $html;
    }

    public function getLevel(Request $request)
    {
        echo $lid = $request->post('lid');
        $html = '';
        if ($lid == 2) {
            $html .= '<div class="mb-3">
            <label for="departemen" class="form-label">Department</label>
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="departemen" id="departemen">
                <option value=""> ---- </option>
            </select>
        </div>';
        }

        echo $html;
    }

    public function listSuratHapus() // get Surat deleted only
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $userLog = User::get();

        $mails = SuratKeluar::onlyTrashed()->get();

        //untuk cek all flag, Untuk view column tujuan 
        $tujuan = $this->coba->columnTujuan($user);

        $datas = [
            'users' => $user,
            'datas' => $mails,
            'userLogs' => $userLog,
            'tujuanDepartemens' => $tujuan['tujuanDepartemen'],
            'tujuanSatkers' => $tujuan['tujuanSatker'],
            'tujuanCabangs' => $tujuan['tujuanCabangs'],
            'seluruhDepartemenMemoIds' => $tujuan['seluruhDepartemenMemoId'],
            'seluruhSatkerMemoIds' => $tujuan['seluruhSatkerMemoId'],
            'seluruhCabangMemoIds' => $tujuan['seluruhCabangMemoId']
        ];

        return view('nomorSurat.deleted', $datas);
    }

    public function hapusPermanen() // Force Delete
    {
        $user_id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $user_id)->first();
        $datas = SuratKeluar::onlyTrashed()->get();

        foreach ($datas as $data) {
            Storage::delete($data->lampiran);

            // Hapus child tujuan cabang
            TujuanKantorCabang::select('memo_id')->where('memo_id', $data->id)->forceDelete();

            // Hapus tujuan departemen
            TujuanDepartemen::select('memo_id')->where('memo_id', $data->id)->forceDelete();

            // Hapus tujuan satuan kerja
            TujuanSatuanKerja::select('memo_id')->where('memo_id', $data->id)->forceDelete();
        }

        // Update audit trail
        $audit = [
            'users' => $user->id,
            'aktifitas' => 'config.constants.FORCE_DELETE',
            'deskripsi' => null
        ];
        storeAudit($audit);

        SuratKeluar::where('deleted_at', '!=', null)->forceDelete();

        return redirect('/nomorSurat/suratHapus')->with('success', 'Surat berhasil dibersihkan');
    }

    public function allSurat() // get all Surat
    {
        $id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $id)->first();
        $mails = SuratKeluar::select('id', 'created_at', 'otor1_by', 'otor2_by', 'otor1_by_pengganti', 'otor2_by_pengganti', 'created_by', 'tanggal_otor2', 'tanggal_otor1', 'nomor_surat', 'perihal', 'satuan_kerja_asal', 'departemen_asal', 'lampiran', 'pesan_tolak', 'internal', 'status', 'deleted_by', 'deleted_at')
            ->withTrashed()->latest()->get();
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();

        //untuk cek all flag, Untuk view column tujuan 
        $tujuan = $this->coba->columnTujuan($user);

        $datas = [
            'users' => $user,
            'tujuanDepartemens' => $tujuan['tujuanDepartemen'],
            'tujuanSatkers' => $tujuan['tujuanSatker'],
            'tujuanCabangs' => $tujuan['tujuanCabangs'],
            'userLogs' => $userLog,
            'datas' => $mails,
            'seluruhDepartemenMemoIds' => $tujuan['seluruhDepartemenMemoId'],
            'seluruhSatkerMemoIds' => $tujuan['seluruhSatkerMemoId'],
            'seluruhCabangMemoIds' => $tujuan['seluruhCabangMemoId'],
        ];

        return view('nomorSurat.all', $datas);
    }
}
