<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Auth;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use App\Models\TujuanKantorCabang;
use App\Models\TujuanSatuanKerja;
use App\Models\Cabang;
use App\Models\BidangCabang;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        if ($user->cabang) {
            $mails = SuratKeluar::where('cabang_asal', $user->cabang)
                ->latest()->get();
        } elseif ($user->satuan_kerja) {
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->latest()->get();
        }

        // Untuk view column tujuan
        $memoIdSatker = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)->orWhere('cabang_asal', $user->cabang)
            ->pluck('id')->toArray();
        $tujuanSatker = TujuanSatuanKerja::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

        //untuk cek all flag
        $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
        $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();

        $datas = [
            'title' => 'Daftar Semua Surat',
            'datas' => $mails,
            'tujuanSatkers' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'users' => $user,
            'seluruhSatkerMemoIds' => $seluruhSatkerMemoId,
            'seluruhCabangMemoIds' => $seluruhCabangMemoId,
        ];

        return view('suratKeluar.index', $datas);
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

        $departemen = Departemen::all();
        $satuanKerja = SatuanKerja::where('id', '!=', 1)
            ->where('satuan_kerja', '!=', 'CABANG JABODETABEK')
            ->where('satuan_kerja', '!=', 'CABANG NON JABODETABEK')->get();
        $cabang = Cabang::select('id', 'cabang')
            ->where('id', '!=', 1)->get();
        $bidangCabang = BidangCabang::all();
        // $kantorCabang = Departemen::where('grup', 2)->get();
        $departemenDireksi = Departemen::where('grup', 4)->get();

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

        return view('suratKeluar.create', $datas);
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

            $satuanKerja = SatuanKerja::all();
            $cabang = Cabang::all();
            $bidangCabang = BidangCabang::all();

            $validated = $request->validate([
                'created_by' => 'required',
                'nomor_surat' => 'required',
                'perihal' => 'required',
                'lampiran' => 'mimes:pdf',
            ]);
            $validated['cabang_asal'] = $request->cabang_asal;
            $validated['satuan_kerja_asal'] = $request->satuan_kerja_asal;
            $validated['otor2_by_pengganti'] = $request->tunjuk_otor2_by;
            $validated['otor1_by_pengganti'] = $request->tunjuk_otor1_by;
            $validated['internal'] = 2;

            $tujuanUnitKerja = $request->tujuan_unit_kerja;
            $tujuanKantorCabang = $request->tujuan_kantor_cabang;

            // get file and store
            if ($request->file('lampiran')) {
                $file = $request->file('lampiran');
                $originalFileName = $file->getClientOriginalName();
                $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
                $fileName = date("YmdHis") . '_' . $fileName;
                $validated['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
            }
            // dd($tujuanUnitKerja);
            // dd(count($tujuanUnitKerja));

            $create = SuratKeluar::create($validated);
            // Return gagal simpan
            if (!$create) {
                return redirect('/suratKeluar/create')->with('error', 'Pembuatan surat gagal');
            }

            $idSurat = $create->id;

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
                    if ($item->cabang_id != $user->cabang) {
                        TujuanKantorCabang::create([
                            'memo_id' => $idSurat,
                            'cabang_id' => $item->id,
                            'all_flag' => 1
                        ]);
                    }
                }
                // foreach ($bidangCabang as $item) {
                //     TujuanKantorCabang::create([
                //         'memo_id' => $idSurat,
                //         'bidang_id' => $item->id,
                //         'all_flag' => 1
                //     ]);
                // }
            }

            if (count($cabangBesar) != 0) {
                foreach ($cabangBesar as $item) {
                    TujuanKantorCabang::create([
                        'memo_id' => $idSurat,
                        'cabang_id' => $item,
                        'all_flag' => 1
                    ]);
                    // $bidangLoop = BidangCabang::where('cabang_id', $item)->get();
                    // foreach ($bidangLoop as $item_bidang) {
                    //     TujuanKantorCabang::create([
                    //         'memo_id' => $idSurat,
                    //         'bidang_id' => $item_bidang->id,
                    //         'all_flag' => 1
                    //     ]);
                    // }
                }
            }


            // if (count($bidang) != 0) {
            //     foreach ($bidang as $item) {
            //         TujuanKantorCabang::create([
            //             'memo_id' => $idSurat,
            //             'bidang_id' => $item,
            //             'all_flag' => 0
            //         ]);
            //     }
            // }

            // Tujuan unit kerja
            if ($tujuanUnitKerja[0] == 'unit_kerja') {
                foreach ($satuanKerja as $item) {
                    if ($item->satuan_kerja != $user->satuan_kerja) {
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

            return redirect('/suratKeluar')->with('success', 'Pembuatan surat berhasil');
        } else if (isset($_POST['lihat'])) {
            $satuanKerja = SatuanKerja::all();
            $cabang = Cabang::all();

            $validated = $request->validate([
                'created_by' => 'required',
                'nomor_surat' => 'required',
                'perihal' => 'required',
                'lampiran' => 'mimes:pdf',
            ]);
            $validated['cabang_asal'] = $request->cabang_asal;
            $validated['satuan_kerja_asal'] = $request->satuan_kerja_asal;
            $validated['tujuan_unit_kerja'] = $request->tujuan_unit_kerja;
            $validated['isi'] = $request->editordata;
            $validated['otor2_by_pengganti'] = $request->tunjuk_otor2_by;
            $validated['otor1_by_pengganti'] = $request->tunjuk_otor1_by;
            $validated['internal'] = 2;

            if ($validated['tujuan_unit_kerja'] == 'unit_kerja') {
                $tujuanSatker = 'Seluruh Unit Kerja Kantor Pusat';
            } elseif ($validated['tujuan_unit_kerja']) {
                $tujuanSatker = $satuanKerja->whereIn('id', $validated['tujuan_unit_kerja'])->pluck('satuan_kerja')->toArray();
            }

            $dari = $satuanKerja->find($validated['satuan_kerja_asal']);
            $ttd1 = User::find($validated['otor1_by_pengganti']);
            $ttd2 = User::find($validated['otor2_by_pengganti']);
            $jabatanTtd1 = User::find($validated['otor1_by_pengganti'])->levelTable['jabatan'];
            $jabatanTtd2 = User::find($validated['otor2_by_pengganti'])->levelTable['jabatan'];

            $pdf = PDF::loadView('preview/preview', [
                'title' => 'Pratinjau',
                'requests' => $validated,
                'tujuanSatkers' => $tujuanSatker,
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
        } else {
            //no button pressed
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
        return false;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
