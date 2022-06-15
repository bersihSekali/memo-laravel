<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        if ($request->user_id == 'all') {
            $logs = AuditTrail::latest()->get();
        } elseif ($request->tipe_log == 2) {
            $logs = AuditTrail::where('id', $request->user_id)
                ->latest()->get();
        } elseif ($request->tipe_log == 1) {
            $logs = AuditTrail::where('id', $request->user_id)
                ->whereBetween('created_at', $request->tanggalmulai, $request->tanggalakhir)
                ->latest()->get();
        } else {
            $logs = AuditTrail::latest()->get();
        }

        $datas = [
            'title' => 'Log Aktivitas',
            'users' => $user,
            'datas' => $logs
        ];

        return view('audit.index', $datas);
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
