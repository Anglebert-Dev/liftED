<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'LiftED') — LiftED</title>
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
    </style>
</head>
<body class="h-full flex">

    {{-- Sidebar --}}
    <x-layout.sidebar />

    {{-- Main area --}}
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">

        {{-- Topbar --}}
        <x-layout.topbar />

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">

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

</body>
</html>
