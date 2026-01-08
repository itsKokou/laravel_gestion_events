@extends('layouts.app')

@section('title', 'Admin · Soirée')

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 900;">
                    {{ $event->exists ? 'Modifier la soirée' : 'Créer une soirée' }}
                </div>
                <div class="muted">Champs essentiels (MVP).</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="btn secondary" href="{{ route('admin.events.index') }}">Retour</a>
                @if ($event->exists)
                    <a class="btn secondary" href="{{ route('public.events.show', $event) }}" target="_blank">Voir public</a>
                @endif
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="margin-bottom: 14px;">
            <div style="font-weight: 800; margin-bottom: 8px;">Erreurs</div>
            <ul class="error" style="line-height:1.55;">
                @foreach ($errors->all() as $e)
                    <li>- {{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="card" method="POST"
        action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}">
        @csrf
        @if ($event->exists)
            @method('PUT')
        @endif

        <div class="grid grid2">
            <div>
                <label>Nom</label>
                <input name="name" value="{{ old('name', $event->name) }}" required />
            </div>
            <div>
                <label>Slug</label>
                <input name="slug" value="{{ old('slug', $event->slug ?: \Illuminate\Support\Str::slug($event->name ?: '')) }}" required />
                <div class="muted" style="font-size: 12px; margin-top: 6px;">Utilisé dans l’URL publique.</div>
            </div>
        </div>

        <div class="grid grid2" style="margin-top: 12px;">
            <div>
                <label>Début</label>
                <input type="datetime-local" name="starts_at"
                    value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\\TH:i')) }}" required />
            </div>
            <div>
                <label>Fin</label>
                <input type="datetime-local" name="ends_at"
                    value="{{ old('ends_at', optional($event->ends_at)->format('Y-m-d\\TH:i')) }}" required />
            </div>
        </div>

        <div class="grid grid2" style="margin-top: 12px;">
            <div>
                <label>Lieu</label>
                <input name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" required />
            </div>
            <div>
                <label>Adresse</label>
                <input name="venue_address" value="{{ old('venue_address', $event->venue_address) }}" required />
            </div>
        </div>

        <div class="grid grid2" style="margin-top: 12px;">
            <div>
                <label>Âge minimum</label>
                <input type="number" min="0" max="120" name="min_age" value="{{ old('min_age', $event->min_age ?? 18) }}" required />
            </div>
            <div>
                <label>Capacité</label>
                <input type="number" min="1" name="capacity" value="{{ old('capacity', $event->capacity ?? 100) }}" required />
            </div>
        </div>

        <div style="margin-top: 12px;">
            <label>Thème</label>
            <input name="theme" value="{{ old('theme', $event->theme) }}" />
        </div>

        <div style="margin-top: 12px;">
            <label>Description</label>
            <textarea name="description" rows="4">{{ old('description', $event->description) }}</textarea>
        </div>

        <div style="margin-top: 12px;">
            <label>Statut</label>
            <select name="status" required>
                @foreach (['draft' => 'Brouillon', 'published' => 'Publié', 'archived' => 'Archivé'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $event->status ?: 'draft') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-top: 14px; display:flex; justify-content:flex-end;">
            <button class="btn" type="submit">{{ $event->exists ? 'Enregistrer' : 'Créer' }}</button>
        </div>
    </form>
@endsection

