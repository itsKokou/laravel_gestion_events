@php
$authUser = auth()->user();
if ($authUser) {
    $authUser->loadMissing('roles');
}
$navDash = request()->routeIs('admin.dashboard');
$navEvents = request()->routeIs('admin.events.*');
$navOrders = request()->routeIs('admin.orders.*');
$navTickets = request()->routeIs('admin.tickets.*');
$navControllers = request()->routeIs('admin.controllers.*');
$navScanner = request()->routeIs('scanner.*');
$navActive = 'bg-white/25 text-white ring-1 ring-white/35 shadow-sm';
$navIdle = 'text-orange-50/90 hover:bg-white/15 hover:text-white';
@endphp

<aside id="admin-sidebar-panel"
    class="fixed inset-y-0 left-0 z-40 flex w-[min(100vw-3rem,280px)] max-w-[280px] -translate-x-full flex-col border-r border-[#A86450]/55 bg-[#af5c41] shadow-sm transition-transform duration-200 peer-checked:translate-x-0 lg:w-[250px] lg:max-w-none lg:translate-x-0"
    role="navigation" aria-label="Administration">
    <div class="flex shrink-0 items-center gap-3 border-b border-[#A86450]/65 px-4 py-5">
        <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-3 text-white no-underline">
            @if (file_exists(public_path('logo.png')))
                <img src="{{ asset('logo.png') }}" alt="" class="h-9 w-auto shrink-0" width="100" height="36" />
            @endif
            <span class="flex min-w-0 flex-col leading-tight">
                <span class="truncate text-sm font-extrabold tracking-tight">{{ config('app.name', "Win's Events") }}</span>
                <span class="text-[10px] font-semibold uppercase tracking-wider text-orange-50/90">Admin</span>
            </span>
        </a>
    </div>

    <nav class="flex flex-1 flex-col gap-0.5 overflow-y-auto overscroll-contain px-3 py-4" aria-label="Menu principal">
        <p class="mb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-orange-50/75">Vue d’ensemble</p>
        <a href="{{ route('admin.dashboard') }}"
            class="{{ $navDash ? $navActive : $navIdle }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 shrink-0 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
            </svg>

            Dashboard
        </a>

        <p class="mb-1 mt-4 px-3 text-[10px] font-bold uppercase tracking-wider text-orange-50/75">Catalogue</p>
        <a href="{{ route('admin.events.index') }}"
            class="{{ $navEvents ? $navActive : $navIdle }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 shrink-0 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
            </svg>

            Événements
        </a>

        <p class="mb-1 mt-4 px-3 text-[10px] font-bold uppercase tracking-wider text-orange-50/75">Ventes</p>
        <a href="{{ route('admin.orders.index') }}"
            class="{{ $navOrders ? $navActive : $navIdle }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 shrink-0 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            Réservations
        </a>
        <a href="{{ route('admin.tickets.index') }}"
            class="{{ $navTickets ? $navActive : $navIdle }} flex items-center gap-3 mt-1 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
            </svg>

            Tickets
        </a>

        <!-- <p class="mb-1 mt-4 px-3 text-[10px] font-bold uppercase tracking-wider text-orange-50/75">Observabilité</p>
        <a href="{{ route('admin.dashboard') }}#journal-audit"
            class="{{ $navIdle }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 shrink-0 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
            </svg>

            Journal d’activité
        </a> -->

        <p class="mb-1 mt-4 px-3 text-[10px] font-bold uppercase tracking-wider text-orange-50/75">Équipe</p>
        <a href="{{ route('admin.controllers.index') }}"
            class="{{ $navControllers ? $navActive : $navIdle }} flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline transition">
            <svg class="size-6 shrink-0 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                    d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>

            Contrôleurs
        </a>
        @if ($authUser && $authUser->hasAnyRole(['admin', 'controller']))
            <a href="{{ route('scanner.home') }}"
                class="{{ $navScanner ? $navActive : $navIdle }} flex items-center mt-1 gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold no-underline transition">
                <svg class="size-6 shrink-0 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4V4Zm10 10h6v6h-6v-6Zm0-10h6v6h-6V4Zm-4 10h.01v.01H10V14Zm0 4h.01v.01H10V18Zm-3 2h.01v.01H7V20Zm0-4h.01v.01H7V16Zm-3 2h.01v.01H4V18Zm0-4h.01v.01H4V14Z"/>
                    <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M7 7h.01v.01H7V7Zm10 10h.01v.01H17V17Z"/>
                </svg>

                Scanner
            </a>
        @endif

        <div class="my-3 border-t border-[#d9846b]"></div>

        <a href="{{ route('public.events.index') }}" target="_blank" rel="noopener noreferrer"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-semibold text-orange-50/90 no-underline transition hover:bg-white/15 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 shrink-0 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
            </svg>

            Site public
            <span class="ml-auto text-xs opacity-70" aria-hidden="true">↗</span>
        </a>
    </nav>

    <div class="shrink-0 border-t border-[#A86450]/65 p-3">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit"
                class="flex w-full items-center gap-3 rounded-xl border border-red-300/40 bg-red-500/20 px-3 py-2.5 text-left text-sm font-bold text-red-50 shadow-sm transition hover:bg-red-500/35 hover:border-red-200/70 hover:text-white">
                <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
                </svg>

                Déconnexion
            </button>
        </form>
    </div>
</aside>
