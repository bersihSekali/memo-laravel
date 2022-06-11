<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanKantorCabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'cabang_id',
        'bidang_id',
        'memo_id',
        'status_baca',
        'all_flag',
        'tanggal_baca',
        'pesan_disposisi',
        'tanggal_disposisi'
    ];

    public function tujuanCabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function tujuanBidang()
    {
        return $this->belongsTo(BidangCabang::class, 'bidang_id');
    }
}
