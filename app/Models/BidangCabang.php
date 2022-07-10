<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangCabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'cabang',
        'bidang_cabang'
    ];

    public function cabangTable()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}
