@extends('layouts.app')

@section('title', 'Laporan Aset')
@section('page_title', 'Laporan Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h1 style="margin:0;">Laporan Aset</h1>
                <div class="subtitle">Ringkasan dan daftar aset berdasarkan data real</div>
            </div>
            <a href="{{ route('aset.monitoring') }}" style="background:#dbeafe; color:#1d4ed8; padding:10px 14px; border-radius:8px; font-weight:700;">Monitoring Aset</a>
        </div>

        <div class="page-card" style="padding:18px; margin-bottom:20px; background:#f8fafc;">
            <h3 style="margin:0 0 14px;">Filter Laporan</h3>
            <form method="GET" action="{{ route('aset.laporan') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; align-items:end;">
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Tanggal Awal</label>
                    <input type="date" name="from_date" value="{{ old('from_date', $filters['from_date'] ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Tanggal Akhir</label>
                    <input type="date" name="to_date" value="{{ old('to_date', $filters['to_date'] ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Klasifikasi</label>
                    <select name="classification" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($filterOptions['classifications'] as $value)
                            <option value="{{ $value }}" {{ ($filters['classification'] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Kondisi</label>
                    <select name="condition" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($filterOptions['conditions'] as $value)
                            <option value="{{ $value }}" {{ ($filters['condition'] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Lokasi</label>
                    <select name="location" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($filterOptions['locations'] as $value)
                            <option value="{{ $value }}" {{ ($filters['location'] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Status Penggunaan</label>
                    <select name="usage_status" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($filterOptions['usageStatus'] as $value)
                            <option value="{{ $value }}" {{ ($filters['usage_status'] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Lifecycle</label>
                    <select name="lifecycle" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($filterOptions['lifecycle'] as $value)
                            <option value="{{ $value }}" {{ ($filters['lifecycle'] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" style="width:100%; padding:10px 14px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Terapkan</button>
                </div>
                <div>
                    <a href="{{ route('aset.laporan') }}" style="display:inline-block; width:100%; text-align:center; padding:10px 14px; background:#e5e7eb; color:#111827; border-radius:8px; font-weight:700;">Reset</a>
                </div>
                <div>
                    <a href="{{ route('aset.laporan.export') }}@if(request()->getQueryString())?{{ request()->getQueryString() }}@endif" style="display:inline-block; width:100%; text-align:center; padding:10px 14px; background:#166534; color:white; border-radius:8px; font-weight:700;">Export CSV</a>
                </div>
            </form>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; margin-bottom:22px;">
            <div class="info-box">
                <div class="muted">Jumlah Aset</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['total'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Nilai Perolehan</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">Rp {{ number_format($summary['procurement_value'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Pemeliharaan</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['with_maintenance'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Evaluasi</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['with_evaluation'] ?? 0 }}</div>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff; min-width:1000px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; text-align:left;">Kode</th>
                        <th style="padding:12px; text-align:left;">Nama</th>
                        <th style="padding:12px; text-align:left;">Klasifikasi</th>
                        <th style="padding:12px; text-align:left;">Kondisi</th>
                        <th style="padding:12px; text-align:left;">Lokasi</th>
                        <th style="padding:12px; text-align:left;">Status</th>
                        <th style="padding:12px; text-align:left;">Lifecycle</th>
                        <th style="padding:12px; text-align:left;">Perolehan</th>
                        <th style="padding:12px; text-align:left;">Pemeliharaan</th>
                        <th style="padding:12px; text-align:left;">Evaluasi</th>
                        <th style="padding:12px; text-align:left;">Penanganan Akhir</th>
                        <th style="padding:12px; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $asset)
                        <tr>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->asset_code }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->asset_name }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->asset_classification }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->condition_status ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->location ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->usage_status ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->lifecycle_status ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->acquisition_value ? 'Rp ' . number_format($asset->acquisition_value, 0, ',', '.') : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->maintenances->count() > 0 ? $asset->maintenances->count() . ' data' : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->evaluations->count() > 0 ? $asset->evaluations->count() . ' data' : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->final_handling ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">
                                <a href="/aset/{{ $asset->id }}" style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="padding:18px; text-align:center; color:#6b7280;">Tidak ada data aset untuk laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $assets->links() }}
        </div>
    </div>
@endsection
