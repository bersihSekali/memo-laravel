<?php

namespace App\Http\Controllers;

use App\Models\Penomoran;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PenomoranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // data user
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $riwayat = Penomoran::latest()->get();
        $nomorSuratTerpakai = SuratKeluar::pluck('nomor_surat')->toArray();
        $nomor = Penomoran::whereNotIn('nomor_surat', $nomorSuratTerpakai)->get();


        $datas = [
            'users' => $user,
            'riwayats' => $riwayat,
            'nomors' => $nomor,
        ];

        return view('penomoran.index', $datas);
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
        // data user
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        $validated = $request->validate([
            'created_by' => 'required',
            'jenis' => 'required',
        ]);

        if ($validated['jenis'] == 1) {
            $lastNomor = Penomoran::where('departemen', $user->departemen)->whereYear('created_at', date('Y'))->max('nomor');
            if ($lastNomor) {
                $validated['nomor'] = $lastNomor + 1;
            } else {
                $validated['nomor'] = 1;
            }
            $validated['tahun'] = date('Y');
            $validated['departemen'] = $user->departemen;
            $validated['nomor_surat'] = sprintf("%03d", $validated['nomor']) . '/MO/' . $user->departemenTable['inisial'] . '/' . $validated['tahun'];
        } elseif ($validated['jenis'] == 2) {
            $lastNomor = Penomoran::where('jenis', 2)->whereYear('created_at', date('Y'))->max('nomor');
            if ($lastNomor) {
                $validated['nomor'] = $lastNomor + 1;
            } else {
                $validated['nomor'] = 1;
            }
            $validated['tahun'] = date('Y');
            $validated['departemen'] = null;
            $validated['nomor_surat'] = sprintf("%03d", $validated['nomor']) . '/MO/' . $user->satuanKerja['inisial'] . '/' . $validated['tahun'];
        }

        $create = Penomoran::create($validated);
        // Return gagal simpan
        if (!$create) {
            return redirect('/penomoran')->with('error', 'Pengambilan nomor gagal');
        } else {
            return redirect('/penomoran')->with('success', 'Pengambilan nomor berhasil dengan nomor: ' . $validated['nomor_surat']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penomoran  $penomoran
     * @return \Illuminate\Http\Response
     */
    public function show(Penomoran $penomoran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penomoran  $penomoran
     * @return \Illuminate\Http\Response
     */
    public function edit(Penomoran $penomoran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penomoran  $penomoran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penomoran $penomoran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penomoran  $penomoran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penomoran $penomoran)
    {
        //
    }
}
