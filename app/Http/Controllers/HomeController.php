<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuratKeluar;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->coba = new SuratKeluar;
    }
    public function index()
    {
        // dd($request->session()->all());
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $userLog = User::get();

        // All Summary
        // Golongan 7


        //Surat Keluar
        if ($user->cabang) {
            $mails = SuratKeluar::where('cabang_asal', $user->cabang)
                ->whereYear('created_at', date("Y"))
                ->latest()->get();
        } elseif ($user->satuan_kerja) {
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->whereYear('created_at', date("Y"))
                ->latest()->get();
        }
        if ($user->levelTable['golongan'] < 99) {
            $countTotalKeluar = $mails->count();
            $countSelesaiKeluar = $mails->where('status', 3)->count();
            $countBelumSelesaiKeluar = $mails->where('status', '!=', 3)->count();
        }

        //Otor
        if ($user->levelTable['golongan'] < 99 && $user->levelTable['golongan'] >= 5) {
            $mails = $this->coba->ambilOtor($user);
            $countOtor = $mails->count();
        } else {
            $countOtor = 0;
        }

        //Surat Masuk
        if ($user->levelTable->golongan == 7) {
            $data = SuratKeluar::with('tujuanSatker')
                ->join('tujuan_satuan_kerjas', 'surat_keluars.id', '=', 'tujuan_satuan_kerjas.memo_id')
                ->where('satuan_kerja_id', $user['satuan_kerja'])->where('status', 3)->latest('tujuan_satuan_kerjas.created_at')->get();
        } elseif ($user->level == 5) {
            $data = SuratKeluar::with('tujuanKantorCabang')
                ->join('tujuan_kantor_cabangs', 'surat_keluars.id', '=', 'tujuan_kantor_cabangs.memo_id')
                ->where('cabang_id', $user['cabang'])->where('status', 3)->latest('tujuan_kantor_cabangs.created_at')->get();
        } elseif ($user->level == 6) {
            $data = SuratKeluar::with('tujuanDepartemen')
                ->join('tujuan_departemens', 'surat_keluars.id', '=', 'tujuan_departemens.memo_id')
                ->where('departemen_id', $user['departemen'])->where('status', 3)->latest('tujuan_departemens.created_at')->get();
        } elseif ($user->satuanKerja['grup'] == 5 && $user->level == 6) {
            $data = SuratKeluar::with('tujuanSatker')
                ->join('tujuan_satuan_kerjas', 'surat_keluars.id', '=', 'tujuan_satuan_kerjas.memo_id')
                ->where('satuan_kerja_id', $user['satuan_kerja'])->where('status', 3)->latest('tujuan_satuan_kerjas.created_at')->get();
        } elseif ($user->level == 10) {
            $data = SuratKeluar::with('tujuanBidangCabang')
                ->join('tujuan_bidang_cabangs', 'surat_keluars.id', '=', 'tujuan_bidang_cabangs.memo_id')
                ->where('bidang_id', $user['bidang_cabang'])->where('status', 3)->latest('tujuan_bidang_cabangs.created_at')->get();
        } else {
            $data = SuratKeluar::with('forward')
                ->join('forwards', 'surat_keluars.id', '=', 'forwards.memo_id')
                ->where('user_id', $id)->where('status', 3)->latest('forwards.created_at')->get();
        }

        if ($user->levelTable['golongan'] < 99) {
            $countBelumSelesaiMasuk = $data->whereNull('tanggal_baca')->count();
            $countSelesaiMasuk = $data->whereNotNull('tanggal_baca')->count();
            $countTotalMasuk = $data->count();

            $datas = [
                'users' => $user,
                'userLogs' => $userLog,
                'countTotalKeluar' => $countTotalKeluar,
                'countSelesaiKeluar' => $countSelesaiKeluar,
                'countBelumSelesaiKeluar' => $countBelumSelesaiKeluar,
                'countSelesaiMasuk' => $countSelesaiMasuk,
                'countBelumSelesaiMasuk' => $countBelumSelesaiMasuk,
                'countTotalMasuk' => $countTotalMasuk,
                'countOtor' => $countOtor,
            ];

            return view('templates.home', $datas);
        } else {
            $datas = [
                'users' => $user,
                'userLogs' => $userLog,
            ];

            return view('templates.homeAdmin', $datas);
        }
    }
}
