<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetClassification extends Model
{
    use HasFactory;

    protected $fillable = [
        'aspek',
        'sub_klasifikasi',
        'penjelasan',
        'kode_klasifikasi',
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'sub_classification_id');
    }
}
