<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Storage;
use App\Models\TujuanSatuanKerja;
use App\Models\TujuanKantorCabang;
use App\Models\TujuanDepartemen;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->suratKeluar = new SuratKeluar;
    }
    public function getSatuanKerja(Request $request)
    {
        echo $skid = $request->post('skid');
        $departemen = DB::table('departemens')->where('satuan_kerja', $skid)->get();
        $html = '<option value=""> ---- </option>';
        foreach ($departemen as $key) {
            $html .= '<option value="' . $key->id . '">' . $key->departemen . '</option>';
        }
        echo $html;
    }

    public function getLevel(Request $request)
    {
        echo $lid = $request->post('lid');
        $html = '';
        if ($lid == 2) {
            $html .= '<div class="mb-3">
            <label for="departemen" class="form-label">Department</label>
            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="departemen" id="departemen">
                <option value=""> ---- </option>
            </select>
        </div>';
        }

        echo $html;
    }

    public function listSuratHapus() // get Surat deleted only
    {
        $id = Auth::id();
        $user = User::find($id);
        $userLog = User::get();

        $mails = SuratKeluar::onlyTrashed()->get();

        //untuk cek all flag, Untuk view column tujuan 
        $memoIdSatker = SuratKeluar::pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

        $seluruhDepartemenMemoId = $tujuanDepartemen->where('departemen_id', 1)->pluck('memo_id')->toArray();
        $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
        $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();

        $tujuan = [
            'tujuanDepartemen' => $tujuanDepartemen,
            'tujuanSatker' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'seluruhDepartemenMemoId' => $seluruhDepartemenMemoId,
            'seluruhSatkerMemoId' => $seluruhSatkerMemoId,
            'seluruhCabangMemoId' => $seluruhCabangMemoId
        ];

        $datas = [
            'users' => $user,
            'datas' => $mails,
            'userLogs' => $userLog,
            'tujuanDepartemens' => $tujuan['tujuanDepartemen'],
            'tujuanSatkers' => $tujuan['tujuanSatker'],
            'tujuanCabangs' => $tujuan['tujuanCabangs'],
            'seluruhDepartemenMemoIds' => $tujuan['seluruhDepartemenMemoId'],
            'seluruhSatkerMemoIds' => $tujuan['seluruhSatkerMemoId'],
            'seluruhCabangMemoIds' => $tujuan['seluruhCabangMemoId']
        ];

        return view('nomorSurat.deleted', $datas);
    }

    public function hapusPermanen() // Force Delete
    {
        $user_id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $user_id)->first();
        $datas = SuratKeluar::onlyTrashed()->get();

        foreach ($datas as $data) {
            Storage::delete($data->berkas);
            Storage::delete($data->lampiran);

            // Hapus child tujuan cabang
            TujuanKantorCabang::select('memo_id')->where('memo_id', $data->id)->forceDelete();

            // Hapus tujuan departemen
            TujuanDepartemen::select('memo_id')->where('memo_id', $data->id)->forceDelete();

            // Hapus tujuan satuan kerja
            TujuanSatuanKerja::select('memo_id')->where('memo_id', $data->id)->forceDelete();
        }

        // Update audit trail
        $audit = [
            'users' => $user->id,
            'kegiatan' => 'config.constants.FORCE_DELETE',
            'deskripsi' => null
        ];
        storeAudit($audit);

        SuratKeluar::where('deleted_at', '!=', null)->forceDelete();

        return redirect('/nomorSurat/suratHapus')->with('success', 'Surat berhasil dibersihkan');
    }

    public function allSurat() // get all Surat
    {
        $id = Auth::id();
        $user = User::find($id);
        if ($user->id != 1) {
            dd('Bukan Admin');
        }
        $mails = SuratKeluar::withTrashed()->where('draft', 0)
            ->latest()->get();
        $userLog = User::all();

        //untuk cek all flag, Untuk view column tujuan 
        $memoIdSatker = SuratKeluar::pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

        $seluruhDepartemenMemoId = $tujuanDepartemen->where('departemen_id', 1)->pluck('memo_id')->toArray();
        $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
        $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();

        $tujuan = [
            'tujuanDepartemen' => $tujuanDepartemen,
            'tujuanSatker' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'seluruhDepartemenMemoId' => $seluruhDepartemenMemoId,
            'seluruhSatkerMemoId' => $seluruhSatkerMemoId,
            'seluruhCabangMemoId' => $seluruhCabangMemoId
        ];

        $datas = [
            'users' => $user,
            'tujuanDepartemens' => $tujuan['tujuanDepartemen'],
            'tujuanSatkers' => $tujuan['tujuanSatker'],
            'tujuanCabangs' => $tujuan['tujuanCabangs'],
            'userLogs' => $userLog,
            'datas' => $mails,
            'seluruhDepartemenMemoIds' => $tujuan['seluruhDepartemenMemoId'],
            'seluruhSatkerMemoIds' => $tujuan['seluruhSatkerMemoId'],
            'seluruhCabangMemoIds' => $tujuan['seluruhCabangMemoId'],
        ];

        return view('admin.index', $datas);
    }

    public function listUser()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $data = User::get();

        $datas = [
            'title' => 'List User',
            'users' => $user,
            'datas' => $data,
            'userLogs' => $data
        ];
        return view('admin/list', $datas);
    }
}
