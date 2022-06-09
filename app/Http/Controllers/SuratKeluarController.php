<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Auth;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use App\Models\TujuanDepartemen;
use App\Models\TujuanSatuanKerja;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $user = User::find($id);

        if ($user->levelTable['golongan'] == 7) {
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->latest()->get();
        } else {
            $mails = SuratKeluar::where('created_by', $id)
                ->latest()->get();
        }

        $datas = [
            'title' => 'Surat Keluar',
            'users' => $user,
            'mails' => $mails
        ];

        return view('suratKeluar.index', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $pengganti = User::all();

        $departemen = Departemen::all();
        $satuanKerja = SatuanKerja::all();
        $kantorCabang = Departemen::where('grup', 2)->get();
        $departemenDireksi = Departemen::where('grup', 4)->get();

        $datas = [
            'title' => 'Tambah Surat',
            'satuanKerjas' => $satuanKerja,
            'departemens' => $departemen,
            'kantorCabangs' => $kantorCabang,
            'departemenDireksis' => $departemenDireksi,
            'users' => $user,
            'penggantis' => $pengganti
        ];

        return view('suratKeluar.create', $datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // mempersiapkan data unit kerja dan cabang
        $satuanKerja = SatuanKerja::all();
        $kantorCabang = Departemen::where('grup', 2)->get();
        $departemenDireksi = Departemen::where('grup', 4)->get();

        $validated = $request->validate([
            'created_by' => 'required',
            'satuan_kerja_asal' => 'required',
            'perihal' => 'required',
            'lampiran' => 'mimes:pdf',
        ]);
        $validated['departemen_asal'] = $request->departemen_asal;
        $validated['otor2_by_pengganti'] = $request->tunjuk_otor2_by;
        $validated['otor1_by_pengganti'] = $request->tunjuk_otor1_by;

        //ambil request tujuan
        $tujuanUnitKerja = $request['tujuan_unit_kerja'];
        $tujuanDepartemenDireksi = $request['tujuan_departemen_direksi'];
        $tujuanKantorCabang = $request['tujuan_kantor_cabang'];

        // get file and store
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $originalFileName = $file->getClientOriginalName();
            $fileName = preg_replace('/[^.\w\s\pL]/', '', $originalFileName);
            $fileName = date("YmdHis") . '_' . $fileName;
            $validated['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);
        }

        $create = SuratKeluar::create($validated);
        //ambil id surat yang baru dibuat
        $idSurat = $create->id;

        if (!$create) {
            return redirect('/nomorSurat/create')->with('error', 'Pembuatan surat gagal');
        }

        //tujuan berdasarkan unit kerja
        if ($tujuanUnitKerja[0] == 'unit_kerja') {
            foreach ($satuanKerja as $item) {
                TujuanSatuanKerja::create([
                    'memo_id' => $idSurat,
                    'satuan_kerja_id' => $item->id,
                ]);
            };
        } elseif ($tujuanUnitKerja) {
            foreach ($tujuanUnitKerja as $item) {
                TujuanSatuanKerja::create([
                    'memo_id' => $idSurat,
                    'satuan_kerja_id' => $item,
                ]);
            };
        };

        //tujuan berdasarkan cabang
        if ($tujuanKantorCabang[0] == 'kantor_cabang') {
            foreach ($kantorCabang as $item) {
                TujuanDepartemen::create([
                    'memo_id' => $idSurat,
                    'departemen_id' => $item->id,
                ]);
            };
        } elseif ($tujuanKantorCabang) {
            foreach ($tujuanKantorCabang as $item) {
                TujuanDepartemen::create([
                    'memo_id' => $idSurat,
                    'departemen_id' => $item,
                ]);
            };
        };

        //tujuan berdasarkan departemen di bawah direksi
        if ($tujuanDepartemenDireksi[0] == 'departemen_direksi') {
            foreach ($departemenDireksi as $item) {
                TujuanDepartemen::create([
                    'memo_id' => $idSurat,
                    'departemen_id' => $item->id,
                ]);
            };
        } elseif ($tujuanDepartemenDireksi) {
            foreach ($tujuanDepartemenDireksi as $item) {
                TujuanDepartemen::create([
                    'memo_id' => $idSurat,
                    'departemen_id' => $item,
                ]);
            };
        };

        return redirect('/suratKeluar')->with('success', 'Pembuatan surat berhasil');
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
