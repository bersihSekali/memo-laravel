<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
        $checker = User::latest()->get();
        $data = SuratMasuk::where('satuan_kerja_tujuan', $user['satuan_kerja'])->latest()->get();
        return view('suratmasuk/index', [
            'title' => 'Surat Masuk',
            'datas' => $data,
            'users' => $user,
            'checker' => $checker
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
     * @param  \App\Models\SuratMasuk  $suratMasuk
     * @return \Illuminate\Http\Response
     */
    public function show(SuratMasuk $suratMasuk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SuratMasuk  $suratMasuk
     * @return \Illuminate\Http\Response
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SuratMasuk  $suratMasuk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $update = SuratMasuk::find($id);
        if (!$update) {
            return redirect('/suratMasuk')->with('error', 'Data not Found');
        }
        $update->checker = $request['checker'];

        $update->save();

        if (!$update) {
            return redirect('/suratMasuk')->with('error', 'Update Failed');
        }
        return redirect('/suratMasuk')->with('success', 'Update Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SuratMasuk  $suratMasuk
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuratMasuk $suratMasuk)
    {
        //
    }
}
