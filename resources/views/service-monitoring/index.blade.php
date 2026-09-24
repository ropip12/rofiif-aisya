@extends('layouts.app')

@section('title', 'Monitoring Layanan')
@section('page_title', 'Monitoring Layanan')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h1 style="margin:0;">Monitoring Layanan</h1>
                <div class="subtitle">Monitoring operasional layanan dan keterkaitannya dengan aset serta risiko</div>
            </div>
        </div>

        @if(session('success'))
            <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; margin-bottom:24px;">
            <div class="info-box">
                <div class="muted">Total Layanan</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['total'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Dengan Aset</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['with_assets'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Dengan Risiko</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ $summary['with_risks'] ?? 0 }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Aktif</div>
                <div style="font-size:28px; font-weight:700; margin-top:8px;">{{ ($summary['by_status']['Aktif'] ?? 0) }}</div>
            </div>
        </div>

        <div class="page-card" style="padding:18px; margin-bottom:20px; background:#f8fafc;">
            <h3 style="margin:0 0 14px;">Filter Monitoring</h3>
            <form method="GET" action="{{ route('layanan.monitoring') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; align-items:end;">
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Pencarian</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Tipe Layanan</label>
                    <select name="service_type" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($serviceTypes as $type)
                            <option value="{{ $type }}" {{ ($filters['service_type'] ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Status</label>
                    <select name="service_status" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($serviceStatuses as $status)
                            <option value="{{ $status }}" {{ ($filters['service_status'] ?? '') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Pemilik</label>
                    <select name="service_owner" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($serviceOwners as $owner)
                            <option value="{{ $owner }}" {{ ($filters['service_owner'] ?? '') === $owner ? 'selected' : '' }}>{{ $owner }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" style="width:100%; padding:10px 14px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Terapkan</button>
                </div>
                <div>
                    <a href="{{ route('layanan.monitoring') }}" style="display:inline-block; width:100%; text-align:center; padding:10px 14px; background:#e5e7eb; color:#111827; border-radius:8px; font-weight:700;">Reset</a>
                </div>
            </form>
        </div>

        <div style="overflow-x:auto; margin-top:20px;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff; min-width:1100px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; text-align:left;">Kode</th>
                        <th style="padding:12px; text-align:left;">Layanan</th>
                        <th style="padding:12px; text-align:left;">Pemilik</th>
                        <th style="padding:12px; text-align:left;">Tipe</th>
                        <th style="padding:12px; text-align:left;">Status</th>
                        <th style="padding:12px; text-align:left;">Monitoring</th>
                        <th style="padding:12px; text-align:left;">Aset</th>
                        <th style="padding:12px; text-align:left;">Risiko</th>
                        <th style="padding:12px; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->service_code }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->service_name }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->service_owner ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->service_type ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->service_status ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->monitoring ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->assets->count() > 0 ? $service->assets->pluck('asset_name')->implode(', ') : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $service->risks->count() > 0 ? $service->risks->pluck('risk_name')->implode(', ') : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">
                                <form method="POST" action="{{ route('layanan.monitoring.update', ['service' => $service->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="service_status" style="width:100%; padding:8px 10px; border:1px solid #dbe3f0; border-radius:8px; margin-bottom:6px;" required>
                                        @foreach ($serviceStatuses as $status)
                                            <option value="{{ $status }}" {{ old('service_status', $service->service_status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <textarea name="monitoring" rows="2" style="width:100%; padding:8px 10px; border:1px solid #dbe3f0; border-radius:8px; margin-bottom:6px;">{{ old('monitoring', $service->monitoring) }}</textarea>
                                    <button type="submit" style="width:100%; background:#1d4ed8; color:#fff; border:none; border-radius:8px; padding:8px 10px; font-weight:700; cursor:pointer;">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:18px; text-align:center; color:#6b7280;">Tidak ada data monitoring layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $services->links() }}
        </div>
    </div>
@endsection
