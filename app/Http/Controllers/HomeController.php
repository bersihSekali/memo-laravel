<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $datas = [
            'title' => 'Pencatatan Memo'
        ];
    
        return view('templates.home', $datas);
    }
}
