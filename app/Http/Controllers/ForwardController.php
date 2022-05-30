<?php

namespace App\Http\Controllers;

use App\Models\Forward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratMasuk;

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
        $edit = SuratMasuk::find($id);
        $forwarded = Forward::where('memo_id', $edit['id'])->get();
        $forwarded_id = Forward::where('memo_id', $edit['id'])->pluck('user_id')->toArray();
        $forward = User::where('departemen', $edit['departemen_tujuan'])->where('level', '>=', 4)->get();
        return view('forward/edit', [
            'title' => 'Terusan Memo',
            'users' => $user,
            'forwards' => $forward,
            'forwardeds' => $forwarded,
            'forwarded_ids' => $forwarded_id,
            'edits' => $edit
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

        $update = SuratMasuk::find($id);
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

        return redirect('/forward/' . $id . '/edit')->with('success', 'Update Success');
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
