<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'evaluation_date',
        'condition',
        'evaluation_result',
        'recommendation',
        'follow_up_status',
        'description',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
