@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
    <div class="page-card">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h1 style="margin:0;">Manajemen Pengguna</h1>
                <div class="subtitle">Daftar pengguna sistem dan konfigurasi akses berdasarkan role dan management</div>
            </div>
            <a href="{{ route('admin.pengguna.create') }}" style="display:inline-block; padding:10px 14px; background:#1d4ed8; color:white; border-radius:8px; font-weight:700;">Tambah Pengguna</a>
        </div>

        @if(session('success'))
            <div style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:12px 14px; border-radius:8px; margin-bottom:16px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="page-card" style="padding:18px; margin-bottom:20px; background:#f8fafc;">
            <h3 style="margin:0 0 14px;">Filter Pengguna</h3>
            <form method="GET" action="{{ route('admin.pengguna.index') }}" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px; align-items:end;">
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Pencarian</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Role</label>
                    <select name="role" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ ($filters['role'] ?? '') === $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Management</label>
                    <select name="management" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Semua</option>
                        @foreach ($managements as $management)
                            <option value="{{ $management }}" {{ ($filters['management'] ?? '') === $management ? 'selected' : '' }}>{{ $management }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" style="width:100%; padding:10px 14px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Terapkan</button>
                </div>
                <div>
                    <a href="{{ route('admin.pengguna.index') }}" style="display:inline-block; width:100%; text-align:center; padding:10px 14px; background:#e5e7eb; color:#111827; border-radius:8px; font-weight:700;">Reset</a>
                </div>
            </form>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; background:#fff; min-width:900px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:12px; text-align:left;">Nama</th>
                        <th style="padding:12px; text-align:left;">Email</th>
                        <th style="padding:12px; text-align:left;">Role</th>
                        <th style="padding:12px; text-align:left;">Management</th>
                        <th style="padding:12px; text-align:left;">Dibuat</th>
                        <th style="padding:12px; text-align:left;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $user->name }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $user->email }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $user->role }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $user->management ?? '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">{{ $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-' }}</td>
                            <td style="padding:12px; border-top:1px solid #eef2f7;">
                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                    <a href="{{ route('admin.pengguna.show', $user) }}" style="padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; color:#0f172a; background:#f8fafc;">Detail</a>
                                    <a href="{{ route('admin.pengguna.edit', $user) }}" style="padding:7px 10px; border:1px solid #bfdbfe; border-radius:8px; color:#1d4ed8; background:#eff6ff;">Edit</a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.pengguna.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="padding:7px 10px; border:1px solid #fecaca; border-radius:8px; color:#b91c1c; background:#fef2f2; cursor:pointer;">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding:18px; text-align:center; color:#6b7280;">Tidak ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:18px;">
            {{ $users->links() }}
        </div>
    </div>
@endsection
