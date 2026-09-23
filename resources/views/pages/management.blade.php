@extends('layouts.app')

@section('title', $title)
@section('page_title', $title)

@section('content')
    <div class="page-card">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Halaman {{ $title }} untuk {{ ucfirst($user->role) }}.</div>

        <div class="info-box">
            <div><strong>Role:</strong> {{ ucfirst($user->role) }}</div>
            <div style="margin-top: 8px;"><strong>Manajemen aktif:</strong> <span class="tag">{{ ucfirst($management) }}</span></div>
        </div>
    </div>
@endsection
