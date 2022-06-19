<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\SatuanKerja;
use App\Models\Level;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        return redirect('/login')->with('error', 'Username / password salah');
    }

    public function registration()
    {
        $satuanKerja = SatuanKerja::select('id', 'satuan_kerja')->get();
        $departemen = Departemen::select('id', 'satuan_kerja', 'departemen')->get();
        $level = Level::select('id', 'jabatan')->get();

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
            return redirect('/listUser')->with('success', 'Registration success!');
        }
    }

    public function listUser()
    {
        $id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $id)->first();
        $data = User::select('name', 'satuan_kerja', 'departemen', 'level')->get();

        $datas = [
            'title' => 'List User',
            'users' => $user,
            'datas' => $data,
            'userLogs' => $data
        ];
        return view('auth/list', $datas);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/login');

        // $request->session()->regenerateToken();

    }
}
