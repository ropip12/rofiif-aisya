@extends('layouts.app')

@section('title', 'Evaluasi Risiko')
@section('page_title', 'Evaluasi Risiko')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h1 style="margin:0;">Evaluasi Risiko</h1>
                <div class="subtitle">Daftar evaluasi risiko berdasarkan data aktif</div>
            </div>
        </div>

        @if(session('success'))
            <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-card" style="padding:18px; margin-bottom:20px; background:#f8fafc;">
            <h3 style="margin:0 0 14px;">Filter Evaluasi</h3>
            <form method="GET" action="{{ route('risiko.evaluasi') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; align-items:end;">
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Pencarian</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Tingkat Risiko</label>
                    <select name="risk_level" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($riskLevels as $level)
                            <option value="{{ $level }}" {{ ($filters['risk_level'] ?? '') === $level ? 'selected' : '' }}>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Status</label>
                    <select name="risk_status" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($riskStatuses as $status)
                            <option value="{{ $status }}" {{ ($filters['risk_status'] ?? '') === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Kemungkinan</label>
                    <select name="likelihood" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($likelihoods as $value)
                            <option value="{{ $value }}" {{ ($filters['likelihood'] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Aset</label>
                    <select name="asset_id" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($assets as $asset)
                            <option value="{{ $asset->id }}" {{ ($filters['asset_id'] ?? '') == $asset->id ? 'selected' : '' }}>{{ $asset->asset_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Layanan</label>
                    <select name="service_id" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ ($filters['service_id'] ?? '') == $service->id ? 'selected' : '' }}>{{ $service->service_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" style="width:100%; padding:10px 14px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Terapkan</button>
                </div>
                <div>
                    <a href="{{ route('risiko.evaluasi') }}" style="display:inline-block; width:100%; text-align:center; padding:10px 14px; background:#e5e7eb; color:#111827; border-radius:8px; font-weight:700;">Reset</a>
                </div>
            </form>
        </div>

        <div style="overflow-x:auto; margin-top:20px;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff; min-width:1100px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; text-align:left;">Kode</th>
                        <th style="padding:12px; text-align:left;">Risiko</th>
                        <th style="padding:12px; text-align:left;">Tingkat</th>
                        <th style="padding:12px; text-align:left;">Status</th>
                        <th style="padding:12px; text-align:left;">Kemungkinan</th>
                        <th style="padding:12px; text-align:left;">Hasil Evaluasi</th>
                        <th style="padding:12px; text-align:left;">Aset</th>
                        <th style="padding:12px; text-align:left;">Layanan</th>
                        <th style="padding:12px; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($risks as $risk)
                        <tr>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $risk->risk_code }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $risk->risk_name }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $risk->risk_level ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $risk->risk_status ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $risk->likelihood ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ Str::limit($risk->evaluation ?? '-', 50) }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $risk->assets->count() > 0 ? $risk->assets->pluck('asset_name')->implode(', ') : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $risk->services->count() > 0 ? $risk->services->pluck('service_name')->implode(', ') : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">
                                <div style="display:flex; flex-direction:column; gap:8px;">
                                    <form method="POST" action="{{ route('risiko.evaluasi.update', ['risiko' => $risk->id]) }}">
                                        @csrf
                                        @method('PUT')
                                        <select name="risk_status" style="width:100%; padding:8px 10px; border:1px solid #dbe3f0; border-radius:8px; margin-bottom:6px;" required>
                                            @foreach ($riskStatuses as $status)
                                                <option value="{{ $status }}" {{ old('risk_status', $risk->risk_status) === $status ? 'selected' : '' }}>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <textarea name="evaluation" rows="3" style="width:100%; padding:8px 10px; border:1px solid #dbe3f0; border-radius:8px; margin-bottom:6px;">{{ old('evaluation', $risk->evaluation) }}</textarea>
                                        <button type="submit" style="width:100%; background:#1d4ed8; color:#fff; border:none; border-radius:8px; padding:8px 10px; font-weight:700; cursor:pointer;">Update</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding:18px; text-align:center; color:#6b7280;">Tidak ada data evaluasi risiko.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $risks->links() }}
        </div>
    </div>
@endsection
