<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard Admin')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icon --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    {{-- Custom Admin Style --}}
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">

    @stack('head')
</head>
<body>

<div class="admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo-suarakuu.png') }}" alt="Logo" width="100" class="brand-logo">
        </div>

        <div class="sidebar-section-title">ADMIN</div>

        <a href="{{ route('admin.rekap-data.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.rekap-data.index') ? 'active' : '' }}">
            <span>Rekap Data</span>
        </a>

        <a href="{{ route('admin.setting-waktu.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.setting-waktu.index') ? 'active' : '' }}">
            <span>Setting Waktu </span>
        </a>

        {{-- Logout --}}
        <a href="#"
           class="sidebar-link logout-link"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
            <span>Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </aside>
    {{-- END SIDEBAR --}}

    {{-- MAIN AREA --}}
    <div class="admin-main">

        {{-- TOPBAR --}}
        <header class="admin-topbar">
            <div class="topbar-spacer"></div>

            <div class="topbar-user">
                <span class="topbar-username">
                    {{ auth('admin')->check() ? auth('admin')->user()->nama : 'admin' }}
                </span>
                <div class="topbar-avatar">
                    {{ strtoupper(substr(auth('admin')->check() ? auth('admin')->user()->nama : 'A', 0, 1)) }}
                </div>
            </div>
        </header>
        {{-- END TOPBAR --}}

        {{-- CONTENT --}}
        <main class="admin-content">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="admin-footer">
            Copyright © Your Website {{ now()->year }}
        </footer>
    </div>
    {{-- END MAIN AREA --}}

</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
