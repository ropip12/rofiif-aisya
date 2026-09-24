<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'sub_classification_id',
        'asset_name',
        'nomor_dokumen',
        'tahun_penyusunan',
        'status_aset',
        'lokasi_keberadaan_aset',
        'format_penyimpanan_aset',
        'pemilik_aset',
        'retensi_aset',
        'kerahasiaan',
        'integritas',
        'ketersediaan',
        'kritikalitas_aset',
        'asset_type',
        'category',
        'spesifikasi_aset',
        'tahun_pengadaan',
        'kondisi_aset',
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

    public function classification(): BelongsTo
    {
        return $this->belongsTo(AssetClassification::class, 'sub_classification_id');
    }

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
