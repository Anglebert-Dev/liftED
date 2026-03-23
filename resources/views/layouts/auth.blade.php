<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Login') — LiftED</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#1B4332', accent: '#D97706', surface: '#F9F7F4' } } }
        }
    </script>
    <style>body { background-color: #F9F7F4; }</style>
</head>
<body class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary mb-4">
                <span class="text-white text-2xl font-bold">L</span>
            </div>
            <h1 class="text-2xl font-bold text-textmain">LiftED</h1>
            <p class="text-slate-500 text-sm mt-1">Education Management Platform</p>
        </div>
        @yield('content')
    </div>
</body>
</html>
