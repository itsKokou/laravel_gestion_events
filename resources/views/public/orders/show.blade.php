@extends('layouts.public')

@section('title', 'Réservation ' . $order->order_number . " · Win's Events")

@section('content')
    @php
$statusConfig = [
    'pending_payment' => [
        'bg' => 'rgba(251, 191, 36, 0.1)',
        'text' => '#d97706',
        'label' => 'En attente de paiement',
        'icon' => '⏳',
    ],
    'paid' => [
        'bg' => 'rgba(34, 197, 94, 0.1)',
        'text' => '#16a34a',
        'label' => 'Payée',
        'icon' => '✅',
    ],
    'cancelled' => [
        'bg' => 'rgba(239, 68, 68, 0.1)',
        'text' => '#dc2626',
        'label' => 'Annulée',
        'icon' => '❌',
    ],
    'failed' => [
        'bg' => 'rgba(239, 68, 68, 0.1)',
        'text' => '#dc2626',
        'label' => 'Échouée',
        'icon' => '⚠️',
    ],
];
$status = $statusConfig[$order->status] ?? $statusConfig['pending_payment'];
    @endphp

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pb-24 mb-16">
        {{-- Header --}}
        <div class="mb-10 sm:mb-14">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-orange-600">
                        Réservation
                    </p>

                    <h1 class="mt-2 text-3xl font-black tracking-tight text-stone-900 sm:text-4xl">
                        {{ $order->order_number }}
                    </h1>

                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }};"
                            class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-[11px] font-black uppercase tracking-widest">
                            <span aria-hidden="true">{{ $status['icon'] }}</span>
                            {{ $status['label'] }}
                        </span>

                        @if ($order->paid_at)
                            <span class="text-sm text-stone-500">
                                Payée le {{ $order->paid_at->format('d/m/Y à H:i') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a class="btn btn-secondary py-3!" href="{{ route('public.events.show', $order->event) }}">
                        <span aria-hidden="true">←</span>
                        Retour à l'événement
                    </a>

                    @auth
                        @php(auth()->user()?->loadMissing('roles'))
                        @if (auth()->user()?->hasAnyRole(['admin', 'controller']))
                            <a class="btn btn-secondary py-3!" href="{{ route('scanner.event', $order->event) }}">
                                <span aria-hidden="true">📱</span>
                                Scanner
                            </a>
                        @endif
                    @endauth

                    @if ($order->status === 'paid')
                        <a class="btn btn-secondary py-3!" href="{{ route('public.orders.invoice', $order) }}">
                            <span aria-hidden="true">📄</span>
                            Facture PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Alert paiement requis --}}
        @if ($order->status !== 'paid')
            <div class="mb-10 card-premium border-2 border-orange-200/60 bg-orange-50/40">
                <div class="flex flex-col gap-4 p-6 sm:p-8 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-500/10 text-orange-600 text-xl"
                            aria-hidden="true">⏳</div>
                        <div>
                            <p class="text-sm font-black uppercase tracking-[0.2em] text-orange-600">Paiement requis</p>
                            <p class="mt-2 text-sm leading-relaxed text-stone-600">
                                Les billets ne sont valides au scan qu'après paiement. Veuillez procéder au paiement pour activer vos billets.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('public.orders.checkout', $order) }}" class="btn btn-primary py-3! shrink-0">
                        <span aria-hidden="true">💳</span>
                        Payer maintenant
                    </a>
                </div>
            </div>
        @endif

        {{-- Layout 2 colonnes --}}
        <div class="lg:grid lg:grid-cols-12 lg:gap-10">
            <main class="space-y-6 lg:col-span-7">
                {{-- Événement --}}
                <section class="card-premium p-6 sm:p-8 lg:p-10">
                    <h2 class="text-xl font-black text-stone-900 sm:text-2xl">
                        Événement
                    </h2>

                    <div class="mt-6 space-y-4 sm:space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-orange-600 shadow-inner text-xl"
                                aria-hidden="true">🎉</div>
                            <div>
                                <p class="font-bold text-stone-900">{{ $order->event->name }}</p>
                                <p class="mt-1 text-sm text-stone-600">
                                    {{ optional($order->event->starts_at)->format('d/m/Y') }} · {{ optional($order->event->starts_at)->format('H:i') }} - {{ optional($order->event->ends_at)->format('H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 shadow-inner text-xl"
                                aria-hidden="true">📍</div>
                            <div>
                                <p class="font-bold text-stone-900">{{ $order->event->venue_name }}</p>
                                <p class="mt-1 text-sm text-stone-600">{{ $order->event->venue_address }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Billets --}}
                <section class="card-premium p-6 sm:p-8 lg:p-10 flex flex-col max-h-[85vh] lg:max-h-[calc(100vh-8rem)] overflow-hidden">
                    <h2 class="text-xl font-black text-stone-900 sm:text-2xl">
                        Billets
                    </h2>

                    <div class="mt-6 min-h-0 flex-1 overflow-y-auto overscroll-contain pr-1 sm:pr-2 space-y-4">
                        @foreach ($order->tickets as $ticket)
                            <article class="rounded-2xl border {{ $ticket->cancelled_at ? 'border-red-200 bg-red-50/50' : 'border-stone-100 bg-stone-50/50' }} p-5 sm:p-6">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                       <p class="">
                                            <span class="inline-flex items-center rounded-full {{ $ticket->cancelled_at ? 'bg-red-600' : 'bg-orange-600' }} px-3 py-1 text-[11px] font-black uppercase tracking-widest text-white">
                                                Billet #{{ $loop->iteration }}
                                            </span>
                                            @if ($ticket->cancelled_at)
                                                <span class="inline-flex items-center rounded-full bg-red-600 px-3 py-1 text-[11px] font-black uppercase tracking-widest text-white">
                                                    annulé
                                                </span>
                                            @endif
                                       </p>
                                        <p class="mt-3 text-lg font-bold text-stone-900">
                                            {{ $ticket->ticketType->name }}
                                        </p>
                                        <p class="mt-1 text-sm text-stone-900/90">
                                            <strong>{{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}</strong>
                                        </p>
                                        <p class="mt-2 text-sm text-stone-600">
                                            {{ $ticket->attendee_email }}
                                            @if ($ticket->attendee_phone)
                                                <span class="text-stone-500">· {{ $ticket->attendee_phone }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="sm:text-right">
                                        <p class="text-xl font-black text-orange-600">
                                            {{ number_format($ticket->ticketType->price_cents, 0, ',', ' ') }} {{ $ticket->ticketType->currency }}
                                        </p>
                                    </div>
                                </div>

                                @if ($order->status === 'paid')
                                    <div class="mt-5 rounded-2xl border border-stone-100 bg-white p-4">
                                        <p class="text-xs font-black uppercase tracking-widest text-stone-400">QR Code</p>
                                        <div class="mt-3 flex items-center justify-center rounded-2xl border border-stone-100 bg-stone-50/30 p-4">
                                            <img src="{{ route('tickets.qr', $ticket) }}" alt="QR Code"
                                                class=" w-40 max-w-full rounded-xl object-contain" />
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-5 rounded-2xl border border-dashed border-stone-200 bg-white p-6 text-center">
                                        <p class="text-4xl" aria-hidden="true">🔒</p>
                                        <p class="mt-2 text-sm font-semibold text-stone-600">
                                            QR Code disponible après paiement
                                        </p>
                                    </div>
                                @endif

                                @if ($order->status === 'paid' && ! $ticket->cancelled_at)
                                    <div class="mt-5 border-t border-stone-100 pt-4">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="text-sm font-semibold text-stone-600">Statut check-in :</span>
                                            @if ($ticket->checked_in_at)
                                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-black text-emerald-700">
                                                    Scanné
                                                </span>
                                                <span class="text-sm text-stone-500">
                                                    le {{ $ticket->checked_in_at->format('d/m/Y à H:i:s') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-400/10 px-3 py-1 text-xs font-black text-slate-600">
                                                    ✅ Valide
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            </main>

            <aside class="space-y-6 mt-6 lg:mt-0 lg:col-span-5">
                {{-- Récapitulatif --}}
                <section class="card-premium p-6 sm:p-8 lg:p-10">
                    <h3 class="text-xl font-black text-stone-900 sm:text-2xl">Récapitulatif</h3>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-start justify-between gap-4 border-b border-stone-100 pb-4">
                            <div>
                                <p class="text-sm font-bold text-stone-900">Billets</p>
                                <p class="mt-1 text-sm text-stone-600">
                                    {{ $order->tickets->where('cancelled_at', null)->count() }} billet{{ $order->tickets->where('cancelled_at', null)->count() > 1 ? 's' : '' }}
                                </p>
                            </div>
                            <p class="text-sm font-black text-stone-900">
                                {{ number_format($order->subtotal_cents, 0, ',', ' ') }} {{ $order->currency }}
                            </p>
                        </div>

                        @if ($order->addons_total_cents > 0)
                            <div class="flex items-start justify-between gap-4 border-b border-stone-100 pb-4">
                                <div>
                                    <p class="text-sm font-bold text-stone-900">Options</p>
                                    <p class="mt-1 text-sm text-stone-600">
                                        @if ($order->metadata && isset($order->metadata['addons']))
                                            {{ count($order->metadata['addons']) }} option{{ count($order->metadata['addons']) > 1 ? 's' : '' }}
                                        @endif
                                    </p>
                                </div>
                                <p class="text-sm font-black text-stone-900">
                                    {{ number_format($order->addons_total_cents, 0, ',', ' ') }} {{ $order->currency }}
                                </p>
                            </div>
                        @endif

                        <div class="pt-2">
                            <div class="flex items-end justify-between gap-4">
                                <p class="text-sm font-black text-stone-900">Total</p>
                                <p class="text-3xl font-black text-orange-600">
                                    {{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Contact --}}
                <section class="card-premium p-6 sm:p-8 lg:p-10">
                    <h3 class="text-xl font-black text-stone-900 sm:text-2xl">Contact</h3>

                    <div class="mt-6 space-y-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-stone-400">Email</p>
                            <p class="mt-2 text-sm font-semibold text-stone-900">{{ $order->customer_email }}</p>
                        </div>

                        @if ($order->customer_phone)
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-stone-400">Téléphone</p>
                                <p class="mt-2 text-sm font-semibold text-stone-900">{{ $order->customer_phone }}</p>
                            </div>
                        @endif

                        <div>
                            <p class="text-xs font-black uppercase tracking-widest text-stone-400">Date de réservation</p>
                            <p class="mt-2 text-sm font-semibold text-stone-900">
                                {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
