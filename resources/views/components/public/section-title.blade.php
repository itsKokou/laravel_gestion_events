<div class="mb-12 text-center flex flex-col items-center">
    @if (!empty($eyebrow))
        <p class="mb-4 text-xs font-black uppercase tracking-[0.25em] text-orange-600/90">{{ $eyebrow }}</p>
    @endif
    <h2 class="text-4xl font-black tracking-tighter text-stone-900 sm:text-5xl">{{ $title }}</h2>
    @if (!empty($subtitle))
        <p class="mt-4 max-w-2xl text-lg font-medium text-stone-500 sm:text-xl">{{ $subtitle }}</p>
    @endif
</div>
