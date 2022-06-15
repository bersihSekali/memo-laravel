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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        // Untuk view column tujuan
        $memoIdSatker = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
            ->pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::whereIn('memo_id', $memoIdSatker)
            ->latest()->get();

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

        $datas = [
            'title' => 'Daftar Otorisasi Surat',
            'datas' => $mails,
            'tujuanDepartemens' => $tujuanDepartemen,
            'tujuanSatkers' => $tujuanSatker,
            'tujuanCabangs' => $tujuanCabangs,
            'users' => $user
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
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);

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
                'aktifitas' => 'config.constants.OTOR2',
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
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);
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
                'aktifitas' => 'config.constants.REJECT2',
                'deskripsi' => $datas['id']
            ];
            storeAudit($audit);

            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    public function approvedOtorSatu(Request $request, $id)
    { // Approved by Otor 1
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);
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
            $lastSuratKeluar = SuratKeluar::where('departemen_asal', $datas->departemen_asal)
                ->latest()->first();
            if ($lastSuratKeluar == '') {
                $datas->no_urut = 1;
            } elseif ($tahun != date("Y", strtotime($datas->tanggal_otor1))) {
                $datas->no_urut = 1;
            } else {
                $temp = SuratKeluar::where('departemen_asal', $datas->departemen_asal)
                    ->max('no_urut');
                $no_urut = $temp + 1;
                $datas->no_urut = $no_urut;
            }
        } else {
            $lastSuratKeluar = SuratKeluar::where('satuan_kerja_asal', $datas->satuan_kerja_asal)
                ->where('internal', 2)
                ->latest()->first();
            if ($lastSuratKeluar == '') {
                $datas->no_urut = 1;
            } elseif ($tahun != date("Y", strtotime($datas->tanggal_otor1))) {
                $datas->no_urut = 1;
            } else {
                $temp = SuratKeluar::where('satuan_kerja_asal', $datas->satuan_kerja_asal)
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
                'aktifitas' => 'config.constants.OTOR1',
                'deskripsi' => $datas['id']
            ];
            storeAudit($audit);

            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }

    public function disApprovedOtorSatu(Request $request, $id)
    { // Disapproved by Otor 1
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);
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
                'aktifitas' => 'config.constants.REJECT1',
                'deskripsi' => $datas['id']
            ];
            storeAudit($audit);

            return redirect('/otorisasi')->with('success', 'Update data success!');
        }
    }
}
