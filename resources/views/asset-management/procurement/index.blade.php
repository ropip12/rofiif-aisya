@extends('layouts.app')

@section('title', 'Pengadaan Aset')
@section('page_title', 'Pengadaan Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
            <div>
                <h1 style="margin:0;">Pengadaan Aset</h1>
                <div class="subtitle" style="margin:8px 0 0;">Catatan pengadaan aset perusahaan.</div>
            </div>
            <a href="{{ route('aset.pengadaan.create') }}" style="display:inline-block; background:#1d4ed8; color:#fff; padding:10px 16px; border-radius:8px; font-weight:700;">Tambah Data</a>
        </div>

        @if (session('success'))
            <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">{{ session('success') }}</div>
        @endif

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aset</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Tanggal</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Metode</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Vendor</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Nilai</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->asset?->asset_name ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->procurement_date?->format('d-m-Y') ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->procurement_method ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->source_vendor ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->procurement_value ? 'Rp ' . number_format($item->procurement_value, 0, ',', '.') : '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('aset.pengadaan.show', ['asset_procurement' => $item->id]) }}" style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Detail</a>
                                    <a href="{{ route('aset.pengadaan.edit', ['asset_procurement' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Edit</a>
                                    <form method="POST" action="{{ route('aset.pengadaan.destroy', ['asset_procurement' => $item->id]) }}" onsubmit="return confirm('Hapus data pengadaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#fee2e2; color:#b91c1c; padding:6px 10px; border:none; border-radius:6px; cursor:pointer; font-size:12px; font-weight:700;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:18px; text-align:center; color:#6b7280;">Belum ada data pengadaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;">{{ $items->links() }}</div>
    </div>
@endsection
