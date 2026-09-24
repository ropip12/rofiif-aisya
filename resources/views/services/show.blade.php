@extends('layouts.app')

@section('title', 'Detail Layanan')
@section('page_title', 'Detail Layanan')

@section('content')
<div class="page-card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h1 style="margin:0;">{{ $service->service_name }}</h1>
            <div class="subtitle" style="margin-top:8px;">Detail data layanan.</div>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('layanan.edit', ['service' => $service->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 16px; border-radius:8px; font-weight:700; text-decoration:none;">Edit</a>
            <a href="{{ route('layanan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 16px; border-radius:8px; font-weight:700; text-decoration:none;">Kembali</a>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:20px;">
        <div style="padding:14px; border:1px solid #e2e8f0; border-radius:10px; background:#f8fafc;">
            <div style="color:#64748b; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">Kode</div>
            <div style="margin-top:8px; font-weight:700;">{{ $service->service_code }}</div>
        </div>
        <div style="padding:14px; border:1px solid #e2e8f0; border-radius:10px; background:#f8fafc;">
            <div style="color:#64748b; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">Jenis</div>
            <div style="margin-top:8px; font-weight:700;">{{ $service->service_type ?? '-' }}</div>
        </div>
        <div style="padding:14px; border:1px solid #e2e8f0; border-radius:10px; background:#f8fafc;">
            <div style="color:#64748b; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">Pengelola</div>
            <div style="margin-top:8px; font-weight:700;">{{ $service->service_owner ?? '-' }}</div>
        </div>
        <div style="padding:14px; border:1px solid #e2e8f0; border-radius:10px; background:#f8fafc;">
            <div style="color:#64748b; font-size:12px; text-transform:uppercase; letter-spacing:0.06em;">Status</div>
            <div style="margin-top:8px; font-weight:700;">{{ $service->service_status ?? '-' }}</div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr; gap:18px;">
        <div style="padding:16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff;">
            <h3 style="margin:0 0 10px;">Deskripsi</h3>
            <p style="margin:0; color:#334155; line-height:1.6;">{{ $service->service_description ?: '-' }}</p>
        </div>

        <div style="padding:16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff;">
            <h3 style="margin:0 0 10px;">Informasi Pendukung</h3>
            <p style="margin:0; color:#334155; line-height:1.6;">{{ $service->supporting_information ?: '-' }}</p>
        </div>

        <div style="padding:16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff;">
            <h3 style="margin:0 0 10px;">Monitoring</h3>
            <p style="margin:0; color:#334155; line-height:1.6;">{{ $service->monitoring ?: '-' }}</p>
        </div>

        <div style="padding:16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff;">
            <h3 style="margin:0 0 10px;">Evaluasi</h3>
            <p style="margin:0; color:#334155; line-height:1.6;">{{ $service->evaluation ?: '-' }}</p>
        </div>

        <div style="padding:16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff;">
            <h3 style="margin:0 0 10px;">Aset Pendukung</h3>
            @if($service->assets->isEmpty())
                <div style="color:#64748b;">Tidak ada aset yang terkait.</div>
            @else
                <ul style="margin:0; padding-left:18px; color:#334155; line-height:1.8;">
                    @foreach($service->assets as $asset)
                        <li>{{ $asset->asset_name }} ({{ $asset->location ?? 'Lokasi tidak tersedia' }})</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div style="padding:16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff;">
            <h3 style="margin:0 0 10px;">Risiko Terkait</h3>
            @if($service->risks->isEmpty())
                <div style="color:#64748b;">Tidak ada risiko yang terkait.</div>
            @else
                <ul style="margin:0; padding-left:18px; color:#334155; line-height:1.8;">
                    @foreach($service->risks as $risk)
                        <li>{{ $risk->risk_name }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
