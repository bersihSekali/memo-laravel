<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $datas = [
            'title' => 'Beranda',
            'judul' => 'Beranda'
        ];
    
        return view('templates/home', $datas);
    }
}
