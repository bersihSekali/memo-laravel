<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;
use App\Models\SatuanKerja;

class SuratKeluarController extends Controller
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
            $mails = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                ->latest()->get();
        } else {
            $mails = SuratMasuk::where('created_by', $id)
                ->latest()->get();
        }

        $datas = [
            'title' => 'Surat Keluar',
            'users' => $user,
            'mails' => $mails
        ];

        return view('suratKeluar.index', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $satuanKerja = SatuanKerja::all();

        $datas = [
            'title' => 'Tambah Surat',
            'satuanKerjas' => $satuanKerja,
            'users' => $user
        ];

        return view('suratKeluar.create', $datas);
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
            'created_by' => 'required',
            'nomor_surat' => 'required',
            'satuan_kerja_asal' => 'required',
            'satuan_kerja_tujuan' => 'required',
            'perihal' => 'required',
            'lampiran' => 'mimes:pdf'
        ]);
        $validated['departemen_tujuan'] = $request->departemen_tujuan;

        // get file and store
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = date("YmdHis") . '_' . $fileName;
            $validated['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        }

        $create = SuratMasuk::create($validated);

        if (!$create) {
            return redirect('/suratKeluar/create')->with('error', 'Pembuatan surat gagal');
        }

        return redirect('/suratKeluar')->with('success', 'Pembuatan surat berhasil');
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
