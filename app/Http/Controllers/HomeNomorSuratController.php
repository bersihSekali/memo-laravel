<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeNomorSuratController extends Controller
{
    public function index(){
        $datas = [
            'title' => 'Nomor Surat'
        ];
    
        return view('nomorSurat.home.index', $datas);
    }
}
