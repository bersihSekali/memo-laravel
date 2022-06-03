<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratMasuk;
use App\Models\User;

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
            ->whereBetween('status', array(0, 3))
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
    public function update(Request $request, $id) // Approved by otor 2
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratMasuk::find($id);

        // Update tanggal otor
        $update[] = $datas['tanggal_otor2'] = date("Y-m-d H:i:s");

        // Update otor_by 
        $datas['otor2_by'] = $user->id;
        array_push($update, $datas['otor2_by']);

        // Nomor surat antar departemen
        $datas['status'] = 2;
        array_push($update, $datas['status']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        $file = $request->file('lampiran');
        $originalFileName = $file->getClientOriginalName();
        $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
        $fileName = date("YmdHis") . '_' . $fileName;
        $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        array_push($update, $datas['lampiran']);

        $datas->update($update);

        if (!$datas) {
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
    public function destroy(Request $request, $id) // Disapproved / Revision by otor 2
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratMasuk::find($id);
        // Update otor status
        $update[] = $datas['status'] = '0';

        // Hapus nomor urut
        $datas['no_urut'] = 0;
        array_push($update, $datas['no_urut']);

        // Update tanggal otor
        $datas['tanggal_otor2'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_otor2']);

        // Update otor_by 
        $datas['otor2_by'] = $user->id;
        array_push($update, $datas['otor2_by']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        $file = $request->file('lampiran');
        $originalFileName = $file->getClientOriginalName();
        $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
        $fileName = date("YmdHis") . '_' . $fileName;
        $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        array_push($update, $datas['lampiran']);

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    public function approvedOtorSatu(Request $request, $id)
    { // Approved by Otor 1
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratMasuk::find($id);
        // Update otor status
        $update[] = $datas['status'] = '3';

        // Update tanggal otor
        $datas['tanggal_otor1'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_otor1']);

        // Update otor_by 
        $datas['otor1_by'] = $user->id;
        array_push($update, $datas['otor1_by']);

        $tahun = date("Y", strtotime($datas['tanggal_otor1']));
        // Nomor surat antar divisi / satuan kerja
        if ($datas->satuan_kerja_asal != $datas->satuan_kerja_tujuan) {
            $no_surat = sprintf("%03d", $datas['no_urut']) . '/MO/' . $datas->satuanKerjaAsal['satuan_kerja'] . '/' . $tahun;
        } else { // Nomor surat antar departemen
            $no_surat = sprintf("%03d", $datas['no_urut']) . '/MO/' . $datas->departemenAsal['departemen'] . '/' . $tahun;
        }
        $datas['nomor_surat'] = $no_surat;
        array_push($update, $datas['nomor_surat']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        $file = $request->file('lampiran');
        $originalFileName = $file->getClientOriginalName();
        $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
        $fileName = date("YmdHis") . '_' . $fileName;
        $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        array_push($update, $datas['lampiran']);

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    public function disApprovedOtorSatu(Request $request, $id)
    { // Disapproved by Otor 1
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratMasuk::find($id);
        // Update otor status
        $update[] = $datas['status'] = '0';

        // Hapus nomor urut
        $datas['no_urut'] = 0;
        array_push($update, $datas['no_urut']);

        // Update tanggal otor
        $datas['tanggal_otor1'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_otor1']);

        // Update otor_by 
        $datas['otor1_by'] = $user->id;
        array_push($update, $datas['otor1_by']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        $file = $request->file('lampiran');
        $originalFileName = $file->getClientOriginalName();
        $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
        $fileName = date("YmdHis") . '_' . $fileName;
        $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        array_push($update, $datas['lampiran']);

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }
}
