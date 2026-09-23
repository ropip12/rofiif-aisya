@extends('layouts.app')

@section('title', 'Tambah Aset')
@section('page_title', 'Tambah Aset')

@section('content')
    <div class="page-card">
        <h1>Tambah Aset</h1>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('aset.store') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:18px;">
            @csrf

            <div>
                <label for="asset_code" style="display:block; margin-bottom:6px; font-weight:700;">Kode aset</label>
                <input type="text" id="asset_code" name="asset_code" value="{{ old('asset_code') }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="asset_name" style="display:block; margin-bottom:6px; font-weight:700;">Nama aset</label>
                <input type="text" id="asset_name" name="asset_name" value="{{ old('asset_name') }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="asset_classification" style="display:block; margin-bottom:6px; font-weight:700;">Klasifikasi</label>
                <input type="text" id="asset_classification" name="asset_classification" value="{{ old('asset_classification') }}" required style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="acquisition_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal perolehan</label>
                <input type="date" id="acquisition_date" name="acquisition_date" value="{{ old('acquisition_date') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="acquisition_value" style="display:block; margin-bottom:6px; font-weight:700;">Nilai perolehan</label>
                <input type="number" id="acquisition_value" name="acquisition_value" value="{{ old('acquisition_value') }}" step="0.01" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="vendor" style="display:block; margin-bottom:6px; font-weight:700;">Vendor</label>
                <input type="text" id="vendor" name="vendor" value="{{ old('vendor') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="condition_status" style="display:block; margin-bottom:6px; font-weight:700;">Kondisi</label>
                <input type="text" id="condition_status" name="condition_status" value="{{ old('condition_status') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="location" style="display:block; margin-bottom:6px; font-weight:700;">Lokasi</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="responsible_person" style="display:block; margin-bottom:6px; font-weight:700;">Penanggung jawab</label>
                <input type="text" id="responsible_person" name="responsible_person" value="{{ old('responsible_person') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="usage_status" style="display:block; margin-bottom:6px; font-weight:700;">Status penggunaan</label>
                <input type="text" id="usage_status" name="usage_status" value="{{ old('usage_status') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div>
                <label for="useful_life_years" style="display:block; margin-bottom:6px; font-weight:700;">Masa manfaat (tahun)</label>
                <input type="number" id="useful_life_years" name="useful_life_years" value="{{ old('useful_life_years') }}" min="0" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="data_storage_information" style="display:block; margin-bottom:6px; font-weight:700;">Informasi penyimpanan data</label>
                <textarea id="data_storage_information" name="data_storage_information" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('data_storage_information') }}</textarea>
            </div>

            <div>
                <label for="lifecycle_status" style="display:block; margin-bottom:6px; font-weight:700;">Status lifecycle</label>
                <input type="text" id="lifecycle_status" name="lifecycle_status" value="{{ old('lifecycle_status') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="final_handling" style="display:block; margin-bottom:6px; font-weight:700;">Penanganan akhir</label>
                <textarea id="final_handling" name="final_handling" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('final_handling') }}</textarea>
            </div>

            <div style="grid-column:1 / -1; display:flex; gap:12px; flex-wrap:wrap; margin-top:8px;">
                <button type="submit" style="background:#1d4ed8; color:#fff; border:none; border-radius:8px; padding:12px 18px; font-weight:700; cursor:pointer;">Simpan</button>
                <a href="{{ route('aset.index') }}" style="background:#e5e7eb; color:#111827; padding:12px 18px; border-radius:8px; font-weight:700; text-decoration:none;">Batal</a>
            </div>
        </form>
    </div>
@endsection
