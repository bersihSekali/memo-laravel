<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index(){
        $datas = [
            'title' => 'Login',
            'judul' => 'Login'
        ];
        return view('auth/index', $datas);
    }

    public function authenticate(Request $request){
        $validated = $request -> validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
 
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'name' => 'The provided credentials do not match our records.',
        ])->onlyInput('name');
    }

    public function registration(){
        $datas = [
            'title' => 'registration',
            'judul' => 'registration'
        ];
        return view('auth/registration', $datas);
    }

    public function register(Request $request){
        $validated = $request -> validate([
            'name' => 'required|max:255|unique:users',
            'satuan_kerja' => 'required',
            'departemen' => 'required',
            'password' => 'required|min:6|max:255'
        ]);

        $validated['password'] = hash::make($validated['password']);

        $registration = User::create($validated);

        if(!$registration){
            return redirect('/registration')->with('error', 'Registration failed!');    
        } else {
            return redirect('/login')->with('success', 'Registration success!');
        }
    }

    public function logout(Request $request) {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/login');
    }
}
