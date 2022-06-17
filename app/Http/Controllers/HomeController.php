<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $userLog = User::all();

        // All Summary
        // Golongan 7


        //Surat Keluar
        $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
            ->whereYear('created_at', date("Y"))
            ->latest()->get();
        $countTotalKeluar = $mails->count();
        $countSelesaiKeluar = $mails->where('status', 3)->count();
        $countBelumSelesaiKeluar = $mails->where('status', '!=', 3)->count();
        //Otor
        // Officer, kepala bidang, kepala operasi cabang, kepala cabang pembantu golongan 5
        if ($user->levelTable->golongan == 5) {
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest()->get();
        }

        // Kepala departemen, golongan 6
        elseif (($user->levelTable->golongan == 6) && ($user->level == 6)) {
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // antar departemen sebagai otor1_by
            $antarDepartemen = SuratKeluar::where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 2)
                ->union($pengganti1)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::where('internal', 2)
                ->where('status', 1)
                ->union($antarDepartemen)
                ->latest()->get();
        }

        // Senior officer
        elseif (($user->levelTable->golongan == 6) && ($user->level == 7)) {
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // antar departemen sebagai otor2_by
            $antarDepartemen2 = SuratKeluar::where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest();
            // antar departemen sebagai otor1_by
            $antarDepartemen1 = SuratKeluar::where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 2)
                ->where('otor2_by', '!=', $user->id)
                ->union($antarDepartemen2)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::where('status', 1)
                ->where('internal', 2)
                ->union($antarDepartemen1)
                ->latest()->get();
        }

        // Kepala satuan kerja
        elseif ($user->levelTable->golongan == 7) {
            $pengganti = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->latest();
            // Antar satuan kerja sebagai otor1_by
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('internal', 2)
                ->where('status', 2)
                ->union($pengganti)
                ->latest()->get();
        }
        $countOtor = $mails->count();

        //Surat Masuk
        if ($user->levelTable->golongan == 7) {
            $data = SuratKeluar::with('tujuanSatker')
                ->join('tujuan_satuan_kerjas', 'surat_keluars.id', '=', 'tujuan_satuan_kerjas.memo_id')
                ->where('satuan_kerja_id', $user['satuan_kerja'])->where('status', 3)->latest('tujuan_satuan_kerjas.created_at')->get();
        } elseif ($user->levelTable->golongan == 6) {
            $data = SuratKeluar::with('tujuanDepartemen')
                ->join('tujuan_departemens', 'surat_keluars.id', '=', 'tujuan_departemens.memo_id')
                ->where('departemen_id', $user['departemen'])->where('status', 3)->latest('tujuan_departemens.created_at')->get();
        } elseif ($user->levelTable['golongan'] <= 5) {
            $data = SuratKeluar::with('forward')
                ->join('forwards', 'surat_keluars.id', '=', 'forwards.memo_id')
                ->where('user_id', $id)->where('status', 3)->latest('forwards.created_at')->get();
        }
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
    }
}
