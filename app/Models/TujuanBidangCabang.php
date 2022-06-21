<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanBidangCabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_id',
        'bidang_id',
        'all_flag',
        'status_baca',
        'tanggal_baca',
        'pesan_disposisi',
        'tanggal_disposisi'
    ];

    public function tujuanBidangCabang()
    {
        return $this->belongsTo(BidangCabang::class, 'bidang_id');
    }
}
