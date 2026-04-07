@extends('layouts.scanner')

@section('title', 'Scanner · ' . config('app.name', "Win's Events"))

@section('content')
    <div class="mb-8">
        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-orange-50/80">Contrôle d’accès</p>
        <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Choisir une soirée</h1>
        <p class="mt-2 max-w-xl text-sm text-orange-50/85">
            Sélectionnez l’événement pour ouvrir le scanner QR (caméra ou saisie du code).
        </p>
    </div>

    @if ($events->isEmpty())
        <div class="rounded-2xl border border-white/20 bg-white/10 px-8 py-16 text-center shadow-inner backdrop-blur-sm">
            <p class="text-4xl" aria-hidden="true">📱</p>
            <h2 class="mt-4 text-xl font-black text-white">Aucune soirée publiée</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-orange-50/85">
                Les événements au statut « publié » apparaîtront ici pour le contrôle des entrées.
            </p>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($events as $event)
                <a href="{{ route('scanner.event', $event) }}"
                    class="group flex flex-col rounded-2xl border border-white/25 bg-white/95 p-5 text-stone-800 shadow-md transition hover:border-white/50 hover:bg-white hover:shadow-lg no-underline">
                    <div
                        class="mb-3 inline-flex w-fit items-center gap-2 rounded-lg bg-[#af5c41]/12 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-[#8f4a36] ring-1 ring-[#af5c41]/25">
                        Événement
                    </div>
                    <h2 class="text-lg font-black leading-snug text-stone-900 group-hover:text-[#af5c41]">
                        {{ $event->name }}
                    </h2>
                    <p class="mt-2 text-sm text-stone-600">
                        <span class="font-medium text-stone-800">{{ $event->venue_name }}</span>
                        @if ($event->venue_address)
                            <span class="mt-0.5 block text-xs text-stone-500">{{ $event->venue_address }}</span>
                        @endif
                    </p>
                    <p class="mt-3 text-xs text-stone-500">
                        {{ $event->starts_at->format('d/m/Y H:i') }}
                        @if ($event->ends_at)
                            — {{ $event->ends_at->format('H:i') }}
                        @endif
                    </p>

                    @php
                        $present = (int) ($event->present_count ?? 0);
                        $cap = (int) ($event->capacity ?? 0);
                        $pct = $cap > 0 ? min(100, round(($present / $cap) * 100)) : 0;
                    @endphp
                    <div class="mt-4 rounded-xl border border-stone-200/90 bg-stone-50/80 p-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold uppercase tracking-wide text-stone-500">Présents</span>
                            <span class="font-black tabular-nums text-[#af5c41]">{{ $present }}</span>
                        </div>
                        @if ($cap > 0)
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-stone-200">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#c96b4f] to-[#af5c41] transition-all"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="mt-1.5 flex justify-between text-[11px] text-stone-500">
                                <span>{{ $pct }}% remplissage</span>
                                <span>/ {{ $cap }} places</span>
                            </div>
                        @endif
                    </div>

                    <span
                        class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-[#af5c41] py-2.5 text-sm font-bold text-white shadow-sm ring-1 ring-[#8f4a36]/40 transition group-hover:bg-[#9d5038] group-hover:ring-[#8f4a36]/60">
                        Ouvrir le scanner
                    </span>
                </a>
            @endforeach
        </div>
    @endif
@endsection
