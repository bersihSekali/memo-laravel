<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratKeluar;
use App\Models\User;
use App\Models\TujuanDepartemen;
use App\Models\TujuanSatuanKerja;
use App\Models\TujuanKantorCabang;
use App\Notifications\EmailSent;
use App\Notifications\MemoSentSatker;
use Illuminate\Support\Facades\Notification;

class OtorisasiBaruController extends Controller
{
    public function __construct()
    {
        $this->coba = new SuratKeluar;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        $mails = $this->coba->ambilOtor($user);

        // Untuk view column tujuan
        $memoIdSatker = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)->orWhere('cabang_asal', $user->cabang)
            ->pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

        //untuk cek all flag
        $seluruhDepartemenMemoId = $tujuanDepartemen->where('departemen_id', 1)->pluck('memo_id')->toArray();
        $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
        $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();

        $datas = [
            'title' => 'Daftar Otorisasi Surat',
            'datas' => $mails,
            'tujuanDepartemens' => $tujuanDepartemen,
            'tujuanSatkers' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'users' => $user,
            'seluruhDepartemenMemoIds' => $seluruhDepartemenMemoId,
            'seluruhSatkerMemoIds' => $seluruhSatkerMemoId,
            'seluruhCabangMemoIds' => $seluruhCabangMemoId,
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
        $user = User::find($user_id);

        $datas = SuratKeluar::find($id);

        // Update tanggal otor
        $update[] = $datas['tanggal_otor2'] = date("Y-m-d H:i:s");

        // Update otor_by
        $datas['otor2_by'] = $user->id;
        array_push($update, $datas['otor2_by']);

        // status surat
        $datas['status'] = 2;
        array_push($update, $datas['status']);

        $datas->update($update);

        if (!$datas) {
            return redirect('/otor')->with('error', 'Update data failed!');
        } else {
            return redirect('/otor')->with('success', 'Update data success!');
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
        $user = User::find($user_id);

        $datas = SuratKeluar::find($id);

        // Update otor status
        $update[] = $datas['status'] = '0';

        // Update pesan tolak
        $datas['pesan_tolak'] = $request->pesan_tolak;
        array_push($update, $datas['pesan_tolak']);

        // Update tanggal otor
        $datas['tanggal_tolak'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_tolak']);

        // get file and store

        if ($request->file('lampiran_tolak')) {
            $file = $request->file('lampiran_tolak');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = 'TOLAK' . '_' . date("YmdHis") . '_' . $fileName;
            $datas['lampiran_tolak'] = $request->file('lampiran_tolak')->storeAs('lampiran_tolak', $fileName);
            array_push($update, $datas['lampiran_tolak']);
        }

        $datas->update($update);

        if (!$datas) {
            return redirect('/otor')->with('error', 'Update data failed!');
        } else {
            return redirect('/otor')->with('success', 'Update data success!');
        }
    }

    public function approvedOtorSatu(Request $request, $id)
    { // Approved by Otor 1
        $user_id = Auth::id();
        $user = User::find($user_id);

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

        $datas->update($update);

        $satuanKerjaId = TujuanSatuanKerja::where('memo_id', $datas->id)->pluck('satuan_kerja_id')->toArray();
        $mailRecipients = User::whereIn('satuan_kerja', $satuanKerjaId)->whereHas('levelTable', function ($q) {
            $q->where('golongan', 7);
        })->get();

        // foreach ($mailRecipients as $user) {
        //     $user->notify(new MemoSentSatker($datas));
        // }

        if (!$datas) {
            return redirect('/otor')->with('error', 'Update data failed!');
        } else {
            return redirect('/otor')->with('success', 'Update data success!');
        }
    }

    public function disApprovedOtorSatu(Request $request, $id)
    { // Disapproved by Otor 1
        $user_id = Auth::id();
        $user = User::find($user_id);

        $datas = SuratKeluar::find($id);
        // Update otor status
        $update[] = $datas['status'] = '0';

        // Update pesan tolak
        $datas['pesan_tolak'] = $request->pesan_tolak;
        array_push($update, $datas['pesan_tolak']);

        // Update tanggal otor
        $datas['tanggal_tolak'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_tolak']);

        // get file and store
        if ($request->file('lampiran_tolak')) {
            $file = $request->file('lampiran_tolak');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = 'TOLAK' . '_' . date("YmdHis") . '_' . $fileName;
            $datas['lampiran_tolak'] = $request->file('lampiran_tolak')->storeAs('lampiran_tolak', $fileName);
            array_push($update, $datas['lampiran_tolak']);
        }

        $datas->update($update);

        if (!$datas) {
            return redirect('/otor')->with('error', 'Update data failed!');
        } else {
            return redirect('/otor')->with('success', 'Update data success!');
        }
    }
}
