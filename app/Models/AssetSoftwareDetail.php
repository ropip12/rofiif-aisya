<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetSoftwareDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'tahun_rilis',
        'uraian_singkat_aplikasi',
        'alamat_aplikasi_url',
        'alamat_ip',
        'aplikasi_ip_publik_internal',
        'platform',
        'sistem_operasi_server',
        'pemilik_aset_opd',
        'data_center',
        'kontak_pengelola_pic',
        'status',
        'kategori_se',
        'kritikalitas_aset',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
