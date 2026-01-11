@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', $event->name . " · Win's Events")

@section('content')
    <!-- Bouton retour -->
    <div style="margin-bottom: 24px;">
        <a href="{{ route('public.events.index') }}" class="btn secondary"
            style="padding: 10px 16px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
            ← Retour aux soirées
        </a>
    </div>

    <!-- Hero avec image -->
    @if($event->hero_image_path)
        <div
            style="width: 100%; height: 400px; border-radius: 16px; overflow: hidden; margin-bottom: 32px; position: relative; box-shadow: 0 10px 30px rgba(15,23,42,0.15);">
            <img src="{{ Storage::url($event->hero_image_path) }}" alt="{{ $event->name }}"
                style="width: 100%; height: 100%; object-fit: cover;" />
            <div
                style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); padding: 40px 32px 32px;">
                <h1
                    style="font-size: 42px; font-weight: 900; color: #fff; margin-bottom: 12px; letter-spacing: -0.5px; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                    {{ $event->name }}</h1>
                @if($event->theme)
                    <div style="font-size: 18px; color: rgba(255,255,255,0.9); font-weight: 600;">{{ $event->theme }}</div>
                @endif
            </div>
        </div>
    @else
        <div style="margin-bottom: 32px;">
            <h1 style="font-size: 42px; font-weight: 900; margin-bottom: 12px; letter-spacing: -0.5px;">{{ $event->name }}</h1>
            @if($event->theme)
                <div style="font-size: 18px; color: var(--we-muted); font-weight: 600;">{{ $event->theme }}</div>
            @endif
        </div>
    @endif

    <div class="grid grid2" style="gap: 32px; margin-bottom: 32px;">
        <!-- Colonne principale -->
        <div style="flex: 1;">
            <!-- Informations principales -->
            <div class="card" style="margin-bottom: 24px; padding: 32px;">
                <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.3px;">Informations
                </h2>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div
                            style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            📅</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 6px;">Date
                                et horaires</div>
                            <div style="font-size: 15px; color: var(--we-text); margin-bottom: 4px;">
                                {{ optional($event->starts_at)->format('d/m/Y') }}
                            </div>
                            <div style="font-size: 14px; color: var(--we-muted);">
                                De {{ optional($event->starts_at)->format('H:i') }} à
                                {{ optional($event->ends_at)->format('H:i') }}
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div
                            style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(96, 165, 250, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            📍</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 6px;">Lieu
                            </div>
                            <div style="font-size: 15px; color: var(--we-text); margin-bottom: 4px; font-weight: 600;">
                                {{ $event->venue_name }}
                            </div>
                            <div style="font-size: 14px; color: var(--we-muted);">
                                {{ $event->venue_address }}
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div
                            style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(148, 163, 184, 0.15), rgba(203, 213, 225, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            ℹ️</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 6px;">
                                Informations pratiques</div>
                            <div style="font-size: 14px; color: var(--we-text);">
                                <div style="margin-bottom: 4px;">Âge minimum : <strong>{{ $event->min_age }} ans</strong>
                                </div>
                                <div style="color: var(--we-muted);">Capacité : {{ $event->capacity }} places</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($event->description)
                <div class="card" style="margin-bottom: 24px; padding: 32px;">
                    <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 16px; letter-spacing: -0.3px;">À propos</h2>
                    <div style="font-size: 16px; line-height: 1.7; color: var(--we-text); white-space: pre-line;">
                        {{ $event->description }}</div>
                </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div style="flex: 0 0 380px;">
            <!-- CTA Réservation -->
            <div class="card"
                style="margin-bottom: 24px; padding: 32px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border: 2px solid rgba(234, 88, 12, 0.2);">
                <div style="text-align: center; margin-bottom: 24px;">
                    <div style="font-size: 32px; margin-bottom: 12px;">🎫</div>
                    <div style="font-weight: 800; font-size: 20px; color: var(--we-text); margin-bottom: 8px;">Réservez
                        votre place</div>
                    <div style="font-size: 14px; color: var(--we-muted);">Ne manquez pas cet événement exceptionnel</div>
                </div>
                <a href="{{ route('public.reservations.create', $event) }}" class="btn"
                    style="width: 100%; padding: 16px; font-size: 16px; justify-content: center;">
                    Réserver maintenant
                </a>
            </div>

            <!-- Tarifs -->
            @if($event->ticketTypes->count() > 0)
                <div class="card" style="margin-bottom: 24px; padding: 24px;">
                    <h3 style="font-size: 20px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.3px;">Tarifs</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach ($event->ticketTypes as $type)
                            <div
                                style="padding: 16px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
                                <div
                                    style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 8px;">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 4px;">
                                            {{ $type->name }}</div>
                                        @if($type->description)
                                            <div style="font-size: 13px; color: var(--we-muted);">{{ $type->description }}</div>
                                        @endif
                                    </div>
                                    <div style="font-weight: 800; font-size: 20px; color: var(--we-primary); white-space: nowrap;">
                                        {{ number_format($type->price_cents, 0, ',', ' ') }} {{ $type->currency }}
                                    </div>
                                </div>
                                @if($type->quantity_limit)
                                    <div style="font-size: 12px; color: var(--we-muted);">
                                        Limité à {{ $type->quantity_limit }} places
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Options -->
            @if($event->addons->count() > 0)
                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 20px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.3px;">Options
                        supplémentaires</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach ($event->addons as $addon)
                            <div
                                style="padding: 16px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 700; font-size: 15px; color: var(--we-text); margin-bottom: 4px;">
                                            {{ $addon->name }}</div>
                                        @if($addon->description)
                                            <div style="font-size: 13px; color: var(--we-muted);">{{ $addon->description }}</div>
                                        @endif
                                    </div>
                                    <div style="font-weight: 800; font-size: 18px; color: var(--we-primary); white-space: nowrap;">
                                        {{ number_format($addon->price_cents, 0, ',', ' ') }} {{ $addon->currency }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection