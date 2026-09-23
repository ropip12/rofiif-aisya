@extends('layouts.app')

@section('title', 'Monitoring Aset')
@section('page_title', 'Monitoring Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h1 style="margin:0;">Monitoring Aset</h1>
                <div class="subtitle">Data real-time berdasarkan aset yang tersimpan di database</div>
            </div>
            <a href="{{ route('aset.index') }}" style="background:#dbeafe; color:#1d4ed8; padding:10px 14px; border-radius:8px; font-weight:700;">Lihat Data Aset</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; margin-bottom:24px;">
            <div class="info-box">
                <div class="muted">Total Aset</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['total'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Pemeliharaan</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['with_maintenance'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Evaluasi</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['with_evaluation'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Penanganan Akhir</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['by_final_handling']->sum() ?? 0 }}</div>
            </div>
        </div>

        <div class="page-card" style="padding:18px; margin-bottom:20px; background:#f8fafc;">
            <h3 style="margin:0 0 14px;">Filter Monitoring</h3>
            <form method="GET" action="{{ route('aset.monitoring') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; align-items:end;">
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Pencarian</label>
                    <input type="text" name="search" value="{{ old('search', $filters['search'] ?? '') }}" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
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
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Lokasi</label>
                    <select name="location" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($filterOptions['locations'] as $value)
                            <option value="{{ $value }}" {{ ($filters['location'] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" style="width:100%; padding:10px 14px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Terapkan</button>
                </div>
                <div>
                    <a href="{{ route('aset.monitoring') }}" style="display:inline-block; width:100%; text-align:center; padding:10px 14px; background:#e5e7eb; color:#111827; border-radius:8px; font-weight:700;">Reset</a>
                </div>
            </form>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:22px;">
            @foreach ($summary['by_condition'] ?? [] as $label => $value)
                <div class="info-box">
                    <div class="muted">{{ $label }}</div>
                    <div style="font-size:22px; font-weight:700; margin-top:8px;">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div style="overflow-x:auto; margin-top:20px;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff; min-width:900px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; text-align:left;">Kode</th>
                        <th style="padding:12px; text-align:left;">Nama</th>
                        <th style="padding:12px; text-align:left;">Klasifikasi</th>
                        <th style="padding:12px; text-align:left;">Kondisi</th>
                        <th style="padding:12px; text-align:left;">Lokasi</th>
                        <th style="padding:12px; text-align:left;">PIC</th>
                        <th style="padding:12px; text-align:left;">Status</th>
                        <th style="padding:12px; text-align:left;">Lifecycle</th>
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
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->responsible_person ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->usage_status ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $asset->lifecycle_status ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">
                                <a href="/aset/{{ $asset->id }}" style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:18px; text-align:center; color:#6b7280;">Tidak ada data aset yang sesuai filter.</td>
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
