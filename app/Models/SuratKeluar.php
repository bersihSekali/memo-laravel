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

    public function forward()
    {
        return $this->belongsTo(Forward::class);
    }

    public function ambilOtor($user)
    {
        // Officer, kepala bidang, kepala operasi cabang, kepala cabang pembantu golongan 5
        if ($user->levelTable->golongan == 5) {
            $pengganti2 = SuratKeluar::select(
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
                ->where('otor2_by_pengganti', $user->id)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::select(
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
                ->where('otor1_by_pengganti', $user->id)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            $mails = SuratKeluar::select(
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
                ->where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest()->get();
        }

        // Kepala departemen, golongan 6
        elseif (($user->levelTable->golongan == 6) && ($user->level == 6)) {
            $pengganti2 = SuratKeluar::select(
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
                ->where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::select(
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
                ->where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // antar departemen sebagai otor1_by
            $antarDepartemen = SuratKeluar::select(
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
                ->where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 2)
                ->union($pengganti1)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::select(
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
                ->where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('internal', 2)
                ->where('status', 1)
                ->union($antarDepartemen)
                ->latest()->get();
        }

        // Senior officer
        elseif (($user->levelTable->golongan == 6) && ($user->level == 7)) {
            $pengganti2 = SuratKeluar::select(
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
                ->where('otor2_by_pengganti', $user->id)
                ->where('otor2_by', null)
                ->where('status', 1)
                ->latest();
            $pengganti1 = SuratKeluar::select(
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
                ->where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->union($pengganti2)
                ->latest();
            // antar departemen sebagai otor2_by
            $antarDepartemen2 = SuratKeluar::select(
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
                ->where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 1)
                ->union($pengganti1)
                ->latest();
            // antar departemen sebagai otor1_by
            $antarDepartemen1 = SuratKeluar::select(
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
                ->where('departemen_asal', $user->departemen)
                ->where('internal', 1)
                ->where('status', 2)
                ->where('otor2_by', '!=', $user->id)
                ->union($antarDepartemen2)
                ->latest();
            // antar satuan kerja sebagai otor2_by
            $mails = SuratKeluar::select(
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
                ->where('status', 1)
                ->where('internal', 2)
                ->union($antarDepartemen1)
                ->latest()->get();
        }

        // Kepala satuan kerja
        elseif ($user->levelTable->golongan == 7) {
            $pengganti = SuratKeluar::select(
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
                ->where('otor1_by_pengganti', $user->id)
                ->where('otor1_by', null)
                ->where('status', 2)
                ->latest();
            // Antar satuan kerja sebagai otor1_by
            $mails = SuratKeluar::select(
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
                ->where('satuan_kerja_asal', $user->satuan_kerja)
                ->where('internal', 2)
                ->where('status', 2)
                ->union($pengganti)
                ->latest()->get();
        }
        return $mails;
    }
}
