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

        // // All Summary
        switch ($user->level) {
            case 1: // Home Admin
                $countTotal = SuratMasuk::withTrashed()->count();
                $countTrashed = SuratMasuk::onlyTrashed()->count();
                $datas = [
                    'title' => 'Pencatatan Memo',
                    'countTotal' => $countTotal,
                    'countTrashed' => $countTrashed,
                    'users' => $user
                ];
                return view('templates.home', $datas);

            case 2: // Home Kepala Satuan Kerja
                $countTotal = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->count();
                $countNeedApprove = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->whereRaw('satuan_kerja_asal != satuan_kerja_tujuan') // Antar Divisi
                    ->where('status', 2)
                    ->count();
                $countAprroved = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('nomor_surat', '!=', '')
                    ->count();
                $countRejected = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('status', 0)
                    ->count();
                $datas = [
                    'title' => 'Pencatatan Memo',
                    'countTotal' => $countTotal,
                    'countNeedApprove' => $countNeedApprove,
                    'countApproved' => $countAprroved,
                    'countRejected' => $countRejected,
                    'users' => $user
                ];
                return view('templates.home', $datas);

            case 3: // Home Kepala Departemen
                $countTotal = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->count();
                $countNeedApprove = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('status', 1)
                    ->count();
                $countAprroved = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('nomor_surat', '!=', '')
                    ->count();
                $countRejected = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('status', 0)
                    ->count();
                $datas = [
                    'title' => 'Pencatatan Memo',
                    'countTotal' => $countTotal,
                    'countNeedApprove' => $countNeedApprove,
                    'countApproved' => $countAprroved,
                    'countRejected' => $countRejected,
                    'users' => $user
                ];
                return view('templates.home', $datas);

            case 4: // Home Senior Officer
                $countTotal = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->count();
                $countNeedApprove = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('status', 1)
                    ->count();
                $countAprroved = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('nomor_surat', '!=', '')
                    ->count();
                $countRejected = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('status', 0)
                    ->count();
                $datas = [
                    'title' => 'Pencatatan Memo',
                    'countTotal' => $countTotal,
                    'countNeedApprove' => $countNeedApprove,
                    'countApproved' => $countAprroved,
                    'countRejected' => $countRejected,
                    'users' => $user
                ];
                return view('templates.home', $datas);

            case 5: // Home Officer
                $countTotal = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->count();
                $countNeedApprove = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('status', 1)
                    ->count();
                $countAprroved = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('nomor_surat', '!=', '')
                    ->count();
                $countRejected = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
                    ->where('status', 0)
                    ->count();
                $datas = [
                    'title' => 'Pencatatan Memo',
                    'countTotal' => $countTotal,
                    'countNeedApprove' => $countNeedApprove,
                    'countApproved' => $countAprroved,
                    'countRejected' => $countRejected,
                    'users' => $user
                ];
                return view('templates.home', $datas);
            default:
        }
    }
}
