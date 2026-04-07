@extends('layouts.admin')

@section('title', 'Admin · Billets & participants')

@section('content')
    <div class="mb-8">
        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-orange-600">Ventes</p>
        <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Tickets & participants</h1>
        <!-- <p class="mt-2 max-w-2xl text-sm text-slate-600">
            Vue transversale : chaque ligne est un <strong>participant</strong> lié à sa <strong>commande</strong> et à la <strong>soirée</strong>.
            Pour annuler ou gérer une réservation entière, utilisez <a href="{{ route('admin.orders.index') }}" class="font-semibold text-orange-700 underline-offset-2 hover:underline">Réservations</a>.
        </p> -->
    </div>

    <form method="GET" action="{{ route('admin.tickets.index') }}" class="mb-6 rounded-2xl border border-stone-200/80 bg-gradient-to-br from-white via-stone-50/40 to-stone-100/30 p-4 shadow-sm sm:p-5">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-4">
                <label for="t-filter-event" class="mb-1.5 block text-[11px] font-medium text-stone-500">Soirée</label>
                <select name="event_id" id="t-filter-event"
                    class="w-full rounded-xl border border-stone-200/90 bg-white/90 px-3 py-2.5 text-sm text-stone-800 shadow-sm focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
                    <option value="">Toutes</option>
                    @foreach ($events as $ev)
                        <option value="{{ $ev->id }}" @selected((string) ($filters['event_id'] ?? '') === (string) $ev->id)>
                            {{ $ev->name }} · {{ optional($ev->starts_at)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-3">
                <label for="t-filter-billet" class="mb-1.5 block text-[11px] font-medium text-stone-500">Billet</label>
                <select name="billet" id="t-filter-billet"
                    class="w-full rounded-xl border border-stone-200/90 bg-white/90 px-3 py-2.5 text-sm text-stone-800 shadow-sm focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
                    <option value="all" @selected(($filters['billet'] ?? 'all') === 'all')>Tous</option>
                    <option value="actifs" @selected(($filters['billet'] ?? '') === 'actifs')>Actifs seulement</option>
                    <option value="annules" @selected(($filters['billet'] ?? '') === 'annules')>Annulés seulement</option>
                </select>
            </div>
            <div class="lg:col-span-5">
                <label for="t-filter-q" class="mb-1.5 block text-[11px] font-medium text-stone-500">Recherche</label>
                <input type="search" name="q" id="t-filter-q" value="{{ $filters['q'] ?? '' }}" placeholder="Nom, e-mail, n° commande…"
                    class="w-full rounded-xl border border-stone-200/90 bg-white/90 px-3 py-2.5 text-sm text-stone-800 shadow-sm placeholder:text-stone-400 focus:border-orange-300/90 focus:outline-none focus:ring-2 focus:ring-orange-500/15">
            </div>
            <div class="flex flex-wrap gap-2 lg:col-span-12 justify-end">
                <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center justify-center rounded-full border border-stone-200 bg-white px-4 py-2.5 text-sm font-medium text-stone-600 shadow-sm hover:bg-stone-50">
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
    </form>

    @if ($tickets->count() > 0)
        <div class="overflow-hidden rounded-3xl border border-stone-100 bg-white shadow-premium">
            <div class="-mx-px overflow-x-auto overscroll-x-contain">
                <table class="min-w-[980px] w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-gradient-to-r from-orange-500/5 to-amber-500/5">
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Participant</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Soirée</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Tarif</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Commande</th>
                            <th class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Statuts</th>
                            <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wide text-slate-700 sm:px-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            @php
                                $canCancelTicket = $ticket->order
                                    && ! $ticket->cancelled_at
                                    && $ticket->order->status !== 'cancelled'
                                    && in_array($ticket->order->status, ['pending_payment', 'paid', 'failed', 'expired'], true);
                            @endphp
                            <tr class="border-t border-stone-100 transition-colors hover:bg-stone-50/80">
                                <td class="px-4 py-4 align-top sm:px-5">
                                    <p class="font-bold text-slate-900">{{ $ticket->attendee_first_name }} {{ $ticket->attendee_last_name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $ticket->attendee_email }}</p>
                                </td>
                                <td class="px-4 py-4 align-top sm:px-5">
                                    <p class="font-semibold text-slate-800">{{ $ticket->event?->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">{{ optional($ticket->event?->starts_at)->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-4 py-4 align-top sm:px-5">
                                    <p class="text-slate-800 font-semibold">{{ $ticket->ticketType?->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">
                                        @if ($ticket->ticketType && $ticket->order)
                                            {{ number_format(($ticket->ticketType->price_cents ?? 0) / 100, 2, ',', ' ') }} {{ $ticket->order->currency }}
                                        @else
                                            —
                                        @endif
                                    </p>
                                </td>
                                <td class="px-4 py-4 align-top sm:px-5">
                                    @if ($ticket->order)
                                        <a href="{{ route('admin.orders.show', $ticket->order) }}" class="font-mono text-sm font-bold text-orange-700 hover:underline">
                                            {{ $ticket->order->order_number }}
                                        </a>
                                        <p class="text-xs text-slate-500">{{ match ($ticket->order->status) {
                                            'paid' => 'Payé',
                                            'pending_payment' => 'En attente de paiement',
                                            'cancelled' => 'Annulé',
                                            'failed' => 'Échoué',
                                            'expired' => 'Expiré',
                                            default => $ticket->order->status,
                                        } }}</p>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 align-top sm:px-5">
                                    <div class="flex flex-col gap-1">
                                        @if ($ticket->cancelled_at)
                                            <span class="inline-flex w-fit rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-800">Billet annulé</span>
                                        @else
                                            <span class="inline-flex w-fit rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-800">Billet actif</span>
                                        @endif
                                        @if ($ticket->order?->status === 'paid' && ! $ticket->cancelled_at)
                                            @if ($ticket->checked_in_at)
                                                <span class="inline-flex w-fit rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-blue-900">Entrée enregistrée</span>
                                            @else
                                                <span class="inline-flex w-fit rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-900">Pas scanné</span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-top text-right sm:px-5">
                                    @if ($canCancelTicket && $ticket->order)
                                        <form id="admin-cancel-ticket-form-{{ $ticket->id }}" method="POST"
                                            action="{{ route('admin.orders.tickets.cancel', [$ticket->order, $ticket]) }}" class="m-0 inline">
                                            @csrf
                                            <button type="button"
                                                class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-extrabold uppercase tracking-wide text-red-700 transition hover:border-red-300 hover:bg-red-100"
                                                onclick='window.adminOpenTicketCancelModal(document.getElementById("admin-cancel-ticket-form-{{ $ticket->id }}"), @json(trim($ticket->attendee_first_name." ".$ticket->attendee_last_name)))'>
                                                Annuler
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs font-medium text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($tickets->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $tickets->links() }}
            </div>
        @endif
    <div id="ticket-cancel-confirm-modal" class="fixed inset-0 z-[105] hidden" aria-hidden="true" role="dialog" aria-modal="true"
        aria-labelledby="ticket-cancel-confirm-title">
        <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm transition-opacity" data-ticket-cancel-backdrop></div>
        <div class="pointer-events-none relative z-10 mx-auto flex min-h-full items-center justify-center p-4 sm:p-6">
            <div class="pointer-events-auto w-full max-w-md rounded-2xl border border-stone-200/80 bg-white p-6 shadow-2xl shadow-stone-900/15 sm:p-8">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 ring-1 ring-red-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <h2 id="ticket-cancel-confirm-title" class="text-center text-lg font-black tracking-tight text-slate-900 sm:text-xl">
                    Annuler ce billet ?
                </h2>
                <p class="mt-2 text-center text-sm leading-relaxed text-slate-600">
                    <span class="font-semibold text-slate-800" id="ticket-cancel-confirm-name"></span>
                    <span class="mt-2 block">La place sera libérée sur l’événement et le tarif. Si c’était le dernier billet actif, la commande sera clôturée.</span>
                </p>
                <div class="mt-6 grid grid-cols-2 gap-3 justify-center">
                    <button type="button"
                        class="w-full cursor-pointer rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-stone-50"
                        data-ticket-cancel-dismiss>
                        Retour
                    </button>
                    <button type="button" id="ticket-cancel-confirm-submit"
                        class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-red-700">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            let ticketCancelFormPending = null;

            function syncTicketCancelBodyScroll() {
                const modal = document.getElementById('ticket-cancel-confirm-modal');
                const open = modal && !modal.classList.contains('hidden');
                document.body.classList.toggle('overflow-hidden', open);
            }

            function closeTicketCancelModal() {
                ticketCancelFormPending = null;
                const modal = document.getElementById('ticket-cancel-confirm-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                }
                syncTicketCancelBodyScroll();
            }

            window.adminOpenTicketCancelModal = function (form, participantName) {
                if (!form) return;
                ticketCancelFormPending = form;
                const nameEl = document.getElementById('ticket-cancel-confirm-name');
                if (nameEl) {
                    nameEl.textContent = participantName != null ? String(participantName) : '';
                }
                const modal = document.getElementById('ticket-cancel-confirm-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    syncTicketCancelBodyScroll();
                    document.getElementById('ticket-cancel-confirm-submit')?.focus();
                }
            };

            document.getElementById('ticket-cancel-confirm-submit')?.addEventListener('click', function () {
                if (ticketCancelFormPending) {
                    ticketCancelFormPending.submit();
                }
            });

            document.querySelector('[data-ticket-cancel-dismiss]')?.addEventListener('click', closeTicketCancelModal);

            document.addEventListener('click', function (ev) {
                const t = ev.target;
                if (t instanceof Element && t.matches('[data-ticket-cancel-backdrop]')) {
                    closeTicketCancelModal();
                }
            });

            document.addEventListener('keydown', function (ev) {
                if (ev.key !== 'Escape') return;
                const modal = document.getElementById('ticket-cancel-confirm-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeTicketCancelModal();
                }
            });
        })();
    </script>

    @else
        <div class="rounded-2xl border border-stone-200 bg-white px-8 py-16 text-center shadow-sm">
            <p class="text-4xl">🎫</p>
            <h2 class="mt-4 text-xl font-black text-slate-900">Aucun billet à afficher</h2>
            <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">Modifiez les filtres ou créez des réservations depuis le site public.</p>
            <a href="{{ route('admin.orders.index') }}" class="mt-6 inline-flex rounded-full border border-stone-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-800 hover:bg-stone-50">Voir les réservations</a>
        </div>
    @endif
@endsection
