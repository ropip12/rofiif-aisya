@extends('layouts.app')

@section('title', 'Edit Pemeliharaan Aset')
@section('page_title', 'Edit Pemeliharaan Aset')

@section('content')
    <div class="page-card">
        <h1>Edit Data Pemeliharaan</h1>
        <div class="subtitle">Perbarui data pemeliharaan aset.</div>

        <form method="POST" action="{{ route('aset.pemeliharaan.update', ['asset_maintenance' => $item->id]) }}" style="display:grid; gap:16px; max-width:800px;">
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
                    <label for="maintenance_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Pemeliharaan</label>
                    <input type="date" id="maintenance_date" name="maintenance_date" value="{{ old('maintenance_date', $item->maintenance_date?->format('Y-m-d')) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('maintenance_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="maintenance_type" style="display:block; margin-bottom:6px; font-weight:700;">Tipe Pemeliharaan</label>
                    <input type="text" id="maintenance_type" name="maintenance_type" value="{{ old('maintenance_type', $item->maintenance_type) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('maintenance_type') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label for="vendor" style="display:block; margin-bottom:6px; font-weight:700;">Vendor</label>
                    <input type="text" id="vendor" name="vendor" value="{{ old('vendor', $item->vendor) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('vendor') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="cost" style="display:block; margin-bottom:6px; font-weight:700;">Biaya</label>
                    <input type="number" id="cost" name="cost" value="{{ old('cost', $item->cost) }}" step="0.01" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('cost') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label for="maintenance_result" style="display:block; margin-bottom:6px; font-weight:700;">Hasil Pemeliharaan</label>
                <input type="text" id="maintenance_result" name="maintenance_result" value="{{ old('maintenance_result', $item->maintenance_result) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                @error('maintenance_result') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="description" style="display:block; margin-bottom:6px; font-weight:700;">Keterangan</label>
                <textarea id="description" name="description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('description', $item->description) }}</textarea>
                @error('description') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:11px 16px; font-weight:700; cursor:pointer;">Perbarui</button>
                <a href="{{ route('aset.pemeliharaan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:11px 16px; border-radius:8px; font-weight:700;">Kembali</a>
            </div>
        </form>
    </div>
@endsection
