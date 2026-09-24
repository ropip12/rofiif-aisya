@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page_title', 'Tambah Pengguna')

@section('content')
    <div class="page-card" style="max-width:760px; margin:0 auto;">
        <h1 style="margin-top:0; margin-bottom:18px;">Tambah Pengguna</h1>

        <form method="POST" action="{{ route('admin.pengguna.store') }}">
            @csrf

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                    @error('name')<div style="color:#b91c1c; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                    @error('email')<div style="color:#b91c1c; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Password</label>
                    <input type="password" name="password" required style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                    @error('password')<div style="color:#b91c1c; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Role</label>
                    <select name="role" required style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Pilih role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('role')<div style="color:#b91c1c; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Management</label>
                    <select name="management" style="width:100%; padding:10px 12px; border:1px solid #dbe3f0; border-radius:8px;">
                        <option value="">Pilih management</option>
                        @foreach ($managements as $management)
                            <option value="{{ $management }}" {{ old('management') === $management ? 'selected' : '' }}>{{ $management }}</option>
                        @endforeach
                    </select>
                    @error('management')<div style="color:#b91c1c; font-size:12px; margin-top:6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:24px; flex-wrap:wrap;">
                <button type="submit" style="padding:10px 16px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Simpan</button>
                <a href="{{ route('admin.pengguna.index') }}" style="padding:10px 16px; background:#e5e7eb; color:#111827; border-radius:8px; font-weight:700;">Batal</a>
            </div>
        </form>
    </div>
@endsection
