<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Models\SatuanKerja;
use App\Models\Departemen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $mails = SuratMasuk::where('satuan_kerja_asal', $user->satuan_kerja)->latest()->get();

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
        $satuanKerja = SatuanKerja::all();

        $datas = [
            'title' => 'Tambah Surat',
            'satuanKerjas' => $satuanKerja,
            'users' => $user
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
        $validated = $request->validate([
            'created_by' => 'required',
            'satuan_kerja_asal' => 'required',
            'satuan_kerja_tujuan' => 'required',
            'departemen_tujuan' => 'required',
            'perihal' => 'required',
            'lampiran' => 'required|mimes:pdf',
            'no_urut' => 'required'
        ]);
        $validated['departemen_asal'] = $request->departemen_asal;
        $file = $request->file('lampiran');
        $fileName = $file->getClientOriginalName();
        $validated['lampiran'] = $request->file('lampiran')->storeAs('lampiran', $fileName);

        $mails = SuratMasuk::where('satuan_kerja_asal', $request->satuan_kerja_asal)->max('no_urut');
        $no_urut = $validated['no_urut'] + $mails;
        $validated['no_urut'] = $no_urut;

        $create = SuratMasuk::create($validated);

        if (!$create) {
            return redirect('/nomorSurat/create')->with('error', 'Pembuatan surat gagal');
        }

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
        $mails = SuratMasuk::find($id);
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
    public function update(Request $request, $id) // Force Delete
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = Auth::id();
        $user = User::where('id', $user_id)->first();

        $datas = SuratMasuk::find($id);
        $update[] = $datas['deleted_by'] = $user->name;

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
        // $departemen = DB::table('departemens')->where('satuan_kerja', $lid)->get();
        // $html = '<option value=""> ---- </option>';
        // foreach ($departemen as $key) {
        //     $html .= '<option value="' . $key->id . '">' . $key->departemen . '</option>';
        // }
        echo $html;
    }

    public function listSuratHapus(Request $request)
    {
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        $mails = SuratMasuk::onlyTrashed()->get();

        $datas = [
            'users' => $user,
            'mails' => $mails
        ];

        return view('nomorSurat.deleted', $datas);
    }

    public function hapusPermanen()
    {
        SuratMasuk::whereNotNull('deleted_at')->forceDelete();

        return redirect('/nomorSurat/suratHapus')->with('success', 'Surat berhasil dibersihkan');
    }
}
