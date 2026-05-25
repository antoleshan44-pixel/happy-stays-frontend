{{-- resources/views/layouts/owner.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Owner Dashboard') | Eserian Homes</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts — DM Sans body + Fraunces display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'secondary':               '#00288e',
                        'secondary-light':         '#e8edf8',
                        'on-surface':              '#131416',
                        'on-surface-variant':      '#52575c',
                        'outline':                 '#8a8f94',
                        'outline-variant':         '#dde1e6',
                        'surface':                 '#f4f6f9',
                        'surface-container':       '#eceef2',
                        'surface-container-low':   '#f0f2f5',
                        'surface-container-high':  '#e4e7ec',
                        'surface-container-lowest':'#ffffff',
                        'surface-variant':         '#e8ebf0',
                        'success':                 '#0d9e6e',
                        'warning':                 '#d97706',
                        'error':                   '#dc2626',
                    },
                    fontFamily: {
                        'sans':    ['"DM Sans"', 'system-ui', 'sans-serif'],
                        'display': ['Fraunces', 'Georgia', 'serif'],
                        'mono':    ['"DM Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --sidebar-w: 260px;
            --brand:     #00288e;
            --brand-dim: rgba(0,40,142,0.07);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: #f4f6f9;
            color: #131416;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar       { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* ── Container ── */
        .container-custom {
            max-width: 1240px;
            margin-inline: auto;
            padding-inline: 1.5rem;
        }
        @media (min-width: 1024px) {
            .container-custom { padding-inline: 2rem; }
        }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-w);
            position: fixed;
            inset-block: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border-right: 1px solid #e8ebf0;
            z-index: 50;
            transition: transform 0.28s cubic-bezier(.4,0,.2,1);
            overflow-y: auto;
        }
        @media (max-width: 1023px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
        }

        /* ── Main offset ── */
        @media (min-width: 1024px) {
            #main-wrapper { margin-left: var(--sidebar-w); }
        }

        /* ── Nav links ── */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.6rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #52575c;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            white-space: nowrap;
        }
        .nav-link:hover {
            background: #f0f2f5;
            color: #131416;
        }
        .nav-link.active {
            background: var(--brand-dim);
            color: var(--brand);
            font-weight: 600;
        }
        .nav-link.active .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            color: var(--brand);
        }
        .nav-link .material-symbols-outlined {
            font-size: 1.2rem;
            flex-shrink: 0;
            transition: color 0.15s;
        }

        /* ── Mobile overlay ── */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(2px);
            z-index: 49;
        }
        #sidebar-overlay.open { display: block; }

        /* ── Mobile top bar ── */
        #mobile-topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 40;
            background: #fff;
            border-bottom: 1px solid #e8ebf0;
            padding: 0.75rem 1rem;
            align-items: center;
            justify-content: space-between;
        }
        @media (max-width: 1023px) {
            #mobile-topbar { display: flex; }
        }

        /* ── Page fade-in ── */
        #main-wrapper { animation: fadeUp 0.35s ease both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Status dot pulse ── */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }
        .status-dot { animation: pulse-dot 2.4s ease-in-out infinite; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>

    @stack('styles')
    @yield('styles')
</head>
<body>

{{-- ── Mobile sidebar overlay ── --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ --}}
@include('layouts.partials.owner-sidebar')

{{-- ── Mobile top bar ── --}}
<div id="mobile-topbar">
    <div class="flex items-center gap-2">
        <button onclick="openSidebar()"
                class="p-2 rounded-lg hover:bg-surface-container transition text-on-surface-variant">
            <span class="material-symbols-outlined text-xl">menu</span>
        </button>
        <span class="font-display text-secondary font-semibold text-lg tracking-tight">Eserian Homes</span>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('owner.property.create') }}"
           class="flex items-center gap-1 bg-secondary text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-secondary/90 transition">
            <span class="material-symbols-outlined text-sm">add</span>
            Add
        </a>
    </div>
</div>

{{-- ════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════ --}}
<div id="main-wrapper" class="flex flex-col min-h-screen">
    <main class="flex-1">
        @yield('content')
    </main>

    @include('layouts.partials.footer-owner')
</div>

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }
    // Close on ESC
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
</script>

@stack('scripts')
</body>
</html>
