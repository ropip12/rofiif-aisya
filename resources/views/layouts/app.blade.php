<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --panel: #ffffff;
            --panel-soft: #eef4ff;
            --sidebar: #12263f;
            --sidebar-soft: #1c3d63;
            --primary: #1d4ed8;
            --primary-soft: #dfe9ff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #dfe7f4;
            --success: #166534;
            --success-soft: #dcfce7;
            --warning: #92400e;
            --warning-soft: #fef3c7;
            --danger: #b91c1c;
            --danger-soft: #fee2e2;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        a { color: inherit; text-decoration: none; }

        .app-shell {
            min-height: 100vh;
            display: flex;
            background: var(--bg);
        }

        .sidebar {
            width: 280px;
            background: var(--sidebar);
            color: #edf3ff;
            min-height: 100vh;
            position: sticky;
            top: 0;
            padding: 20px 16px;
            transition: transform 0.25s ease;
        }

        .brand {
            padding: 8px 12px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            margin-bottom: 18px;
        }

        .brand-title {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .brand-subtitle {
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            margin-top: 6px;
        }

        .nav-section {
            margin-bottom: 14px;
        }

        .nav-label {
            margin: 8px 10px 8px;
            font-size: 11px;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
        }

        .nav-item {
            display: block;
            padding: 10px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 4px;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(255,255,255,0.08);
        }

        .nav-submenu {
            margin: 6px 0 10px 10px;
            padding-left: 10px;
            border-left: 1px solid rgba(255,255,255,0.14);
        }

        .nav-subitem {
            display: block;
            padding: 7px 10px;
            border-radius: 8px;
            color: rgba(255,255,255,0.78);
            font-size: 13px;
            margin-bottom: 2px;
        }

        .nav-subitem.is-placeholder {
            color: rgba(255,255,255,0.64);
        }

        .content {
            flex: 1;
            min-width: 0;
            padding: 0;
        }

        .header {
            background: var(--panel);
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 74px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-toggle {
            display: none;
            border: 1px solid var(--border);
            background: var(--panel);
            border-radius: 8px;
            width: 38px;
            height: 38px;
            font-size: 18px;
            cursor: pointer;
        }

        .header-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
        }

        .header-user {
            color: var(--muted);
            font-size: 14px;
        }

        .logout-btn {
            background: var(--danger-soft);
            color: var(--danger);
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 9px 14px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
        }

        .main {
            padding: 28px;
        }

        .page-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
            padding: 24px;
        }

        .page-card h1 {
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 26px;
        }

        .subtitle {
            color: var(--muted);
            margin-bottom: 18px;
        }

        .info-box {
            padding: 16px 18px;
            border-radius: 10px;
            background: var(--panel-soft);
            border: 1px solid var(--border);
            margin-top: 18px;
        }

        .tag {
            display: inline-block;
            background: var(--primary-soft);
            color: var(--primary);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .tag.success {
            background: var(--success-soft);
            color: var(--success);
        }

        .tag.warning {
            background: var(--warning-soft);
            color: var(--warning);
        }

        .muted {
            color: var(--muted);
        }

        @media (max-width: 920px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                z-index: 20;
                box-shadow: 10px 0 30px rgba(0,0,0,0.12);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .menu-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .header {
                padding: 14px 18px;
            }

            .main {
                padding: 16px;
            }

            .page-card {
                padding: 18px;
            }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $currentPath = request()->path();

        $adminMenu = [
            'dashboard' => ['title' => 'Dashboard', 'route' => route('dashboard')],
            'aset' => ['title' => 'Manajemen Aset', 'route' => route('management.aset')],
            'risiko' => ['title' => 'Manajemen Risiko', 'route' => route('management.risiko')],
            'layanan' => ['title' => 'Manajemen Layanan', 'route' => route('management.layanan')],
        ];

        $userAsetMenu = [
            'dashboard' => ['title' => 'Dashboard', 'route' => route('dashboard')],
            'aset' => ['title' => 'Aset', 'route' => route('management.aset')],
        ];

        $userRisikoMenu = [
            'dashboard' => ['title' => 'Dashboard', 'route' => route('dashboard')],
            'risiko' => ['title' => 'Risiko', 'route' => route('management.risiko')],
        ];

        $userLayananMenu = [
            'dashboard' => ['title' => 'Dashboard', 'route' => route('dashboard')],
            'layanan' => ['title' => 'Layanan', 'route' => route('management.layanan')],
        ];

        $navMenu = $user && $user->isAdmin() ? $adminMenu : ($user && $user->management === 'aset' ? $userAsetMenu : ($user && $user->management === 'risiko' ? $userRisikoMenu : ($user && $user->management === 'layanan' ? $userLayananMenu : [])));
    @endphp

    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-title">Sistem Informasi</div>
                <div class="brand-subtitle">Aset, Risiko, & Layanan</div>
            </div>

            @if ($user && $user->isAdmin())
                <div class="nav-section">
                    <div class="nav-label">Menu</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>

                    <div class="nav-label">Manajemen Aset</div>
                    <a href="{{ route('management.aset') }}" class="nav-item {{ request()->is('aset') ? 'active' : '' }}">Aset</a>
                    <div class="nav-submenu">
                        <span class="nav-subitem is-placeholder">Rincian</span>
                        <span class="nav-subitem is-placeholder">Data Aset</span>
                        <span class="nav-subitem is-placeholder">Semua Aset</span>
                        <span class="nav-subitem is-placeholder">Data & Informasi</span>
                        <span class="nav-subitem is-placeholder">Perangkat Lunak</span>
                        <span class="nav-subitem is-placeholder">Perangkat Keras</span>
                        <span class="nav-subitem is-placeholder">Sarana Pendukung</span>
                        <span class="nav-subitem is-placeholder">SDM & Pihak Ketiga</span>
                        <span class="nav-subitem is-placeholder">Pengadaan / Perolehan</span>
                        <span class="nav-subitem is-placeholder">Penggunaan & Penempatan</span>
                        <span class="nav-subitem is-placeholder">Gudang</span>
                        <span class="nav-subitem is-placeholder">Pemeliharaan</span>
                        <span class="nav-subitem is-placeholder">Evaluasi Aset</span>
                        <a href="{{ route('aset.penanganan-akhir.index') }}" class="nav-subitem {{ request()->is('aset/penanganan-akhir*') ? 'active' : '' }}">Penanganan Akhir</a>
                        <a href="{{ route('aset.riwayat.index') }}" class="nav-subitem {{ request()->is('aset/riwayat*') ? 'active' : '' }}">Riwayat Aset</a>
                        <a href="{{ route('aset.monitoring') }}" class="nav-subitem {{ request()->is('aset/monitoring*') ? 'active' : '' }}">Monitoring</a>
                        <a href="{{ route('aset.laporan') }}" class="nav-subitem {{ request()->is('aset/laporan*') ? 'active' : '' }}">Laporan</a>
                    </div>

                    <div class="nav-label">Manajemen Risiko</div>
                    <a href="{{ route('management.risiko') }}" class="nav-item {{ request()->is('risiko') ? 'active' : '' }}">Risiko</a>
                    <div class="nav-submenu">
                        <a href="{{ route('risiko.index') }}" class="nav-subitem {{ request()->is('risiko') ? 'active' : '' }}">Data Risiko</a>
                        <a href="{{ route('risiko.identifikasi') }}" class="nav-subitem {{ request()->is('risiko/identifikasi*') ? 'active' : '' }}">Identifikasi</a>
                        <a href="{{ route('risiko.penilaian') }}" class="nav-subitem {{ request()->is('risiko/penilaian*') ? 'active' : '' }}">Penilaian</a>
                        <a href="{{ route('risiko.pengendalian') }}" class="nav-subitem {{ request()->is('risiko/pengendalian*') ? 'active' : '' }}">Pengendalian</a>
                        <a href="{{ route('risiko.monitoring') }}" class="nav-subitem {{ request()->is('risiko/monitoring*') ? 'active' : '' }}">Monitoring</a>
                        <a href="{{ route('risiko.evaluasi') }}" class="nav-subitem {{ request()->is('risiko/evaluasi*') ? 'active' : '' }}">Evaluasi</a>
                        <a href="{{ route('risiko.laporan') }}" class="nav-subitem {{ request()->is('risiko/laporan*') ? 'active' : '' }}">Laporan</a>
                    </div>

                    <div class="nav-label">Manajemen Layanan</div>
                    <a href="{{ route('layanan.index') }}" class="nav-item {{ request()->is('layanan') || request()->is('layanan/*') ? 'active' : '' }}">Layanan</a>
                    <div class="nav-submenu">
                        <a href="{{ route('layanan.index') }}" class="nav-subitem {{ request()->is('layanan') || request()->is('layanan/*') && !request()->is('layanan/pengelolaan*') && !request()->is('layanan/monitoring*') && !request()->is('layanan/evaluasi*') && !request()->is('layanan/laporan*') ? 'active' : '' }}">Data Layanan</a>
                        <a href="{{ route('layanan.pengelolaan') }}" class="nav-subitem {{ request()->is('layanan/pengelolaan*') ? 'active' : '' }}">Pengelolaan</a>
                        <a href="{{ route('layanan.monitoring') }}" class="nav-subitem {{ request()->is('layanan/monitoring*') ? 'active' : '' }}">Monitoring</a>
                        <a href="{{ route('layanan.evaluasi') }}" class="nav-subitem {{ request()->is('layanan/evaluasi*') ? 'active' : '' }}">Evaluasi</a>
                        <a href="{{ route('layanan.laporan') }}" class="nav-subitem {{ request()->is('layanan/laporan*') ? 'active' : '' }}">Laporan</a>
                    </div>

                    <div class="nav-label">Pengaturan</div>
                    <a href="{{ route('admin.import.index') }}" class="nav-item {{ request()->is('admin/import*') ? 'active' : '' }}">Import Data Excel</a>
                    <a href="{{ route('admin.pengguna.index') }}" class="nav-item {{ request()->is('admin/pengguna*') ? 'active' : '' }}">Manajemen Pengguna</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 10px 0 0;">
                        @csrf
                        <button type="submit" class="logout-btn" style="width: 100%; text-align: left;">Logout</button>
                    </form>
                </div>
            @elseif ($user && $user->management === 'aset')
                <div class="nav-section">
                    <div class="nav-label">Menu</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <div class="nav-label">Aset</div>
                    <a href="{{ route('management.aset') }}" class="nav-item {{ request()->is('aset') ? 'active' : '' }}">Rincian</a>
                    <div class="nav-submenu">
                        <span class="nav-subitem is-placeholder">Data Aset</span>
                        <span class="nav-subitem is-placeholder">Pengadaan</span>
                        <span class="nav-subitem is-placeholder">Penggunaan</span>
                        <span class="nav-subitem is-placeholder">Gudang</span>
                        <span class="nav-subitem is-placeholder">Pemeliharaan</span>
                        <span class="nav-subitem is-placeholder">Evaluasi</span>
                        <a href="{{ route('aset.penanganan-akhir.index') }}" class="nav-subitem {{ request()->is('aset/penanganan-akhir*') ? 'active' : '' }}">Penanganan Akhir</a>
                        <a href="{{ route('aset.riwayat.index') }}" class="nav-subitem {{ request()->is('aset/riwayat*') ? 'active' : '' }}">Riwayat</a>
                        <a href="{{ route('aset.monitoring') }}" class="nav-subitem {{ request()->is('aset/monitoring*') ? 'active' : '' }}">Monitoring</a>
                        <a href="{{ route('aset.laporan') }}" class="nav-subitem {{ request()->is('aset/laporan*') ? 'active' : '' }}">Laporan</a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 10px 0 0;">
                        @csrf
                        <button type="submit" class="logout-btn" style="width: 100%; text-align: left;">Logout</button>
                    </form>
                </div>
            @elseif ($user && $user->management === 'risiko')
                <div class="nav-section">
                    <div class="nav-label">Menu</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <div class="nav-label">Risiko</div>
                    <a href="{{ route('risiko.index') }}" class="nav-item {{ request()->is('risiko') ? 'active' : '' }}">Data Risiko</a>
                    <div class="nav-submenu">
                        <a href="{{ route('risiko.identifikasi') }}" class="nav-subitem {{ request()->is('risiko/identifikasi*') ? 'active' : '' }}">Identifikasi</a>
                        <a href="{{ route('risiko.penilaian') }}" class="nav-subitem {{ request()->is('risiko/penilaian*') ? 'active' : '' }}">Penilaian</a>
                        <a href="{{ route('risiko.pengendalian') }}" class="nav-subitem {{ request()->is('risiko/pengendalian*') ? 'active' : '' }}">Pengendalian</a>
                        <a href="{{ route('risiko.monitoring') }}" class="nav-subitem {{ request()->is('risiko/monitoring*') ? 'active' : '' }}">Monitoring</a>
                        <a href="{{ route('risiko.evaluasi') }}" class="nav-subitem {{ request()->is('risiko/evaluasi*') ? 'active' : '' }}">Evaluasi</a>
                        <a href="{{ route('risiko.laporan') }}" class="nav-subitem {{ request()->is('risiko/laporan*') ? 'active' : '' }}">Laporan</a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 10px 0 0;">
                        @csrf
                        <button type="submit" class="logout-btn" style="width: 100%; text-align: left;">Logout</button>
                    </form>
                </div>
            @elseif ($user && $user->management === 'layanan')
                <div class="nav-section">
                    <div class="nav-label">Menu</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <div class="nav-label">Layanan</div>
                    <a href="{{ route('layanan.index') }}" class="nav-item {{ request()->is('layanan') || request()->is('layanan/*') ? 'active' : '' }}">Data Layanan</a>
                    <div class="nav-submenu">
                        <a href="{{ route('layanan.pengelolaan') }}" class="nav-subitem {{ request()->is('layanan/pengelolaan*') ? 'active' : '' }}">Pengelolaan</a>
                        <a href="{{ route('layanan.monitoring') }}" class="nav-subitem {{ request()->is('layanan/monitoring*') ? 'active' : '' }}">Monitoring</a>
                        <a href="{{ route('layanan.evaluasi') }}" class="nav-subitem {{ request()->is('layanan/evaluasi*') ? 'active' : '' }}">Evaluasi</a>
                        <a href="{{ route('layanan.laporan') }}" class="nav-subitem {{ request()->is('layanan/laporan*') ? 'active' : '' }}">Laporan</a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 10px 0 0;">
                        @csrf
                        <button type="submit" class="logout-btn" style="width: 100%; text-align: left;">Logout</button>
                    </form>
                </div>
            @endif
        </aside>

        <div class="content">
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" type="button" id="menuToggle" aria-label="Toggle menu">☰</button>
                    <div class="header-title">@yield('page_title', 'Dashboard')</div>
                </div>
                <div class="header-user">
                    {{ $user ? $user->email : 'Guest' }}
                    <form method="POST" action="{{ route('logout') }}" style="display: inline-block; margin-left: 12px;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </header>

            <main class="main">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', function () {
                sidebar.classList.toggle('open');
            });
        }
    </script>
</body>
</html>
