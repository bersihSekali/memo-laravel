<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuratMasuk;
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
            $needApprovePengganti = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('otor1_by_pengganti', $user->id)
                ->latest();
            $needApprove = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 2)
                ->union($needApprovePengganti)->count();
            $approvedAntarSatker = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                ->whereRaw('satuan_kerja_asal != satuan_kerja_tujuan')
                ->where('status', 3)->count();
            $approvedAntarDepartemen = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                ->whereRaw('satuan_kerja_asal = satuan_kerja_tujuan')
                ->where('status', 3)->count();
        }

        $datas = [
            'users' => $user
        ];

        return view('templates.home', $datas);
    }
}
