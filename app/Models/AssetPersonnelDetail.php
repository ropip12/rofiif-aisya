<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetPersonnelDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'nama_personil',
        'kategori_aset',
        'nip_nib',
        'fungsi',
        'unit',
        'jabatan',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
