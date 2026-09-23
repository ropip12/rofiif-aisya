@extends('layouts.app')

@section('title', 'Edit Gudang Aset')
@section('page_title', 'Edit Gudang Aset')

@section('content')
    <div class="page-card">
        <h1>Edit Data Gudang</h1>
        <div class="subtitle">Perbarui lokasi dan status penyimpanan aset.</div>

        <form method="POST" action="{{ route('aset.gudang.update', ['asset_warehouse' => $item->id]) }}" style="display:grid; gap:16px; max-width:800px;">
            @csrf
            @method('PUT')

            <div>
                <label for="asset_id" style="display:block; margin-bottom:6px; font-weight:700;">Aset</label>
                <select id="asset_id" name="asset_id" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @foreach ($assets as $id => $label)
                        <option value="{{ $id }}" {{ old('asset_id', $item->asset_id) == $id ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('asset_id') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label for="warehouse_location" style="display:block; margin-bottom:6px; font-weight:700;">Lokasi Gudang</label>
                    <input type="text" id="warehouse_location" name="warehouse_location" value="{{ old('warehouse_location', $item->warehouse_location) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('warehouse_location') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="storage_status" style="display:block; margin-bottom:6px; font-weight:700;">Status Penyimpanan</label>
                    <input type="text" id="storage_status" name="storage_status" value="{{ old('storage_status', $item->storage_status) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('storage_status') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label for="entry_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Masuk</label>
                    <input type="date" id="entry_date" name="entry_date" value="{{ old('entry_date', $item->entry_date?->format('Y-m-d')) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('entry_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="exit_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Keluar</label>
                    <input type="date" id="exit_date" name="exit_date" value="{{ old('exit_date', $item->exit_date?->format('Y-m-d')) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('exit_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label for="description" style="display:block; margin-bottom:6px; font-weight:700;">Keterangan</label>
                <textarea id="description" name="description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('description', $item->description) }}</textarea>
                @error('description') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:11px 16px; font-weight:700; cursor:pointer;">Perbarui</button>
                <a href="{{ route('aset.gudang.index') }}" style="background:#e2e8f0; color:#0f172a; padding:11px 16px; border-radius:8px; font-weight:700;">Kembali</a>
            </div>
        </form>
    </div>
@endsection
