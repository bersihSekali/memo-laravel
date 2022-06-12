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

        $edit = SuratKeluar::where('id', $id)->first();

        $forwarded = Forward::where('memo_id', $edit['id'])->get();
        $forwarded_id = Forward::where('memo_id', $edit['id'])->pluck('user_id')->toArray();
        $forward = User::where('departemen', $user->departemen)->whereHas('levelTable', function ($query) {
            $query->where('golongan', '<', '6');
        })->get();

        return view('forward/edit', [
            'title' => 'Teruskan',
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
        $validated = $request->validate([
            'forward' => 'required'
        ]);

        $update = SuratKeluar::find($id);
        if (!$update) {
            return redirect('/suratMasuk')->with('error', 'Data not Found');
        }

        foreach ($validated['forward'] as $user) {
            $create = Forward::create([
                'user_id' => $user,
                'memo_id' => $id
            ]);
            if (!$create) {
                return redirect('/suratMasuk/index')->with('error', 'Gagal meneruskan !');
            }
        }

        return redirect('forward/' . $id . '/edit')->with('success', 'Update Success');
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
