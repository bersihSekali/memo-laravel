<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SatuanKerja;
use App\Models\Departemen;

class DisposisiController extends Controller
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
        $data = SuratMasuk::where('satuan_kerja_tujuan_disposisi', $user['satuan_kerja'])->where('status', 1)->latest()->get();
        $satuanKerja = SatuanKerja::all();
        $departemen = Departemen::all();
        return view('disposisi/index', [
            'title' => 'Disposisi Masuk',
            'datas' => $data,
            'users' => $user,
            'checker' => $checker,
            'satuanKerja' => $satuanKerja,
            'departemen' => $departemen

        ]);
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

        return redirect('/suratMasuk')->with('success', 'Pembuatan surat berhasil');
    }

    public function selesai($id)
    {
        $update = SuratMasuk::find($id);
        if (!$update) {
            return redirect('/disposisi')->with('error', 'Data not Found');
        }
        $update->tanggal_selesai_disposisi = date("Y-m-d");
        $update->status_disposisi = 1;

        $update->save();

        if (!$update) {
            return redirect('/disposisi')->with('error', 'Update Failed');
        }
        return redirect('/disposisi')->with('success', 'Update Success');
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
