@props(['landing' => true])

@if ($landing)
<section class="relative w-full overflow-hidden bg-stone-900 mb-16 sm:mb-24 -mt-4 sm:-mt-8">
    <div class="relative min-h-[min(90vh,750px)] flex items-center">
        @php
            $heroImage = 'https://images.unsplash.com/photo-1540039155073-9bb7d5d3f0d0?auto=format&fit=crop&w=2000&q=80';
        @endphp
        <div class="absolute inset-0">
            <img src="{{ $heroImage }}" alt="Ambiance événement" class="h-full w-full object-cover opacity-80 mix-blend-overlay" loading="eager" />
            <div class="absolute inset-0 bg-gradient-to-r from-stone-950/95 via-stone-900/80 to-stone-900/40"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-transparent to-transparent"></div>
        </div>

        <div class="relative w-full flex flex-col items-center justify-center px-8 py-24 sm:px-12 lg:px-16 z-10 text-center">
            <div class="max-w-4xl flex flex-col items-center">
                <div class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-1.5 backdrop-blur-md mb-8">
                    <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-400">Expériences Premium</span>
                </div>
                
                <h1 class="text-5xl font-black leading-[1.05] tracking-tighter text-white sm:text-6xl md:text-7xl">
                    Découvrez les événements qui <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">marqueront votre année</span>
                </h1>
                
                <p class="mt-8 max-w-xl text-lg font-medium text-stone-300 sm:text-xl leading-relaxed">
                    Concerts, soirées, expériences uniques — réservez en quelques secondes votre accès aux meilleurs moments.
                </p>
                
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    <a href="#evenements" class="btn-primary px-8 py-4 text-lg">
                        Explorer les événements
                        <svg class="h-5 w-5 inline ml-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </a>
                    <a href="{{ route('public.about') }}" class="btn-glass px-8 py-4 text-lg rounded-[2rem]">
                        En savoir plus
                    </a>
                </div>
                
                <!-- Social Proof -->
                <div class="mt-16 flex items-center justify-center gap-6 sm:gap-12 border-t border-white/10 pt-8 w-full max-w-3xl">
                    <div>
                        <p class="text-2xl sm:text-3xl font-black text-white">+100</p>
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-stone-400 mt-1">Événements organisés</p>
                    </div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-black text-white">+5000</p>
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-stone-400 mt-1">Participants</p>
                    </div>
                    <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
                    <div class="hidden sm:block">
                        <p class="text-2xl sm:text-3xl font-black text-white">98%</p>
                        <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-stone-400 mt-1">De satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
