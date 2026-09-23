@extends('layouts.app')

@section('title', 'Detail Penanganan Akhir Aset')
@section('page_title', 'Detail Penanganan Akhir Aset')

@section('content')
    <div class="page-card">
        <h1>Detail Penanganan Akhir</h1>
        <div class="subtitle">Informasi lengkap penanganan akhir aset.</div>

        <div style="display:grid; gap:12px; max-width:900px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Aset:</strong><br>{{ $item->asset?->asset_name ?? '-' }}</div>
                <div><strong>Kode Aset:</strong><br>{{ $item->asset?->asset_code ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Jenis Penanganan:</strong><br>{{ $item->final_handling_type ?? '-' }}</div>
                <div><strong>Tanggal Penanganan:</strong><br>{{ $item->handling_date?->format('d-m-Y') ?? '-' }}</div>
            </div>
            <div><strong>Alasan:</strong><br>{{ $item->reason ?? '-' }}</div>
            <div><strong>Keterangan:</strong><br>{{ $item->description ?? '-' }}</div>
            <div><strong>Status:</strong><br>{{ $item->status ?? '-' }}</div>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('aset.penanganan-akhir.edit', ['asset_final_handling' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
            <a href="{{ route('aset.penanganan-akhir.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
        </div>
    </div>
@endsection
