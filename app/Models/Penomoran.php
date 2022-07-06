<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penomoran extends Model
{
    use HasFactory;

    protected $fillable = [
        'timestamp',
        'created_by',
        'nomor',
        'jenis',
        'departemen',
        'tahun',
        'nomor_surat',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
