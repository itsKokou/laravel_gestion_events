@extends('layouts.app')

@section('title', "Soirées à venir · Win's Events")

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 750;">Soirées à venir</div>
                <div class="muted">Réservez votre place en quelques clics.</div>
            </div>
            <form method="GET" action="{{ route('public.events.index') }}" style="min-width: min(420px, 100%);">
                <label for="q">Recherche</label>
                <div style="display:flex; gap:10px;">
                    <input id="q" name="q" value="{{ $q }}" placeholder="Nom, lieu, thème…" />
                    <button class="btn" type="submit">OK</button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
        @forelse ($events as $event)
            <a href="{{ route('public.events.show', $event) }}" style="text-decoration:none;">
                <div class="card">
                    <div style="font-weight: 750; font-size: 16px;">{{ $event->name }}</div>
                    <div class="muted" style="margin-top: 6px;">
                        {{ $event->starts_at->format('d/m/Y H:i') }} · {{ $event->venue_name }}
                    </div>
                    @if ($event->theme)
                        <div class="muted" style="margin-top: 6px;">{{ $event->theme }}</div>
                    @endif
                    <div style="margin-top: 10px;">
                        <span class="btn secondary">Voir le détail</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="card">Aucune soirée pour le moment.</div>
        @endforelse
    </div>

    <div style="margin-top: 16px;">
        {{ $events->links() }}
    </div>
@endsection

