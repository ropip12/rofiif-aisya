<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_code',
        'service_name',
        'service_description',
        'service_type',
        'service_owner',
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
}
