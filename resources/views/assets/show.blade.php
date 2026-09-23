@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page_title', 'Detail Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
            <div>
                <h1 style="margin:0;">{{ $asset->asset_name }}</h1>
                <div class="subtitle" style="margin:8px 0 0;">Kode aset: {{ $asset->asset_code }}</div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="/aset/{{ $asset->id }}/edit" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
                <a href="/aset" style="background:#e5e7eb; color:#111827; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:16px;">
            <div class="info-box"><strong>Klasifikasi:</strong> {{ $asset->asset_classification }}</div>
            <div class="info-box"><strong>Tanggal perolehan:</strong> {{ $asset->acquisition_date ? $asset->acquisition_date->format('d-m-Y') : '-' }}</div>
            <div class="info-box"><strong>Nilai perolehan:</strong> {{ $asset->acquisition_value ? 'Rp ' . number_format($asset->acquisition_value, 0, ',', '.') : '-' }}</div>
            <div class="info-box"><strong>Vendor:</strong> {{ $asset->vendor ?? '-' }}</div>
            <div class="info-box"><strong>Kondisi:</strong> {{ $asset->condition_status ?? '-' }}</div>
            <div class="info-box"><strong>Lokasi:</strong> {{ $asset->location ?? '-' }}</div>
            <div class="info-box"><strong>Penanggung jawab:</strong> {{ $asset->responsible_person ?? '-' }}</div>
            <div class="info-box"><strong>Status penggunaan:</strong> {{ $asset->usage_status ?? '-' }}</div>
            <div class="info-box"><strong>Masa manfaat:</strong> {{ $asset->useful_life_years ? $asset->useful_life_years . ' tahun' : '-' }}</div>
            <div class="info-box" style="grid-column: 1 / -1;"><strong>Informasi penyimpanan data:</strong> <br>{{ $asset->data_storage_information ?? '-' }}</div>
            <div class="info-box"><strong>Status lifecycle:</strong> {{ $asset->lifecycle_status ?? '-' }}</div>
            <div class="info-box" style="grid-column: 1 / -1;"><strong>Penanganan akhir:</strong> <br>{{ $asset->final_handling ?? '-' }}</div>
        </div>
    </div>
@endsection
