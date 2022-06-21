<?php

namespace App\Http\Controllers;

use App\Models\TujuanBidangCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratKeluar;
use App\Models\Forward;

class ForwardCabangController extends Controller
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
        $idUser = Auth::id();
        $user = User::find($idUser);

        //cek memo
        $tujuanBidang = TujuanBidangCabang::where('bidang_id', $user['bidang_cabang'])->pluck('memo_id')->toArray();
        if (!in_array($id, $tujuanBidang)) {
            dd('tidak ditemukan');
        }
        $tujuanBidangById = TujuanBidangCabang::where('bidang_id', $user['bidang_cabang'])->where('memo_id', $id)->first();
        if (!$tujuanBidangById->status_baca) {
            dd('surat belum dibaca');
        }

        $edit = SuratKeluar::with('tujuanBidangCabang')
            ->join('tujuan_bidang_cabangs', 'surat_keluars.id', '=', 'tujuan_bidang_cabangs.memo_id')
            ->where('surat_keluars.id', $id)->first();

        $forwarded = Forward::where('memo_id', $edit['id'])->get();
        $forwarded_id = Forward::where('memo_id', $edit['id'])->pluck('user_id')->toArray();
        $forward = User::where('bidang_cabang', $user->bidang_cabang)->get();

        return view('forward/edit', [
            'title' => 'Teruskan ke Departemen',
            'users' => $user,
            'edits' => $edit,
            'forwardeds' => $forwarded,
            'forwarded_ids' => $forwarded_id,
            'forwards' => $forward,
        ]);
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
        $idUser = Auth::id();
        $user = User::find($idUser);

        $validated = $request->validate([
            'user_tujuan' => 'required',
            'pesan_disposisi' => 'required',
        ]);

        //isi tabel forward
        foreach ($validated['user_tujuan'] as $item) {
            Forward::create([
                'memo_id' => $id,
                'user_id' => $item,
                'pesan_disposisi' => $validated['pesan_disposisi'],
                'tanggal_disposisi' => date('Y-m-d')
            ]);
        };

        return redirect()->back()->with('success', 'Update data success!');
    }

    public function selesaikan(Request $request, $id)
    {
        $idUser = Auth::id();
        $user = User::find($idUser);

        //update tujuan departemen
        $tujuanBidangId = TujuanBidangCabang::where('bidang_id', $user->bidang_cabang)
            ->where('memo_id', $id)->first();

        $datas = TujuanBidangCabang::find($tujuanBidangId->id);
        $update[] = $datas['tanggal_baca'] = date('Y-m-d');
        $datas['status_baca'] = 1;
        $datas->update($update);

        return redirect('forwardCabang/' . $id . '/edit')->with('success', 'Surat telah ditandai sebagai telah dibaca, silakan teruskan memo jika diperlukan.');
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
