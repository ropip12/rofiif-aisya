@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="page-card">
        <h1>Dashboard</h1>
        <div class="subtitle">Selamat datang, {{ $user->name ?? $user->email }}</div>

        <div class="info-box">
            <div><strong>Akses:</strong> {{ $user->isAdmin() ? 'Admin' : 'User' }}</div>
            @if ($user->isAdmin())
                <div style="margin-top: 8px;"><strong>Manajemen:</strong> <span class="tag success">Semua Manajemen</span></div>
            @else
                <div style="margin-top: 8px;"><strong>Manajemen:</strong> <span class="tag">{{ ucfirst($user->management) }}</span></div>
            @endif
        </div>
    </div>
@endsection
