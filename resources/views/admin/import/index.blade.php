@extends('layouts.app')

@section('title', 'Import Data Excel')
@section('page_title', 'Import Data Excel')

@section('content')
    <div style="display:grid; gap:20px; max-width:900px;">
        <div style="background:white; border:1px solid #e5e7eb; border-radius:16px; padding:24px; box-shadow:0 8px 20px rgba(15,23,42,.04);">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
                <div>
                    <h1 style="margin:0; font-size:2rem; color:#0f172a;">Import Data Excel</h1>
                    <div style="margin-top:8px; color:#475569;">Unggah file spreadsheet untuk menambahkan data aset, risiko, atau layanan.</div>
                </div>
            </div>

            @if (session('success'))
                <div style="margin-bottom:16px; padding:12px 14px; border-radius:10px; background:#dcfce7; color:#166534; border:1px solid #bbf7d0; font-weight:600;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div style="margin-bottom:16px; padding:12px 14px; border-radius:10px; background:#fee2e2; color:#991b1b; border:1px solid #fecaca; font-weight:600;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.import.store') }}" enctype="multipart/form-data" style="display:grid; gap:18px;">
                @csrf

                <div>
                    <label for="type" style="display:block; margin-bottom:8px; font-weight:700; color:#1f2937;">Jenis data</label>
                    <select name="type" id="type" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:1rem;">
                        <option value="">-- Pilih jenis data --</option>
                        <option value="asset">Aset</option>
                        <option value="risk">Risiko</option>
                        <option value="service">Layanan</option>
                    </select>
                    @error('type')
                        <div style="margin-top:6px; color:#b91c1c; font-size:0.92rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="excel_file" style="display:block; margin-bottom:8px; font-weight:700; color:#1f2937;">File Excel</label>
                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required style="width:100%; padding:12px 14px; border:1px solid #cbd5e1; border-radius:10px; background:#fff;">
                    @error('excel_file')
                        <div style="margin-top:6px; color:#b91c1c; font-size:0.92rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="padding:14px 16px; border:1px dashed #cbd5e1; background:#f8fafc; border-radius:12px; color:#475569;">
                    <strong>Format tabel yang didukung:</strong><br>
                    - Header harus ada pada baris pertama<br>
                    - Kolom minimum untuk setiap tipe: Aset: asset_code, asset_name; Risiko: risk_code, risk_name; Layanan: service_code, service_name<br>
                    - Kode data harus unik dan belum ada di database.
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px; flex-wrap:wrap;">
                    <button type="submit" style="padding:12px 18px; border:0; border-radius:10px; background:#2563eb; color:white; font-weight:700; cursor:pointer;">Import Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection
