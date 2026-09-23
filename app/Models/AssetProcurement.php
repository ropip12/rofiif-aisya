<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetProcurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'procurement_date',
        'procurement_method',
        'source_vendor',
        'procurement_value',
        'description',
    ];

    protected $casts = [
        'procurement_date' => 'date',
        'procurement_value' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
