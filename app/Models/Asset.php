<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'asset_name',
        'asset_classification',
        'acquisition_date',
        'acquisition_value',
        'vendor',
        'condition_status',
        'location',
        'responsible_person',
        'usage_status',
        'useful_life_years',
        'data_storage_information',
        'lifecycle_status',
        'final_handling',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'acquisition_value' => 'decimal:2',
    ];

    public function risks(): BelongsToMany
    {
        return $this->belongsToMany(Risk::class)->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withTimestamps();
    }

    public function procurements(): HasMany
    {
        return $this->hasMany(AssetProcurement::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(AssetUsage::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(AssetWarehouse::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(AssetEvaluation::class);
    }

    public function finalHandlings(): HasMany
    {
        return $this->hasMany(AssetFinalHandling::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AssetHistory::class);
    }
}
