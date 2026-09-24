@extends('layouts.app')

@section('title', 'Tambah Layanan')
@section('page_title', 'Tambah Layanan')

@section('content')
<div class="page-card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:20px; flex-wrap:wrap;">
        <div>
            <h1 style="margin:0;">Tambah Layanan</h1>
            <div class="subtitle" style="margin-top:8px;">Tambah data layanan baru.</div>
        </div>
        <a href="{{ route('layanan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 16px; border-radius:8px; font-weight:700; text-decoration:none;">Kembali</a>
    </div>

    @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('layanan.store') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:16px;">
        @csrf

        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Kode Layanan</label>
            <input type="text" name="service_code" value="{{ old('service_code') }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>

        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Nama Layanan</label>
            <input type="text" name="service_name" value="{{ old('service_name') }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>

        <div style="grid-column:1 / -1;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Deskripsi</label>
            <textarea name="service_description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('service_description') }}</textarea>
        </div>

        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Jenis Layanan</label>
            <select name="service_type" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Pilih jenis</option>
                @foreach ($serviceTypes as $type)
                    <option value="{{ $type }}" {{ old('service_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Pengelola / Penanggung Jawab</label>
            <input type="text" name="service_owner" value="{{ old('service_owner') }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>

        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Status</label>
            <select name="service_status" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Pilih status</option>
                @foreach ($serviceStatuses as $status)
                    <option value="{{ $status }}" {{ old('service_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div style="grid-column:1 / -1;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Informasi Pendukung</label>
            <textarea name="supporting_information" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('supporting_information') }}</textarea>
        </div>

        <div style="grid-column:1 / -1;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Monitoring</label>
            <textarea name="monitoring" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('monitoring') }}</textarea>
        </div>

        <div style="grid-column:1 / -1;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Evaluasi</label>
            <textarea name="evaluation" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('evaluation') }}</textarea>
        </div>

        @if ($assets->isNotEmpty())
            <div style="grid-column:1 / -1;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Aset Pendukung</label>
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:8px;">
                    @foreach ($assets as $asset)
                        <label style="display:flex; align-items:center; gap:8px; background:#f8fafc; padding:10px; border:1px solid #e2e8f0; border-radius:8px;">
                            <input type="checkbox" name="assets[]" value="{{ $asset->id }}" {{ in_array($asset->id, old('assets', []), true) ? 'checked' : '' }}>
                            <span>{{ $asset->asset_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($risks->isNotEmpty())
            <div style="grid-column:1 / -1;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Risiko Terkait</label>
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:8px;">
                    @foreach ($risks as $risk)
                        <label style="display:flex; align-items:center; gap:8px; background:#f8fafc; padding:10px; border:1px solid #e2e8f0; border-radius:8px;">
                            <input type="checkbox" name="risks[]" value="{{ $risk->id }}" {{ in_array($risk->id, old('risks', []), true) ? 'checked' : '' }}>
                            <span>{{ $risk->risk_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <div style="grid-column:1 / -1; display:flex; justify-content:flex-end; gap:12px; margin-top:8px;">
            <a href="{{ route('layanan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 16px; border-radius:8px; font-weight:700; text-decoration:none;">Batal</a>
            <button type="submit" style="background:#1d4ed8; color:#fff; border:none; padding:10px 18px; border-radius:8px; font-weight:700; cursor:pointer;">Simpan</button>
        </div>
    </form>
</div>
@endsection
