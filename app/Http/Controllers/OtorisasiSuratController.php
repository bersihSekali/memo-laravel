<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;

class OtorisasiSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mails = SuratMasuk::latest()->get();

        $datas = [
            'title' => 'Daftar Otorisasi Surat',
            'datas' => $mails
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
        $datas = SuratMasuk::find($id);
        $update[] = $datas['otor_status'] = '2';

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
        $datas = SuratMasuk::find($id);
        $update[] = $datas['otor_status'] = '3';

        $datas->update($update);
        
        if(!$datas){
            return redirect('/otorisasi')->with('error', 'Update data failed!');    
        } else {
            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }
}
