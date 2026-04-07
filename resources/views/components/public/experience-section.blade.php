{{-- Collage superposé : halo sunset, 2 plans, micro-interactions (sans logique serveur) --}}
<div class="mb-24 lg:mb-32">
    <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-16">
        <div class="pr-0 lg:pr-8">
            <p class="mb-3 text-xs font-black uppercase tracking-[0.2em] text-orange-600">L'Ambiance</p>
            <h2 class="mb-6 text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl">Vivez des moments inoubliables</h2>
            <p class="mb-8 text-lg font-medium leading-relaxed text-stone-500">
                De l'achat de votre billet jusqu'aux dernières notes de musique, nous orchestrons chaque détail pour que votre expérience soit absolue. Rejoignez une communauté vibrante et partagez des instants uniques.
            </p>
            <a href="{{ route('public.about') }}" class="btn-secondary px-8 py-3.5 text-base rounded-full">
                Découvrir notre histoire
            </a>
        </div>

        <div class="relative mx-auto w-full max-w-sm lg:mx-0 lg:ml-auto lg:max-w-md">
            {{-- Halos ambiance (animés léger) --}}
            <div
                class="animate-experience-glow pointer-events-none absolute -left-1/4 top-0 h-56 w-56 rounded-full bg-gradient-to-br from-orange-400/50 via-amber-400/30 to-transparent blur-3xl sm:h-72 sm:w-72"
                aria-hidden="true"></div>
            <div
                class="animate-experience-glow pointer-events-none absolute -bottom-8 -right-1/4 h-64 w-64 rounded-full bg-gradient-to-tl from-rose-400/35 via-orange-500/25 to-transparent blur-3xl [animation-delay:2.5s] sm:h-80 sm:w-80"
                aria-hidden="true"></div>

            {{-- Image Unique --}}
            <div class="group relative mx-auto w-full max-w-xs sm:max-w-sm lg:max-w-md ml-auto">
                <div class="relative z-10 w-full aspect-[4/5] overflow-hidden rounded-[2rem] sm:rounded-[2.5rem] shadow-[0_28px_60px_-8px_rgba(234,88,12,0.38)] ring-1 ring-orange-200/60 transition duration-700 ease-[cubic-bezier(0.22,1,0.36,1)] will-change-transform group-hover:-translate-y-2 group-hover:shadow-[0_36px_70px_-8px_rgba(234,88,12,0.45)] motion-reduce:transition-none">
                    <img
                        src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?auto=format&fit=crop&w=1400&q=85"
                        alt="Ambiance concert"
                        class="h-full w-full object-cover transition duration-[1100ms] ease-out group-hover:scale-105 motion-reduce:group-hover:scale-100"
                        loading="lazy"
                        decoding="async"
                    />
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-t from-stone-950/55 via-orange-950/15 to-orange-400/10">
                    </div>
                    <div
                        class="pointer-events-none absolute inset-0 bg-gradient-to-br from-orange-500/20 via-transparent to-amber-300/10">
                    </div>
                    <div
                        class="absolute left-5 top-5 sm:left-6 sm:top-6 flex items-center gap-2 rounded-full border border-white/25 bg-white/90 px-3 py-1.5 sm:px-4 sm:py-2 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-orange-700 shadow-sm backdrop-blur-md">
                        <span class="relative flex h-2 w-2 sm:h-2.5 sm:w-2.5">
                            <span
                                class="motion-safe:animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-500 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full bg-orange-500"></span>
                        </span>
                        Live
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
