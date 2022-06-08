<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        // All Summary
        // Golongan 7
        if ($user->levelTable->golongan == 7) {
            $needApprovePengganti = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('otor1_by_pengganti', $user->id)
                ->latest();
            $needApprove = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 2)
                ->union($needApprovePengganti)->count();
            $approvedAntarSatker = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->whereRaw('satuan_kerja_asal != satuan_kerja_tujuan')
                ->where('status', 3)->count();
            $approvedAntarDepartemen = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->whereRaw('satuan_kerja_asal = satuan_kerja_tujuan')
                ->where('status', 3)->count();
        }

        $datas = [
            'users' => $user
        ];

        return view('templates.home', $datas);
    }
}
