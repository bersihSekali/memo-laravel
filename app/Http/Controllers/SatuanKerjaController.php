<?php

namespace App\Http\Controllers;

use App\Models\SatuanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SatuanKerjaController extends Controller
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
        $satuanKerja = SatuanKerja::orderBy('id')->get();
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();
        $datas = [
            'title' => 'Daftar Satuan Kerja dan Departemen',
            'users' => $user,
            'satuanKerjas' => $satuanKerja,
            'userLogs' => $userLog
        ];
        return view('satuanKerja.index', $datas);
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
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();
        $datas = [
            'title' => 'Tambah Satuan Kerja',
            'users' => $user,
            'userLogs' => $userLog
        ];
        return view('satuanKerja.create', $datas);
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
            'satuan_kerja' => 'required',
        ]);

        $create = SatuanKerja::create($validated);

        if (!$create) {
            return redirect('/satuanKerja/create')->with('error', 'Pembuatan surat gagal');
        }

        return redirect('/satuanKerja')->with('success', 'Pembuatan surat berhasil');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SatuanKerja  $satuanKerja
     * @return \Illuminate\Http\Response
     */
    public function show(SatuanKerja $satuanKerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SatuanKerja  $satuanKerja
     * @return \Illuminate\Http\Response
     */
    public function edit(SatuanKerja $satuanKerja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SatuanKerja  $satuanKerja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SatuanKerja $satuanKerja)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SatuanKerja  $satuanKerja
     * @return \Illuminate\Http\Response
     */
    public function destroy(SatuanKerja $satuanKerja)
    {
        //
    }
}
