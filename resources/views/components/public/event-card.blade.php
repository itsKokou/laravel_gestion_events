@php
    use Illuminate\Support\Facades\Storage;
    $variant = $variant ?? 'default';
    $ctaLabel = $ctaLabel ?? 'Réserver';
    $activeTypes = $event->relationLoaded('ticketTypes')
        ? $event->ticketTypes->where('is_active', true)
        : collect();
    $minPrice = $activeTypes->isNotEmpty() ? $activeTypes->min('price_cents') : null;
    $isFeatured = $variant === 'featured';
@endphp

<a href="{{ route('public.events.show', $event) }}"
    class="card-premium group relative flex h-full flex-col {{ $isFeatured ? 'ring-2 ring-orange-200 shadow-sunset' : '' }}">
    
    <div class="relative w-full aspect-[4/3] overflow-hidden bg-gradient-to-br from-orange-200 to-amber-100 rounded-t-3xl border-b border-stone-100">
        @if ($event->hero_image_path)
            <img src="{{ Storage::url($event->hero_image_path) }}" alt="{{ $event->name }}"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" />
        @else
            <div class="flex h-full items-center justify-center text-6xl opacity-80 transition-transform duration-700 group-hover:scale-110">🎟️</div>
        @endif
        
        <!-- Sunset Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-stone-900/80 via-stone-900/30 to-transparent transition-opacity duration-300"></div>
        
        <!-- Hover Glass CTA Overlay -->
        <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-all duration-300 group-hover:opacity-100 group-hover:bg-stone-900/20 backdrop-blur-[2px]">
             <span class="btn-primary rounded-full px-8 py-3 text-sm tracking-wide shadow-sunset">
                 {{ $ctaLabel }}
             </span>
        </div>

        @if ($isFeatured)
            <span
                class="absolute left-4 top-4 rounded-full bg-gradient-to-r from-orange-400 to-red-500 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-white shadow-md z-10">
                🔥 Populaire
            </span>
        @elseif ($event->theme)
            <span
                class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-orange-600 shadow-sm backdrop-blur-md z-10">
                {{ $event->theme }}
            </span>
        @endif
        
        <!-- Date Badge Floating -->
        <div class="absolute right-4 top-4 flex flex-col items-center justify-center rounded-2xl bg-white/95 px-3 py-2 text-center shadow-lg backdrop-blur-md">
            @php
                $rawMonth = $event->starts_at
                    ? $event->starts_at->locale('fr')->translatedFormat('M')
                    : '';
            @endphp
            <span class="text-[10px] font-black uppercase text-orange-600 leading-none tracking-wider">{{ $rawMonth }}</span>
            <span class="text-xl font-black text-stone-900 leading-none mt-1">{{ optional($event->starts_at)->format('d') }}</span>
        </div>
    </div>

    <div class="flex flex-1 flex-col p-6 relative bg-white rounded-b-3xl">
        <h3 class="line-clamp-2 text-xl font-black leading-snug text-stone-900 transition-colors group-hover:text-orange-600">
            {{ $event->name }}
        </h3>

        <div class="mt-4 flex flex-col gap-3 text-sm font-medium text-stone-500">
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-50/80 text-orange-600 shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                <span>{{ optional($event->starts_at)->format('H\hi') }}</span>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-50/80 text-orange-600 shrink-0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </span>
                <span class="line-clamp-1 break-all">{{ $event->venue_name }}</span>
            </div>
        </div>

        <div class="mt-auto flex items-center justify-between gap-3 border-t border-stone-100 pt-5 mt-6">
            <div class="flex flex-col">
                <span class="text-[10px] font-black uppercase tracking-widest text-stone-400 mb-0.5">À partir de</span>
                @if ($minPrice !== null)
                    <span class="text-lg font-black text-stone-900 leading-none">{{ number_format($minPrice, 0, ',', ' ') }} <span class="text-sm font-bold text-stone-500">FCFA</span></span>
                @else
                    <span class="text-base font-bold text-stone-900 leading-none">Tarifs sur place</span>
                @endif
            </div>
            
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-stone-50 text-stone-400 transition-colors group-hover:bg-orange-500 group-hover:text-white shadow-sm group-hover:shadow-sunset">
                <svg class="h-5 w-5 ml-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>
</a>
