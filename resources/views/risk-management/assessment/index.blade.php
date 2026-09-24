@extends('layouts.app')

@section('title', 'Penilaian Risiko')
@section('page_title', 'Penilaian Risiko')

@section('content')
<div class="page-card">
    <h1>Penilaian Risiko</h1>
    <div class="subtitle">Kelola kemungkinan dan tingkat risiko</div>

    @if(session('success'))
        <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:18px;">
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / nama risiko" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Tingkat Risiko</label>
            <select name="risk_level" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Semua</option>
                @foreach ($riskLevels as $level)
                    <option value="{{ $level }}" {{ request('risk_level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex; align-items:end;">
            <button type="submit" style="width:100%; background:#0f172a; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer;">Filter</button>
        </div>
    </form>

    @if ($risks->isEmpty())
        <div class="info-box">Belum ada data penilaian risiko.</div>
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

                <form method="POST" action="{{ route('risiko.penilaian.update', ['risiko' => $risk->id]) }}">
                    @csrf
                    @method('PUT')
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
                        <div>
                            <label style="display:block; margin-bottom:6px; font-weight:700;">Kemungkinan</label>
                            <input type="text" name="likelihood" value="{{ old('likelihood', $risk->likelihood) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                        </div>
                        <div>
                            <label style="display:block; margin-bottom:6px; font-weight:700;">Tingkat Risiko</label>
                            <select name="risk_level" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                                <option value="">Pilih</option>
                                @foreach ($riskLevels as $level)
                                    <option value="{{ $level }}" {{ old('risk_level', $risk->risk_level) == $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="margin-top:14px;">
                        <button type="submit" style="background:#1d4ed8; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer;">Simpan Penilaian</button>
                    </div>
                </form>
            </div>
        @endforeach
        <div style="margin-top:18px;">{{ $risks->links() }}</div>
    @endif
</div>
@endsection
