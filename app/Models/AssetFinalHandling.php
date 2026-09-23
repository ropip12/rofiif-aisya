<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetFinalHandling extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'final_handling_type',
        'handling_date',
        'reason',
        'description',
        'status',
    ];

    protected $casts = [
        'handling_date' => 'date',
    ];

    public const VALID_TYPES = [
        'Digunakan Kembali',
        'Dipindahkan',
        'Gudang',
        'Diarsipkan',
        'Diperbaiki',
        'Dialihkan',
        'Dilepas',
        'Dihapuskan',
        'Dimusnahkan',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
