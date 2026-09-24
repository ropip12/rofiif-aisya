@extends('layouts.app')

@section('title', 'Detail Risiko')
@section('page_title', 'Detail Risiko')

@section('content')
<div class="page-card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
        <div>
            <h1>{{ $risk->risk_name }}</h1>
            <div class="subtitle">Kode risiko: {{ $risk->risk_code }}</div>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('risiko.edit', ['risiko' => $risk->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
            <a href="{{ route('risiko.index') }}" style="background:#e5e7eb; color:#111827; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
        <div class="info-box"><strong>Kemungkinan:</strong> {{ $risk->likelihood ?? '-' }}</div>
        <div class="info-box"><strong>Tingkat Risiko:</strong> {{ $risk->risk_level ?? '-' }}</div>
        <div class="info-box"><strong>Status:</strong> {{ $risk->risk_status ?? '-' }}</div>
        <div class="info-box"><strong>Jumlah Aset:</strong> {{ $risk->assets->count() }}</div>
        <div class="info-box"><strong>Jumlah Layanan:</strong> {{ $risk->services->count() }}</div>
    </div>

    <div class="info-box"><strong>Penyebab:</strong><br>{{ $risk->cause ?? '-' }}</div>
    <div class="info-box"><strong>Dampak:</strong><br>{{ $risk->impact ?? '-' }}</div>
    <div class="info-box"><strong>Pengendalian:</strong><br>{{ $risk->control_measures ?? '-' }}</div>
    <div class="info-box"><strong>Rencana Mitigasi:</strong><br>{{ $risk->mitigation_plan ?? '-' }}</div>
    <div class="info-box"><strong>Monitoring:</strong><br>{{ $risk->monitoring ?? '-' }}</div>
    <div class="info-box"><strong>Evaluasi:</strong><br>{{ $risk->evaluation ?? '-' }}</div>

    <div class="info-box">
        <strong>Aset terkait:</strong>
        @if($risk->assets->isEmpty())
            <div class="muted" style="margin-top:6px;">-</div>
        @else
            <ul style="margin:8px 0 0 18px;">
                @foreach($risk->assets as $asset)
                    <li>{{ $asset->asset_name }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="info-box">
        <strong>Layanan terkait:</strong>
        @if($risk->services->isEmpty())
            <div class="muted" style="margin-top:6px;">-</div>
        @else
            <ul style="margin:8px 0 0 18px;">
                @foreach($risk->services as $service)
                    <li>{{ $service->service_name }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
