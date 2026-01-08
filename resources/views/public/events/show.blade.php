@extends('layouts.app')

@section('title', $event->name . " · Win's Events")

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 24px; font-weight: 800;">{{ $event->name }}</div>
                <div class="muted" style="margin-top: 6px;">
                    {{ $event->starts_at->format('d/m/Y H:i') }} → {{ $event->ends_at->format('d/m/Y H:i') }}
                </div>
                <div class="muted" style="margin-top: 6px;">
                    {{ $event->venue_name }} · {{ $event->venue_address }}
                </div>
                <div class="muted" style="margin-top: 6px;">
                    Âge minimum : {{ $event->min_age }} ans · Capacité : {{ $event->capacity }} places
                </div>
            </div>

            <div style="display:flex; align-items:flex-start; gap:10px;">
                <a class="btn" href="{{ route('public.reservations.create', $event) }}">Réserver</a>
                <a class="btn secondary" href="{{ route('public.events.index') }}">Retour</a>
            </div>
        </div>

        @if ($event->description)
            <div style="margin-top: 14px; line-height: 1.55;" class="muted">{{ $event->description }}</div>
        @endif
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
        <div class="card">
            <div style="font-weight: 800; margin-bottom: 10px;">Tarifs billets</div>
            @forelse ($event->ticketTypes as $type)
                <div style="display:flex; justify-content:space-between; gap:10px; padding: 10px 0; border-top: 1px solid rgba(255,255,255,0.10);">
                    <div>
                        <div style="font-weight:700;">{{ $type->name }}</div>
                        <div class="muted" style="font-size: 13px;">{{ $type->code }}</div>
                    </div>
                    <div style="font-weight:800;">{{ number_format($type->price_cents / 100, 2, ',', ' ') }} {{ $type->currency }}</div>
                </div>
            @empty
                <div class="muted">Aucun tarif configuré.</div>
            @endforelse
        </div>

        <div class="card">
            <div style="font-weight: 800; margin-bottom: 10px;">Options</div>
            @forelse ($event->addons as $addon)
                <div style="display:flex; justify-content:space-between; gap:10px; padding: 10px 0; border-top: 1px solid rgba(255,255,255,0.10);">
                    <div>
                        <div style="font-weight:700;">{{ $addon->name }}</div>
                        <div class="muted" style="font-size: 13px;">{{ $addon->code }}</div>
                    </div>
                    <div style="font-weight:800;">{{ number_format($addon->price_cents / 100, 2, ',', ' ') }} {{ $addon->currency }}</div>
                </div>
            @empty
                <div class="muted">Aucune option disponible.</div>
            @endforelse
        </div>
    </div>
@endsection

