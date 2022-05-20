<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        $countTotal = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->count();
        $countApproved = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->where('otor_status', '2')->count();
        $countRejected = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->where('otor_status', '3')->count();
        $countPending = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->where('otor_status', '1')->count();

        $datas = [
            'title' => 'Pencatatan Memo',
            'countTotal' => $countTotal,
            'countApproved' => $countApproved,
            'countRejected' => $countRejected,
            'countPending' => $countPending,
            'users' => $user
        ];
    
        return view('templates.home', $datas);
    }
}
