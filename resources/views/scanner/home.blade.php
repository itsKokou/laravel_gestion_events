@extends('layouts.app')

@section('title', 'Scanner · Win’s Events')

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="font-size: 22px; font-weight: 850;">Scanner</div>
        <div class="muted">Sélectionnez une soirée pour démarrer le contrôle d’accès.</div>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
        @forelse ($events as $event)
            <a href="{{ route('scanner.event', $event) }}" style="text-decoration:none;">
                <div class="card">
                    <div style="font-weight: 800;">{{ $event->name }}</div>
                    <div class="muted" style="margin-top: 6px;">
                        {{ $event->starts_at->format('d/m/Y H:i') }} · {{ $event->venue_name }}
                    </div>
                    <div style="margin-top: 10px;">
                        <span class="btn">Ouvrir</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="card">Aucune soirée publiée.</div>
        @endforelse
    </div>
@endsection

