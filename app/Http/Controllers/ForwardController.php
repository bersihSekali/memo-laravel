<?php

namespace App\Http\Controllers;

use App\Models\Forward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratKeluar;
use App\Models\TujuanDepartemen;
use Illuminate\Database\Schema\Builder;

class ForwardController extends Controller
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
     * @param  \App\Models\Forward  $forward
     * @return \Illuminate\Http\Response
     */
    public function show(Forward $forward)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Forward  $forward
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $idUser = Auth::id();
        $user = User::find($idUser);

        //cek memo
        $tujuanDepartemen = TujuanDepartemen::where('departemen_id', $user['departemen'])->pluck('memo_id')->toArray();
        if (!in_array($id, $tujuanDepartemen)) {
            dd('tidak ditemukan');
        }
        $tujuanDepartemenById = TujuanDepartemen::where('departemen_id', $user['departemen'])->where('memo_id', $id)->first();
        if (!$tujuanDepartemenById->status_baca) {
            dd('surat belum dibaca');
        }

        $edit = SuratKeluar::with('tujuanDepartemen')
            ->join('tujuan_departemens', 'surat_keluars.id', '=', 'tujuan_departemens.memo_id')
            ->where('surat_keluars.id', $id)->first();

        $forwarded = Forward::where('memo_id', $edit['id'])->get();
        $forwarded_id = Forward::where('memo_id', $edit['id'])->pluck('user_id')->toArray();
        $forward = User::where('departemen', $user->departemen)->get();

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
     * @param  \App\Models\Forward  $forward
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
        $tujuanDepartemenId = TujuanDepartemen::where('departemen_id', $user->departemen)
            ->where('memo_id', $id)->first();

        $datas = TujuanDepartemen::find($tujuanDepartemenId->id);
        $update[] = $datas['tanggal_baca'] = date('Y-m-d');
        $datas['status_baca'] = 1;
        $datas->update($update);

        return redirect('forward/' . $id . '/edit')->with('success', 'Surat telah ditandai sebagai telah dibaca, silakan teruskan memo jika diperlukan.');
    }

    public function baca(Request $request, $id)
    {
        $idUser = Auth::id();
        $user = User::find($idUser);

        //update tujuan user
        $forwardId = Forward::where('user_id', $user->id)
            ->where('memo_id', $id)->first();

        $datas = Forward::find($forwardId->id);
        $update[] = $datas['tanggal_baca'] = date('Y-m-d');
        $datas['status_baca'] = 1;
        $datas->update($update);

        return redirect('/suratMasuk')->with('success', 'Surat telah ditandai sebagai telah dibaca.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Forward  $forward
     * @return \Illuminate\Http\Response
     */
    public function destroy(Forward $forward)
    {
        //
    }
}
