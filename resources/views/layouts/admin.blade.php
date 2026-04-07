<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin · ' . config('app.name', "Win's Events"))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    {{-- peer doit précéder la sidebar pour peer-checked:* --}}
    <input type="checkbox" id="admin-sidebar-toggle" class="peer sr-only" />

    @include('partials.admin.sidebar')

    <label for="admin-sidebar-toggle"
        class="pointer-events-none fixed inset-0 z-30 bg-slate-900/40 opacity-0 transition peer-checked:pointer-events-auto peer-checked:opacity-100 lg:hidden"
        aria-hidden="true"></label>

    <div class="min-h-screen lg:pl-[250px]">
        @include('partials.admin.topbar')

        <main class="mx-auto max-w-7xl overflow-x-hidden px-4 pb-6 pt-20 sm:px-6 lg:px-8">
            @if (session('status'))
                <div
                    class="card mb-6 flex items-start gap-3 border border-orange-200/60 bg-gradient-to-r from-orange-50/90 to-amber-50/50 p-4">
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-600 text-sm font-extrabold text-white"
                        aria-hidden="true">✓</span>
                    <p class="m-0 pt-0.5 text-sm font-semibold text-slate-900">{{ session('status') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
