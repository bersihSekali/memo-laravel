<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratKeluar;
use App\Models\User;

class OtorisasiBaruController extends Controller
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

        // Officer, kepala bidang, kepala operasi cabang, kepala cabang pembantu golongan 5
        if ($user->levelTable->golongan == 5) {
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('departemen_asal', $user->departemen)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest()->get();
        }

        // Kepala departemen, golongan 6
        elseif (($user->levelTable->golongan == 6) && ($user->level == 6)) {
            $pengganti = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->orWhere('otor1_by_pengganti', $user->id)
                ->latest();
            // antar departemen sebagai otor1_by
            $antarDepartemen = SuratKeluar::where('departemen_asal', $user->departemen)
                ->where('status', 2)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::where('status', 1)
                ->union($antarDepartemen)
                ->latest()->get();
        }

        // Senior officer
        elseif (($user->levelTable->golongan == 6) && ($user->level == 7)) {
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->latest();
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->union($pengganti2)
                ->latest();
            // antar departemen sebagai otor2_by
            $antarDepartemen2 = SuratKeluar::where('departemen_asal', $user->departemen)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest();
            // antar departemen sebagai otor1_by
            $antarDepartemen1 = SuratKeluar::where('departemen_asal', $user->departemen)
                ->where('status', 2)
                ->where('otor2_by', '!=', $user->id)
                ->union($antarDepartemen2)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::where('status', 1)
                ->union($antarDepartemen1)
                ->latest()->get();
        }

        // Kepala satuan kerja
        elseif ($user->levelTable->golongan == 7) {
            $pengganti = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->latest();
            // Antar satuan kerja sebagai otor1_by
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 2)
                ->union($pengganti)
                ->latest()->get();
        }

        $datas = [
            'title' => 'Daftar Otorisasi Surat',
            'datas' => $mails,
            'users' => $user
        ];
        // dd($datas);
        return view('otorisasiBaru.index', $datas);
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
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);

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
    public function destroy(Request $request, $id)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);
        // Update otor status
        $update[] = $datas['status'] = '0';

        // Update pesan tolak
        $datas['pesan_tolak'] = $request->pesan_tolak;
        array_push($update, $datas['pesan_tolak']);

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
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = date("YmdHis") . '_' . $fileName;
            $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
            array_push($update, $datas['lampiran']);
        }

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

        $datas = SuratKeluar::find($id);
        // Update otor status
        $update[] = $datas['status'] = '3';

        // Update tanggal otor
        $datas['tanggal_otor1'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_otor1']);

        // Update otor_by 
        $datas['otor1_by'] = $user->id;
        array_push($update, $datas['otor1_by']);

        // Compare latest date with now to reset no_urut
        // $lastSuratMasuk = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
        //     ->latest()->first();
        // $nowDate = date("Y-m-d H:i:s");
        // if ($lastSuratMasuk == '') {
        //     $datas['no_urut'] = 1;
        // } else if (date("Y", strtotime($nowDate)) != date("Y", strtotime($lastSuratMasuk->created_at))) {
        //     $datas['no_urut'] = 1;
        // } else {
        //     $mails = SuratMasuk::whereRaw('satuan_kerja_asal = satuan_kerja_tujuan')
        //         ->max('no_urut');
        //     $no_urut = $mails + 1;
        //     $datas['no_urut'] = $no_urut;
        // }

        $lastSuratKeluar = SuratKeluar::where('departemen_asal', $datas->departemen_asal)
            ->latest()->first();
        if ($lastSuratKeluar == '') {
            $datas->no_urut = 1;
        } else {
            $temp = SuratKeluar::where('departemen_asal', $datas->departemen_asal)
                ->max('no_urut');
            $no_urut = $temp + 1;
            $datas->no_urut = $no_urut;
        }
        array_push($update, $datas->no_urut);

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

        $datas = SuratKeluar::find($id);
        // Update otor status
        $update[] = $datas['status'] = '0';

        // Update pesan tolak
        $datas['pesan_tolak'] = $request->pesan_tolak;
        array_push($update, $datas['pesan_tolak']);

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
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = date("YmdHis") . '_' . $fileName;
            $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
            array_push($update, $datas['lampiran']);
        }

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }
}
