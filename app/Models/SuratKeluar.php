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
        'berkas',
        'lampiran',
        'pesan_tolak',
        'lampiran_tolak',
        'tanggal_tolak',
        'kriteria',
        'isi',
        'tanggal_otor2',
        'tanggal_otor1',
        'draft',
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
        //cabang
        if ($user->cabang) {
            $mails1 = SuratKeluar::where('cabang_asal', $user->cabang)
                ->where('status', 1)
                ->where('draft', 0)
                ->where('otor2_by', $user['id']);
            $mails = SuratKeluar::where('cabang_asal', $user->cabang)
                ->where('status', 2)
                ->where('draft', 0)
                ->where('otor1_by', $user['id'])
                ->union($mails1)
                ->latest()->get();
        }

        // dep di bawah direksi
        elseif ($user->satuanKerja['grup'] == 5) {
            $mails1 = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 1)
                ->where('draft', 0)
                ->where('otor2_by', $user['id']);
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 2)
                ->where('draft', 0)
                ->where('otor1_by', $user['id'])
                ->union($mails1)
                ->latest()->get();
        }

        // Satuan Kerja
        elseif ($user->satuan_kerja) {
            $mails1 = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 1)
                ->where('draft', 0)
                ->where('otor2_by', $user['id']);
            $mails = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('status', 2)
                ->where('draft', 0)
                ->where('otor1_by', $user['id'])
                ->union($mails1)
                ->latest()->get();
        }

        return $mails;
    }

    public function columnTujuan($user)
    {
        $memoIdSatker = SuratKeluar::where('satuan_kerja_asal', $user->satuan_kerja)
            ->pluck('id')->toArray();
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

        return $tujuan;
    }
}
