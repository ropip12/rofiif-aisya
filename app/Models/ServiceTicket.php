<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'tiket_id',
        'tanggal_masuk',
        'nama_pemohon',
        'jenis_permintaan',
        'tingkat_dampak',
        'risiko_tik_terkait',
        'solusi_tindakan_operasional',
        'status_tiket',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
