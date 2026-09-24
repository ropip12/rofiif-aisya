@extends('layouts.app')

@section('title', 'Detail Pengguna')
@section('page_title', 'Detail Pengguna')

@section('content')
    <div class="page-card" style="max-width:760px; margin:0 auto;">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h1 style="margin:0;">{{ $user->name }}</h1>
                <div class="subtitle">Informasi profil pengguna</div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('admin.pengguna.edit', $user) }}" style="padding:10px 14px; border:1px solid #bfdbfe; border-radius:8px; color:#1d4ed8; background:#eff6ff; font-weight:700;">Edit</a>
                <a href="{{ route('admin.pengguna.index') }}" style="padding:10px 14px; background:#e5e7eb; color:#111827; border-radius:8px; font-weight:700;">Kembali</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
            <div class="info-box">
                <div class="muted">Nama</div>
                <div style="font-size:20px; font-weight:700; margin-top:8px;">{{ $user->name }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Email</div>
                <div style="font-size:20px; font-weight:700; margin-top:8px;">{{ $user->email }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Role</div>
                <div style="font-size:20px; font-weight:700; margin-top:8px;">{{ $user->role }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Management</div>
                <div style="font-size:20px; font-weight:700; margin-top:8px;">{{ $user->management ?? '-' }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Tanggal Dibuat</div>
                <div style="font-size:20px; font-weight:700; margin-top:8px;">{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-' }}</div>
            </div>
            <div class="info-box">
                <div class="muted">Terakhir Diperbarui</div>
                <div style="font-size:20px; font-weight:700; margin-top:8px;">{{ $user->updated_at ? $user->updated_at->format('d-m-Y H:i') : '-' }}</div>
            </div>
        </div>
    </div>
@endsection
