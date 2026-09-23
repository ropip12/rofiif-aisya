@extends('layouts.app')

@section('title', 'Detail Evaluasi Aset')
@section('page_title', 'Detail Evaluasi Aset')

@section('content')
    <div class="page-card">
        <h1>Detail Evaluasi</h1>
        <div class="subtitle">Informasi lengkap evaluasi aset.</div>

        <div style="display:grid; gap:12px; max-width:900px;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Aset:</strong><br>{{ $item->asset?->asset_name ?? '-' }}</div>
                <div><strong>Kode Aset:</strong><br>{{ $item->asset?->asset_code ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Tanggal Evaluasi:</strong><br>{{ $item->evaluation_date?->format('d-m-Y') ?? '-' }}</div>
                <div><strong>Kondisi:</strong><br>{{ $item->condition ?? '-' }}</div>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div><strong>Hasil Evaluasi:</strong><br>{{ $item->evaluation_result ?? '-' }}</div>
                <div><strong>Status Tindak Lanjut:</strong><br>{{ $item->follow_up_status ?? '-' }}</div>
            </div>
            <div><strong>Rekomendasi:</strong><br>{{ $item->recommendation ?? '-' }}</div>
            <div><strong>Keterangan:</strong><br>{{ $item->description ?? '-' }}</div>
        </div>

        <div style="margin-top:20px; display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('aset.evaluasi.edit', ['asset_evaluation' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:10px 14px; border-radius:8px; font-weight:700;">Edit</a>
            <a href="{{ route('aset.evaluasi.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 14px; border-radius:8px; font-weight:700;">Kembali</a>
        </div>
    </div>
@endsection
