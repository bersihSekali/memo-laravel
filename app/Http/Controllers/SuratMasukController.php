<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use App\Models\Forward;
use App\Models\TujuanDepartemen;
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
                ->where('satuan_kerja_id', $user['satuan_kerja'])->latest('tujuan_satuan_kerjas.created_at')->get();
        } elseif ($user->levelTable->golongan == 6) {
            $data = SuratKeluar::with('tujuanDepartemen')
                ->join('tujuan_departemens', 'surat_keluars.id', '=', 'tujuan_departemens.memo_id')
                ->where('departemen_id', $user['departemen'])->latest('tujuan_departemens.created_at')->get();
        } elseif ($user->levelTable['golongan'] <= 5) {
            $memoId = Forward::where('user_id', $user['id'])->pluck('memo_id')->toArray();
            $data = SuratKeluar::whereIn('id', $memoId)->latest()->get();
            $pesan = [];
        }
        $satuanKerja = SatuanKerja::all();
        $departemen = Departemen::all();
        return view('suratmasuk/index', [
            'title' => 'Surat Masuk',
            'datas' => $data,
            'users' => $user,
            'satuanKerjas' => $satuanKerja,
            'departemens' => $departemen,
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
