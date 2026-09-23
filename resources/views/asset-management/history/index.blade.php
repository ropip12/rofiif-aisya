@extends('layouts.app')

@section('title', 'Riwayat Aset')
@section('page_title', 'Riwayat Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
            <div>
                <h1 style="margin:0;">Riwayat Aset</h1>
                <div class="subtitle" style="margin:8px 0 0;">Perjalanan dan perubahan data aset dari aktivitas yang terjadi.</div>
            </div>
        </div>

        <form method="GET" action="{{ route('aset.riwayat.index') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:20px;">
            <div>
                <label for="search" style="display:block; margin-bottom:6px; font-weight:700;">Cari</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Aset / aktivitas / perubahan" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>
            <div>
                <label for="asset_id" style="display:block; margin-bottom:6px; font-weight:700;">Aset</label>
                <select id="asset_id" name="asset_id" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    <option value="">Semua aset</option>
                    @foreach ($assets as $id => $label)
                        <option value="{{ $id }}" {{ request('asset_id') == $id ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; align-items:end; gap:8px;">
                <button type="submit" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer;">Filter</button>
                <a href="{{ route('aset.riwayat.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 16px; border-radius:8px; font-weight:700;">Reset</a>
            </div>
        </form>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aset</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Jenis Aktivitas</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Tanggal</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Informasi Perubahan</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->asset?->asset_name ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->activity_type ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->activity_date?->format('d-m-Y') ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->change_info ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->description ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:18px; text-align:center; color:#6b7280;">Belum ada riwayat aset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;">{{ $items->links() }}</div>
    </div>
@endsection
