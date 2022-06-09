<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\User;
use App\Models\SatuanKerja;
use App\Models\TujuanDepartemen;
use App\Models\TujuanSatuanKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class NomorSuratController extends Controller
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
        $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)->latest()->get();

        $datas = [
            'title' => 'Daftar Semua Surat',
            'datas' => $mails,
            'users' => $user
        ];

        // dd($datas);

        return view('nomorSurat.index', $datas);
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

        return view('nomorSurat.create', $datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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

        $idSurat = $create->id;

        if (!$create) {
            return redirect('/nomorSurat/create')->with('error', 'Pembuatan surat gagal');
        }

        if ($tujuanUnitKerja[0] == 'unit_kerja') {
            foreach ($satuanKerja as $item) {
                $create = TujuanSatuanKerja::create([
                    'memo_id' => $idSurat,
                    'satuan_kerja_id' => $item->id,
                ]);
            }
        };

        return redirect('/nomorSurat')->with('success', 'Pembuatan surat berhasil');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mails = SuratKeluar::find($id);
        $datas = [
            'title' => 'Detil Surat',
            'datas' => $mails
        ];

        return view('nomorSurat.index', $datas);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) // Soft Delete
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratKeluar::find($id);
        $update[] = $datas['deleted_by'] = $user->name;

        Storage::delete($datas->lampiran);

        $datas['no_urut'] = 0;
        array_push($update, $datas['no_urut']);

        $datas->update($update);

        $datas->delete();

        return redirect('/nomorSurat')->with('success', 'Surat berhasil dihapus');
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
        $user = User::where('id', $id)->first();

        $mails = SuratKeluar::onlyTrashed()->get();

        $datas = [
            'users' => $user,
            'datas' => $mails
        ];

        return view('nomorSurat.deleted', $datas);
    }

    public function hapusPermanen() // Force Delete
    {
        $datas = SuratKeluar::onlyTrashed()->get();
        foreach ($datas as $data) {
            Storage::delete($data->lampiran);
        }
        SuratKeluar::whereNotNull('deleted_at')->forceDelete();

        return redirect('/nomorSurat/suratHapus')->with('success', 'Surat berhasil dibersihkan');
    }

    public function allSurat() // get all Surat
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();
        $mails = SuratKeluar::withTrashed()->get();

        $datas = [
            'users' => $user,
            'datas' => $mails
        ];

        return view('nomorSurat.all', $datas);
    }
}
