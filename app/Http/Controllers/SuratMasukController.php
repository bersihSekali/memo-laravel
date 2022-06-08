<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use App\Models\Forward;

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

        if ($user->levelTable['golongan'] == 7) {
            $data = SuratKeluar::where('satuan_kerja_tujuan', $user['satuan_kerja'])
                ->where('status', '>=', 3)
                ->latest()->get();
        } elseif ($user->levelTable['golongan'] == 6) {
            $data = SuratKeluar::where('satuan_kerja_tujuan', $user['satuan_kerja'])
                ->where('status', '>=', 4)
                ->latest()->get();
        } elseif ($user->levelTable['golongan'] >= 4) {
            $memoId = Forward::where('user_id', $user['id'])->pluck('memo_id')->toArray();
            $data = SuratKeluar::where('satuan_kerja_tujuan', $user['satuan_kerja'])
                ->where('status', '>=', 5)
                ->whereIn('id', $memoId)
                ->latest()->get();
        }

        $satuanKerja = SatuanKerja::all();
        $departemen = Departemen::all();
        $departemenDisposisi = Departemen::where('satuan_kerja', $user['satuan_kerja'])->get();
        return view('suratmasuk/index', [
            'title' => 'Surat Masuk',
            'datas' => $data,
            'users' => $user,
            'satuanKerja' => $satuanKerja,
            'departemen' => $departemen,
            'departemenDisposisi' => $departemenDisposisi

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
