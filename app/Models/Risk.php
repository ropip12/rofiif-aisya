<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_code',
        'risk_name',
        'cause',
        'impact',
        'likelihood',
        'risk_level',
        'control_measures',
        'mitigation_plan',
        'risk_status',
        'monitoring',
        'evaluation',
    ];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class)->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withTimestamps();
    }
}
