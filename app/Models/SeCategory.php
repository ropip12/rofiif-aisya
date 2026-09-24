<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kategori',
        'karakteristik',
        'status',
        'skor',
        'deskripsi',
    ];
}
