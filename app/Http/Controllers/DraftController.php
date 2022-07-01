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
            $mails = SuratKeluar::where('cabang_asal', $user->cabang)->where('draft', 1)
                ->latest()->get();
        } elseif ($user->satuan_kerja) {
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)->where('draft', 1)
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

        $draft = SuratKeluar::find($id);
        $tujuanSatkerDraft = TujuanSatuanKerja::where('memo_id', $id)->pluck('satuan_kerja_id')->toArray();

        if (in_array(1, $tujuanSatkerDraft)) {
            $tujuanSatker = 'Seluruh Unit Kerja Kantor Pusat';
        } else {
            $tujuanSatker = $satuanKerja->whereIn('id', $tujuanSatkerDraft)->pluck('satuan_kerja')->toArray();
        }

        $dari = $satuanKerja->find($draft['satuan_kerja_asal']);
        $ttd1 = User::find($draft['otor1_by']);
        $ttd2 = User::find($draft['otor2_by']);
        $jabatanTtd1 = User::find($draft['otor1_by'])->levelTable['jabatan'];
        $jabatanTtd2 = User::find($draft['otor2_by'])->levelTable['jabatan'];

        $pdf = PDF::loadView('preview/preview', [
            'title' => 'Pratinjau',
            'requests' => $draft,
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
        dd($request);
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
