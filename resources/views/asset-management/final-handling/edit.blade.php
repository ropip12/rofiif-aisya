@extends('layouts.app')

@section('title', 'Edit Penanganan Akhir Aset')
@section('page_title', 'Edit Penanganan Akhir Aset')

@section('content')
    <div class="page-card">
        <h1>Edit Data Penanganan Akhir</h1>
        <div class="subtitle">Perbarui jenis penanganan akhir aset.</div>

        <form method="POST" action="{{ route('aset.penanganan-akhir.update', ['asset_final_handling' => $item->id]) }}" style="display:grid; gap:16px; max-width:800px;">
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
                    <label for="final_handling_type" style="display:block; margin-bottom:6px; font-weight:700;">Jenis Penanganan</label>
                    <select id="final_handling_type" name="final_handling_type" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                        @foreach ($finalHandlingTypes as $type)
                            <option value="{{ $type }}" {{ old('final_handling_type', $item->final_handling_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('final_handling_type') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label for="handling_date" style="display:block; margin-bottom:6px; font-weight:700;">Tanggal Penanganan</label>
                    <input type="date" id="handling_date" name="handling_date" value="{{ old('handling_date', $item->handling_date?->format('Y-m-d')) }}" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                    @error('handling_date') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <label for="reason" style="display:block; margin-bottom:6px; font-weight:700;">Alasan</label>
                <textarea id="reason" name="reason" rows="3" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('reason', $item->reason) }}</textarea>
                @error('reason') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="description" style="display:block; margin-bottom:6px; font-weight:700;">Keterangan</label>
                <textarea id="description" name="description" rows="4" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">{{ old('description', $item->description) }}</textarea>
                @error('description') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="status" style="display:block; margin-bottom:6px; font-weight:700;">Status</label>
                <input type="text" id="status" name="status" value="{{ old('status', $item->status) }}" placeholder="Contoh: Selesai, Dalam Proses" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                @error('status') <div style="color:#b91c1c; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <button type="submit" style="background:#0f172a; color:#fff; border:none; border-radius:8px; padding:11px 16px; font-weight:700; cursor:pointer;">Perbarui</button>
                <a href="{{ route('aset.penanganan-akhir.index') }}" style="background:#e2e8f0; color:#0f172a; padding:11px 16px; border-radius:8px; font-weight:700;">Kembali</a>
            </div>
        </form>
    </div>
@endsection
