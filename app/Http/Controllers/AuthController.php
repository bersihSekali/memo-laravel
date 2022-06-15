<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\Departemen;
use App\Models\SatuanKerja;
use App\Models\Level;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        $datas = [
            'title' => 'Login',
            'judul' => 'Login'
        ];
        return view('auth/index', $datas);
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'password' => 'required|string|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return redirect('/login')->with('success', 'Login gagal');
    }

    public function registration()
    {

        $satuanKerja = SatuanKerja::all();
        $departemen = Departemen::all();
        $level = Level::all();

        $datas = [
            'title' => 'registration',
            'judul' => 'registration',
            'satuanKerja' => $satuanKerja,
            'departemen' => $departemen,
            'level' => $level
        ];
        return view('auth/registration', $datas);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:users',
            'satuan_kerja' => 'required',
            'password' => 'required|string|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'level' => 'required'
        ]);

        $validated['departemen'] = $request->departemen;
        $validated['password'] = hash::make($validated['password']);

        $registration = User::create($validated);

        if (!$registration) {
            return redirect('/registration')->with('error', 'Registration failed!');
        } else {
            return redirect('/login')->with('success', 'Registration success!');
        }
    }

    public function listUser()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $data = User::All();

        $datas = [
            'title' => 'List User',
            'users' => $user,
            'datas' => $data
        ];
        return view('auth/list', $datas);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        if (!session()) {
            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/login');
        }

        return redirect('/login');
    }
}
