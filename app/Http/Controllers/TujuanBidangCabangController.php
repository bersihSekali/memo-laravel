<?php

namespace App\Http\Controllers;

use App\Models\BidangCabang;
use App\Models\TujuanKantorCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratKeluar;
use App\Models\TujuanBidangCabang;

class TujuanBidangCabangController extends Controller
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
        $tujuanCabang = TujuanKantorCabang::where('cabang_id', $user['cabang'])->pluck('memo_id')->toArray();
        if (!in_array($id, $tujuanCabang)) {
            dd('tidak ditemukan');
        }
        $tujuanCabangById = TujuanKantorCabang::where('cabang_id', $user['cabang'])->where('memo_id', $id)->first();
        if (!$tujuanCabangById->status_baca) {
            dd('surat belum dibaca');
        }

        $edit = SuratKeluar::where('id', $id)->first();

        $forwarded = TujuanBidangCabang::where('memo_id', $edit['id'])->get();
        $forwarded_id = TujuanBidangCabang::where('memo_id', $edit['id'])->pluck('bidang_id')->toArray();
        $forward = BidangCabang::where('cabang_id', $user->cabang)->get();

        return view('tujuanBidangCabang/edit', [
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
            'departemen_tujuan' => 'required',
            'pesan_disposisi' => 'required',
        ]);

        //tujuan departemen
        foreach ($validated['departemen_tujuan'] as $item) {
            TujuanBidangCabang::create([
                'memo_id' => $id,
                'bidang_id' => $item,
                'all_flag' => 0,
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

        //update tujuan satuan kerja
        $tujuanCabangId = TujuanKantorCabang::where('cabang_id', $user->cabang)
            ->where('memo_id', $id)->first();

        $datas = TujuanKantorCabang::find($tujuanCabangId->id);
        $update[] = $datas['tanggal_baca'] = date('Y-m-d');
        $datas['status_baca'] = 1;
        $datas->update($update);

        return redirect('cabang/' . $id . '/edit')->with('success', 'Surat telah ditandai sebagai telah dibaca, silakan masukkan disposisi jika diperlukan.');
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
