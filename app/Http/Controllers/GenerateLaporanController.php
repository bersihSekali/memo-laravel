<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use App\Models\Forward;
use Illuminate\Auth\Events\Validated;

class GenerateLaporanController extends Controller
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
        $validated = $request->validate([
            'jenis' => 'required',
            'tanggalmulai' => 'required',
            'tanggalakhir' => 'required'
        ]);

        $id = Auth::id();
        $user = User::find($id);

        if ($validated['jenis'] == 'masuk') {
            if ($user['level'] == 2) {
                $data = SuratMasuk::where('satuan_kerja_tujuan', $user['satuan_kerja'])
                    ->where('status', '>=', 3)
                    ->whereBetween('created_at', [$validated['tanggalmulai'], $validated['tanggalakhir']])
                    ->latest()->get();
            } elseif ($user['level'] == 3) {
                $data = SuratMasuk::where('satuan_kerja_tujuan', $user['satuan_kerja'])
                    ->where('status', '>=', 4)
                    ->whereBetween('created_at', [$validated['tanggalmulai'], $validated['tanggalakhir']])
                    ->latest()->get();
            } elseif ($user['level'] >= 4) {
                $memoId = Forward::where('user_id', $user['id'])->pluck('memo_id')->toArray();
                $data = SuratMasuk::where('satuan_kerja_tujuan', $user['satuan_kerja'])
                    ->where('status', '>=', 5)
                    ->whereIn('id', $memoId)
                    ->whereBetween('created_at', [$validated['tanggalmulai'], $validated['tanggalakhir']])
                    ->latest()->get();
            }
        } elseif ($validated['jenis'] == 'keluar') {
            if ($user['level'] == 2) {
                $data = SuratMasuk::where('satuan_kerja_asal', $user['satuan_kerja'])
                    ->where('status', '>=', 3)
                    ->whereBetween('created_at', [$validated['tanggalmulai'], $validated['tanggalakhir']])
                    ->latest()->get();
            } elseif ($user['level'] == 3) {
                $data = SuratMasuk::where('satuan_kerja_asal', $user['satuan_kerja'])
                    ->where('status', '>=', 4)
                    ->whereBetween('created_at', [$validated['tanggalmulai'], $validated['tanggalakhir']])
                    ->latest()->get();
            } elseif ($user['level'] >= 4) {
                $memoId = Forward::where('user_id', $user['id'])->pluck('memo_id')->toArray();
                $data = SuratMasuk::where('satuan_kerja_asal', $user['satuan_kerja'])
                    ->where('status', '>=', 5)
                    ->whereIn('id', $memoId)
                    ->whereBetween('created_at', [$validated['tanggalmulai'], $validated['tanggalakhir']])
                    ->latest()->get();
            }
        }

        $satuanKerja = SatuanKerja::all();
        $departemen = Departemen::all();
        return view('laporan/index', [
            'title' => 'Surat Masuk',
            'datas' => $data,
            'users' => $user,
            'satuanKerja' => $satuanKerja,
            'departemen' => $departemen,
            'requests' => $validated

        ]);
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
