<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'otor2_by',
        'otor1_by',
        'created_by',
        'nomor_surat',
        'perihal',
        'satuan_kerja_asal',
        'departemen_asal',
        'satuan_kerja_tujuan',
        'departemen_tujuan',
        'lampiran',
        'checker',
        'tanggal_disposisi',
        'satuan_kerja_tujuan_disposisi',
        'departemen_tujuan_disposisi',
        'pesan_disposisi',
        'lampiran_disposisi',
        'tanggal_selesai',
        'tanggal_otor2',
        'tanggal_otor1',
        'status',
        'target',
        'no_urut'
    ];

    public function satuanKerjaAsal()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_asal');
    }

    public function satuanKerjaTujuan()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_tujuan');
    }

    public function departemenAsal()
    {
        return $this->belongsTo(Departemen::class, 'departemen_asal');
    }

    public function departemenTujuan()
    {
        return $this->belongsTo(Departemen::class, 'departemen_tujuan');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function otor2By()
    {
        return $this->belongsTo(User::class, 'otor2_by');
    }

    public function otor1By()
    {
        return $this->belongsTo(User::class, 'otor1_by');
    }

    public function checkerUser()
    {
        return $this->belongsTo(User::class, 'checker');
    }

    public function satuanKerjaDisposisi()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_tujuan_disposisi');
    }

    public function departemenDisposisi()
    {
        return $this->belongsTo(Departemen::class, 'departemen_tujuan_disposisi');
    }

    public function checkerUserDisposisi()
    {
        return $this->belongsTo(User::class, 'checker_disposisi');
    }
}
