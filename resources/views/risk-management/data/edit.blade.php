@extends('layouts.app')

@section('title', 'Edit Risiko')
@section('page_title', 'Edit Risiko')

@section('content')
<div class="page-card">
    <h1>Edit Risiko</h1>
    @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('risiko.update', ['risiko' => $risk->id]) }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:18px;">
        @csrf
        @method('PUT')

        <div>
            <label for="risk_code" style="display:block; margin-bottom:6px; font-weight:700;">Kode Risiko</label>
            <input type="text" id="risk_code" name="risk_code" value="{{ old('risk_code', $risk->risk_code) }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>

        <div>
            <label for="risk_name" style="display:block; margin-bottom:6px; font-weight:700;">Nama Risiko</label>
            <input type="text" id="risk_name" name="risk_name" value="{{ old('risk_name', $risk->risk_name) }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>

        <div style="grid-column:1 / -1;">
            <label for="cause" style="display:block; margin-bottom:6px; font-weight:700;">Penyebab</label>
            <textarea id="cause" name="cause" rows="3" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('cause', $risk->cause) }}</textarea>
        </div>

        <div style="grid-column:1 / -1;">
            <label for="impact" style="display:block; margin-bottom:6px; font-weight:700;">Dampak</label>
            <textarea id="impact" name="impact" rows="3" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('impact', $risk->impact) }}</textarea>
        </div>

        <div>
            <label for="likelihood" style="display:block; margin-bottom:6px; font-weight:700;">Kemungkinan</label>
            <input type="text" id="likelihood" name="likelihood" value="{{ old('likelihood', $risk->likelihood) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>

        <div>
            <label for="risk_level" style="display:block; margin-bottom:6px; font-weight:700;">Tingkat Risiko</label>
            <select id="risk_level" name="risk_level" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Pilih</option>
                @foreach ($riskLevels as $level)
                    <option value="{{ $level }}" {{ old('risk_level', $risk->risk_level) == $level ? 'selected' : '' }}>{{ $level }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="risk_status" style="display:block; margin-bottom:6px; font-weight:700;">Status Risiko</label>
            <select id="risk_status" name="risk_status" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Pilih</option>
                @foreach ($riskStatuses as $status)
                    <option value="{{ $status }}" {{ old('risk_status', $risk->risk_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div style="grid-column:1 / -1;">
            <label for="control_measures" style="display:block; margin-bottom:6px; font-weight:700;">Pengendalian</label>
            <textarea id="control_measures" name="control_measures" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('control_measures', $risk->control_measures) }}</textarea>
        </div>

        <div style="grid-column:1 / -1;">
            <label for="mitigation_plan" style="display:block; margin-bottom:6px; font-weight:700;">Rencana Mitigasi</label>
            <textarea id="mitigation_plan" name="mitigation_plan" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('mitigation_plan', $risk->mitigation_plan) }}</textarea>
        </div>

        <div style="grid-column:1 / -1;">
            <label style="display:block; margin-bottom:6px; font-weight:700;">Aset terkait</label>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:10px;">
                @foreach ($assets as $asset)
                    <label style="display:flex; align-items:center; gap:8px; border:1px solid #dfe7f4; border-radius:8px; padding:10px 12px;">
                        <input type="checkbox" name="assets[]" value="{{ $asset->id }}" {{ ($risk->assets->contains($asset->id) || in_array($asset->id, old('assets', []), true)) ? 'checked' : '' }}>
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
                        <input type="checkbox" name="services[]" value="{{ $service->id }}" {{ ($risk->services->contains($service->id) || in_array($service->id, old('services', []), true)) ? 'checked' : '' }}>
                        <span>{{ $service->service_name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div style="grid-column:1 / -1;">
            <label for="monitoring" style="display:block; margin-bottom:6px; font-weight:700;">Monitoring</label>
            <textarea id="monitoring" name="monitoring" rows="2" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('monitoring', $risk->monitoring) }}</textarea>
        </div>

        <div style="grid-column:1 / -1;">
            <label for="evaluation" style="display:block; margin-bottom:6px; font-weight:700;">Evaluasi</label>
            <textarea id="evaluation" name="evaluation" rows="2" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('evaluation', $risk->evaluation) }}</textarea>
        </div>

        <div style="grid-column:1 / -1; display:flex; gap:12px; flex-wrap:wrap; margin-top:8px;">
            <button type="submit" style="background:#1d4ed8; color:#fff; border:none; border-radius:8px; padding:12px 18px; font-weight:700; cursor:pointer;">Update</button>
            <a href="{{ route('risiko.show', ['risiko' => $risk->id]) }}" style="background:#e5e7eb; color:#111827; padding:12px 18px; border-radius:8px; font-weight:700; text-decoration:none;">Batal</a>
        </div>
    </form>
</div>
@endsection
