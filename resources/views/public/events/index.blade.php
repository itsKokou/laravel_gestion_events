@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', "Soirées à venir · Win's Events")

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Événements</div>
        <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">Soirées à venir</h1>
        <p class="muted" style="font-size: 16px;">Découvrez nos prochains événements et réservez votre place.</p>
    </div>

    <!-- Barre de recherche -->
    @if($events->count() > 0 || $q)
        <div class="card" style="margin-bottom: 32px; padding: 20px;">
            <form method="GET" action="{{ route('public.events.index') }}" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="q" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">Rechercher</label>
                    <input id="q" name="q" value="{{ $q }}" placeholder="Nom, lieu, thème…" 
                           style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;" 
                           onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                           onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                </div>
                <button class="btn" type="submit" style="padding: 12px 24px;">Rechercher</button>
                @if($q)
                    <a href="{{ route('public.events.index') }}" class="btn secondary" style="padding: 12px 24px;">Effacer</a>
                @endif
            </form>
        </div>
    @endif

    <!-- Liste des soirées -->
    @if($events->count() > 0)
        <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            @foreach ($events as $event)
                <a href="{{ route('public.events.show', $event) }}" style="text-decoration: none; display: block;">
                    <div class="card" style="padding: 0; overflow: hidden; transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer;"
                         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(15,23,42,0.12)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                        <!-- Image -->
                        @if($event->hero_image_path)
                            <div style="width: 100%; height: 200px; overflow: hidden; background: #f0f0f0; position: relative;">
                                <img src="{{ Storage::url($event->hero_image_path) }}" alt="{{ $event->name }}" 
                                     style="width: 100%; height: 100%; object-fit: cover;" />
                            </div>
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)); display: flex; align-items: center; justify-content: center;">
                                <div style="font-size: 48px;">🎉</div>
                            </div>
                        @endif
                        
                        <!-- Contenu -->
                        <div style="padding: 20px;">
                            <div style="font-weight: 800; font-size: 20px; color: var(--we-text); margin-bottom: 12px; line-height: 1.3;">{{ $event->name }}</div>
                            
                            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--we-text);">
                                    <span style="font-size: 18px;">📅</span>
                                    <div>
                                        <div style="font-weight: 600;">{{ optional($event->starts_at)->format('d/m/Y') }}</div>
                                        <div style="font-size: 12px; color: var(--we-muted);">{{ optional($event->starts_at)->format('H:i') }} - {{ optional($event->ends_at)->format('H:i') }}</div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--we-text);">
                                    <span style="font-size: 18px;">📍</span>
                                    <div>
                                        <div style="font-weight: 600;">{{ $event->venue_name }}</div>
                                        <div style="font-size: 12px; color: var(--we-muted);">{{ strlen($event->venue_address) > 40 ? substr($event->venue_address, 0, 40) . '...' : $event->venue_address }}</div>
                                    </div>
                                </div>
                                @if($event->theme)
                                    <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--we-muted);">
                                        <span style="font-size: 18px;">🎵</span>
                                        <span>{{ $event->theme }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid var(--we-border);">
                                <span class="btn secondary" style="padding: 8px 16px; font-size: 14px;">Voir les détails</span>
                                <!--@if($event->ticketTypes->where('is_active', true)->count() > 0)
                                    @php
                                        $minPrice = $event->ticketTypes->where('is_active', true)->min('price_cents');
                                    @endphp
                                    <div style="font-weight: 700; font-size: 18px; color: var(--we-primary);">
                                        À partir de {{ number_format($minPrice, 0, ',', ' ') }} FCFA
                                    </div>
                                @endif-->
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div style="margin-top: 32px; display: flex; justify-content: center;">
                {{ $events->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="card" style="padding: 64px 32px; text-align: center;">
            <div style="font-size: 64px; margin-bottom: 24px;">🔍</div>
            <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 12px;">
                @if($q)
                    Aucun résultat trouvé
                @else
                    Aucune soirée pour le moment
                @endif
            </h3>
            <p class="muted" style="font-size: 16px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto;">
                @if($q)
                    Aucune soirée ne correspond à votre recherche "{{ $q }}". Essayez avec d'autres mots-clés.
                @else
                    Revenez bientôt pour découvrir nos prochains événements !
                @endif
            </p>
            @if($q)
                <a href="{{ route('public.events.index') }}" class="btn secondary" style="padding: 12px 24px;">Voir toutes les soirées</a>
            @endif
        </div>
    @endif
@endsection

