<div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">

{{-- Détail événement (inclus par public.events.show) --}}

<nav class="mb-6 mt-4" aria-label="Navigation">
    <a href="{{ route('public.events.index') }}"
        class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-bold text-stone-600 shadow-sm transition hover:bg-stone-50 hover:text-stone-900 border border-stone-200/80">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Retour
    </a>
</nav>

{{-- Premium Hero --}}
<div class="relative w-full rounded-[2.5rem] sm:rounded-[3rem] overflow-hidden bg-stone-900 shadow-2xl mb-12 sm:mb-20 group">
    <div class="aspect-[4/3] sm:aspect-[21/9] lg:aspect-[3/1] w-full">
        @if ($event->hero_image_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($event->hero_image_path) }}" alt="{{ $event->name }}"
                class="h-full w-full object-cover opacity-80 mix-blend-overlay transition-transform duration-[2s] group-hover:scale-105" />
        @else
            <div class="flex h-full items-center justify-center text-8xl opacity-80 mix-blend-overlay bg-gradient-to-br from-orange-400 to-orange-600 transition-transform duration-[2s] group-hover:scale-105">🎉</div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-900/40 to-transparent"></div>
    </div>
    
    <div class="absolute inset-0 flex flex-col justify-end p-8 sm:p-12 lg:p-16">
        @if ($event->theme)
            <span class="mb-4 inline-flex items-center rounded-full bg-orange-500/20 px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-orange-400 backdrop-blur-md self-start border border-orange-500/30">
                {{ $event->theme }}
            </span>
        @endif
        <h1 class="text-4xl font-black tracking-tighter text-white sm:text-5xl lg:text-6xl max-w-4xl leading-[1.05]">
            {{ $event->name }}
        </h1>
    </div>
</div>

<div class="lg:grid lg:grid-cols-12 lg:items-start lg:gap-10 xl:gap-16">
    {{-- Content --}}
    <div class="lg:col-span-7 space-y-12 lg:space-y-16">
        
        <div class="flex flex-col sm:flex-row gap-8 sm:gap-12 pb-10 border-b border-stone-200/60">
            <div class="flex gap-4 items-start">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-orange-50/80 text-orange-600 shadow-inner">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" /></svg>
                </span>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-1">Date</p>
                    <p class="text-lg font-black text-stone-900">{{ optional($event->starts_at)->format('d/m/Y') }}</p>
                    <p class="text-sm font-medium text-stone-500 mt-0.5">De {{ optional($event->starts_at)->format('H\hi') }} à {{ optional($event->ends_at)->format('H\hi') }}</p>
                </div>
            </div>
            <div class="flex gap-4 items-start">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-stone-100 text-stone-600 shadow-inner">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                </span>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-1">Lieu</p>
                    <p class="text-lg font-black text-stone-900 line-clamp-1">{{ $event->venue_name }}</p>
                    <p class="text-sm font-medium text-stone-500 mt-0.5 max-w-[250px]">{{ $event->venue_address }}</p>
                </div>
            </div>
        </div>

        @if ($event->description)
            <section aria-labelledby="desc-heading" class="prose prose-stone prose-lg max-w-none">
                <h2 id="desc-heading" class="text-3xl font-black tracking-tight text-stone-900 mb-6">À propos</h2>
                <div class="text-stone-600 leading-relaxed font-medium whitespace-pre-line text-[1.1rem]">
                    {{ $event->description }}
                </div>
                @if ($event->min_age)
                <p class="text-[1.1rem] font-medium text-stone-900 mt-4"><span class="font-bold text-red-600 uppercase">Important :</span> Cet événement est réservé aux personnes de plus de {{ $event->min_age }} ans.</p>
                @endif
            </section>
        @endif
        
        <section aria-labelledby="tickets-heading">
            <h2 id="tickets-heading" class="text-3xl font-black tracking-tight text-stone-900 mb-8">Tarifs & places</h2>

            @if ($event->ticketTypes->count() > 0)
                <div class="grid gap-5 sm:grid-cols-2">
                    @foreach ($event->ticketTypes as $type)
                        @php
                            $isActive = isset($activeTicketType) && $activeTicketType && $activeTicketType->id === $type->id;
                        @endphp
                        <div class="relative rounded-[2rem] p-6 transition-all duration-300 {{ $isActive ? 'bg-orange-50/70 border-2 border-orange-400 shadow-sunset scale-[1.02]' : 'border border-stone-200 bg-white hover:border-stone-300 hover:shadow-md' }}">
                            @if ($isActive)
                                <span class="absolute -top-3 right-6 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white shadow-md">En cours</span>
                            @endif
                            <p class="text-xl font-black text-stone-900 mb-1">{{ $type->name }}</p>
                            @if ($type->sales_starts_at && $type->sales_ends_at)
                                <p class="text-[11px] font-black uppercase tracking-wider text-stone-400 mb-4">
                                    {{ $type->sales_starts_at->format('d/m') }} → {{ $type->sales_ends_at->format('d/m') }}
                                </p>
                            @endif
                            <div class="mt-4 flex items-end">
                                <p class="text-3xl font-black text-orange-600 leading-none">
                                    {{ number_format($type->price_cents, 0, ',', ' ') }}
                                </p>
                                <span class="text-sm font-bold text-stone-500 ml-1 mb-1">{{ $type->currency }}</span>
                            </div>
                            @if ($type->quantity_limit)
                                <p class="mt-4 inline-flex items-center rounded-[0.75rem] bg-stone-100 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-stone-500 pt-1.5 pb-1">
                                    Quota : {{ $type->quantity_limit - $type->sold_tickets }} places
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-stone-300 bg-stone-50/50 p-10 text-center">
                    <p class="text-lg font-medium text-stone-500">Tarifs communiqués prochainement.</p>
                </div>
            @endif
        </section>

        @if ($event->addons->count() > 0)
            <section aria-labelledby="addons-heading">
                <h2 id="addons-heading" class="text-3xl font-black tracking-tight text-stone-900 mb-6">Options & Extras</h2>
                <div class="rounded-[2rem] border border-stone-200/80 bg-white p-2 shadow-sm">
                    <ul class="divide-y divide-stone-100">
                        @foreach ($event->addons as $addon)
                            <li class="flex items-center justify-between p-5 px-6 hover:bg-stone-50 transition-colors rounded-2xl">
                                <span class="text-lg font-bold text-stone-900">{{ $addon->name }}</span>
                                <span class="text-lg font-black text-orange-600">
                                    + {{ number_format($addon->price_cents, 0, ',', ' ') }} <span class="text-sm font-bold text-stone-500">{{ $addon->currency }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif
    </div>

    {{-- Floating Purchase Card (Desktop) --}}
    <div class="mt-12 flex flex-col lg:col-span-5 lg:mt-0 relative z-20">
        <div class="lg:sticky lg:top-36">
            <div class="card-premium overflow-hidden bg-white p-8 lg:p-10 transform lg:-translate-y-48 lg:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border-stone-100 relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-orange-400 to-orange-600"></div>
                
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-400 mb-3">Réservation</p>
                
                @if (isset($activeTicketType) && $activeTicketType)
                    <div class="mb-8 relative">
                        <p class="text-5xl font-black text-stone-900 tracking-tight flex items-baseline gap-1">
                            {{ number_format($activeTicketType->price_cents, 0, ',', ' ') }}
                            <span class="text-xl font-bold text-orange-400">{{ $activeTicketType->currency }}</span>
                        </p>
                        <p class="mt-2 flex items-center gap-2 text-sm font-black uppercase tracking-wider text-orange-600">
                            <span class="relative flex h-2.5 w-2.5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                            </span>
                            {{ $activeTicketType->name }} en vente
                        </p>
                    </div>
                @elseif ($minPrice !== null)
                    <div class="mb-8">
                        <p class="text-xs font-black uppercase tracking-widest text-stone-400 mb-2">À partir de</p>
                        <p class="text-5xl font-black text-stone-900 tracking-tight flex items-baseline gap-1">
                            {{ number_format($minPrice, 0, ',', ' ') }}
                            <span class="text-xl font-bold text-stone-400">FCFA</span>
                        </p>
                    </div>
                @else
                    <div class="mb-8 py-4">
                        <p class="text-lg font-bold text-stone-600">Tarifs communiqués à la réservation</p>
                    </div>
                @endif

                <div class="mb-8 flex items-center justify-between rounded-2xl bg-stone-50 p-5 border border-stone-100">
                    <span class="text-sm font-bold text-stone-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        Disponibilité
                    </span>
                    <span class="inline-flex items-center rounded-xl bg-orange-100/50 px-3 py-1.5 text-[11px] font-black uppercase tracking-wider text-orange-700">
                        @if ($remainingSeats === 0)
                            Complet
                        @else
                            {{ $remainingSeats }} place(s)
                        @endif
                    </span>
                </div>

                <a href="{{ route('public.reservations.create', $event) }}"
                    class="btn-primary flex w-full justify-center rounded-[1.5rem] py-4 text-lg shadow-sunset mt-4">
                    Réserver ma place
                </a>
                
                <p class="text-center text-[11px] font-black uppercase tracking-widest text-stone-400 mt-6 flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    Paiement 100% sécurisé
                </p>
            </div>
        </div>
    </div>
</div>

{{-- CTA mobile fixe --}}
<div class="fixed bottom-0 left-0 right-0 z-50 border-t border-stone-200/80 bg-white/95 p-4 pb-[max(1rem,env(safe-area-inset-bottom))] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] backdrop-blur-xl lg:hidden">
    <a href="{{ route('public.reservations.create', $event) }}"
        class="btn-primary flex w-full items-center justify-center rounded-2xl py-4 text-base shadow-sunset">
        Réserver maintenant
    </a>
</div>
<div class="h-28 shrink-0 lg:hidden" aria-hidden="true"></div>

</div>
