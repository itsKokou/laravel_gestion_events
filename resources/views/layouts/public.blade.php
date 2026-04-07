<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', "Win's Events"))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />

    <!-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="flex min-h-screen flex-col font-sans text-stone-900 antialiased bg-[#fdfcfb]">
    @include('partials.public.navbar')

    <main class="flex flex-1 flex-col pt-20 sm:pt-24" id="main-content">
        @if (session('status'))
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-6 mb-6">
                <div
                    class="glass-panel mx-auto flex max-w-2xl items-start gap-4 rounded-2xl p-5 border-orange-200/50 shadow-sm">
                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-500 text-sm font-extrabold text-white"
                        aria-hidden="true">✓</span>
                    <p class="m-0 pt-0.5 text-sm font-medium text-stone-800">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.public.footer')

    @stack('scripts')
</body>

</html>