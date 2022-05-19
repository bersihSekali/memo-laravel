<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;

class DisposisiController extends Controller
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
        $validated = $request->validate([
            'tanggal_disposisi' => 'required',
            'satuan_kerja_tujuan_disposisi' => 'required',
            'departemen_tujuan_disposisi' => 'required',
            'pesan_disposisi' => 'required',
            'lampiran_disposisi' => 'required|mimes:pdf'
        ]);

        $file = $request->file('lampiran_disposisi');
        $fileName = $file->getClientOriginalName();

        $validated['lampiran_disposisi'] = $request->file('lampiran_disposisi')->storeAs('lampiran_disposisi', $fileName);

        SuratMasuk::where('id', $id)->update([
            'tanggal_disposisi' => $validated['tanggal_disposisi'],
            'satuan_kerja_tujuan_disposisi' => $validated['satuan_kerja_tujuan_disposisi'],
            'departemen_tujuan_disposisi' => $validated['departemen_tujuan_disposisi'],
            'pesan_disposisi' => $validated['pesan_disposisi'],
            'lampiran_disposisi' => $validated['lampiran_disposisi']
        ]);

        // if (!$update) {
        //     return redirect('/suratMasuk')->with('error', 'Pembuatan surat gagal');
        // }

        return redirect('/suratMasuk')->with('success', 'Pembuatan surat berhasil');
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