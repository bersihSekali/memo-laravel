<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'otor2_by',
        'otor1_by',
        'otor2_by_pengganti',
        'otor1_by_pengganti',
        'created_by',
        'deleted_by',
        'nomor_surat',
        'perihal',
        'satuan_kerja_asal',
        'cabang_asal',
        'departemen_asal',
        'lampiran',
        'tanggal_otor2',
        'tanggal_otor1',
        'status',
        'internal',
        'no_urut'
    ];

    public function satuanKerjaAsal()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_asal');
    }

    public function departemenAsal()
    {
        return $this->belongsTo(Departemen::class, 'departemen_asal');
    }

    public function cabangAsal()
    {
        return $this->belongsTo(Cabang::class, 'cabang_asal');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function otor2By()
    {
        return $this->belongsTo(User::class, 'otor2_by');
    }

    public function otor2ByPengganti()
    {
        return $this->belongsTo(User::class, 'otor2_by_pengganti');
    }

    public function otor1By()
    {
        return $this->belongsTo(User::class, 'otor1_by');
    }

    public function otor1ByPengganti()
    {
        return $this->belongsTo(User::class, 'otor1_by_pengganti');
    }

    public function tujuanSatker()
    {
        return $this->belongsTo(TujuanSatuanKerja::class);
    }

    public function tujuanDepartemen()
    {
        return $this->belongsTo(TujuanSatuanKerja::class);
    }

    public function tujuanKantorCabang()
    {
        return $this->belongsTo(TujuanKantorCabang::class);
    }

    public function tujuanBidangCabang()
    {
        return $this->belongsTo(TujuanBidangCabang::class);
    }

    public function forward()
    {
        return $this->belongsTo(Forward::class);
    }

    public function ambilOtor($user)
    {
        //kepala operasi cabang
        if (($user->levelTable->golongan == 5) && ($user->level == 9)) {
            // sebagai otor2 pengganti
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            // sebagai otor1 pengganti
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::where('cabang_asal', $user->cabang)
                ->where('internal', 2)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest()->get();
        }

        // Kepala dep di bawah direksi
        elseif ($user->levelTable->golongan == 6 && $user->satuanKerja['grup'] == 5) {
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->latest();
            // Antar satuan kerja sebagai otor1_by
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('internal', 2)
                ->where('status', 2)
                ->union($pengganti1)
                ->latest()->get();
        }

        // Associate officer departemen di bawah direksi
        elseif ($user->levelTable['golongan'] >= 4 || $user->satuanKerja['grup'] == 5) {
            // sebagai otor2 pengganti
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            // sebagai otor1 pengganti
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 1)
                ->where('internal', 2)
                ->union($pengganti1)
                ->latest()->get();
        }

        //kepala cabang
        elseif (($user->levelTable->golongan == 6) && ($user->level == 5)) {
            // sebagai otor2 pengganti
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            // sebagai otor1 pengganti
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::where('cabang_asal', $user->cabang)
                ->where('internal', 2)
                ->where('status', 2)
                ->union($pengganti1)
                ->latest()->get();
        }

        // Officer, kepala bidang, golongan 5
        elseif ($user->levelTable->golongan == 5) {
            // sebagai otor2 pengganti
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('status', 1)
                ->latest();
            // sebagai otor1 pengganti
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // sebagai otor 2
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest()->get();
        }

        // Kepala departemen, golongan 6
        elseif (($user->levelTable->golongan == 6) && ($user->level == 6)) {
            // sebagai otor2 pengganti
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            // sebagai otor1 pengganti
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
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('internal', 2)
                ->where('status', 1)
                ->union($antarDepartemen)
                ->latest()->get();
        }

        // Senior officer
        elseif (($user->levelTable->golongan == 6) && ($user->level == 7)) {
            // sebagai otor2 pengganti
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            // sebagai otor1 pengganti
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
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 1)
                ->where('internal', 2)
                ->union($antarDepartemen1)
                ->latest()->get();
        }

        // Kepala satuan kerja
        elseif ($user->levelTable->golongan == 7) {
            $pengganti2 = SuratKeluar::where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->latest();
            // Antar satuan kerja sebagai otor1_by
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('internal', 2)
                ->where('status', 2)
                ->union($pengganti1)
                ->latest()->get();
        }
        return $mails;
    }

    public function columnTujuan($user)
    {
        $memoIdSatker = SuratKeluar::select('id', 'satuan_kerja_asal')
            ->where('satuan_kerja_asal', $user->satuan_kerja)
            ->pluck('id')->toArray();
        $tujuanDepartemen = TujuanDepartemen::select('id', 'memo_id', 'departemen_id', 'all_flag')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanSatker = TujuanSatuanKerja::select('id', 'memo_id', 'satuan_kerja_id', 'all_flag')
            ->whereIn('memo_id', $memoIdSatker)
            ->latest()->get();
        $tujuanCabangs = TujuanKantorCabang::select('id', 'memo_id', 'cabang_id', 'bidang_id', 'all_flag')
            ->whereIn('memo_id', $memoIdSatker)
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

        return $tujuan;
    }
}
