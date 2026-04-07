<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Scanner · ' . config('app.name', "Win's Events"))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

@php
    $authUser = auth()->user();
@endphp

<body
    class="scanner-theme min-h-screen bg-[#af5c41] font-sans text-orange-50 antialiased [--scanner-border:#A86450] [--scanner-deep:#8f4a36]">
    <header
        class="fixed top-0 left-0 right-0 z-50 transition-all bg-white duration-300 glass-panel border-b-gray-200 backdrop-blur">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
           
            <a href="{{ route('scanner.home') }}"
                class="flex min-w-0 shrink-0 items-center gap-3 cursor-pointer text-stone-900 no-underline group">
                @if (file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}" alt=""
                        class="h-9 w-auto sm:h-10 transition-transform group-hover:scale-105" width="120" height="40" />
                @else
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white font-bold text-xl shadow-sunset transition-transform group-hover:scale-105">
                        W</div>
                @endif
                <span class="flex min-w-0 flex-col leading-none">
                    <span
                        class="truncate text-xl font-black tracking-tight group-hover:text-orange-600 transition-colors">{{ config('app.name', "Win's Events") }}</span>
                    <span
                        class="hidden text-[10px] font-bold uppercase tracking-widest text-orange-500/80 sm:block mt-1">Experiences</span>
                </span>
            </a>
            <nav class="flex flex-wrap items-center justify-end gap-2 text-sm font-semibold">
                @if ($authUser?->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                        class="rounded-full px-5 py-2.5 text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'bg-orange-50 text-orange-600' : 'text-stone-700 hover:text-stone-900 hover:bg-stone-100' }}">
                        Admin
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="m-0 inline">
                    @csrf
                    <button type="submit"
                        class="cursor-pointer rounded-full border border-red-600 bg-red-600 text-white px-3.5 py-2 text-sm font-semibold shadow-sm transition hover:border-red-200 hover:bg-red-50/80 hover:text-red-700">
                        Déconnexion
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 pt-24 sm:px-6">
        @if (session('status'))
            <div
                class="mb-6 flex items-start gap-3 rounded-2xl border border-white/25 bg-white/15 p-4 text-white shadow-sm backdrop-blur-sm">
                <span
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-sm font-extrabold text-white"
                    aria-hidden="true">✓</span>
                <p class="m-0 pt-0.5 text-sm font-semibold">{{ session('status') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
