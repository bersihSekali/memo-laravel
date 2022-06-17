<?php

namespace App\Http\Controllers;

use App\Models\BidangCabang;
use App\Models\Cabang;
use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use App\Models\Forward;
use App\Models\TujuanDepartemen;
use App\Models\TujuanKantorCabang;
use App\Models\TujuanSatuanKerja;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::find($id);

        if ($user->levelTable->golongan == 7) {
            $data = SuratKeluar::with('tujuanSatker')
                ->join('tujuan_satuan_kerjas', 'surat_keluars.id', '=', 'tujuan_satuan_kerjas.memo_id')
                ->where('satuan_kerja_id', $user['satuan_kerja'])->where('status', 3)->latest('tujuan_satuan_kerjas.created_at')->get();
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

        return view('suratmasuk/index', [
            'title' => 'Surat Masuk',
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
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suratmasuk/index', [
            'title' => 'Surat Masuk'
        ]);
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
     * @param  \App\Models\SuratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function show(SuratKeluar $suratKeluar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SuratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function edit(SuratKeluar $suratKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SuratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $idUser = Auth::id();
        $user = User::find($idUser);
        $update = SuratKeluar::find($id);
        if (!$update) {
            return redirect('/suratMasuk')->with('error', 'Data not Found');
        }
        if ($user->levelTable['golongan'] == 7) {
            $update->tanggal_sk = date("Y-m-d");
            $update->status = 4;
            $update->save();
        } elseif ($user->levelTable['golongan'] == 6) {
            $update->tanggal_dep = date("Y-m-d");
            $update->status = 5;
            $update->save();
        }
        if (!$update) {
            return redirect('/suratMasuk')->with('error', 'Update Failed');
        }
        return redirect('/suratMasuk')->with('success', 'Update Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SuratKeluar  $suratKeluar
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuratKeluar $suratKeluar)
    {
        //
    }
}
