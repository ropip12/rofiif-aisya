<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRangeDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_range',
        'nama_range',
        'kategori',
        'kriteria',
        'deskripsi',
        'skor',
    ];
}
