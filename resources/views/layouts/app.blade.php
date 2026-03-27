<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'LiftED') — LiftED</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:   '#1B4332',
                        accent:    '#D97706',
                        surface:   '#F9F7F4',
                        textmain:  '#1E293B',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F9F7F4; color: #1E293B; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        body.mobile-nav-open {
            overflow: hidden;
        }
        @media (min-width: 1024px) {
            body.mobile-nav-open {
                overflow: auto;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-surface lg:flex-row lg:h-screen lg:overflow-hidden">

    {{-- Mobile overlay --}}
    <div id="sidebar-backdrop"
         class="fixed inset-0 z-30 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-200 lg:hidden"
         aria-hidden="true"></div>

    {{-- Sidebar --}}
    <x-layout.sidebar />

    {{-- Main area --}}
    <div class="flex min-h-screen min-w-0 flex-1 flex-col overflow-hidden lg:min-h-0">

        {{-- Topbar --}}
        <x-layout.topbar />

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6">

            {{-- Flash messages --}}
            @if(session('success'))
                <x-ui.alert type="success" :message="session('success')" class="mb-4" />
            @endif
            @if($errors->has('error'))
                <x-ui.alert type="error" :message="$errors->first('error')" class="mb-4" />
            @endif

            @yield('content')
        </main>

        <x-layout.footer />
    </div>

    <script>
        (function () {
            var sidebar = document.getElementById('app-sidebar');
            var backdrop = document.getElementById('sidebar-backdrop');
            var openBtn = document.getElementById('sidebar-open');
            var closeBtn = document.getElementById('sidebar-close');
            if (!sidebar || !backdrop) return;

            function isDesktop() {
                return window.matchMedia('(min-width: 1024px)').matches;
            }

            function openNav() {
                if (isDesktop()) return;
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100');
                document.body.classList.add('mobile-nav-open');
            }

            function closeNav() {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100');
                document.body.classList.remove('mobile-nav-open');
            }

            function onResize() {
                if (isDesktop()) {
                    closeNav();
                }
            }

            openBtn && openBtn.addEventListener('click', openNav);
            closeBtn && closeBtn.addEventListener('click', closeNav);
            backdrop.addEventListener('click', closeNav);
            window.addEventListener('resize', onResize);
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !isDesktop()) closeNav();
            });
            sidebar.querySelectorAll('a.sidebar-link').forEach(function (a) {
                a.addEventListener('click', function () {
                    if (!isDesktop()) closeNav();
                });
            });
            sidebar.querySelector('form[action*="logout"]')?.addEventListener('submit', function () {
                if (!isDesktop()) closeNav();
            });
        })();
    </script>
</body>
</html>
