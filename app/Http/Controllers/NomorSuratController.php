<?php

namespace App\Http\Controllers;

use App\Models\BidangCabang;
use App\Models\Cabang;
use App\Models\Departemen;
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


class NomorSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $id)->first();
        if ($user->levelTable->golongan == 7) {
            $mails = SuratKeluar::select('id', 'created_at', 'otor1_by', 'otor2_by', 'otor1_by_pengganti', 'otor2_by_pengganti', 'created_by', 'tanggal_otor2', 'tanggal_otor1', 'nomor_surat', 'perihal', 'satuan_kerja_asal', 'departemen_asal', 'lampiran', 'pesan_tolak', 'internal', 'status', 'deleted_by', 'deleted_at')
                ->where('satuan_kerja_asal', $user->satuan_kerja)
                ->latest()->get();
        } elseif ($user->levelTable->golongan <= 6) {
            $mails = SuratKeluar::select('id', 'created_at', 'otor1_by', 'otor2_by', 'otor1_by_pengganti', 'otor2_by_pengganti', 'created_by', 'tanggal_otor2', 'tanggal_otor1', 'nomor_surat', 'perihal', 'satuan_kerja_asal', 'departemen_asal', 'lampiran', 'pesan_tolak', 'internal', 'status', 'deleted_by', 'deleted_at')
                ->where('departemen_asal', $user->departemen)
                ->latest()->get();
        }

        // Untuk view column tujuan
        $memoIdSatker = SuratKeluar::select('id', 'satuan_kerja_asal')
            ->where('satuan_kerja_asal', $user->satuan_kerja)
            ->pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::select('id', 'memo_id', 'departemen_id')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::select('id', 'memo_id', 'satuan_kerja_id')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::select('id', 'memo_id', 'cabang_id', 'bidang_id')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

        //untuk cek all flag
        $seluruhDepartemenMemoId = $tujuanDepartemen->where('departemen_id', 1)->pluck('memo_id')->toArray();
        $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
        $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();

        $datas = [
            'title' => 'Daftar Semua Surat',
            'datas' => $mails,
            'tujuanDepartemens' => $tujuanDepartemen,
            'tujuanSatkers' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'users' => $user,
            'seluruhDepartemenMemoIds' => $seluruhDepartemenMemoId,
            'seluruhSatkerMemoIds' => $seluruhSatkerMemoId,
            'seluruhCabangMemoIds' => $seluruhCabangMemoId,
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
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $id)->first();
        // $pengganti = User::all();
        $pengganti = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();

        $departemen = Departemen::select('id', 'satuan_kerja', 'inisial', 'grup')->get();
        $satuanKerja = SatuanKerja::select('id', 'inisial', 'grup')->get();
        $cabang = Cabang::select('id', 'cabang')->get();
        $bidangCabang = BidangCabang::select('id', 'bidang', 'cabang_id')->get();
        // $kantorCabang = Departemen::where('grup', 2)->get();
        $departemenDireksi = Departemen::select('id', 'satuan_kerja', 'inisial', 'grup')
            ->where('grup', 4)->get();

        $datas = [
            'title' => 'Tambah Surat',
            'satuanKerjas' => $satuanKerja,
            'departemens' => $departemen,
            'cabangs' => $cabang,
            'bidangCabangs' => $bidangCabang,
            'departemenDireksis' => $departemenDireksi,
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
        $id = Auth::id();
        $user = User::find($id);

        $satuanKerja = SatuanKerja::all();
        $departemenInternal = Departemen::where('satuan_kerja', 2)->get();
        $cabang = Cabang::all();
        $bidangCabang = BidangCabang::all();

        $validated = $request->validate([
            'created_by' => 'required',
            'satuan_kerja_asal' => 'required',
            'perihal' => 'required',
            'lampiran' => 'mimes:pdf',
        ]);
        $validated['departemen_asal'] = $request->departemen_asal;
        $validated['otor2_by_pengganti'] = $request->tunjuk_otor2_by;
        $validated['otor1_by_pengganti'] = $request->tunjuk_otor1_by;
        $validated['internal'] = $request->tipe_surat;

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
            TujuanDepartemen::create([
                'memo_id' => $idSurat,
                'departemen_id' => 1,
                'all_flag' => 1
            ]);
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

        $cabangBesar = array();
        $bidang = array();
        if ($tujuanKantorCabang != null) {
            foreach ($tujuanKantorCabang as $item) {
                if (substr($item, 0, 1) == 'S') {
                    $item = ltrim($item, $item[0]);
                    array_push($cabangBesar, $item);
                } elseif ($item == 'kantor_cabang') {
                    continue;
                } else {
                    array_push($bidang, $item);
                }
            }
        }

        // Seluruh tujuan kantor cabang
        if ($tujuanKantorCabang[0] == 'kantor_cabang') {
            foreach ($cabang as $item) {
                TujuanKantorCabang::create([
                    'memo_id' => $idSurat,
                    'cabang_id' => $item->id,
                    'all_flag' => 1
                ]);
            }
            foreach ($bidangCabang as $item) {
                TujuanKantorCabang::create([
                    'memo_id' => $idSurat,
                    'bidang_id' => $item->id,
                    'all_flag' => 1
                ]);
            }
        }

        if (count($cabangBesar) != 0) {
            foreach ($cabangBesar as $item) {
                TujuanKantorCabang::create([
                    'memo_id' => $idSurat,
                    'cabang_id' => $item,
                    'all_flag' => 1
                ]);
                $bidangLoop = BidangCabang::where('cabang_id', $item)->get();
                foreach ($bidangLoop as $item_bidang) {
                    TujuanKantorCabang::create([
                        'memo_id' => $idSurat,
                        'bidang_id' => $item_bidang->id,
                        'all_flag' => 1
                    ]);
                }
            }
        }


        if (count($bidang) != 0) {
            foreach ($bidang as $item) {
                TujuanKantorCabang::create([
                    'memo_id' => $idSurat,
                    'bidang_id' => $item,
                    'all_flag' => 0
                ]);
            }
        }

        // Tujuan unit kerja
        if ($tujuanUnitKerja[0] == 'unit_kerja') {
            foreach ($satuanKerja as $item) {
                TujuanSatuanKerja::create([
                    'memo_id' => $idSurat,
                    'satuan_kerja_id' => $item->id,
                    'all_flag' => 1
                ]);
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
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $id)->first();

        $mails = SuratKeluar::onlyTrashed()->get();

        $datas = [
            'users' => $user,
            'datas' => $mails
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

        SuratKeluar::whereNotNull('deleted_at')->forceDelete();

        return redirect('/nomorSurat/suratHapus')->with('success', 'Surat berhasil dibersihkan');
    }

    public function allSurat() // get all Surat
    {
        $id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $id)->first();
        $mails = SuratKeluar::withTrashed()->latest()->get();
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();

        // Untuk view column tujuan
        $memoIdSatker = SuratKeluar::select('id', 'satuan_kerja_asal')
            ->where('satuan_kerja_asal', $user->satuan_kerja)
            ->pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::select('id', 'memo_id', 'departemen_id', 'all_flag')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::select('id', 'memo_id', 'satuan_kerja_id', 'all_flag')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::select('id', 'memo_id', 'cabang_id', 'bidang_id', 'all_flag')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

        $datas = [
            'users' => $user,
            'tujuanDepartemens' => $tujuanDepartemen,
            'tujuanSatkers' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'userLogs' => $userLog,
            'datas' => $mails
        ];

        return view('nomorSurat.all', $datas);
    }
}
