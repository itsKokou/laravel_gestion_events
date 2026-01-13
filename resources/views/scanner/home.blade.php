@extends('layouts.app')

@section('title', 'Scanner · Win\'s Events')

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Scanner</div>
        <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 12px; letter-spacing: -0.5px;">
            Contrôle d'accès
        </h1>
        <p class="muted" style="font-size: 16px;">
            Sélectionnez une soirée pour démarrer le scanner de billets
        </p>
    </div>

    @if($events->isEmpty())
        <div class="card" style="padding: 60px 40px; text-align: center;">
            <div style="font-size: 64px; margin-bottom: 16px;">📱</div>
            <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 12px; color: #1f1b18;">
                Aucune soirée disponible
            </h2>
            <p class="muted" style="font-size: 16px;">
                Il n'y a actuellement aucune soirée publiée à scanner.
            </p>
        </div>
    @else
        <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            @foreach ($events as $event)
                <a href="{{ route('scanner.event', $event) }}" style="text-decoration: none; display: block;">
                    <div class="card" style="padding: 24px; transition: transform 120ms ease, box-shadow 120ms ease; cursor: pointer; height: 100%; display: flex; flex-direction: column;" 
                         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                        <!-- Header de la carte -->
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px;">
                            <div style="flex: 1;">
                                <div style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 8px; background: rgba(234, 88, 12, 0.1); margin-bottom: 12px;">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(234, 88, 12, 0.2); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 900; color: #ea580c;">
                                        EV
                                    </div>
                                    <span style="font-size: 11px; font-weight: 700; color: #ea580c; text-transform: uppercase; letter-spacing: 0.5px;">
                                        Événement
                                    </span>
                                </div>
                                <h3 style="font-size: 20px; font-weight: 900; color: #1f1b18; margin: 0 0 8px 0; line-height: 1.3;">
                                    {{ $event->name }}
                                </h3>
                            </div>
                        </div>

                        <!-- Informations de l'événement -->
                        <div style="flex: 1; margin-bottom: 16px;">
                            <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                                <div style="width: 20px; height: 20px; flex-shrink: 0; color: #8b7355; font-size: 16px; line-height: 20px;">📍</div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 600; color: #1f1b18; margin-bottom: 2px;">
                                        {{ $event->venue_name }}
                                    </div>
                                    <div style="font-size: 12px; color: #8b7355;">
                                        {{ $event->venue_address }}
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                                <div style="width: 20px; height: 20px; flex-shrink: 0; color: #8b7355; font-size: 16px; line-height: 20px;">📅</div>
                                <div style="flex: 1;">
                                    <div style="font-size: 13px; font-weight: 600; color: #1f1b18;">
                                        {{ $event->starts_at->format('d/m/Y à H:i') }}
                                    </div>
                                    <div style="font-size: 12px; color: #8b7355;">
                                        Jusqu'à {{ $event->ends_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques -->
                        <div style="padding: 16px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-radius: 12px; border: 1px solid rgba(234, 88, 12, 0.15); margin-bottom: 16px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <div style="font-size: 11px; font-weight: 700; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Présents
                                </div>
                                <div style="font-size: 18px; font-weight: 900; color: #ea580c;">
                                    {{ $event->present_count ?? 0 }}
                                </div>
                            </div>
                            @if($event->capacity)
                                <div style="height: 6px; background: rgba(234, 88, 12, 0.1); border-radius: 999px; overflow: hidden;">
                                    <div style="height: 100%; background: linear-gradient(90deg, #ea580c, rgba(245, 130, 32, 0.8)); border-radius: 999px; width: {{ $event->capacity > 0 ? min(100, ($event->present_count ?? 0) / $event->capacity * 100) : 0 }}%; transition: width 0.3s ease;"></div>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                                    <div style="font-size: 11px; color: #8b7355;">
                                        {{ $event->capacity > 0 ? round((($event->present_count ?? 0) / $event->capacity) * 100) : 0 }}% de capacité
                                    </div>
                                    <div style="font-size: 11px; color: #8b7355;">
                                        / {{ $event->capacity }} places
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Bouton d'action -->
                        <div style="margin-top: auto;">
                            <div class="btn" style="width: 100%; text-align: center; padding: 12px 20px; font-size: 14px;">
                                📱 Ouvrir le scanner
                            </div>
                    </div>
                </div>
            </a>
            @endforeach
    </div>
    @endif
@endsection
