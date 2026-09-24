@extends('layouts.app')

@section('title', 'Data Risiko')
@section('page_title', 'Data Risiko')

@section('content')
<div class="page-card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
        <div>
            <h1>Data Risiko</h1>
            <div class="subtitle">Daftar dan pengelolaan data risiko</div>
        </div>
        <a href="{{ route('risiko.create') }}" style="background:#1d4ed8; color:#fff; padding:10px 16px; border-radius:8px; font-weight:700;">Tambah Risiko</a>
    </div>

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

    <form method="GET" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:18px;">
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / nama / sebab / dampak" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
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
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Tingkat</label>
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
        <div class="info-box">
            <strong>Belum ada data risiko.</strong>
            <div class="muted" style="margin-top:8px;">Tambahkan risiko pertama untuk memulai.</div>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; min-width:1000px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Kode</th>
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Nama Risiko</th>
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Kemungkinan</th>
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Tingkat</th>
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Status</th>
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Aset</th>
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Layanan</th>
                        <th style="padding:12px; border-bottom:1px solid #edf2f7; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($risks as $risk)
                        <tr>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $risk->risk_code }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $risk->risk_name }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $risk->likelihood ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $risk->risk_level ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $risk->risk_status ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $risk->assets->count() }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $risk->services->count() }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('risiko.show', ['risiko' => $risk->id]) }}" style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Detail</a>
                                    <a href="{{ route('risiko.edit', ['risiko' => $risk->id]) }}" style="background:#fef3c7; color:#92400e; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Edit</a>
                                    <form method="POST" action="{{ route('risiko.destroy', ['risiko' => $risk->id]) }}" onsubmit="return confirm('Hapus risiko ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#fee2e2; color:#991b1b; border:none; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700; cursor:pointer;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:18px;">{{ $risks->links() }}</div>
    @endif
</div>
@endsection
