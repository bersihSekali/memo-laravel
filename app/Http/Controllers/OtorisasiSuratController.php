<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\SuratKeluar;
use App\Models\TujuanDepartemen;
use App\Models\TujuanKantorCabang;
use App\Models\TujuanSatuanKerja;
use App\Models\User;


class OtorisasiSuratController extends Controller
{
    public function __construct()
    {
        $this->coba = new SuratKeluar;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $id)->first();

        $mails = $this->coba->ambilOtor($user);

        // Untuk view column tujuan
        $memoIdSatker = SuratKeluar::select('id', 'satuan_kerja_asal')
            ->where('satuan_kerja_asal', $user->satuan_kerja)
            ->pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::select('id', 'memo_id', 'departemen_id')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::select('id', 'memo_id', 'satuan_kerja_id')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::select('id', 'memo_id', 'cabang_id', 'bidang_id')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

        //untuk cek all flag
        $seluruhDepartemenMemoId = $tujuanDepartemen->where('departemen_id', 1)->pluck('memo_id')->toArray();
        $seluruhSatkerMemoId = $tujuanSatker->where('satuan_kerja_id', 1)->pluck('memo_id')->toArray();
        $seluruhCabangMemoId = $tujuanCabangs->where('cabang_id', 1)->pluck('memo_id')->toArray();

        $datas = [
            'title' => 'Daftar Otorisasi Surat',
            'datas' => $mails,
            'tujuanDepartemens' => $tujuanDepartemen,
            'tujuanSatkers' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'users' => $user,
            'seluruhDepartemenMemoIds' => $seluruhDepartemenMemoId,
            'seluruhSatkerMemoIds' => $seluruhSatkerMemoId,
            'seluruhCabangMemoIds' => $seluruhCabangMemoId,
        ];
        // dd($datas);
        return view('otorisasi.index', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) // Approved by otor 2
    {
        $user_id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $user_id)->first();

        $datas = SuratKeluar::select(
            'id',
            'created_at',
            'otor1_by',
            'otor2_by',
            'otor1_by_pengganti',
            'otor2_by_pengganti',
            'created_by',
            'tanggal_otor2',
            'tanggal_otor1',
            'nomor_surat',
            'perihal',
            'satuan_kerja_asal',
            'departemen_asal',
            'lampiran',
            'pesan_tolak',
            'internal',
            'status',
            'deleted_by',
            'deleted_at'
        )
            ->find($id);

        // Update tanggal otor
        $update[] = $datas['tanggal_otor2'] = date("Y-m-d H:i:s");

        // Update otor_by 
        if ($datas->otor2_by_pengganti) {
            if ($datas->otor2_by_pengganti != $user->id) {
                $datas->otor2_by_pengganti = null;
                $datas['otor2_by'] = $user->id;
            } else {
                $datas['otor2_by'] = $user->id;
            }
        } else {
            $datas['otor2_by'] = $user->id;
        }
        array_push($update, $datas['otor2_by']);

        // Nomor surat antar departemen
        $datas['status'] = 2;
        array_push($update, $datas['status']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        $file = $request->file('lampiran');
        $originalFileName = $file->getClientOriginalName();
        $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
        $fileName = date("YmdHis") . '_' . $fileName;
        $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        array_push($update, $datas['lampiran']);

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            // Update audit trail
            $audit = [
                'users' => $user->id,
                'aktifitas' => config('constants.OTOR2'),
                'deskripsi' => $datas['id']
            ];
            storeAudit($audit);

            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) // Disapproved / Revision by otor 2
    {
        $user_id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $user_id)->first();

        $datas = SuratKeluar::select(
            'id',
            'created_at',
            'otor1_by',
            'otor2_by',
            'otor1_by_pengganti',
            'otor2_by_pengganti',
            'created_by',
            'tanggal_otor2',
            'tanggal_otor1',
            'nomor_surat',
            'perihal',
            'satuan_kerja_asal',
            'departemen_asal',
            'lampiran',
            'pesan_tolak',
            'internal',
            'status',
            'deleted_by',
            'deleted_at'
        )
            ->find($id);
        // Update otor status
        $update[] = $datas['status'] = 0;

        // Update pesan tolak
        $datas['pesan_tolak'] = $request->pesan_tolak;
        array_push($update, $datas['pesan_tolak']);

        // Hapus nomor urut
        $datas['no_urut'] = 0;
        array_push($update, $datas['no_urut']);

        // Update tanggal otor
        $datas['tanggal_otor2'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_otor2']);

        // Update otor_by 
        if ($datas->otor2_by_pengganti) {
            if ($datas->otor2_by_pengganti != $user->id) {
                $datas->otor2_by_pengganti = null;
                $datas['otor2_by'] = $user->id;
            } else {
                $datas['otor2_by'] = $user->id;
            }
        } else {
            $datas['otor2_by'] = $user->id;
        }
        array_push($update, $datas['otor2_by']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = date("YmdHis") . '_' . $fileName;
            $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
            array_push($update, $datas['lampiran']);
        }

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            // Update audit trail
            $audit = [
                'users' => $user->id,
                'aktifitas' => config('constants.REJECT2'),
                'deskripsi' => $datas['id']
            ];
            storeAudit($audit);

            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    public function approvedOtorSatu(Request $request, $id)
    { // Approved by Otor 1
        $user_id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $user_id)->first();

        $datas = SuratKeluar::select(
            'id',
            'created_at',
            'otor1_by',
            'otor2_by',
            'otor1_by_pengganti',
            'otor2_by_pengganti',
            'created_by',
            'tanggal_otor2',
            'tanggal_otor1',
            'nomor_surat',
            'perihal',
            'no_urut',
            'satuan_kerja_asal',
            'departemen_asal',
            'lampiran',
            'pesan_tolak',
            'internal',
            'status',
            'deleted_by',
            'deleted_at'
        )
            ->find($id);
        // Update otor status
        $update[] = $datas['status'] = 3;

        // Update tanggal otor
        $datas['tanggal_otor1'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_otor1']);

        // Update otor_by 
        if ($datas->otor1_by_pengganti) {
            if ($datas->otor1_by_pengganti != $user->id) {
                $datas->otor1_by_pengganti = null;
                $datas['otor1_by'] = $user->id;
            } else {
                $datas['otor1_by'] = $user->id;
            }
        } else {
            $datas['otor1_by'] = $user->id;
        }
        array_push($update, $datas['otor1_by']);

        // Compare latest date with now to reset no_urut
        // $lastSuratMasuk = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)
        //     ->latest()->first();
        // $nowDate = date("Y-m-d H:i:s");
        // if ($lastSuratMasuk == '') {
        //     $datas['no_urut'] = 1;
        // } else if (date("Y", strtotime($nowDate)) != date("Y", strtotime($lastSuratMasuk->created_at))) {
        //     $datas['no_urut'] = 1;
        // } else {
        //     $mails = SuratMasuk::where('internal', 1)
        //         ->max('no_urut');
        //     $no_urut = $mails + 1;
        //     $datas['no_urut'] = $no_urut;
        // }

        // $lastSuratMasuk = SuratMasuk::where('departemen_asal', $datas->departemen_asal)
        //     ->latest()->first();
        // if ($lastSuratMasuk == '') {
        //     $datas->no_urut = 1;
        // } else {
        //     $temp = SuratMasuk::where('departemen_asal', $datas->departemen_asal)
        //         ->max('no_urut');
        //     $no_urut = $temp + 1;
        //     $datas->no_urut = $no_urut;
        // }

        $tahun = date("Y", strtotime($datas['tanggal_otor1']));
        if ($datas->internal == 1) {
            $lastSuratKeluar = SuratKeluar::select('departemen_asal', 'no_urut')
                ->where('departemen_asal', $datas->departemen_asal)
                ->latest()->first();
            if ($lastSuratKeluar == '') {
                $datas->no_urut = 1;
            } elseif ($tahun != date("Y", strtotime($datas->tanggal_otor1))) {
                $datas->no_urut = 1;
            } else {
                $temp = SuratKeluar::select('departemen_asal', 'no_urut')
                    ->where('departemen_asal', $datas->departemen_asal)
                    ->max('no_urut');
                $no_urut = $temp + 1;
                $datas->no_urut = $no_urut;
            }
        } else {
            $lastSuratKeluar = SuratKeluar::select('satuan_kerja_asal', 'internal')
                ->where('satuan_kerja_asal', $datas->satuan_kerja_asal)
                ->where('internal', 2)
                ->latest()->first();
            if ($lastSuratKeluar == '') {
                $datas->no_urut = 1;
            } elseif ($tahun != date("Y", strtotime($datas->tanggal_otor1))) {
                $datas->no_urut = 1;
            } else {
                $temp = SuratKeluar::select('satuan_kerja_asal', 'internal', 'no_urut')
                    ->where('satuan_kerja_asal', $datas->satuan_kerja_asal)
                    ->where('internal', 2)
                    ->max('no_urut');
                $no_urut = $temp + 1;
                $datas->no_urut = $no_urut;
            }
        }
        array_push($update, $datas->no_urut);

        // Nomor surat antar divisi / satuan kerja
        if ($datas->internal == 2) {
            $no_surat = sprintf("%03d", $datas['no_urut']) . '/MO/' . $datas->satuanKerjaAsal['inisial'] . '/' . $tahun;
        } else { // Nomor surat antar departemen
            $no_surat = sprintf("%03d", $datas['no_urut']) . '/MO/' . $datas->departemenAsal['inisial'] . '/' . $tahun;
        }
        $datas['nomor_surat'] = $no_surat;
        array_push($update, $datas['nomor_surat']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        $file = $request->file('lampiran');
        $originalFileName = $file->getClientOriginalName();
        $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
        $fileName = date("YmdHis") . '_' . $fileName;
        $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        array_push($update, $datas['lampiran']);

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            // Update audit trail
            $audit = [
                'users' => $user->id,
                'aktifitas' => config('constants.OTOR1'),
                'deskripsi' => $datas['id']
            ];
            storeAudit($audit);

            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    public function disApprovedOtorSatu(Request $request, $id)
    { // Disapproved by Otor 1
        $user_id = Auth::id();
        $user = User::select('id', 'name', 'satuan_kerja', 'departemen', 'level')
            ->where('id', $user_id)->first();

        $datas = SuratKeluar::select(
            'id',
            'created_at',
            'otor1_by',
            'otor2_by',
            'otor1_by_pengganti',
            'otor2_by_pengganti',
            'created_by',
            'tanggal_otor2',
            'tanggal_otor1',
            'nomor_surat',
            'perihal',
            'satuan_kerja_asal',
            'departemen_asal',
            'lampiran',
            'pesan_tolak',
            'internal',
            'status',
            'deleted_by',
            'deleted_at'
        )
            ->find($id);
        // Update otor status
        $update[] = $datas['status'] = 0;

        // Update pesan tolak
        $datas['pesan_tolak'] = $request->pesan_tolak;
        array_push($update, $datas['pesan_tolak']);

        // Hapus nomor urut
        $datas['no_urut'] = 0;
        array_push($update, $datas['no_urut']);

        // Update tanggal otor
        $datas['tanggal_otor1'] = date("Y-m-d H:i:s");
        array_push($update, $datas['tanggal_otor1']);

        // Update otor_by 
        if ($datas->otor1_by_pengganti) {
            if ($datas->otor1_by_pengganti != $user->id) {
                $datas->otor1_by_pengganti = null;
                $datas['otor1_by'] = $user->id;
            } else {
                $datas['otor1_by'] = $user->id;
            }
        } else {
            $datas['otor1_by'] = $user->id;
        }
        array_push($update, $datas['otor1_by']);

        // Update lampiran
        if ($datas->lampiran) {
            Storage::delete($datas->lampiran);
        }

        // get file and store
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = date("YmdHis") . '_' . $fileName;
            $datas['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
            array_push($update, $datas['lampiran']);
        }

        $datas->update($update);

        if (!$datas) {
            return redirect('/otorisasi')->with('error', 'Update data failed!');
        } else {
            // Update audit trail
            $audit = [
                'users' => $user->id,
                'aktifitas' => config('constants.REJECT1'),
                'deskripsi' => $datas['id']
            ];
            storeAudit($audit);

            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }
}
