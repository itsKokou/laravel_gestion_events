@extends('layouts.public')

@section('title', 'Paiement ' . $order->order_number . " · Win's Events")

@section('content')
    @php
        $billing = (array) data_get($order->metadata, 'billing', []);
        $ticketLines = $order->tickets
            ->groupBy(fn($ticket) => ($ticket->ticketType->name ?? 'Billet') . '|' . (int) ($ticket->ticketType->price_cents ?? 0) . '|' . ($ticket->ticketType->currency ?? ''))
            ->map(function ($group) {
                $first = $group->first();
                $unitPrice = (int) ($first->ticketType->price_cents ?? 0);
                $currency = $first->ticketType->currency ?? '';

                return [
                    'name' => $first->ticketType->name ?? 'Billet',
                    'qty' => $group->count(),
                    'unit_price' => $unitPrice,
                    'line_total' => $unitPrice * $group->count(),
                    'currency' => $currency,
                ];
            })
            ->values();
    @endphp

    <div class="mx-auto w-full max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
        <div class="mb-10 mt-2 sm:mt-4">
            <a href="{{ route('public.orders.show', $order) }}"
                class="inline-flex items-center gap-2 rounded-full border border-stone-200 bg-white px-5 py-3 text-sm font-bold text-stone-600 shadow-sm transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-700">
                <span aria-hidden="true">←</span>
                Retour à la commande
            </a>
        </div>

        <div class="mb-10 flex flex-col gap-3 sm:mb-12">
            <p class="text-xs font-black uppercase tracking-[0.2em] text-orange-600">Checkout</p>
            <h1 class="text-3xl font-black tracking-tight text-stone-900 sm:text-4xl">Finaliser votre paiement</h1>
            <p class="max-w-3xl text-sm leading-relaxed text-stone-600 sm:text-base">
                Vérifiez votre commande puis Finalisez le paiement pour recevoir vos billets.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-8 rounded-3xl border border-red-200 bg-red-50 p-5 sm:p-6">
                <p class="text-sm font-black uppercase tracking-widest text-red-700">Paiement impossible</p>
                <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('public.orders.pay', $order) }}" class="lg:grid lg:grid-cols-12 lg:gap-10 mb-16">
            @csrf
            <main class="space-y-6 lg:col-span-7">
                 <section class="card-premium p-6 sm:p-8 lg:p-10">
                    <h2 class="text-xl font-black text-stone-900 sm:text-2xl">Adresse de facturation</h2>
                    <p class="mt-2 text-sm text-stone-600">
                        Utilisée uniquement pour l'édition de facture.
                    </p>

                    <div class="mt-6 space-y-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-stone-700">Adresse</label>
                            <input type="text" name="billing_address" class="form-input"
                                value="{{ old('billing_address', $billing['address'] ?? '') }}" required />
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-stone-700">Ville</label>
                                <input type="text" name="billing_city" class="form-input"
                                    value="{{ old('billing_city', $billing['city'] ?? 'Dakar') }}" required />
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-stone-700">Code postal</label>
                                <input type="text" name="billing_postal_code" class="form-input"
                                    value="{{ old('billing_postal_code', $billing['postal_code'] ?? '11500') }}" />
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-stone-700">Pays</label>
                            <input type="text" name="billing_country" class="form-input"
                                value="{{ old('billing_country', $billing['country'] ?? "Sénégal") }}" required />
                        </div>
                    </div>
                </section>
                
                <section class="card-premium p-6 sm:p-8 lg:p-10">
                    <div class="mb-6 flex items-start gap-4 border-b border-stone-100 pb-6">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-orange-600 text-xl"
                            aria-hidden="true">🔒</span>
                        <div>
                            <h2 class="text-xl font-black text-stone-900 sm:text-2xl">Paiement sécurisé</h2>
                            <p class="mt-1 text-sm text-stone-600">Numéro de commande : {{ $order->order_number }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @php($selectedMethod = old('payment_method', 'card'))

                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-stone-200 bg-white p-4 transition hover:border-orange-300 has-checked:border-orange-400 has-checked:bg-orange-50/40">
                            <input type="radio" name="payment_method" value="card" class="mt-1"
                                @checked($selectedMethod === 'card')>
                            <span>
                                <span class="block text-sm font-bold text-stone-900">Carte bancaire</span>
                                <span class="mt-1 block text-xs text-stone-500">Visa, Mastercard (simulation)</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-stone-200 bg-white p-4 transition hover:border-orange-300 has-checked:border-orange-400 has-checked:bg-orange-50/40">
                            <input type="radio" name="payment_method" value="orange_money" class="mt-1"
                                @checked($selectedMethod === 'orange_money')>
                            <span>
                                <span class="block text-sm font-bold text-stone-900">Orange Money</span>
                                <span class="mt-1 block text-xs text-stone-500">Paiement mobile instantané</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-stone-200 bg-white p-4 transition hover:border-orange-300 has-checked:border-orange-400 has-checked:bg-orange-50/40">
                            <input type="radio" name="payment_method" value="wave" class="mt-1"
                                @checked($selectedMethod === 'wave')>
                            <span>
                                <span class="block text-sm font-bold text-stone-900">Wave</span>
                                <span class="mt-1 block text-xs text-stone-500">Paiement mobile sécurisé</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-stone-200 bg-white p-4 transition hover:border-orange-300 has-checked:border-orange-400 has-checked:bg-orange-50/40">
                            <input type="radio" name="payment_method" value="paypal" class="mt-1"
                                @checked($selectedMethod === 'paypal')>
                            <span>
                                <span class="block text-sm font-bold text-stone-900">PayPal</span>
                                <span class="mt-1 block text-xs text-stone-500">Paiement rapide via compte PayPal</span>
                            </span>
                        </label>
                    </div>
                </section>

               
            </main>

            <aside class="mt-6 lg:mt-0 lg:col-span-5">
                <section class="card-premium p-6 sm:p-8 lg:sticky lg:top-28">
                    <h2 class="text-xl font-black text-stone-900 sm:text-2xl">Résumé du paiement</h2>

                    <div class="mt-6 space-y-4">
                        @foreach ($ticketLines as $line)
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-bold text-stone-900">{{ $line['name'] }}</p>
                                    <p class="mt-1 text-sm text-stone-600">
                                        {{ $line['qty'] }} billet{{ $line['qty'] > 1 ? 's' : '' }} ×
                                        {{ number_format($line['unit_price'], 0, ',', ' ') }} {{ $line['currency'] }}
                                    </p>
                                </div>
                                <p class="text-sm font-black text-stone-900">
                                    {{ number_format($line['line_total'], 0, ',', ' ') }} {{ $line['currency'] }}
                                </p>
                            </div>
                        @endforeach

                        <div class="flex items-start justify-between gap-4 border-t border-b border-stone-100 py-4">
                            <p class="text-sm font-black text-stone-900">Sous-total billets</p>
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

                        <div class="flex items-end justify-between pt-1">
                            <p class="text-sm font-black text-stone-900">Total à payer</p>
                            <p class="text-3xl font-black text-orange-600">
                                {{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-7 space-y-3">
                        <button type="submit" class="btn btn-primary w-full justify-center py-3! text-base font-bold">
                            <span aria-hidden="true">🔐</span>
                            Payer {{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}
                        </button>

                        <p class="text-center text-xs text-stone-500">
                            Paiement simulé pour l'environnement de démonstration.
                        </p>
                    </div>
                </section>
            </aside>
        </form>
    </div>
@endsection
