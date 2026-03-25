@extends('layouts.admin')

@section('title', 'Admin · Réservations')

@php
    $statusColors = [
        'pending_payment' => ['bg' => 'rgba(251, 191, 36, 0.1)', 'text' => '#d97706', 'label' => 'En attente'],
        'paid' => ['bg' => 'rgba(34, 197, 94, 0.1)', 'text' => '#16a34a', 'label' => 'Payée'],
        'cancelled' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Annulée'],
        'failed' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Échouée'],
        'expired' => ['bg' => 'rgba(100, 116, 139, 0.12)', 'text' => '#64748b', 'label' => 'Expirée'],
    ];
@endphp

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div
                    style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">
                    Gestion</div>
                <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">Réservations</h1>
                <p class="muted" style="font-size: 16px;">Filtrez, exportez et ouvrez le détail de chaque commande.</p>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
                <a href="{{ route('admin.orders.export', request()->query()) }}"
                    class="btn border border-slate-300 hover:bg-slate-100 transition-colors"
                    style="padding: 12px 20px; font-size: 13px; font-weight: 700;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>

                     Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- KPIs (sur les filtres actifs) -->
    <!-- <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 20px;">
            <div class="muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                En attente de paiement</div>
            <div style="font-size: 28px; font-weight: 900; margin-top: 8px; color: #d97706;">{{ $kpis['pending'] }}</div>
        </div>
        <div class="card" style="padding: 20px;">
            <div class="muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                Commandes payées</div>
            <div style="font-size: 28px; font-weight: 900; margin-top: 8px; color: #16a34a;">{{ $kpis['paid_count'] }}</div>
        </div>
        <div class="card" style="padding: 20px;">
            <div class="muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                Billets (commandes payées)</div>
            <div style="font-size: 28px; font-weight: 900; margin-top: 8px; color: var(--we-text);">{{ $kpis['tickets_sold'] }}</div>
        </div>
        <div class="card" style="padding: 20px;">
            <div class="muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                Chiffre d’affaires (payé)</div>
            <div style="margin-top: 8px; font-weight: 800; font-size: 18px; color: var(--we-text); line-height: 1.4;">
                @forelse ($kpis['revenue_by_currency'] as $row)
                    <div>{{ number_format($row['total_cents'], 0, ',', ' ') }} {{ $row['currency'] }}</div>
                @empty
                    <span class="muted" style="font-weight: 600;">—</span>
                @endforelse
            </div>
        </div>
    </div> -->

    @php
        $ordersFilterActive = ($filters['status'] ?? 'all') !== 'all'
            || filled($filters['event_id'] ?? null)
            || filled($filters['from'] ?? null)
            || filled($filters['to'] ?? null)
            || filled($filters['q'] ?? null);
    @endphp

    <!-- Filtres -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-8">
        <div
            class="rounded-2xl border border-stone-200/80 bg-gradient-to-br from-white via-stone-50/40 to-white p-4 shadow-[0_1px_2px_rgba(15,23,42,0.04)] sm:p-5">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-2 border-b border-stone-200/60 pb-3">
                <div class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400">
                    <span class="flex size-7 items-center justify-center rounded-lg bg-stone-100/90 text-stone-500 ring-1 ring-stone-200/80"
                        aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>

                    </span>
                    Affiner la liste
                </div>
                @if ($ordersFilterActive)
                    <a href="{{ route('admin.orders.index') }}"
                        class="text-xs font-medium text-stone-500 underline decoration-stone-300/80 underline-offset-2 transition hover:text-orange-700 hover:decoration-orange-400/80">
                        Tout effacer
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12 lg:items-end lg:gap-x-3 lg:gap-y-3">
                <div class="lg:col-span-2">
                    <label for="filter-status" class="mb-1.5 block text-[11px] font-medium text-stone-500">Statut</label>
                    <select name="status" id="filter-status"
                        class="w-full appearance-none rounded-xl border border-stone-200/90 bg-white/90 px-3 py-2.5 text-sm text-stone-800 shadow-sm shadow-stone-900/5 transition focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
                        <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>Tous les statuts</option>
                        <option value="pending_payment" @selected(($filters['status'] ?? '') === 'pending_payment')>En attente</option>
                        <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Payée</option>
                        <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Annulée</option>
                        <option value="expired" @selected(($filters['status'] ?? '') === 'expired')>Expirée</option>
                    </select>
                </div>
                <div class="lg:col-span-4">
                    <label for="filter-event" class="mb-1.5 block text-[11px] font-medium text-stone-500">Soirée</label>
                    <select name="event_id" id="filter-event"
                        class="w-full appearance-none rounded-xl border border-stone-200/90 bg-white/90 px-3 py-2.5 text-sm text-stone-800 shadow-sm shadow-stone-900/5 transition focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
                        <option value="">Toutes les soirées</option>
                        @foreach ($events as $ev)
                            <option value="{{ $ev->id }}" @selected((string) ($filters['event_id'] ?? '') === (string) $ev->id)>
                                {{ $ev->name }} · {{ optional($ev->starts_at)->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="filter-from" class="mb-1.5 block text-[11px] font-medium text-stone-500">Du</label>
                    <input type="date" name="from" id="filter-from" value="{{ $filters['from'] ?? '' }}"
                        class="w-full rounded-xl border border-stone-200/90 bg-white/90 px-3 py-2.5 text-sm text-stone-800 shadow-sm shadow-stone-900/5 transition focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
                </div>
                <div class="lg:col-span-2">
                    <label for="filter-to" class="mb-1.5 block text-[11px] font-medium text-stone-500">Au</label>
                    <input type="date" name="to" id="filter-to" value="{{ $filters['to'] ?? '' }}"
                        class="w-full rounded-xl border border-stone-200/90 bg-white/90 px-3 py-2.5 text-sm text-stone-800 shadow-sm shadow-stone-900/5 transition focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
                </div>
                <div class="sm:col-span-2 lg:col-span-12">
                    <label for="filter-q" class="mb-1.5 block text-[11px] font-medium text-stone-500">Recherche</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-stone-400" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </span>
                        <input type="search" name="q" id="filter-q" value="{{ $filters['q'] ?? '' }}"
                            placeholder="N°, e-mail, téléphone…"
                            class="w-full rounded-xl border border-stone-200/90 bg-white/90 py-2.5 pl-10 pr-3 text-sm text-stone-800 shadow-sm shadow-stone-900/5 placeholder:text-stone-400 transition focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:justify-end lg:col-span-12 lg:pt-1">
                    
                    <a href="{{ route('admin.orders.index') }}"
                        class="inline-flex items-center justify-center rounded-full border border-stone-200/90 bg-white/80 px-4 py-2.5 text-sm font-medium text-stone-600 shadow-sm shadow-stone-900/5 transition hover:border-stone-300 hover:bg-stone-50 hover:text-stone-900">
                        Réinitialiser
                    </a>
                    <button type="submit"
                        class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-orange-900/10 transition hover:from-orange-600 hover:to-orange-700 hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" class="size-4 opacity-90">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        Appliquer
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Tableau des réservations -->
    @if($orders->count() > 0)
        <div class="bg-white rounded-3xl border border-stone-100 shadow-premium transition-all duration-300 overflow-hidden p-0 mb-8">
            <div class="-mx-px overflow-x-auto overscroll-x-contain">
                <table class="min-w-[800px] w-full border-collapse" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr
                            style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-bottom: 2px solid var(--we-border);">
                            <th
                                style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Réservation</th>
                            <th
                                style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Soirée</th>
                            <th
                                style="padding: 16px 20px; text-align: center; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Statut</th>
                            <th
                                style="padding: 16px 20px; text-align: right; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Total</th>
                            <th
                                style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Date</th>
                            <th
                                style="padding: 16px 20px; text-align: right; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                                @php
                                    $status = $statusColors[$order->status] ?? $statusColors['pending_payment'];
                                @endphp
                                <tr style="border-top: 1px solid var(--we-border); transition: background 0.2s ease;"
                                    onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 20px;">
                                        <a href="{{ route('public.orders.show', $order) }}"
                                            style="display: inline-block; padding: 8px 14px; border-radius: 8px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)); color: var(--we-primary); font-weight: 700; font-size: 13px; text-decoration: none; font-family: ui-monospace, monospace; transition: transform 0.2s ease;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                            {{ $order->order_number }}
                                        </a>
                                        <div class="muted" style="font-size: 12px; margin-top: 8px;">{{ $order->customer_email }}</div>
                                        @if($order->customer_phone)
                                            <div class="muted" style="font-size: 12px;">{{ $order->customer_phone }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px;">
                                        <div style="font-weight: 800; font-size: 15px; color: var(--we-text); margin-bottom: 6px;">
                                            {{ $order->event?->name ?? 'N/A' }}</div>
                                        @if($order->event?->slug)
                                            <div class="muted" style="font-size: 12px; font-family: ui-monospace, monospace;">
                                                {{ $order->event->slug }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px; text-align: center;">
                            <span
                                            style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; letter-spacing: 0.5px;">
                                            {{ $status['label'] }}
                                        </span>
                                        @if($order->paid_at)
                                            <div class="muted" style="font-size: 11px; margin-top: 4px;">
                                                {{ $order->paid_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                        @if($order->status === 'pending_payment' && $order->expires_at)
                                            <div class="muted" style="font-size: 11px; margin-top: 4px;">
                                                Expire {{ $order->expires_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px; text-align: right;">
                                        <div style="font-weight: 800; font-size: 16px; color: var(--we-text);">
                                            {{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}
                                        </div>
                                        @if($order->subtotal_cents > 0 || $order->addons_total_cents > 0)
                                            <div class="muted" style="font-size: 11px; margin-top: 4px;">
                                                Billets: {{ number_format($order->subtotal_cents, 0, ',', ' ') }} {{ $order->currency }}
                                                @if($order->addons_total_cents > 0)
                                                    + Options: {{ number_format($order->addons_total_cents, 0, ',', ' ') }}
                                                    {{ $order->currency }}
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px;">
                                        <div style="font-weight: 600; font-size: 14px; color: var(--we-text);">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="muted" style="font-size: 12px;">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td style="padding: 20px; text-align: right; vertical-align: middle;">
                                        @php
                                            $canCancelFromList = $order->status !== 'cancelled'
                                                && in_array($order->status, ['pending_payment', 'paid', 'failed', 'expired'], true);
                                        @endphp
                                        @if ($canCancelFromList)
                                            <form id="admin-cancel-order-form-{{ $order->id }}" method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="m-0 inline">
                                                @csrf
                                                <button type="button"
                                                    class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xs font-extrabold uppercase tracking-wide text-red-700 transition hover:border-red-300 hover:bg-red-100"
                                                    onclick='window.adminOpenOrderCancelModal(document.getElementById("admin-cancel-order-form-{{ $order->id }}"), @json($order->order_number))'>
                                                    Annuler
                                                </button>
                                            </form>
                                        @else
                                            <span class="muted text-sm font-semibold">—</span>
                                        @endif
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="card" style="padding: 64px 32px; text-align: center;">
            <div style="font-size: 64px; margin-bottom: 24px;">🎫</div>
            @if ($ordersFilterActive)
                <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 12px;">Aucune réservation ne correspond</h3>
                <p class="muted"
                    style="font-size: 16px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    Ajustez les filtres ou réinitialisez la recherche pour voir toutes les commandes.
                </p>
                <a href="{{ route('admin.orders.index') }}" class="btn secondary" style="padding: 14px 28px; font-size: 16px;">
                    Réinitialiser les filtres
                </a>
            @else
                <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 12px;">Aucune réservation pour le moment</h3>
                <p class="muted"
                    style="font-size: 16px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    Les réservations apparaîtront ici une fois que vos soirées seront publiées et que les clients
                    commenceront à réserver.
                </p>
                <a href="{{ route('admin.events.index') }}" class="btn secondary" style="padding: 14px 28px; font-size: 16px;">
                    📅 Gérer les soirées
                </a>
            @endif
        </div>
    @endif

    <div id="order-cancel-confirm-modal" class="fixed inset-0 z-[105] hidden" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="order-cancel-confirm-title">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm transition-opacity" data-order-cancel-backdrop></div>
        <div class="pointer-events-none relative z-10 mx-auto flex min-h-full items-center justify-center p-4 sm:p-6">
            <div class="pointer-events-auto w-full max-w-md rounded-2xl border border-stone-200/80 bg-white p-6 shadow-2xl shadow-stone-900/15 sm:p-8">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 ring-1 ring-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <h2 id="order-cancel-confirm-title" class="text-center text-lg font-black tracking-tight text-slate-900 sm:text-xl">
                    Annuler cette réservation ?
                </h2>
                <p class="mt-2 text-center text-sm leading-relaxed text-slate-600">
                    <span class="font-mono font-semibold text-slate-800" id="order-cancel-confirm-number"></span>
                    <span class="mt-2 block">Tous les billets encore actifs seront invalidés et les places libérées sur l’événement.</span>
                </p>
                <div class="mt-6 grid grid-cols-2 gap-6 justify-center">
                    <button type="button"
                        class=" w-full cursor-pointer items-center justify-center rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-stone-50 sm:w-auto"
                        data-order-cancel-dismiss>
                        Retour
                    </button>
                    <button type="button" id="order-cancel-confirm-submit"
                        class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-red-700 sm:w-auto">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            let orderCancelFormPending = null;

            function syncOrderCancelBodyScroll() {
                const modal = document.getElementById('order-cancel-confirm-modal');
                const open = modal && !modal.classList.contains('hidden');
                document.body.classList.toggle('overflow-hidden', open);
            }

            function closeOrderCancelModal() {
                orderCancelFormPending = null;
                const modal = document.getElementById('order-cancel-confirm-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                }
                syncOrderCancelBodyScroll();
            }

            window.adminOpenOrderCancelModal = function (form, orderNumber) {
                if (!form) return;
                orderCancelFormPending = form;
                const numEl = document.getElementById('order-cancel-confirm-number');
                if (numEl) {
                    numEl.textContent = orderNumber != null ? String(orderNumber) : '';
                }
                const modal = document.getElementById('order-cancel-confirm-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    syncOrderCancelBodyScroll();
                    document.getElementById('order-cancel-confirm-submit')?.focus();
                }
            };

            document.getElementById('order-cancel-confirm-submit')?.addEventListener('click', function () {
                if (orderCancelFormPending) {
                    orderCancelFormPending.submit();
                }
            });

            document.querySelector('[data-order-cancel-dismiss]')?.addEventListener('click', closeOrderCancelModal);

            document.addEventListener('click', function (ev) {
                const t = ev.target;
                if (t instanceof Element && t.matches('[data-order-cancel-backdrop]')) {
                    closeOrderCancelModal();
                }
            });

            document.addEventListener('keydown', function (ev) {
                if (ev.key !== 'Escape') return;
                const modal = document.getElementById('order-cancel-confirm-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeOrderCancelModal();
                }
            });
        })();
    </script>
@endsection
