<?php

namespace App\Http\Controllers;

use App\Models\SatuanKerja;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DepartemenController extends Controller
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
        $satuanKerja = SatuanKerja::select('id', 'satuan_kerja')->get();
        $departemen = Departemen::select('id', 'satuan_kerja', 'departemen')->get();
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();
        $datas = [
            'title' => 'Daftar Satuan Kerja dan Departemen',
            'users' => $user,
            'satuanKerjas' => $satuanKerja,
            'departemens' => $departemen,
            'userLogs' => $userLog
        ];
        return view('departemen.index', $datas);
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
        $satuanKerja = SatuanKerja::select('id', 'satuan_kerja')->get();
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();
        $datas = [
            'title' => 'Tambah Departemen',
            'users' => $user,
            'satuanKerja' => $satuanKerja,
            'userLogs' => $userLog
        ];
        return view('departemen.create', $datas);
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
            'departemen' => 'required',
        ]);

        $create = Departemen::create($validated);

        if (!$create) {
            return redirect('/departemen/create')->with('error', 'Pembuatan surat gagal');
        }

        return redirect('/departemen')->with('success', 'Tambah Departemen berhasil');
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
