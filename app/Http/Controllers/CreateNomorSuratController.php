<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departemen;
use Illuminate\Support\Facades\DB;

class CreateNomorSuratController extends Controller
{
    public function index(Request $request){
        echo $skid = $request->post('skid');
        // $departemen = Departemen::where('id', $skid)->get();
        $departemen = DB::table('departemens')->where('satuan_kerja', $skid)->get();
        $html = '<option value=""> ---- </option>';
        foreach ($departemen as $key) {
            $html.='<option value="'.$key->id.'">'.$key->departemen.'</option>';
        }
        echo $html;
    }
}
