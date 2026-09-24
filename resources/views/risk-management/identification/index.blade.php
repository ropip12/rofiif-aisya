@extends('layouts.app')

@section('title', 'Identifikasi Risiko')
@section('page_title', 'Identifikasi Risiko')

@section('content')
<div class="page-card">
    <h1>Identifikasi Risiko</h1>
    <div class="subtitle">Kelola penyebab, dampak, dan keterkaitan risiko dengan aset/layanan</div>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:18px;">
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / nama / penyebab" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Status</label>
            <select name="risk_status" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Semua</option>
                @foreach ($riskStatuses as $status)
                    <option value="{{ $status }}" {{ request('risk_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex; align-items:end;">
            <button type="submit" style="width:100%; background:#0f172a; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer;">Filter</button>
        </div>
    </form>

    @if ($risks->isEmpty())
        <div class="info-box">Belum ada data identifikasi risiko.</div>
    @else
        @foreach ($risks as $risk)
            <div style="border:1px solid #dfe7f4; border-radius:12px; padding:18px; margin-bottom:16px; background:#fff;">
                <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px;">
                    <div>
                        <div style="font-size:18px; font-weight:700;">{{ $risk->risk_name }}</div>
                        <div class="muted">{{ $risk->risk_code }} · {{ $risk->risk_status ?? '-' }}</div>
                    </div>
                    <div class="tag">{{ $risk->risk_level ?? '-' }}</div>
                </div>

                <form method="POST" action="{{ route('risiko.identifikasi.update', ['risiko' => $risk->id]) }}">
                    @csrf
                    @method('PUT')

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px;">
                        <div style="grid-column:1 / -1;">
                            <label style="display:block; margin-bottom:6px; font-weight:700;">Penyebab</label>
                            <textarea name="cause" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('cause', $risk->cause) }}</textarea>
                        </div>
                        <div style="grid-column:1 / -1;">
                            <label style="display:block; margin-bottom:6px; font-weight:700;">Dampak</label>
                            <textarea name="impact" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('impact', $risk->impact) }}</textarea>
                        </div>
                        <div style="grid-column:1 / -1;">
                            <label style="display:block; margin-bottom:6px; font-weight:700;">Monitoring</label>
                            <textarea name="monitoring" rows="2" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('monitoring', $risk->monitoring) }}</textarea>
                        </div>
                        <div style="grid-column:1 / -1;">
                            <label style="display:block; margin-bottom:6px; font-weight:700;">Aset terkait</label>
                            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:10px;">
                                @foreach ($assets as $asset)
                                    <label style="display:flex; align-items:center; gap:8px; border:1px solid #dfe7f4; border-radius:8px; padding:10px 12px;">
                                        <input type="checkbox" name="assets[]" value="{{ $asset->id }}" {{ $risk->assets->contains($asset->id) || in_array($asset->id, old('assets', []), true) ? 'checked' : '' }}>
                                        <span>{{ $asset->asset_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div style="grid-column:1 / -1;">
                            <label style="display:block; margin-bottom:6px; font-weight:700;">Layanan terkait</label>
                            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:10px;">
                                @foreach ($services as $service)
                                    <label style="display:flex; align-items:center; gap:8px; border:1px solid #dfe7f4; border-radius:8px; padding:10px 12px;">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" {{ $risk->services->contains($service->id) || in_array($service->id, old('services', []), true) ? 'checked' : '' }}>
                                        <span>{{ $service->service_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:14px;">
                        <button type="submit" style="background:#1d4ed8; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer;">Simpan Identifikasi</button>
                    </div>
                </form>
            </div>
        @endforeach
        <div style="margin-top:18px;">{{ $risks->links() }}</div>
    @endif
</div>
@endsection
