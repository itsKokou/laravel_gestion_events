@php
$authUser = auth()->user();
if ($authUser) {
    $authUser->loadMissing('roles');
}
@endphp

<div
    class="fixed top-0 left-0 right-0 z-30 flex h-14 items-center justify-between gap-4 border-b border-slate-200 bg-white/90 px-4 backdrop-blur supports-[backdrop-filter]:bg-white/75 sm:px-6 lg:left-[250px] lg:px-8">
    <div class="flex min-w-0 flex-1 items-center gap-3">
        <label for="admin-sidebar-toggle"
            class="inline-flex cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-700 shadow-sm lg:hidden"
            aria-controls="admin-sidebar-panel" role="button">
            <span class="sr-only">Ouvrir le menu</span>
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </label>
        <h1 class="truncate text-base font-bold text-slate-900 sm:text-lg">
            @yield('title', 'Admin')
        </h1>
    </div>
    <div class="flex shrink-0 items-center gap-2">
        @if ($authUser)
                    @php
            $roleLabel = $authUser->hasRole('admin') ? 'Administrateur' : ($authUser->hasRole('controller') ? 'Contrôleur' : 'Équipe');
            $initial = strtoupper(mb_substr((string) $authUser->name, 0, 1));
                    @endphp
                    <!-- <div
                        class="hidden h-10 items-center gap-2 rounded-full border border-slate-200 bg-white px-2 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md sm:flex">
                        <span
                            class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-orange-500 text-xs font-black text-white">
                            {{ $initial }}
                        </span>
                        <div class="min-w-0 pr-1">
                            <p class="max-w-[9rem] truncate text-xs font-bold text-slate-900 leading-tight">{{ $authUser->name }}</p>
                            <p class="text-[10px] font-semibold leading-tight text-slate-500">
                                {{ $roleLabel }}
                            </p>
                        </div>
                    </div> -->
                    <div
                        class="flex my-2 max-w-full items-center gap-2 rounded-full border border-orange-200/80 bg-gradient-to-r from-orange-50/95 to-amber-50/40 py-1 pl-1 pr-2 shadow-sm ring-1 ring-orange-100/60 backdrop-blur-sm sm:gap-3 sm:pr-3 hover:-translate-y-0.5 hover:shadow-md sm:flex">
                        <span
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-sm font-bold text-white shadow-inner shadow-orange-900/20"
                            aria-hidden="true">{{ $initial }}</span>
                        <div class="hidden min-w-0 flex-col leading-tight sm:flex">
                            <span class="max-w-[9rem] truncate text-sm font-semibold text-stone-800 lg:max-w-[11rem]"
                            title="{{ $authUser->name }}">{{ $authUser->name }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-orange-700/90">{{ $roleLabel }}</span>
                        </div>
                    </div>
        @endif

        @hasSection('topbar_actions')
            @yield('topbar_actions')
        @endif
    </div>
</div>
