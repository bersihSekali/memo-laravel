<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\TujuanDepartemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratKeluar;
use App\Models\TujuanSatuanKerja;

class TujuanDepartemenController extends Controller
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
     * @param  \App\Models\TujuanDepartemen  $tujuanDepartemen
     * @return \Illuminate\Http\Response
     */
    public function show(TujuanDepartemen $tujuanDepartemen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TujuanDepartemen  $tujuanDepartemen
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $idUser = Auth::id();
        $user = User::find($idUser);

        //cek memo
        $tujuanSatker = TujuanSatuanKerja::where('satuan_kerja_id', $user['satuan_kerja'])->pluck('memo_id')->toArray();
        if (!in_array($id, $tujuanSatker)) {
            dd('tidak ditemukan');
        }
        $tujuanSatkerById = TujuanSatuanKerja::where('satuan_kerja_id', $user['satuan_kerja'])->where('memo_id', $id)->first();
        if (!$tujuanSatkerById->status_baca) {
            dd('surat belum dibaca');
        }

        $edit = SuratKeluar::where('id', $id)->first();

        $forwarded = TujuanDepartemen::where('memo_id', $edit['id'])->get();
        $forwarded_id = TujuanDepartemen::where('memo_id', $edit['id'])->pluck('departemen_id')->toArray();
        $forward = Departemen::where('satuan_kerja', $user->satuan_kerja)->get();

        return view('tujuanDepartemen/edit', [
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
     * @param  \App\Models\TujuanDepartemen  $tujuanDepartemen
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
            TujuanDepartemen::create([
                'memo_id' => $id,
                'departemen_id' => $item,
                'pesan_disposisi' => $validated['pesan_disposisi'],
                'tanggal_disposisi' => date('Y-m-d'),
                'all_flag' => 0
            ]);
        };

        return redirect()->back()->with('success', 'Update data success!');
    }

    public function selesaikan(Request $request, $id)
    {
        $idUser = Auth::id();
        $user = User::find($idUser);

        //update tujuan satuan kerja
        $tujuanSatkerId = TujuanSatuanKerja::where('satuan_kerja_id', $user->satuan_kerja)
            ->where('memo_id', $id)->first();

        $datas = TujuanSatuanKerja::find($tujuanSatkerId->id);
        $update[] = $datas['tanggal_baca'] = date('Y-m-d');
        $datas['status_baca'] = 1;
        $datas->update($update);

        if ($user->satuanKerja['grup'] == 5 && $user->levelTable['golongan'] == 6) {
            return redirect('forward/' . $id . '/edit')->with('success', 'Surat telah ditandai sebagai telah dibaca, silakan teruskan pesan jika diperlukan.');
        } else {
            return redirect('tujuanDepartemen/' . $id . '/edit')->with('success', 'Surat telah ditandai sebagai telah dibaca, silakan masukkan pesan disposisi jika diperlukan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TujuanDepartemen  $tujuanDepartemen
     * @return \Illuminate\Http\Response
     */
    public function destroy(TujuanDepartemen $tujuanDepartemen)
    {
        //
    }
}
