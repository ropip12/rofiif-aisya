@extends('layouts.app')

@section('title', 'Penanganan Akhir Aset')
@section('page_title', 'Penanganan Akhir Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
            <div>
                <h1 style="margin:0;">Penanganan Akhir Aset</h1>
                <div class="subtitle" style="margin:8px 0 0;">Catatan penanganan akhir aset sesuai kategori yang berlaku.</div>
            </div>
            <a href="{{ route('aset.penanganan-akhir.create') }}" style="display:inline-block; background:#1d4ed8; color:#fff; padding:10px 16px; border-radius:8px; font-weight:700;">Tambah Data</a>
        </div>

        @if (session('success'))
            <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('aset.penanganan-akhir.index') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:20px;">
            <div>
                <label for="search" style="display:block; margin-bottom:6px; font-weight:700;">Cari</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nama/kode/alas an" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>
            <div>
                <label for="final_handling_type" style="display:block; margin-bottom:6px; font-weight:700;">Jenis Penanganan</label>
                <select id="final_handling_type" name="final_handling_type" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    <option value="">Semua</option>
                    @foreach ($finalHandlingTypes as $type)
                        <option value="{{ $type }}" {{ request('final_handling_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
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
                <a href="{{ route('aset.penanganan-akhir.index') }}" style="background:#e2e8f0; color:#0f172a; padding:10px 16px; border-radius:8px; font-weight:700;">Reset</a>
            </div>
        </form>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aset</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Jenis</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Tanggal</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Alasan</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Status</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->asset?->asset_name ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->final_handling_type ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->handling_date?->format('d-m-Y') ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ Str::limit($item->reason ?? '-', 40) }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $item->status ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('aset.penanganan-akhir.show', ['asset_final_handling' => $item->id]) }}" style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Detail</a>
                                    <a href="{{ route('aset.penanganan-akhir.edit', ['asset_final_handling' => $item->id]) }}" style="background:#fef3c7; color:#92400e; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Edit</a>
                                    <form method="POST" action="{{ route('aset.penanganan-akhir.destroy', ['asset_final_handling' => $item->id]) }}" onsubmit="return confirm('Hapus data penanganan akhir ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#fee2e2; color:#b91c1c; padding:6px 10px; border:none; border-radius:6px; cursor:pointer; font-size:12px; font-weight:700;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:18px; text-align:center; color:#6b7280;">Belum ada data penanganan akhir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;">{{ $items->links() }}</div>
    </div>
@endsection
