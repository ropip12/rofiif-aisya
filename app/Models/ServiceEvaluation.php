<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'realisasi_uptime',
        'target_sla',
        'status_capaian',
        'total_gangguan_bulan_ini',
        'rata_rata_waktu_resolusi_mttr',
        'rekomendasi_peningkatan_kontrol_mitigasi',
        'periode',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
