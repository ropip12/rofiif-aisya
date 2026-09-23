@extends('layouts.app')

@section('title', 'Tambah Evaluasi Aset')
@section('page_title', 'Tambah Evaluasi Aset')

@section('content')
    <div class="page-card">
        <h1>Tambah Data Evaluasi</h1>
        <div class="subtitle">Catat hasil evaluasi dan tindak lanjut aset.</div>

        <form method="POST" action="{{ route('aset.evaluasi.store') }}" style="display:grid; gap:16px; max-width:800px;">
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
                    <label for="evaluation_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Evaluasi</label>
                    <input type="date" id="evaluation_date" name="evaluation_date" value="{{ old('evaluation_date') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('evaluation_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="condition" style="display:block; margin-bottom:6px; font-weight:700;">Kondisi</label>
                    <input type="text" id="condition" name="condition" value="{{ old('condition') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('condition') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label for="evaluation_result" style="display:block; margin-bottom:6px; font-weight:700;">Hasil Evaluasi</label>
                    <input type="text" id="evaluation_result" name="evaluation_result" value="{{ old('evaluation_result') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('evaluation_result') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="follow_up_status" style="display:block; margin-bottom:6px; font-weight:700;">Status Tindak Lanjut</label>
                    <input type="text" id="follow_up_status" name="follow_up_status" value="{{ old('follow_up_status') }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('follow_up_status') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label for="recommendation" style="display:block; margin-bottom:6px; font-weight:700;">Rekomendasi</label>
                <textarea id="recommendation" name="recommendation" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('recommendation') }}</textarea>
                @error('recommendation') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="description" style="display:block; margin-bottom:6px; font-weight:700;">Keterangan</label>
                <textarea id="description" name="description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('description') }}</textarea>
                @error('description') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:11px 16px; font-weight:700; cursor:pointer;">Simpan</button>
                <a href="{{ route('aset.evaluasi.index') }}" style="background:#e2e8f0; color:#0f172a; padding:11px 16px; border-radius:8px; font-weight:700;">Batal</a>
            </div>
        </form>
    </div>
@endsection
