@extends('layouts.app')

@section('title', 'Data Aset')
@section('page_title', 'Data Aset')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
            <div>
                <h1 style="margin:0;">Data Aset</h1>
                <div class="subtitle" style="margin:8px 0 0;">Daftar aset yang tersedia dalam sistem.</div>
            </div>
            <div>
                <a href="{{ route('aset.create') }}" class="btn-primary" style="display:inline-block; background:#1d4ed8; color:#fff; padding:10px 16px; border-radius:8px; font-weight:700;">Tambah Aset</a>
            </div>
        </div>

        <form method="GET" action="{{ route('aset.index') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:20px;">
            <div>
                <label for="search" style="display:block; margin-bottom:6px; font-weight:700;">Cari</label>
                <input id="search" type="text" name="search" value="{{ old('search', request('search')) }}" placeholder="Kode / nama aset" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>
            <div>
                <label for="classification" style="display:block; margin-bottom:6px; font-weight:700;">Klasifikasi</label>
                <select id="classification" name="classification" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    <option value="">Semua</option>
                    <option value="Perangkat Keras" {{ request('classification') === 'Perangkat Keras' ? 'selected' : '' }}>Perangkat Keras</option>
                    <option value="Perangkat Lunak" {{ request('classification') === 'Perangkat Lunak' ? 'selected' : '' }}>Perangkat Lunak</option>
                    <option value="Sarana Pendukung" {{ request('classification') === 'Sarana Pendukung' ? 'selected' : '' }}>Sarana Pendukung</option>
                </select>
            </div>
            <div>
                <label for="condition" style="display:block; margin-bottom:6px; font-weight:700;">Kondisi</label>
                <select id="condition" name="condition" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    <option value="">Semua</option>
                    <option value="Baik" {{ request('condition') === 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('condition') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('condition') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div>
                <label for="usage" style="display:block; margin-bottom:6px; font-weight:700;">Status penggunaan</label>
                <select id="usage" name="usage" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    <option value="">Semua</option>
                    <option value="Digunakan" {{ request('usage') === 'Digunakan' ? 'selected' : '' }}>Digunakan</option>
                    <option value="Disimpan" {{ request('usage') === 'Disimpan' ? 'selected' : '' }}>Disimpan</option>
                    <option value="Dipinjam" {{ request('usage') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                </select>
            </div>
            <div style="display:flex; align-items:end;">
                <button type="submit" style="width:100%; background:#0f172a; color:#fff; border:none; border-radius:8px; padding:11px 12px; font-weight:700; cursor:pointer;">Filter</button>
            </div>
        </form>

        @if (session('success'))
            <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; background:#fff;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Kode</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Nama</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Klasifikasi</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Kondisi</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Lokasi</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Penanggung Jawab</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Status</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $asset)
                        <tr>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $asset->asset_code }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $asset->asset_name }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $asset->asset_classification }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $asset->condition_status ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $asset->location ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $asset->responsible_person ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $asset->usage_status ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="/aset/{{ $asset->id }}" style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Detail</a>
                                    <a href="/aset/{{ $asset->id }}/edit" style="background:#fef3c7; color:#92400e; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700;">Edit</a>
                                    <form method="POST" action="/aset/{{ $asset->id }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#fee2e2; color:#b91c1c; padding:6px 10px; border:none; border-radius:6px; cursor:pointer; font-size:12px; font-weight:700;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding:18px; text-align:center; color:#6b7280;">Belum ada data aset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;">
            {{ $assets->links() }}
        </div>
    </div>
@endsection
