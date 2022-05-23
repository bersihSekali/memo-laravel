<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OtorisasiSuratController extends Controller
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
        $mails = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('otor_status', 1)
                    ->latest()->get();

        $datas = [
            'title' => 'Daftar Otorisasi Surat',
            'datas' => $mails,
            'users' => $user
        ];

        return view('otorisasi.index', $datas);
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
    public function update(Request $request, $id) //Approved
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratMasuk::find($id);
        $update[] = $datas['otor_status'] = '2';
        $datas['tanggal_otor'] = Carbon::now();
        array_push($update, $datas['tanggal_otor']);
        $datas['otor_by'] = $user->name;
        array_push($update, $datas['otor_by']);

        $datas->update($update);
        
        if(!$datas){
            return redirect('/otorisasi')->with('error', 'Update data failed!');    
        } else {
            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //Disapproved
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratMasuk::find($id);
        $update[] = $datas['otor_status'] = '3';
        $datas['tanggal_otor'] = Carbon::now();
        array_push($update, $datas['tanggal_otor']);
        $datas['otor_by'] = $user->name;
        array_push($update, $datas['otor_by']);

        $datas->update($update);
        
        if(!$datas){
            return redirect('/otorisasi')->with('error', 'Update data failed!');    
        } else {
            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }
}
