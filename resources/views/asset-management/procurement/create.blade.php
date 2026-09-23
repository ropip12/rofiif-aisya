@extends('layouts.app')

@section('title', 'Tambah Pengadaan Aset')
@section('page_title', 'Tambah Pengadaan Aset')

@section('content')
    <div class="page-card">
        <h1>Tambah Data Pengadaan</h1>
        <div class="subtitle">Isi detail pengadaan aset untuk dokumentasi pembelian.</div>

        <form method="POST" action="{{ route('aset.pengadaan.store') }}" style="display:grid; gap:16px; max-width:800px;">
            @csrf

            <div>
                <label for="asset_id" style="display:block; margin-bottom:6px; font-weight:700;">Aset</label>
                <select id="asset_id" name="asset_id" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    <option value="">Pilih aset</option>
                    @foreach ($assets as $id => $label)
                        <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('asset_id') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label for="procurement_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Pengadaan</label>
                    <input type="date" id="procurement_date" name="procurement_date" value="{{ old('procurement_date') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('procurement_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="procurement_method" style="display:block; margin-bottom:6px; font-weight:700;">Metode</label>
                    <input type="text" id="procurement_method" name="procurement_method" value="{{ old('procurement_method') }}" placeholder="Pembelian / Hibah / Sewa" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('procurement_method') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label for="source_vendor" style="display:block; margin-bottom:6px; font-weight:700;">Vendor / Sumber</label>
                    <input type="text" id="source_vendor" name="source_vendor" value="{{ old('source_vendor') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('source_vendor') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="procurement_value" style="display:block; margin-bottom:6px; font-weight:700;">Nilai</label>
                    <input type="number" id="procurement_value" name="procurement_value" value="{{ old('procurement_value') }}" step="0.01" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('procurement_value') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label for="description" style="display:block; margin-bottom:6px; font-weight:700;">Keterangan</label>
                <textarea id="description" name="description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('description') }}</textarea>
                @error('description') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:11px 16px; font-weight:700; cursor:pointer;">Simpan</button>
                <a href="{{ route('aset.pengadaan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:11px 16px; border-radius:8px; font-weight:700;">Batal</a>
            </div>
        </form>
    </div>
@endsection
