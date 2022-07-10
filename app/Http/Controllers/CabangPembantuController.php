<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Cabang;
use App\Models\BidangCabang;
use Illuminate\Http\Request;

class CabangPembantuController extends Controller
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
        $cabang = Cabang::get();
        $cabangPembantu = BidangCabang::get();
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();
        $datas = [
            'title' => 'Daftar Satuan Kerja dan Departemen',
            'users' => $user,
            'cabangs' => $cabang,
            'cabangPembantus' => $cabangPembantu,
            'userLogs' => $userLog
        ];
        return view('cabangPembantu.index', $datas);
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
