@extends('layouts.app')

@section('title', 'Pengelolaan Layanan')
@section('page_title', 'Pengelolaan Layanan')

@section('content')
<div class="page-card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
        <div>
            <h1 style="margin:0;">Pengelolaan Layanan</h1>
            <div class="subtitle" style="margin-top:8px;">Kelola data operasional layanan yang tersedia.</div>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('layanan.pengelolaan') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-bottom:18px;">
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / nama / pemilik" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
        </div>
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Jenis</label>
            <select name="service_type" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Semua</option>
                @foreach ($serviceTypes as $type)
                    <option value="{{ $type }}" {{ request('service_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:6px; font-weight:700;">Status</label>
            <select name="service_status" style="width:100%; padding:10px 12px; border:1px solid #dfe7f4; border-radius:8px;">
                <option value="">Semua</option>
                @foreach ($serviceStatuses as $status)
                    <option value="{{ $status }}" {{ request('service_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex; align-items:end;">
            <button type="submit" style="width:100%; background:#0f172a; color:#fff; border:none; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer;">Filter</button>
        </div>
    </form>

    @if ($services->isEmpty())
        <div style="background:#f8fafc; border:1px solid #e5e7eb; padding:20px; border-radius:12px; text-align:center; color:#475569;">
            <strong>Belum ada layanan untuk dikelola.</strong>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; min-width:1000px; background:#fff; border:1px solid #e5e7eb;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Nama</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Jenis</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Pemilik</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Status</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aset</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Risiko</th>
                        <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $service->service_name }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $service->service_type ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $service->service_owner ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $service->service_status ?? '-' }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $service->assets->count() }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">{{ $service->risks->count() }}</td>
                            <td style="padding:12px; border-bottom:1px solid #eef2f7;">
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('layanan.show', ['service' => $service->id]) }}" style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700; text-decoration:none;">Detail</a>
                                    <form method="POST" action="{{ route('layanan.pengelolaan.update', ['service' => $service->id]) }}" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" style="background:#22c55e; color:#fff; border:none; padding:6px 10px; border-radius:6px; font-size:12px; font-weight:700; cursor:pointer;">Perbarui</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:16px;">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
