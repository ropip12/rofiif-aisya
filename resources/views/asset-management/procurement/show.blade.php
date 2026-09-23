@extends('layouts.app')

@section('title', 'Detail Pengadaan Aset')
@section('page_title', 'Detail Pengadaan Aset')

@section('content')
    <div class="page-card">
        <h1>Detail Pengadaan</h1>
        <div class="subtitle">Informasi lengkap data pengadaan aset.</div>

        <div style="display:grid; gap:12px; max-width:900px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Aset:</strong><br>{{ $item->asset?->asset_name ?? '-' }}</div>
                <div><strong>Kode Aset:</strong><br>{{ $item->asset?->asset_code ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Tanggal Pengadaan:</strong><br>{{ $item->procurement_date?->format('d-m-Y') ?? '-' }}</div>
                <div><strong>Metode:</strong><br>{{ $item->procurement_method ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Vendor / Sumber:</strong><br>{{ $item->source_vendor ?? '-' }}</div>
                <div><strong>Nilai:</strong><br>{{ $item->procurement_value ? 'Rp ' . number_format($item->procurement_value, 0, ',', '.') : '-' }}</div>
            </div>
            <div><strong>Keterangan:</strong><br>{{ $item->description ?? '-' }}</div>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('aset.pengadaan.edit', ['asset_procurement' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
            <a href="{{ route('aset.pengadaan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
        </div>
    </div>
@endsection
