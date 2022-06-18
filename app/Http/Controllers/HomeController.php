<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // dd($request->session()->all());
        $id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')->where('id', $id)->first();
        $userLog = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->get();

        // All Summary
        // Golongan 7

        $datas = [
            'users' => $user,
            'userLogs' => $userLog
        ];

        return view('templates.home', $datas);
    }
}
