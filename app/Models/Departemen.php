<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'satuan_kerja',
        'departemen'
    ];

    public function satuanKerjaTable()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja');
    }
}
