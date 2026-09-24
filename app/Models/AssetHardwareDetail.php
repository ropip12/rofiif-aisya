<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetHardwareDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'spesifikasi_aset',
        'tahun_pengadaan',
        'lokasi_keberadaan_aset',
        'pemilik_aset',
        'kondisi_aset',
        'kategori',
        'kritikalitas_aset',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
