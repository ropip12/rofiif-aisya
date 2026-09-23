@extends('layouts.app')

@section('title', 'Tambah Penggunaan Aset')
@section('page_title', 'Tambah Penggunaan Aset')

@section('content')
    <div class="page-card">
        <h1>Tambah Data Penggunaan</h1>
        <div class="subtitle">Catat lokasi dan status penggunaan aset.</div>

        <form method="POST" action="{{ route('aset.penggunaan.store') }}" style="display:grid; gap:16px; max-width:800px;">
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
                    <label for="location" style="display:block; margin-bottom:6px; font-weight:700;">Lokasi</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('location') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="responsible_person" style="display:block; margin-bottom:6px; font-weight:700;">Penanggung Jawab</label>
                    <input type="text" id="responsible_person" name="responsible_person" value="{{ old('responsible_person') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('responsible_person') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label for="usage_status" style="display:block; margin-bottom:6px; font-weight:700;">Status</label>
                    <input type="text" id="usage_status" name="usage_status" value="{{ old('usage_status') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('usage_status') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="start_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('start_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label for="end_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Selesai</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                @error('end_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="description" style="display:block; margin-bottom:6px; font-weight:700;">Keterangan</label>
                <textarea id="description" name="description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('description') }}</textarea>
                @error('description') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:11px 16px; font-weight:700; cursor:pointer;">Simpan</button>
                <a href="{{ route('aset.penggunaan.index') }}" style="background:#e2e8f0; color:#0f172a; padding:11px 16px; border-radius:8px; font-weight:700;">Batal</a>
            </div>
        </form>
    </div>
@endsection
