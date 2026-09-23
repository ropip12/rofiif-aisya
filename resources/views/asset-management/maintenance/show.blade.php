@extends('layouts.app')

@section('title', 'Detail Pemeliharaan Aset')
@section('page_title', 'Detail Pemeliharaan Aset')

@section('content')
    <div class="page-card">
        <h1>Detail Pemeliharaan</h1>
        <div class="subtitle">Informasi lengkap pemeliharaan aset.</div>

        <div style="display:grid; gap:12px; max-width:900px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Aset:</strong><br>{{ $item->asset?->asset_name ?? '-' }}</div>
                <div><strong>Kode Aset:</strong><br>{{ $item->asset?->asset_code ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Tanggal Pemeliharaan:</strong><br>{{ $item->maintenance_date?->format('d-m-Y') ?? '-' }}</div>
                <div><strong>Tipe:</strong><br>{{ $item->maintenance_type ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Vendor:</strong><br>{{ $item->vendor ?? '-' }}</div>
                <div><strong>Biaya:</strong><br>{{ $item->cost ? 'Rp ' . number_format($item->cost, 0, ',', '.') : '-' }}</div>
            </div>
            <div><strong>Hasil Pemeliharaan:</strong><br>{{ $item->maintenance_result ?? '-' }}</div>
            <div><strong>Keterangan:</strong><br>{{ $item->description ?? '-' }}</div>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('aset.pemeliharaan.edit', ['asset_maintenance' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
            <a href="{{ route('aset.pemeliharaan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
        </div>
    </div>
@endsection
