<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratKeluar;
use App\Models\TujuanSatuanKerja;
use App\Models\TujuanKantorCabang;
use App\Models\SatuanKerja;
use App\Models\Cabang;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Departemen;
use App\Models\TujuanDepartemen;
use Illuminate\Support\Facades\Storage;


class DraftController extends Controller
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
                ->where('draft', 1)
                ->where('created_by', $id)
                ->latest()->get();
        } elseif ($user->satuan_kerja) {
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('draft', 1)
                ->where('created_by', $id)
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
            'title' => 'Daftar Draft',
            'datas' => $mails,
            'tujuanSatkers' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'users' => $user,
            'seluruhSatkerMemoIds' => $seluruhSatkerMemoId,
            'seluruhCabangMemoIds' => $seluruhCabangMemoId,
        ];

        return view('draft.index', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $satuanKerja = SatuanKerja::all();
        $cabang = Cabang::all();
        $departemenInternal = Departemen::where('satuan_kerja', 2)->get();

        $draft = SuratKeluar::find($id);
        $tujuanSatkerDraft = TujuanSatuanKerja::where('memo_id', $id)->pluck('satuan_kerja_id')->toArray();
        $tujuanCabangDraft = TujuanKantorCabang::where('memo_id', $id)->pluck('cabang_id')->toArray();
        $tujuanDepartemenDraft = TujuanDepartemen::where('memo_id', $id)->pluck('departemen_id')->toArray();

        if (in_array(1, $tujuanSatkerDraft)) {
            $tujuanSatker = ['Segenap Unit Kerja Kantor Pusat'];
        } else {
            $tujuanSatker = $satuanKerja->whereIn('id', $tujuanSatkerDraft)->pluck('satuan_kerja')->toArray();
        }
        if (in_array(1, $tujuanCabangDraft)) {
            $tujuanCabang = ['Segenap Kantor Layanan'];
        } else {
            $tujuanCabang = $cabang->whereIn('id', $tujuanCabangDraft)->pluck('cabang')->toArray();
        }
        if (in_array(1, $tujuanDepartemenDraft)) {
            $tujuanDepartemen = ['Segenap Departemen di SKTILOG'];
        } else {
            $tujuanDepartemen = $departemenInternal->whereIn('id', $tujuanDepartemenDraft)->pluck('departemen')->toArray();
        }

        if ($draft['internal'] == 1) {
            $dari = $departemenInternal->find($draft['departemen_asal'])->departemen;
        } elseif ($draft['satuan_kerja_asal']) {
            $dari = $satuanKerja->find($draft['satuan_kerja_asal'])->satuan_kerja;
        } elseif ($draft['cabang_asal']) {
            $dari = 'Cabang' . ' ' . $cabang->find($draft['cabang_asal'])->cabang;
        }
        $ttd1 = User::find($draft['otor1_by']);
        $ttd2 = User::find($draft['otor2_by']);
        $jabatanTtd1 = User::find($draft['otor1_by'])->levelTable['jabatan'];
        $jabatanTtd2 = User::find($draft['otor2_by'])->levelTable['jabatan'];

        $pdf = PDF::loadView('preview/preview', [
            'title' => 'Pratinjau',
            'requests' => $draft,
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $idUser = Auth::id();
        $user = User::where('id', $idUser)->first();
        $pengganti = User::all();
        $edit = SuratKeluar::find($id);

        $tujuanSatkerDraft = TujuanSatuanKerja::where('memo_id', $id)->pluck('satuan_kerja_id')->toArray();
        $tujuanCabangDraft = TujuanKantorCabang::where('memo_id', $id)->pluck('cabang_id')->toArray();

        $departemen = Departemen::all();
        $satuanKerja = SatuanKerja::where('id', '!=', 1)
            ->where('satuan_kerja', '!=', 'CABANG JABODETABEK')
            ->where('satuan_kerja', '!=', 'CABANG NON JABODETABEK')->get();
        $cabang = Cabang::select('id', 'cabang')
            ->where('id', '!=', 1)->get();

        $datas = [
            'title' => 'Edit Surat',
            'edit' => $edit,
            'satuanKerjas' => $satuanKerja,
            'departemens' => $departemen,
            'cabangs' => $cabang,
            'users' => $user,
            'penggantis' => $pengganti,
            'tujuanSatkerDrafts' => $tujuanSatkerDraft,
            'tujuanCabangDrafts' => $tujuanCabangDraft,
        ];

        return view('draft.edit', $datas);
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
        $idUser = Auth::id();
        $user = User::find($idUser);
        $draft = SuratKeluar::find($id);
        $satuanKerja = SatuanKerja::all();
        $cabang = Cabang::all();

        $deletedTujuanSatker = TujuanSatuanKerja::where('memo_id', $id)->delete();
        $deletedTujuanCabang = TujuanKantorCabang::where('memo_id', $id)->delete();

        $validated = $request->validate([
            'created_by' => 'required',
            'kriteria' => 'required',
            'nomor_surat' => 'required',
            'perihal' => 'required',
            'lampiran' => 'mimes:pdf',
        ]);
        $validated['cabang_asal'] = $request->cabang_asal;
        $validated['satuan_kerja_asal'] = $request->satuan_kerja_asal;
        $validated['tujuan_unit_kerja'] = $request->tujuan_unit_kerja;
        $validated['isi'] = $request->editordata;
        $validated['otor2_by'] = $request->tunjuk_otor2_by;
        $validated['otor1_by'] = $request->tunjuk_otor1_by;
        $validated['internal'] = 2;
        $validated['status'] = 1;
        $validated['draft'] = 1;

        // bersihkan tolak
        $validated['pesan_tolak'] = null;
        $validated['tanggal_tolak'] = null;
        if ($draft['lampiran_tolak']) {
            $validated['lampiran_tolak'] = null;
            Storage::delete($draft->lampiran);
        }

        // get file and store
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = date("YmdHis") . '_' . $fileName;
            $validated['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        }

        $draft->update($validated);

        $tujuanUnitKerja = $request->tujuan_unit_kerja;
        $tujuanKantorCabang = $request->tujuan_kantor_cabang;

        //TujuanCabang
        if ($tujuanKantorCabang[0] == 'kantor_cabang') {
            foreach ($cabang as $item) {
                if ($item->id != $user->cabang) {
                    TujuanKantorCabang::create([
                        'memo_id' => $id,
                        'cabang_id' => $item->id,
                        'all_flag' => 1
                    ]);
                }
            }
        } else {
            if ($tujuanKantorCabang != null)
                foreach ($tujuanKantorCabang as $item) {
                    TujuanKantorCabang::create([
                        'memo_id' => $id,
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
                        'memo_id' => $id,
                        'satuan_kerja_id' => $item->id,
                        'all_flag' => 1
                    ]);
                }
            }
        } else {
            if ($tujuanUnitKerja != null)
                foreach ($tujuanUnitKerja as $item) {
                    TujuanSatuanKerja::create([
                        'memo_id' => $id,
                        'satuan_kerja_id' => $item,
                        'all_flag' => 0
                    ]);
                }
        }

        if (isset($_POST['simpan'])) {
            $update[] = $draft['draft'] = 0;
            $draft['status'] = 1;
            array_push($update, $draft['status']);
            $draft->update($update);

            return redirect('/suratKeluar');
        } else if (isset($_POST['lihat'])) {
            if ($validated['tujuan_unit_kerja'] == 'unit_kerja') {
                $tujuanSatker = 'Seluruh Unit Kerja Kantor Pusat';
            } elseif ($validated['tujuan_unit_kerja']) {
                $tujuanSatker = $satuanKerja->whereIn('id', $validated['tujuan_unit_kerja'])->pluck('satuan_kerja')->toArray();
            }

            $dari = $satuanKerja->find($validated['satuan_kerja_asal']);
            $ttd1 = User::find($validated['otor1_by']);
            $ttd2 = User::find($validated['otor2_by']);
            $jabatanTtd1 = User::find($validated['otor1_by'])->levelTable['jabatan'];
            $jabatanTtd2 = User::find($validated['otor2_by'])->levelTable['jabatan'];

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
        } else if (isset($_POST['draft'])) {
            return redirect('/draft')->with('success', 'Draft berhasil disimpan');
        }
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
