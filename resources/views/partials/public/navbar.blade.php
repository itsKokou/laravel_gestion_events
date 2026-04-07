@php
$authUser = auth()->user();
if ($authUser) {
    $authUser->loadMissing('roles');
}
$contactMail = config('mail.from.address', 'contact@example.com');
$userInitial = $authUser
    ? mb_strtoupper(mb_substr(trim($authUser->name ?: '?'), 0, 1))
    : '';
@endphp

<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 glass-panel border-b-white/50">
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-8"
        aria-label="Navigation principale">
        <a href="{{ route('home') }}"
            class="flex min-w-0 shrink-0 items-center gap-3 text-stone-900 no-underline group">
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

        <div class="hidden items-center justify-center gap-1 md:flex md:flex-1 md:px-8">
            <a href="{{ route('home') }}"
                class="rounded-full px-5 py-2.5 text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'bg-orange-50 text-orange-600' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100' }}">
                Accueil
            </a>
            <a href="{{ route('public.events.index') }}"
                class="rounded-full px-5 py-2.5 text-sm font-semibold transition-all {{ request()->routeIs('public.events.*') ? 'bg-orange-50 text-orange-600' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100' }}">
                Événements
            </a>
            <a href="{{ route('public.about') }}"
                class="rounded-full px-5 py-2.5 text-sm font-semibold transition-all {{ request()->routeIs('public.about') ? 'bg-orange-50 text-orange-600' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100' }}">
                À propos 
            </a>
            <a href="{{ route('public.contact') }}"
                class="rounded-full px-5 py-2.5 text-sm font-semibold transition-all {{ request()->routeIs('public.contact') ? 'bg-orange-50 text-orange-600' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100' }}">
                Contact
            </a>
        </div>

        <div class="hidden items-center gap-3 md:flex">
            @auth
                {{-- Bloc session : lisible sans être criard (contraste avec l’invité) --}}
                <div
                    class="flex max-w-full items-center gap-2 rounded-full border border-orange-200/80 bg-gradient-to-r from-orange-50/95 to-amber-50/40 py-1 pl-1 pr-2 shadow-sm ring-1 ring-orange-100/60 backdrop-blur-sm sm:gap-3 sm:pr-3">
                    <span
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-sm font-bold text-white shadow-inner shadow-orange-900/20"
                        aria-hidden="true">{{ $userInitial }}</span>
                    <div class="hidden min-w-0 flex-col leading-tight sm:flex">
                        <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-orange-700/90">Connecté</span>
                        <span class="max-w-[9rem] truncate text-sm font-semibold text-stone-800 lg:max-w-[11rem]"
                            title="{{ $authUser->name }}">{{ $authUser->name }}</span>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    @if ($authUser->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}"
                            class="rounded-full px-5 py-2.5 text-sm font-semibold transition-all text-stone-600 hover:text-stone-900 hover:bg-stone-100">Dashboard</a>
                    @endif
                    @if ($authUser->hasAnyRole(['admin', 'controller']))
                        <a href="{{ route('scanner.home') }}"
                            class="rounded-full px-5 py-2.5 text-sm font-semibold transition-all text-stone-600 hover:text-stone-900 hover:bg-stone-100">Scanner</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="m-0 inline">
                        @csrf
                        <button type="submit"
                            class="cursor-pointer rounded-full border border-red-600 bg-red-600 text-white px-3.5 py-2 text-sm font-semibold shadow-sm transition hover:border-red-200 hover:bg-red-50/80 hover:text-red-700">
                            Déconnexion
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="text-sm font-semibold border border-orange-600 rounded-2xl bg-orange-600 text-white hover:text-orange-600 hover:bg-white transition-colors px-4 py-1.5 shadow-sm">Connexion</a>
            @endauth
        </div>

        {{-- Menu mobile (hamburger) --}}
        <details class="relative md:hidden group">
            <summary
                class="flex list-none cursor-pointer items-center justify-center rounded-2xl border border-stone-200 bg-white/50 p-3 text-stone-800 shadow-sm backdrop-blur-md transition-all hover:bg-white [&::-webkit-details-marker]:hidden"
                aria-label="Ouvrir le menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </summary>
            <div
                class="absolute right-0 z-[60] mt-3 w-[min(100vw-2rem,20rem)] rounded-3xl border border-white/60 bg-white/95 p-3 shadow-premium backdrop-blur-xl">
                <div class="space-y-1">
                    <a href="{{ route('home') }}"
                        class="block rounded-2xl px-4 py-3 text-base font-semibold text-stone-800 no-underline hover:bg-orange-50 hover:text-orange-600">Accueil</a>
                    <a href="{{ route('public.events.index') }}"
                        class="block rounded-2xl px-4 py-3 text-base font-semibold text-stone-800 no-underline hover:bg-orange-50 hover:text-orange-600">Événements</a>
                    <a href="{{ route('public.about') }}"
                        class="block rounded-2xl px-4 py-3 text-base font-semibold text-stone-800 no-underline hover:bg-orange-50 hover:text-orange-600">À
                        propos</a>
                    <a href="{{ route('public.contact') }}"
                        class="block rounded-2xl px-4 py-3 text-base font-semibold text-stone-800 no-underline hover:bg-orange-50 hover:text-orange-600">Contact</a>
                </div>
                <div class="my-3 border-t border-stone-100"></div>
                @auth
                    <div
                        class="mb-3 flex items-center gap-3 rounded-2xl border border-orange-200/80 bg-gradient-to-r from-orange-50/90 to-amber-50/50 px-3 py-2.5 shadow-sm ring-1 ring-orange-100/50">
                        <span
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-base font-bold text-white shadow-inner">{{ $userInitial }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-orange-700/90">Session active</p>
                            <p class="truncate text-sm font-semibold text-stone-800">{{ $authUser->name }}</p>
                        </div>
                    </div>
                    <div class="space-y-1">
                        @if ($authUser->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}"
                                class="block rounded-2xl px-4 py-3 text-sm font-semibold text-stone-800 no-underline hover:bg-orange-50 hover:text-orange-800">Dashboard</a>
                        @endif
                        @if ($authUser->hasAnyRole(['admin', 'controller']))
                            <a href="{{ route('scanner.home') }}"
                                class="block rounded-2xl px-4 py-3 text-sm font-semibold text-stone-800 no-underline hover:bg-orange-50 hover:text-orange-800">Scanner</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-2xl border border-transparent px-4 py-3 text-left text-sm font-semibold text-stone-600 transition hover:border-red-100 hover:bg-red-50/90 hover:text-red-700">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-2 pt-2">
                        <a href="{{ route('login') }}" class="w-full justify-center py-3 border border-orange-600 rounded-2xl bg-orange-600 text-white hover:text-orange-600 hover:bg-white transition-colors">Connexion</a>
                    </div>
                @endauth
            </div>
        </details>
    </nav>
</header>