@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page_title', 'Detail Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
            <div>
                <h1 style="margin:0;">{{ $aset->asset_name }}</h1>
                <div class="subtitle" style="margin:8px 0 0;">Kode aset: {{ $aset->asset_code }}</div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="/aset/{{ $aset->id }}/edit" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
                <a href="/aset" style="background:#e5e7eb; color:#111827; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:16px;">
            <div class="info-box"><strong>Klasifikasi:</strong> {{ $aset->asset_classification }}</div>
            <div class="info-box"><strong>Tanggal perolehan:</strong> {{ $aset->acquisition_date ? $aset->acquisition_date->format('d-m-Y') : '-' }}</div>
            <div class="info-box"><strong>Nilai perolehan:</strong> {{ $aset->acquisition_value ? 'Rp ' . number_format($aset->acquisition_value, 0, ',', '.') : '-' }}</div>
            <div class="info-box"><strong>Vendor:</strong> {{ $aset->vendor ?? '-' }}</div>
            <div class="info-box"><strong>Kondisi:</strong> {{ $aset->condition_status ?? '-' }}</div>
            <div class="info-box"><strong>Lokasi:</strong> {{ $aset->location ?? '-' }}</div>
            <div class="info-box"><strong>Penanggung jawab:</strong> {{ $aset->responsible_person ?? '-' }}</div>
            <div class="info-box"><strong>Status penggunaan:</strong> {{ $aset->usage_status ?? '-' }}</div>
            <div class="info-box"><strong>Masa manfaat:</strong> {{ $aset->useful_life_years ? $aset->useful_life_years . ' tahun' : '-' }}</div>
            <div class="info-box" style="grid-column: 1 / -1;"><strong>Informasi penyimpanan data:</strong> <br>{{ $aset->data_storage_information ?? '-' }}</div>
            <div class="info-box"><strong>Status lifecycle:</strong> {{ $aset->lifecycle_status ?? '-' }}</div>
            <div class="info-box" style="grid-column: 1 / -1;"><strong>Penanganan akhir:</strong> <br>{{ $aset->final_handling ?? '-' }}</div>
        </div>
    </div>
@endsection
