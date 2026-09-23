@extends('layouts.app')

@section('title', 'Detail Gudang Aset')
@section('page_title', 'Detail Gudang Aset')

@section('content')
    <div class="page-card">
        <h1>Detail Gudang</h1>
        <div class="subtitle">Informasi lengkap data penyimpanan aset.</div>

        <div style="display:grid; gap:12px; max-width:900px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Aset:</strong><br>{{ $item->asset?->asset_name ?? '-' }}</div>
                <div><strong>Kode Aset:</strong><br>{{ $item->asset?->asset_code ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Lokasi Gudang:</strong><br>{{ $item->warehouse_location ?? '-' }}</div>
                <div><strong>Status Penyimpanan:</strong><br>{{ $item->storage_status ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Tanggal Masuk:</strong><br>{{ $item->entry_date?->format('d-m-Y') ?? '-' }}</div>
                <div><strong>Tanggal Keluar:</strong><br>{{ $item->exit_date?->format('d-m-Y') ?? '-' }}</div>
            </div>
            <div><strong>Keterangan:</strong><br>{{ $item->description ?? '-' }}</div>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('aset.gudang.edit', ['asset_warehouse' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
            <a href="{{ route('aset.gudang.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
        </div>
    </div>
@endsection
