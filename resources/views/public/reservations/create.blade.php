@extends('layouts.public')

@section('title', 'Réserver · ' . $event->name)


@section('content')
        <div class="mx-auto mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mb-16">
            {{-- En-tête --}}
            <header class="mb-12 max-w-3xl sm:mb-14">
                <a href="{{ route('public.events.show', $event) }}"
                    class="mb-6 inline-flex items-center gap-2 rounded-full border border-stone-200/90 bg-white px-4 py-2.5 text-sm font-bold text-stone-600 shadow-sm transition hover:border-orange-200 hover:bg-orange-50/50 hover:text-orange-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Retour à l’événement
                </a>
                <p class="mb-3 text-xs font-black uppercase tracking-[0.2em] text-orange-600">Réservation</p>
                <h1 class="text-3xl font-black tracking-tight text-stone-900 sm:text-4xl lg:text-[2.5rem] lg:leading-tight">Réserver vos places</h1>
                <p class="mt-5 text-base leading-relaxed text-stone-600 sm:text-lg">
                    {{ $event->name }}
                    <span class="mx-2 text-stone-300">·</span>
                    {{ $event->starts_at->format('d/m/Y à H:i') }}
                </p>
            </header>

            @if ($errors->any())
                <div class="mb-10 rounded-2xl border border-red-200/80 bg-red-50/90 p-6 shadow-sm sm:p-7" role="alert">
                    <div class="flex items-start gap-4">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-black text-white"
                            aria-hidden="true">!</span>
                        <div class="min-w-0">
                            <p class="text-base font-bold text-red-900">Merci de corriger les points suivants</p>
                            <ul class="mt-3 list-inside list-disc space-y-1.5 text-sm font-medium leading-relaxed text-red-800">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @php
$initialAttendees = old('attendees', [['first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'birthdate' => '']]);
            @endphp

            <form method="POST" action="{{ route('public.reservations.store', $event) }}">
                @csrf
                <input type="hidden" id="quantity" name="quantity" value="{{ old('quantity', count($initialAttendees)) }}">

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                    <div class="space-y-10 lg:space-y-12 lg:col-span-7">

                {{-- Coordonnées --}}
                <section
                    class="bg-white p-8 shadow-[0_20px_50px_-20px_rgba(28,25,23,0.1)] sm:p-10 rounded-3xl border-amber-50 border">
                    <div class="flex flex-col gap-4 pb-8 sm:flex-row sm:items-center sm:gap-5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 text-xl shadow-inner"
                            aria-hidden="true">✉️</span>
                        <div>
                            <h2 class="text-xl font-extrabold text-stone-900 sm:text-2xl">Coordonnées</h2>
                            <p class="mt-1.5 text-base text-stone-500">Pour recevoir la confirmation et le billet</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 sm:gap-x-10 sm:gap-y-8">
                        <div class="space-y-3">
                            <label for="customer_email" class="block text-sm font-semibold text-stone-700">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}"
                                required autocomplete="email" placeholder="vous@exemple.com" class="form-input text-base" />
                        </div>
                        <div class="space-y-3">
                            <label for="customer_phone" class="block text-sm font-semibold text-stone-700">
                                Téléphone <span class="text-stone-400 font-normal">(optionnel)</span>
                            </label>
                            <input id="customer_phone" name="customer_phone" type="tel" value="{{ old('customer_phone') }}"
                                autocomplete="tel" placeholder="+33 6 12 34 56 78" class="form-input text-base" />
                        </div>
                    </div>
                </section>

                {{-- Tarif (mobile) --}}
                <section
                    class="lg:hidden rounded-[1.75rem] border border-stone-100 bg-white p-8 shadow-[0_20px_50px_-20px_rgba(28,25,23,0.1)] sm:p-10 sm:rounded-[2rem]">
                    <div class="mb-8 flex flex-col gap-4 border-b border-stone-100 pb-8 sm:flex-row sm:items-center sm:gap-5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 text-xl shadow-inner"
                            aria-hidden="true">🎫</span>
                        <div>
                            <h2 class="text-xl font-extrabold text-stone-900 sm:text-2xl">Tarif</h2>
                            <p class="mt-1.5 text-base text-stone-500">Tarif appliqué à la réservation</p>
                        </div>
                    </div>

                    <div
                        class="relative mb-10 overflow-hidden rounded-2xl border border-orange-200/60 bg-gradient-to-br from-orange-50/90 via-amber-50/40 to-white p-8 shadow-inner sm:p-10">
                        <div
                            class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-orange-400/15 blur-2xl">
                        </div>
                        <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between lg:gap-12">
                            <div class="max-w-xl">
                                <span
                                    class="mb-3 inline-block rounded-full bg-orange-600 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-white shadow-sm">Tarif actif</span>
                                <p class="text-2xl font-black text-stone-900 sm:text-3xl">{{ $activeTicketType->name }}</p>
                                @if ($activeTicketType->sales_starts_at && $activeTicketType->sales_ends_at)
                                    <p class="mt-4 text-base leading-relaxed text-stone-600">
                                        Du {{ $activeTicketType->sales_starts_at->format('d/m/Y H:i') }} au
                                        {{ $activeTicketType->sales_ends_at->format('d/m/Y H:i') }}
                                    </p>
                                @else
                                    <p class="mt-4 text-base text-stone-600">Disponible en permanence</p>
                                @endif
                            </div>
                            <div class="shrink-0 text-left lg:text-right">
                                <p class="text-4xl font-black tabular-nums text-orange-600 sm:text-5xl">
                                    {{ number_format($activeTicketType->price_cents, 0, ',', ' ') }}
                                    <span class="text-xl font-bold text-orange-700/90 sm:text-2xl">{{ $activeTicketType->currency }}</span>
                                </p>
                                <p class="mt-2 text-sm font-semibold uppercase tracking-wide text-stone-500">par billet</p>
                            </div>
                        </div>
                    </div>

                    <div class="max-w-xl rounded-2xl border border-orange-200/60 bg-orange-50/60 p-5 sm:p-6">
                        <p class="text-sm font-semibold text-stone-700">
                            Le nombre de billets est calculé automatiquement selon le nombre de participants.
                        </p>
                        <p class="mt-2 text-sm text-stone-600">
                            <span id="participant-count-display">{{ count($initialAttendees) }}</span> participant(s) =
                            <span id="ticket-count-display">{{ count($initialAttendees) }}</span> billet(s)
                        </p>
                    </div>
                </section>

                {{-- Addons --}}
                @if ($event->addons->count() > 0)
                    <section
                        class="rounded-[1.75rem] border border-stone-100 bg-white p-8 shadow-[0_20px_50px_-20px_rgba(28,25,23,0.1)] sm:p-10 sm:rounded-[2rem]">
                        <div class="mb-8 flex flex-col gap-4 border-b border-stone-100 pb-8 sm:flex-row sm:items-center sm:gap-5">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 text-xl shadow-inner"
                                aria-hidden="true">✨</span>
                            <div>
                                <h2 class="text-xl font-extrabold text-stone-900 sm:text-2xl">Options</h2>
                                <p class="mt-1.5 text-base text-stone-500">Ajouts facultatifs à votre réservation</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:gap-6">
                            @foreach ($event->addons as $addon)
                                <label
                                    class="group flex cursor-pointer items-start gap-4 rounded-2xl border border-stone-200/90 bg-stone-50/50 p-5 transition hover:border-orange-200 hover:bg-white hover:shadow-md sm:p-6 has-checked:border-orange-300 has-checked:bg-orange-50/40 has-checked:ring-2 has-checked:ring-orange-400/30">
                                    <input type="checkbox" name="addons[]" value="{{ $addon->id }}"
                                        class="mt-1 size-5 shrink-0 rounded-md border-stone-300 text-orange-600 focus:ring-orange-500/30"
                                        @checked(in_array($addon->id, (array) old('addons', []), false)) />
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-base font-bold text-stone-900">{{ $addon->name }}</span>
                                        <span class="mt-1 block text-sm font-semibold text-orange-600">+
                                            {{ number_format($addon->price_cents, 0, ',', ' ') }} {{ $addon->currency }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Participants --}}
                <section
                    class="rounded-[1.75rem] border border-stone-100 bg-white p-8 shadow-[0_20px_50px_-20px_rgba(28,25,23,0.1)] sm:p-10 sm:rounded-[2rem] overflow-hidden">
                    <div class="mb-8 flex flex-col gap-4 border-b border-stone-100 pb-8 sm:flex-row sm:items-center sm:gap-5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 text-xl shadow-inner"
                            aria-hidden="true">👥</span>
                        <div class="flex-1">
                            <h2 class="text-xl font-extrabold text-stone-900 sm:text-2xl">Participants</h2>
                            <p class="mt-1.5 text-base text-stone-500">Une fiche par billet — ajoutez ou retirez des participants selon votre besoin.</p>
                        </div>
                        <button
                            id="add-attendee-btn"
                            type="button"
                            class="btn-primary inline-flex items-center justify-center px-6 py-3 text-sm font-bold shadow-sm sm:ml-auto"
                        >
                            + Ajouter un participant
                        </button>
                    </div>

                    @php
$attendees = $initialAttendees;
                    @endphp
                    <div id="attendeesContainer" class="space-y-4 lg:space-y-5">
                        @foreach ($attendees as $i => $a)
                            @php
    $firstName = $a['first_name'] ?? '';
    $lastName = $a['last_name'] ?? '';
    $email = $a['email'] ?? '';
    $summaryName = trim($firstName . ' ' . $lastName);
                            @endphp
                            <div class="attendeeRow rounded-2xl border border-stone-100 bg-stone-50/40 p-5 shadow-sm transition"
                                data-attendee-index="{{ $i }}">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <span
                                            class="attendeeBadge flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-sm font-black text-white shadow-md">
                                            {{ $i + 1 }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-extrabold text-stone-900">Participant {{ $i + 1 }}</p>
                                            <p class="mt-1 text-xs text-stone-500">
                                                <span class="attendee-summary-name">{{ $summaryName ?: 'À compléter' }}</span>
                                                @if (!empty($email))
                                                    <span class="attendee-summary-email text-stone-400"> — {{ $email }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            class="edit-attendee-btn inline-flex items-center justify-center rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-bold text-stone-600 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-800"
                                            data-attendee-index="{{ $i }}">
                                            Modifier
                                        </button>
                                        <button type="button"
                                            class="remove-attendee-btn inline-flex items-center justify-center rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-bold text-stone-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700"
                                            data-attendee-index="{{ $i }}" @if ($i === 0) hidden @endif >
                                            Retirer
                                        </button>
                                    </div>
                                </div>

                                {{-- Champs cachés envoyés au backend --}}
                                <div class="hidden">
                                    <input type="hidden" data-attendee-field="first_name"
                                        name="attendees[{{ $i }}][first_name]" value="{{ $firstName }}">
                                    <input type="hidden" data-attendee-field="last_name"
                                        name="attendees[{{ $i }}][last_name]" value="{{ $lastName }}">
                                    <input type="hidden" data-attendee-field="email"
                                        name="attendees[{{ $i }}][email]" value="{{ $email }}">
                                    <input type="hidden" data-attendee-field="phone"
                                        name="attendees[{{ $i }}][phone]" value="{{ $a['phone'] ?? '' }}">
                                    <input type="hidden" data-attendee-field="birthdate"
                                        name="attendees[{{ $i }}][birthdate]" value="{{ $a['birthdate'] ?? '' }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Modal participant (UI uniquement) --}}
                <div id="attendee-modal"
                    class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6 overflow-y-auto">
                    <div class="absolute inset-0 z-0 attendee-modal-backdrop backdrop-blur-sm transition-opacity"
                        data-modal-backdrop style="background-color: rgba(0, 0, 0, 0.6);"></div>

                    <div
                        class="relative z-10 w-full max-w-lg max-h-[calc(100vh-3rem)] overflow-y-auto rounded-[2rem] bg-white p-6 shadow-premium transition-transform opacity-0 scale-95"
                        role="dialog" aria-modal="true" aria-labelledby="attendee-modal-title">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 id="attendee-modal-title" class="text-xl font-extrabold text-stone-900">Ajouter un participant</h3>
                                <p class="mt-1 text-sm text-stone-500">Un ticket = un participant. Remplissez les informations ci-dessous.</p>
                            </div>
                            <button type="button" id="attendee-modal-close"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-stone-200 bg-white text-stone-600 hover:border-orange-200 hover:text-orange-800">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="mt-6 space-y-5">
                            <input type="hidden" id="attendee-modal-index" value="">

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="modal-first_name" class="block text-sm font-semibold text-stone-700">Prénom <span class="text-red-500">*</span></label>
                                    <input id="modal-first_name" type="text"
                                        class="form-input text-base" placeholder="Prénom" />
                                </div>
                                <div class="space-y-2">
                                    <label for="modal-last_name" class="block text-sm font-semibold text-stone-700">Nom <span class="text-red-500">*</span></label>
                                    <input id="modal-last_name" type="text"
                                        class="form-input text-base" placeholder="Nom" />
                                </div>
                            </div>

                            <div class="space-y-4 sm:flex sm:items-start sm:gap-6">
                                <div class="w-full space-y-2">
                                    <label for="modal-email" class="block text-sm font-semibold text-stone-700">Email <span class="text-red-500">*</span></label>
                                    <input id="modal-email" type="email"
                                        class="form-input text-base" placeholder="email@exemple.com" />
                                </div>
                                <div class="w-full space-y-2 sm:max-w-[12rem]">
                                    <label for="modal-phone" class="block text-sm font-semibold text-stone-700">Téléphone <span class="text-stone-400 font-normal">(optionnel)</span></label>
                                    <input id="modal-phone" type="tel"
                                        class="form-input text-base" placeholder="+33…" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="modal-birthdate" class="block text-sm font-semibold text-stone-700">Date de naissance <span class="text-red-500">*</span></label>
                                <input id="modal-birthdate" type="date"
                                    class="form-input text-base" />
                            </div>
                        </div>

                        <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button" id="attendee-modal-cancel"
                                class="btn-secondary w-full sm:w-auto px-10 py-3.5 text-base font-bold">
                                Annuler
                            </button>
                            <button type="button" id="attendee-modal-save"
                                class="btn-primary w-full sm:w-auto px-10 py-3.5 text-base font-bold shadow-lg">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CGV --}}
                <section
                    class="p-8 sm:p-10">
                    <label class="flex cursor-pointer gap-5">
                        <input type="checkbox" name="agree_terms" value="1" required
                            class="mt-1 size-5 shrink-0 rounded-md border-stone-300 text-orange-600 focus:ring-orange-500/30"
                            @checked(old('agree_terms')) />
                        <span class="text-base font-medium leading-relaxed text-stone-700">
                            J’accepte les <strong class="text-stone-900">conditions générales de vente</strong> et certifie
                            l’exactitude des informations.
                        </span>
                    </label>
                </section>
                    </div>

                    <div class="lg:col-span-5 lg:sticky lg:top-28 space-y-6">

                {{-- Tarif (desktop sticky) --}}
                <section
                    class="rounded-[1.75rem] border border-stone-100 bg-white p-8 shadow-[0_20px_50px_-20px_rgba(28,25,23,0.1)] sm:p-10 sm:rounded-[2rem]">
                    <div class="mb-6 flex items-center gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-amber-50 text-xl shadow-inner"
                            aria-hidden="true">🎫</span>
                        <div>
                            <h2 class="text-xl font-extrabold text-stone-900 sm:text-2xl">Tarif</h2>
                            <p class="mt-1.5 text-base text-stone-500">Appliqué à votre réservation</p>
                        </div>
                    </div>

                    <div
                        class="relative overflow-hidden rounded-2xl border border-orange-200/60 bg-gradient-to-br from-orange-50/90 via-amber-50/40 to-white p-6 shadow-inner">
                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-orange-400/15 blur-2xl pointer-events-none"></div>
                        <div class="relative flex flex-col gap-4 lg:items-start lg:justify-between">
                            <div class="max-w-xl">
                                <span
                                    class="mb-3 inline-block rounded-full bg-orange-600 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-white shadow-sm">Tarif actif</span>
                                <p class="text-2xl font-black text-stone-900 sm:text-3xl">{{ $activeTicketType->name }}</p>
                            </div>
                            <div class="shrink-0 text-left lg:text-left">
                                <p class="text-4xl font-black tabular-nums text-orange-600 sm:text-5xl">
                                    {{ number_format($activeTicketType->price_cents, 0, ',', ' ') }}
                                    <span class="text-xl font-bold text-orange-700/90 sm:text-2xl">{{ $activeTicketType->currency }}</span>
                                </p>
                                <p class="mt-2 text-sm font-semibold uppercase tracking-wide text-stone-500">par billet</p>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="mt-6 rounded-2xl border border-orange-200/60 bg-orange-50/60 p-5">
                        <p class="text-sm font-semibold text-stone-700">
                            {{ 'X participant(s) = X billet(s)' }}
                        </p>
                        <p class="mt-2 text-sm text-stone-600">
                            <span id="participant-count-display-sticky">{{ count($initialAttendees) }}</span> participant(s) =
                            <span id="ticket-count-display-sticky">{{ count($initialAttendees) }}</span> billet(s)
                        </p>
                    </div> -->
                </section>

                {{-- Récap --}}
                <section
                    class="overflow-hidden rounded-[1.75rem] border border-orange-200/50 bg-gradient-to-br from-orange-50/90 via-white to-amber-50/30 p-8 sm:p-10 sm:rounded-[2rem]">
                    <div>
                        <h2 class="mb-8 flex items-center gap-3 text-xl font-extrabold text-stone-900 sm:text-2xl">
                            <span aria-hidden="true" class="text-2xl">📋</span> Récapitulatif
                        </h2>
                        <div class="space-y-4 border-b border-orange-200/40 pb-6">
                            <div class="flex items-start justify-between gap-6">
                                <div>
                                    <p class="text-lg font-bold text-stone-900">{{ $activeTicketType->name }}</p>
                                    <p class="mt-2 text-base text-stone-500">
                                        <span id="quantity-display">1</span> billet<span id="quantity-plural"
                                            class="hidden">s</span>
                                    </p>
                                </div>
                                <p class="shrink-0 text-lg font-bold tabular-nums text-stone-900">
                                    <span id="subtotal-display">{{ number_format($activeTicketType->price_cents, 0, ',', ' ') }}</span>
                                    {{ $activeTicketType->currency }}
                                </p>
                            </div>
                            <div id="addons-summary" class="hidden space-y-3"></div>
                        </div>
                        <div class="mt-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <span class="text-xl font-extrabold text-stone-900">Total</span>
                            <span id="total-display" class="text-3xl font-black tabular-nums text-orange-600 sm:text-4xl">
                                {{ number_format($activeTicketType->price_cents, 0, ',', ' ') }}
                                <span class="text-xl font-bold text-orange-700/90">{{ $activeTicketType->currency }}</span>
                            </span>
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="flex flex-col-reverse gap-4 pt-8 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
                        <a href="{{ route('public.events.show', $event) }}"
                            class="btn-secondary inline-flex w-full sm:w-auto items-center justify-center px-6 py-3 text-center text-base font-bold">Annuler</a>
                        <button type="submit"
                            class="btn-primary inline-flex w-full sm:w-auto items-center justify-center px-6 py-3 text-sm font-bold shadow-sm sm:ml-auto">
                            Confirmer
                        </button>
                    </div>
                </section>

                    </div>
                </div>
            </form>
        </div>

        <script>
            (function () {
                const quantityInput = document.getElementById('quantity');
                const container = document.getElementById('attendeesContainer');
                const addAttendeeBtn = document.getElementById('add-attendee-btn');

                const totalDisplay = document.getElementById('total-display');
                const subtotalDisplay = document.getElementById('subtotal-display');

                const quantityDisplay = document.getElementById('quantity-display');
                const quantityPlural = document.getElementById('quantity-plural');

                const participantCountDisplay = document.getElementById('participant-count-display');
                const ticketCountDisplay = document.getElementById('ticket-count-display');
                const participantCountDisplaySticky = document.getElementById('participant-count-display-sticky');
                const ticketCountDisplaySticky = document.getElementById('ticket-count-display-sticky');

                const addonsSummary = document.getElementById('addons-summary');

                const unitPrice = {{ $activeTicketType->price_cents }};
                const currency = @json($activeTicketType->currency);
                const addons = @json($event->addons->map(fn($addon) => ['id' => $addon->id, 'name' => $addon->name, 'price' => $addon->price_cents]));

                const customerEmailInput = document.getElementById('customer_email');
                const customerPhoneInput = document.getElementById('customer_phone');

                const maxParticipants = 10;

                // Modal
                const modal = document.getElementById('attendee-modal');
                const modalBackdrop = modal ? modal.querySelector('[data-modal-backdrop]') : null;
                const closeBtn = document.getElementById('attendee-modal-close');
                const cancelBtn = document.getElementById('attendee-modal-cancel');
                const saveBtn = document.getElementById('attendee-modal-save');
                const modalTitle = document.getElementById('attendee-modal-title');
                const modalIndexInput = document.getElementById('attendee-modal-index');

                const modalFirstName = document.getElementById('modal-first_name');
                const modalLastName = document.getElementById('modal-last_name');
                const modalEmail = document.getElementById('modal-email');
                const modalPhone = document.getElementById('modal-phone');
                const modalBirthdate = document.getElementById('modal-birthdate');

                let activeRow = null;

                function formatPrice(price) {
                    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                }

                function getRows() {
                    return container ? Array.from(container.querySelectorAll('.attendeeRow')) : [];
                }

                function getParticipantCount() {
                    const count = getRows().length;
                    return Math.max(1, Math.min(maxParticipants, count));
                }

                function updateQuantityAndBadges() {
                    const count = getParticipantCount();
                    if (quantityInput) quantityInput.value = count;

                    if (quantityDisplay) quantityDisplay.textContent = count;
                    if (quantityPlural) quantityPlural.classList.toggle('hidden', count <= 1);

                    if (participantCountDisplay) participantCountDisplay.textContent = count;
                    if (ticketCountDisplay) ticketCountDisplay.textContent = count;
                    if (participantCountDisplaySticky) participantCountDisplaySticky.textContent = count;
                    if (ticketCountDisplaySticky) ticketCountDisplaySticky.textContent = count;

                    if (addAttendeeBtn) {
                        const disabled = count >= maxParticipants;
                        addAttendeeBtn.disabled = disabled;
                        addAttendeeBtn.classList.toggle('opacity-50', disabled);
                        addAttendeeBtn.classList.toggle('cursor-not-allowed', disabled);
                    }
                }

                function getRowField(row, field) {
                    if (!row) return '';
                    const input = row.querySelector(`input[type="hidden"][data-attendee-field="${field}"]`);
                    return input ? input.value.trim() : '';
                }

                function setRowField(row, field, value) {
                    if (!row) return;
                    const input = row.querySelector(`input[type="hidden"][data-attendee-field="${field}"]`);
                    if (input) input.value = value || '';
                }

                function updateRowSummary(row) {
                    const first = getRowField(row, 'first_name');
                    const last = getRowField(row, 'last_name');
                    const email = getRowField(row, 'email');
                    const summary = (first + ' ' + last).trim() || 'À compléter';

                    const nameEl = row.querySelector('.attendee-summary-name');
                    if (nameEl) nameEl.textContent = summary;

                    const pEl = row.querySelector('p.text-xs.text-stone-500');
                    if (!pEl) return;
                    let emailSpan = row.querySelector('.attendee-summary-email');

                    if (email) {
                        if (!emailSpan) {
                            emailSpan = document.createElement('span');
                            emailSpan.className = 'attendee-summary-email text-stone-400';
                            pEl.appendChild(emailSpan);
                        }
                        emailSpan.textContent = ` — ${email}`;
                    } else if (emailSpan) {
                        emailSpan.remove();
                    }
                }

                function syncContactToParticipants() {
                    if (!customerEmailInput && !customerPhoneInput) return;
                    const contactEmail = customerEmailInput ? customerEmailInput.value.trim() : '';
                    const contactPhone = customerPhoneInput ? customerPhoneInput.value.trim() : '';

                    getRows().forEach(function (row) {
                        const email = getRowField(row, 'email');
                        const phone = getRowField(row, 'phone');
                        if (!email && contactEmail) setRowField(row, 'email', contactEmail);
                        if (!phone && contactPhone) setRowField(row, 'phone', contactPhone);
                        updateRowSummary(row);
                    });
                }

                function updateTotal() {
                    const quantity = getParticipantCount();
                    const subtotal = unitPrice * quantity;

                    const selectedAddons = Array.from(document.querySelectorAll('input[name="addons[]"]:checked'))
                        .map(cb => parseInt(cb.value, 10));
                    let addonsTotal = 0;
                    if (addonsSummary) addonsSummary.innerHTML = '';

                    if (addonsSummary) {
                        if (selectedAddons.length > 0) {
                            selectedAddons.forEach(function (addonId) {
                                const addon = addons.find(a => a.id === addonId);
                                if (addon) {
                                    addonsTotal += addon.price;
                                    const row = document.createElement('div');
                                    row.className = 'flex items-center justify-between gap-4 text-sm sm:text-base';
                                    row.innerHTML =
                                        `<span class="font-semibold text-stone-700">${addon.name}</span><span class="font-bold tabular-nums text-stone-900">${formatPrice(addon.price)} ${currency}</span>`;
                                    addonsSummary.appendChild(row);
                                }
                            });
                            addonsSummary.classList.remove('hidden');
                        } else {
                            addonsSummary.classList.add('hidden');
                        }
                    }

                    const total = subtotal + addonsTotal;
                    if (subtotalDisplay) subtotalDisplay.textContent = formatPrice(subtotal);
                    if (totalDisplay) totalDisplay.innerHTML =
                        `${formatPrice(total)} <span class="text-xl font-bold text-orange-700/90">${currency}</span>`;
                }

                function renumberParticipants() {
                    getRows().forEach(function (row, index) {
                        row.dataset.attendeeIndex = String(index);

                        const badge = row.querySelector('.attendeeBadge');
                        if (badge) badge.textContent = String(index + 1);

                        const editBtn = row.querySelector('.edit-attendee-btn');
                        if (editBtn) editBtn.dataset.attendeeIndex = String(index);

                        const removeBtn = row.querySelector('.remove-attendee-btn');
                        if (removeBtn) {
                            removeBtn.dataset.attendeeIndex = String(index);
                            removeBtn.hidden = index === 0;
                        }

                        row.querySelectorAll('input[type="hidden"][data-attendee-field]').forEach(function (input) {
                            const field = input.dataset.attendeeField;
                            input.name = `attendees[${index}][${field}]`;
                        });

                        updateRowSummary(row);
                    });

                    updateQuantityAndBadges();
                    updateTotal();
                    syncContactToParticipants();
                }

                function openModal({ row = null, title = '' } = {}) {
                    activeRow = row;
                    if (modalTitle) modalTitle.textContent = title;

                    modalIndexInput.value = row ? row.dataset.attendeeIndex : '';

                    const first = row ? getRowField(row, 'first_name') : '';
                    const last = row ? getRowField(row, 'last_name') : '';
                    const email = row ? getRowField(row, 'email') : '';
                    const phone = row ? getRowField(row, 'phone') : '';
                    const birthdate = row ? getRowField(row, 'birthdate') : '';

                    modalFirstName.value = first;
                    modalLastName.value = last;
                    modalEmail.value = email || (customerEmailInput ? customerEmailInput.value.trim() : '');
                    modalPhone.value = phone || (customerPhoneInput ? customerPhoneInput.value.trim() : '');
                    modalBirthdate.value = birthdate;

                    if (!modal) return;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    const dialog = modal.querySelector('div[role="dialog"]');
                    if (dialog) {
                        dialog.classList.remove('opacity-0', 'scale-95');
                        dialog.classList.add('opacity-100', 'scale-100');
                    }
                }

                function closeModal() {
                    if (!modal) return;
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');

                    const dialog = modal.querySelector('div[role="dialog"]');
                    if (dialog) {
                        dialog.classList.remove('opacity-100', 'scale-100');
                        dialog.classList.add('opacity-0', 'scale-95');
                    }
                }

                function validateModal() {
                    const errors = [];
                    if (!modalFirstName.value.trim()) errors.push(modalFirstName);
                    if (!modalLastName.value.trim()) errors.push(modalLastName);
                    if (!modalEmail.value.trim()) errors.push(modalEmail);
                    if (!modalBirthdate.value.trim()) errors.push(modalBirthdate);

                    errors.forEach(function (el) {
                        el.classList.add('ring-4', 'ring-red-500/20', 'border-red-300');
                    });
                    return errors;
                }

                function clearModalErrors() {
                    [modalFirstName, modalLastName, modalEmail, modalBirthdate].forEach(function (el) {
                        if (!el) return;
                        el.classList.remove('ring-4', 'ring-red-500/20', 'border-red-300');
                    });
                }

                function attendeeRowTemplate(index, data) {
                    const first = data.first_name || '';
                    const last = data.last_name || '';
                    const email = data.email || '';
                    const phone = data.phone || '';
                    const birthdate = data.birthdate || '';
                    const summary = (first + ' ' + last).trim() || 'À compléter';

                    return `
                        <div class="attendeeRow rounded-2xl border border-stone-100 bg-stone-50/40 p-5 shadow-sm transition"
                            data-attendee-index="${index}">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <span class="attendeeBadge flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-sm font-black text-white shadow-md">${index + 1}</span>
                                    <div>
                                        <p class="text-sm font-extrabold text-stone-900">Participant ${index + 1}</p>
                                        <p class="mt-1 text-xs text-stone-500">
                                            <span class="attendee-summary-name">${summary}</span>
                                        ${email ? `<span class="attendee-summary-email text-stone-400"> — ${email}</span>` : ''}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        class="edit-attendee-btn inline-flex items-center justify-center rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-bold text-stone-600 transition hover:border-orange-200 hover:bg-orange-50 hover:text-orange-800"
                                        data-attendee-index="${index}">
                                        Modifier
                                    </button>
                                    <button type="button"
                                        class="remove-attendee-btn inline-flex items-center justify-center rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-bold text-stone-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700"
                                        data-attendee-index="${index}" ${index === 0 ? 'hidden' : ''}>
                                        Retirer
                                    </button>
                                </div>
                            </div>
                            <div class="hidden">
                                <input type="hidden" data-attendee-field="first_name" name="attendees[${index}][first_name]" value="${first}">
                                <input type="hidden" data-attendee-field="last_name" name="attendees[${index}][last_name]" value="${last}">
                                <input type="hidden" data-attendee-field="email" name="attendees[${index}][email]" value="${email}">
                                <input type="hidden" data-attendee-field="phone" name="attendees[${index}][phone]" value="${phone}">
                                <input type="hidden" data-attendee-field="birthdate" name="attendees[${index}][birthdate]" value="${birthdate}">
                            </div>
                        </div>
                    `;
                }

                function addParticipantFromModal() {
                    const count = getRows().length;
                    if (count >= maxParticipants) return;

                    const index = count;
                    const row = document.createElement('div');
                    row.innerHTML = attendeeRowTemplate(index, {
                        first_name: modalFirstName.value.trim(),
                        last_name: modalLastName.value.trim(),
                        email: modalEmail.value.trim(),
                        phone: modalPhone.value.trim(),
                        birthdate: modalBirthdate.value.trim(),
                    }).trim();

                    const firstEl = row.firstElementChild;
                    if (firstEl) {
                        firstEl.classList.add('opacity-0', 'translate-y-2', 'transition-all', 'duration-200');
                        container.appendChild(firstEl);
                        requestAnimationFrame(function () {
                            firstEl.classList.remove('opacity-0', 'translate-y-2');
                        });
                    }

                    renumberParticipants();
                }

                function saveModal() {
                    if (!container) return;
                    clearModalErrors();

                    const errors = validateModal();
                    if (errors.length > 0) {
                        errors[0].focus();
                        return;
                    }

                    const payload = {
                        first_name: modalFirstName.value.trim(),
                        last_name: modalLastName.value.trim(),
                        email: modalEmail.value.trim(),
                        phone: modalPhone.value.trim(),
                        birthdate: modalBirthdate.value.trim(),
                    };

                    if (activeRow) {
                        setRowField(activeRow, 'first_name', payload.first_name);
                        setRowField(activeRow, 'last_name', payload.last_name);
                        setRowField(activeRow, 'email', payload.email);
                        setRowField(activeRow, 'phone', payload.phone);
                        setRowField(activeRow, 'birthdate', payload.birthdate);
                        updateRowSummary(activeRow);
                        renumberParticipants();
                    } else {
                        addParticipantFromModal();
                    }

                    closeModal();
                }

                if (addAttendeeBtn) {
                    addAttendeeBtn.addEventListener('click', function () {
                        openModal({ title: 'Ajouter un participant' });
                    });
                }

                if (modalBackdrop) {
                    modalBackdrop.addEventListener('click', closeModal);
                }
                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
                if (saveBtn) saveBtn.addEventListener('click', saveModal);

                container.addEventListener('click', function (event) {
                    const editBtn = event.target.closest('.edit-attendee-btn');
                    const removeBtn = event.target.closest('.remove-attendee-btn');

                    if (editBtn) {
                        const idx = editBtn.dataset.attendeeIndex;
                        const row = getRows().find(r => String(r.dataset.attendeeIndex) === String(idx));
                        if (row) openModal({ row, title: 'Modifier le participant' });
                        return;
                    }

                    if (removeBtn) {
                        const idx = parseInt(removeBtn.dataset.attendeeIndex || '0', 10);
                        const row = getRows()[idx];
                        if (!row || idx === 0) return;

                        row.classList.add('opacity-0', '-translate-y-2', 'transition-all', 'duration-200');
                        window.setTimeout(function () {
                            row.remove();
                            renumberParticipants();
                        }, 180);
                    }
                });

                document.querySelectorAll('input[name="addons[]"]').forEach(function (cb) {
                    cb.addEventListener('change', updateTotal);
                });
                if (customerEmailInput) customerEmailInput.addEventListener('input', syncContactToParticipants);
                if (customerPhoneInput) customerPhoneInput.addEventListener('input', syncContactToParticipants);

                // Init
                syncContactToParticipants();
                getRows().forEach(updateRowSummary);
                updateQuantityAndBadges();
                updateTotal();
                renumberParticipants();
            })();
        </script>
@endsection
