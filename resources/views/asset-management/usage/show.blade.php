@extends('layouts.app')

@section('title', 'Detail Penggunaan Aset')
@section('page_title', 'Detail Penggunaan Aset')

@section('content')
    <div class="page-card">
        <h1>Detail Penggunaan</h1>
        <div class="subtitle">Informasi lengkap data pemakaian aset.</div>

        <div style="display:grid; gap:12px; max-width:900px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Aset:</strong><br>{{ $item->asset?->asset_name ?? '-' }}</div>
                <div><strong>Kode Aset:</strong><br>{{ $item->asset?->asset_code ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Lokasi:</strong><br>{{ $item->location ?? '-' }}</div>
                <div><strong>Penanggung Jawab:</strong><br>{{ $item->responsible_person ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Status:</strong><br>{{ $item->usage_status ?? '-' }}</div>
                <div><strong>Tanggal Mulai:</strong><br>{{ $item->start_date?->format('d-m-Y') ?? '-' }}</div>
            </div>
            <div><strong>Tanggal Selesai:</strong><br>{{ $item->end_date?->format('d-m-Y') ?? '-' }}</div>
            <div><strong>Keterangan:</strong><br>{{ $item->description ?? '-' }}</div>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('aset.penggunaan.edit', ['asset_usage' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
            <a href="{{ route('aset.penggunaan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
        </div>
    </div>
@endsection
