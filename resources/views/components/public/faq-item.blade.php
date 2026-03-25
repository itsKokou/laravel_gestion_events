@props([
    'faqId',
    'question',
    'answer',
])

<div class="faq-item rounded-2xl mb-4 border border-gray-300 bg-white shadow-sm transition-all hover:border-orange-200 hover:shadow-md"
    data-faq-item>
    <button
        type="button"
        class="faq-button w-full px-5 py-5 text-left flex items-start justify-between gap-4 focus:outline-none focus:ring-4 focus:ring-orange-500/20"
        data-faq-button
        aria-expanded="false"
        aria-controls="faq-panel-{{ $faqId }}"
    >
        <span class="min-w-0 text-base font-extrabold text-stone-900">
            {{ $question }}
        </span>

        <span
            class="faq-icon mt-1 px-2 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange-50 text-orange-700 ring-1 ring-orange-200/70 transition-transform duration-300"
            data-faq-icon
            aria-hidden="true"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
            </svg>
        </span>
    </button>

    <div
        id="faq-panel-{{ $faqId }}"
        data-faq-panel
        class="overflow-hidden transition-all duration-300 ease-out will-change-[max-height,opacity,transform]"
        style="max-height:0px;opacity:0;transform:translateY(-4px);"
    >
        <div class="px-5 pb-5 pt-0 text-sm leading-relaxed text-stone-600">
            {{ $answer }}
        </div>
    </div>
</div>

