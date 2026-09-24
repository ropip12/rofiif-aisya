<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_code',
        'service_name',
        'service_description',
        'service_type',
        'service_owner',
        'pengelola_teknis',
        'cara_akses',
        'target_ketersediaan_sla',
        'waktu_pelayanan',
        'service_status',
        'supporting_information',
        'monitoring',
        'evaluation',
    ];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class)->withTimestamps();
    }

    public function risks(): BelongsToMany
    {
        return $this->belongsToMany(Risk::class)->withTimestamps();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(ServiceTicket::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(ServiceEvaluation::class);
    }
}
