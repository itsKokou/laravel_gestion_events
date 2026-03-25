{{-- Contenu listing / landing (inclus par public.events.index) — layout : layouts.public --}}

@if ($landing)
    <x-public.hero />

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">
@else
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-16 pt-6">
@endif

    @if ($landing)
        <div class="py-10"></div>
    @endif

    @if (!$landing)
        <div class="mb-12 text-center mt-8 flex flex-col items-center">
            @if ($q !== '')
                <p class="mb-3 text-xs font-black uppercase tracking-[0.2em] text-orange-600">Résultats</p>
                <h1 class="text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl lg:text-6xl">Trouvez &laquo;&nbsp;{{ $q }}&nbsp;&raquo;</h1>
                <p class="mt-4 text-lg font-medium text-stone-500">Affinez votre recherche ou parcourez notre catalogue.</p>
            @else
                <p class="mb-3 text-xs font-black uppercase tracking-[0.2em] text-orange-600">Catalogue</p>
                <h1 class="text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl lg:text-6xl">Événements à venir</h1>
                <p class="mt-4 max-w-2xl text-lg font-medium text-stone-500 sm:text-xl">Parcourez le meilleur de l'événementiel.</p>
            @endif
        </div>
    @endif

    @if ($events->count() > 0 || $q)
        <div class="mb-12">
            <form method="GET" action="{{ route('public.events.index') }}"
                class="flex flex-col gap-4 rounded-[2rem] border border-stone-200/50 bg-white/80 p-3 shadow-glass backdrop-blur-md md:flex-row md:items-center">
                <div class="relative flex-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5">
                        <svg class="h-5 w-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input id="q" name="q" value="{{ $q }}" type="search" placeholder="Nom de l'événement, ville, ou ambiance..."
                        class="block w-full border-0 bg-transparent py-3 pl-12 pr-4 text-stone-900 font-medium placeholder:text-stone-400 focus:ring-0 sm:text-lg outline-none" />
                </div>
                <div class="flex shrink-0 gap-2 p-1 w-full md:w-auto">
                    <button type="submit" class="btn-primary flex-1 md:flex-none justify-center rounded-[1.5rem] py-3.5 px-8 text-base">
                        Explorer
                    </button>
                    @if ($q)
                        <a href="{{ route('public.events.index') }}" class="btn-secondary rounded-[1.5rem] py-3.5 px-6 text-base inline-flex items-center justify-center">
                            Effacer
                        </a>
                    @endif
                </div>
            </form>
        </div>
    @endif

    @if ($landing && $featuredEvents->isNotEmpty())
        @include('components.public.section-title', [
            'eyebrow' => 'Sélection',
            'title' => 'À la une',
            'subtitle' => 'Les incontournables du moment. Réservez avant qu\'il ne soit trop tard.',
        ])
        <div @if ($allOnFeaturedOnly) id="evenements" @endif
            class="mb-24 grid scroll-mt-32 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($featuredEvents as $event)
                @include('components.public.event-card', ['event' => $event, 'variant' => 'featured'])
            @endforeach
        </div>
    @endif

    @if ($landing)
        <x-public.why-choose-us />
        <x-public.experience-section />
    @endif

    @if ($landing && $restEvents->isNotEmpty())
        <div class="mt-16">
            @include('components.public.section-title', [
                'eyebrow' => 'Agenda',
                'title' => 'Tous les événements',
                'subtitle' => 'Ne manquez pas ces événements très demandés.',
            ])
        </div>
    @endif

    @if ($events->count() > 0)
        @php
            $gridItems = $landing ? $restEvents : $items;
        @endphp

        @if ($gridItems->isNotEmpty())
            @php
                $idEvenementsGrid = !$landing || ($landing && !$allOnFeaturedOnly);
            @endphp
            <div @if ($idEvenementsGrid) id="evenements" @endif
                class="grid scroll-mt-32 gap-8 sm:grid-cols-2 lg:grid-cols-3 mb-16">
                @foreach ($gridItems as $event)
                    @include('components.public.event-card', ['event' => $event, 'variant' => 'default'])
                @endforeach
            </div>
        @endif

        @if ($events->hasPages())
            <div class="mt-16 flex justify-center w-full">
                <div class="bg-white px-6 py-4 rounded-3xl shadow-glass border border-stone-100">
                    {{ $events->links() }}
                </div>
            </div>
        @endif
    @else
        <div id="evenements" class="scroll-mt-32 py-12">
            <div class="flex flex-col items-center justify-center rounded-[3rem] border border-stone-200/60 bg-white px-6 py-24 text-center shadow-glass sm:px-16 overflow-hidden relative">
                <div class="absolute inset-0 bg-stone-50/50 backdrop-blur-[1px]"></div>
                <div class="z-10 mb-8 flex h-24 w-24 items-center justify-center rounded-3xl bg-orange-50/80 text-4xl shadow-inner rotate-3">
                    <svg class="w-10 h-10 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                </div>
                <h2 class="z-10 text-3xl font-black tracking-tight text-stone-900">
                    @if ($q)
                        On ne trouve pas ça !
                    @else
                        La scène se prépare.
                    @endif
                </h2>
                <p class="z-10 mt-4 max-w-md text-lg font-medium text-stone-500">
                    @if ($q)
                        Aucun événement ne correspond à « {{ $q }} ». Essayez d'autres mots-clés ou utilisez des termes plus génériques.
                    @else
                        Nous préparons en coulisses nos prochaines pépites. Revenez très bientôt pour découvrir la suite.
                    @endif
                </p>
                @if ($q)
                    <a href="{{ route('public.events.index') }}" class="z-10 btn-primary mt-10 rounded-full px-8 py-3 text-base">
                        Voir la sélection complète
                    </a>
                @endif
            </div>
        </div>
    @endif

    @if ($landing)
        <x-public.cta />
    @endif

</div>
