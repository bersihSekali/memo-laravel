<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use App\Models\TujuanSatuanKerja;
use App\Models\TujuanKantorCabang;
use App\Models\TujuanDepartemen;
use Barryvdh\DomPDF\Facade\Pdf;


class GenerateLaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validated = $request->validate([
            'jenis' => 'required',
            'tanggalmulai' => 'required',
            'tanggalakhir' => 'required'
        ]);

        if ($validated['jenis'] == 'masuk') {
            $id = Auth::id();
            $user = User::find($id);

            if ($user->levelTable->golongan == 7) {
                $data = SuratKeluar::with('tujuanSatker')
                    ->join('tujuan_satuan_kerjas', 'surat_keluars.id', '=', 'tujuan_satuan_kerjas.memo_id')
                    ->where('satuan_kerja_id', $user['satuan_kerja'])
                    ->where('status', 3)
                    ->whereBetween('tanggal_otor1', [$validated['tanggalmulai'], date("Y-m-d", strtotime($validated['tanggalakhir'] . "+1 days"))])
                    ->latest('tujuan_satuan_kerjas.created_at')->get();
                $countSelesai = $data->whereNotNull('tanggal_baca')->count();
                $countBelumSelesai = $data->whereNull('tanggal_baca')->count();
            } elseif ($user->levelTable->golongan == 6) {
                $data = SuratKeluar::with('tujuanDepartemen')
                    ->join('tujuan_departemens', 'surat_keluars.id', '=', 'tujuan_departemens.memo_id')
                    ->where('departemen_id', $user['departemen'])->where('status', 3)->latest('tujuan_departemens.created_at')->get();
            } elseif ($user->levelTable['golongan'] <= 5) {
                $data = SuratKeluar::with('forward')
                    ->join('forwards', 'surat_keluars.id', '=', 'forwards.memo_id')
                    ->where('user_id', $id)->where('status', 3)->latest('forwards.created_at')->get();
            }
            $satuanKerja = SatuanKerja::all();
            $departemen = Departemen::all();

            //untuk kolom tujuan
            $tujuanId = $data->pluck('memo_id')->toArray();
            $tujuanSatker = TujuanSatuanKerja::whereIn('memo_id', $tujuanId)->get();

            $tujuanDepartemen = TujuanDepartemen::whereIn('memo_id', $tujuanId)->get();
            $tujuanCabangs = TujuanKantorCabang::whereIn('memo_id', $tujuanId)->get();

            //untuk cek all flag
            $seluruhDepartemenMemoId = $tujuanDepartemen->where('departemen_id', 1)->pluck('memo_id')->toArray();
            $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
            $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();
        } elseif ($validated['jenis'] == 'keluar') {
            $id = Auth::id();
            $user = User::where('id', $id)->first();
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->whereBetween('created_at', [$validated['tanggalmulai'], date("Y-m-d", strtotime($validated['tanggalakhir'] . "+1 days"))])
                ->latest()->get();
            $countSelesai = $mails->where('status', 3)->count();
            $countBelumSelesai = $mails->where('status', '!=', 3)->count();

            // Untuk view column tujuan
            $memoIdSatker = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->pluck('id')->toArray();
            $tujuanSatker = TujuanSatuanKerja::whereIn('memo_id', $memoIdSatker)
                ->latest()->get();
            $tujuanCabangs = TujuanKantorCabang::whereIn('memo_id', $memoIdSatker)
                ->latest()->get();

            //untuk cek all flag
            $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
            $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();
        }

        // dompdf

        if ($validated['jenis'] == 'masuk') {
            $pdf = PDF::loadView('laporan/download', [
                'title' => 'Surat Masuk',
                'requests' => $validated,
                'countSelesai' => $countSelesai,
                'countBelumSelesai' => $countBelumSelesai,
                'datas' => $data,
                'users' => $user,
                'satuanKerjas' => $satuanKerja,
                'departemens' => $departemen,
                'tujuanSatkers' => $tujuanSatker,
                'tujuanDepartemens' => $tujuanDepartemen,
                'tujuanCabangs' => $tujuanCabangs,
                'seluruhDepartemenMemoIds' => $seluruhDepartemenMemoId,
                'seluruhSatkerMemoIds' => $seluruhSatkerMemoId,
                'seluruhCabangMemoIds' => $seluruhCabangMemoId,
            ])->setPaper('a4', 'landscape');

            $pdf->output();
            $dompdf = $pdf->getDomPDF();

            $canvas = $dompdf->get_canvas();
            $canvas->page_text(740, 560, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", null, 10, array(0, 0, 0));

            return $pdf->stream();
        } elseif ($validated['jenis'] == 'keluar') {
            $pdf = PDF::loadView('laporan/download', [
                'title' => 'Surat Keluar',
                'requests' => $validated,
                'datas' => $mails,
                'tujuanSatkers' => $tujuanSatker,
                'tujuanCabangs' => $tujuanCabangs,
                'users' => $user,
                'seluruhSatkerMemoIds' => $seluruhSatkerMemoId,
                'seluruhCabangMemoIds' => $seluruhCabangMemoId,
                'countSelesai' => $countSelesai,
                'countBelumSelesai' => $countBelumSelesai,
            ])->setPaper('a4', 'landscape');

            $pdf->output();
            $dompdf = $pdf->getDomPDF();

            $canvas = $dompdf->get_canvas();
            $canvas->page_text(740, 560, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", null, 10, array(0, 0, 0));

            return $pdf->stream();
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
        //
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
