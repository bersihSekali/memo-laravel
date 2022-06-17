<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->session()->all());
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $userLog = User::all();

        // All Summary
        // Golongan 7

        $datas = [
            'users' => $user,
            'userLogs' => $userLog
        ];

        return view('templates.home', $datas);
    }
}
