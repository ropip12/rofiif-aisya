<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'activity_type',
        'activity_date',
        'change_info',
        'description',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public static function record(Asset $asset, string $activityType, string $changeInfo, ?string $description = null, Carbon|string|\DateTimeInterface|null $date = null): self
    {
        $activityDate = $date instanceof \DateTimeInterface ? Carbon::instance($date) : Carbon::parse($date ?? now());

        return static::create([
            'asset_id' => $asset->id,
            'activity_type' => $activityType,
            'activity_date' => $activityDate->toDateString(),
            'change_info' => $changeInfo,
            'description' => $description,
        ]);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
