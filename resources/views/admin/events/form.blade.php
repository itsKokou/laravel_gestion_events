@extends('layouts.admin')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Admin · Soirée')

@section('content')
        <!-- Header -->
        <div style="margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                <div>
                    <div
                        style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">
                        {{ $event->exists ? 'Modification' : 'Création' }}
                    </div>
                    <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">
                        {{ $event->exists ? 'Modifier la soirée' : 'Créer une nouvelle soirée' }}
                    </h1>
                    <p class="muted" style="font-size: 16px;">Remplissez les informations essentielles de votre événement.</p>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a class="text-sm text-orange-700 hover:text-orange-900 transition-colors" href="{{ route('admin.events.index') }}">← Retour</a>
                </div>
            </div>
        </div>

        <!-- Stepper Indicator -->
        <div class="card" style="margin-bottom: 24px; padding: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                @php
    $steps = [
        ['id' => 1, 'label' => 'Informations'],
        ['id' => 2, 'label' => 'Date & Lieu'],
        ['id' => 3, 'label' => 'Configuration'],
        ['id' => 4, 'label' => 'Tarifs'],
        ['id' => 5, 'label' => 'Description'],
    ];
                @endphp
                @foreach($steps as $index => $step)
                    <div style="display: flex; align-items: center; flex: 1; min-width: 0;">
                        <div class="step-indicator" data-step="{{ $step['id'] }}"
                            style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1; position: relative;">
                            <div class="step-circle"
                                style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; transition: all 0.3s ease; border: 2px solid var(--we-border); background: #fff; color: var(--we-muted);">
                                <span class="step-number">{{ $step['id'] }}</span>
                                <span class="step-icon flex items-center justify-center" style="display: none;" aria-hidden="true">
                                    @if ($step['id'] === 1)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    @elseif ($step['id'] === 2)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                        </svg>
                                    @elseif ($step['id'] === 3)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    @elseif ($step['id'] === 4)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75a8.25 8.25 0 0 0-8.25 8.25v.75c0 .414.336.75.75.75h16.5a.75.75 0 0 0 .75-.75v-4.5a.75.75 0 0 0-.75-.75H9.75a.75.75 0 0 0-.75.75v4.5c0 .414.336.75.75.75h.75m-1.5-15h11.25a2.25 2.25 0 0 1 2.25 2.25v6.75a2.25 2.25 0 0 1-2.25 2.25H9.75a2.25 2.25 0 0 1-2.25-2.25v-6.75a2.25 2.25 0 0 1 2.25-2.25Zm-3 3h.008v.008H6V9.75Zm0 3h.008v.008H6V12.75Zm0 3h.008v.008H6V15.75Zm6-9h.008v.008H12V6.75Zm0 3h.008v.008H12V9.75Zm0 3h.008v.008H12V12.75Zm0 3h.008v.008H12V15.75Zm6-9h.008v.008H18V6.75Zm0 3h.008v.008H18V9.75Zm0 3h.008v.008H18V12.75Zm0 3h.008v.008H18V15.75Z" />
                                        </svg>
                                    @endif
                                </span>
                            </div>
                            <div class="step-label"
                                style="font-size: 12px; font-weight: 500; color: var(--we-muted); text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;">
                                {{ $step['label'] }}
                            </div>
                        </div>
                    </div>
                    @if($index < count($steps) - 1)
                        <div class="step-arrow" data-arrow-step="{{ $step['id'] }}"
                            style="display: flex; align-items: center; justify-content: center; padding: 0 8px; flex-shrink: 0;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                style="transition: all 0.3s ease;">
                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    style="stroke: var(--we-border);" />
                            </svg>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        @if ($errors->any())
            <div class="card" style="margin-bottom: 24px; padding: 20px; background: #fef2f2; border-color: #fecaca;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div
                        style="width: 24px; height: 24px; border-radius: 50%; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                        !</div>
                    <div style="font-weight: 700; color: #991b1b; font-size: 15px;">Erreurs de validation</div>
                </div>
                <ul style="margin-left: 36px; color: #7f1d1d; line-height: 1.8;">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}"
            enctype="multipart/form-data" id="event-form">
            @csrf
            @if ($event->exists)
                @method('PUT')
            @endif

            <!-- Étape 1: Informations de base -->
            <div class="step-content" data-step="1" style="display: none;">
                <div class="card" style="margin-bottom: 24px; padding: 32px;">
                    <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Informations de base</h2>
                        <p class="muted" style="font-size: 14px;">Les informations essentielles de votre soirée.</p>
                    </div>

                    <input type="file" name="hero_image" id="hero-image-input" accept="image/jpeg,image/jpg,image/png,image/webp"
                        style="display: none;" onchange="previewHeroImage(this)" />

                    <div class="grid grid2" style="gap: 24px; align-items: flex-start;">
                        <div style="flex: 1;">
                <div>
                                <label
                                    style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                    Nom de la soirée <span style="color: #dc2626;">*</span>
                                </label>
                                <input name="name" id="event-name" value="{{ old('name', $event->name) }}" required
                                    placeholder="Ex: Soirée Electro Summer"
                                    style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                                    onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                    onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                <div class="muted" style="font-size: 12px; margin-top: 8px;">Le slug sera généré automatiquement à
                                    partir du nom.</div>
                            </div>
                        </div>

                        <div style="flex: 0 0 320px;">
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Image principale
                            </label>
                            <div id="image-preview-container" onclick="document.getElementById('hero-image-input').click()"
                                style="width: 100%; min-height: 200px; max-height: 300px; border-radius: 12px; border: 2px dashed var(--we-border); background: #fafafa; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; transition: all 0.2s ease; cursor: pointer;"
                                onmouseover="handleImagePreviewHover(this, true)" onmouseout="handleImagePreviewHover(this, false)">
                                @if($event->hero_image_path)
                                    <img id="current-image-preview" src="{{ Storage::url($event->hero_image_path) }}"
                                        alt="Image actuelle"
                                        style="width: 100%; height: 100%; object-fit: cover; display: block; pointer-events: none;" />
                                @else
                                    <div id="image-preview-placeholder"
                                        style="text-align: center; padding: 40px 20px; color: var(--we-muted); pointer-events: none;">
                                        <div style="font-size: 48px; margin-bottom: 12px;">📷</div>
                                        <div style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">Cliquez pour ajouter une
                                            image</div>
                                        <div style="font-size: 12px;">JPEG, PNG, WebP (max 5MB)</div>
                                    </div>
                                @endif
                                <img id="new-image-preview"
                                    style="display: none; width: 100%; height: 100%; object-fit: cover; pointer-events: none;" />
                                <div id="image-overlay"
                                    style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; pointer-events: none; border-radius: 12px;">
                                    Cliquez pour changer
                                </div>
                            </div>
                            <div id="image-preview-info" class="muted" style="font-size: 12px; margin-top: 8px; display: none;">
                            </div>
                        </div>
                </div>
                </div>
            </div>

            <!-- Étape 2: Date et lieu -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="card" style="margin-bottom: 24px; padding: 32px;">
                    <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Date et lieu</h2>
                        <p class="muted" style="font-size: 14px;">Quand et où se déroule votre soirée.</p>
                    </div>

                    <div class="grid grid2" style="gap: 20px; margin-bottom: 20px;">
                <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Date et heure de début <span style="color: #dc2626;">*</span>
                            </label>
                    <input type="datetime-local" name="starts_at"
                                value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\\TH:i')) }}" required
                                style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                </div>
                <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Date et heure de fin <span style="color: #dc2626;">*</span>
                            </label>
                    <input type="datetime-local" name="ends_at"
                                value="{{ old('ends_at', optional($event->ends_at)->format('Y-m-d\\TH:i')) }}" required
                                style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                    </div>

                    <div class="grid grid2" style="gap: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Nom du lieu <span style="color: #dc2626;">*</span>
                            </label>
                            <input name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" required
                                placeholder="Ex: Le Club"
                                style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Adresse complète <span style="color: #dc2626;">*</span>
                            </label>
                            <input name="venue_address" value="{{ old('venue_address', $event->venue_address) }}" required
                                placeholder="Ex: 123 Rue de la Fête, 75001 Paris"
                                style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 3: Configuration -->
            <div class="step-content" data-step="3" style="display: none;">
                <div class="card" style="margin-bottom: 24px; padding: 32px;">
                    <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Configuration</h2>
                        <p class="muted" style="font-size: 14px;">Paramètres de votre événement.</p>
                    </div>

                    <div class="grid grid2" style="gap: 20px; margin-bottom: 20px;">
                <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Âge minimum <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" min="0" max="120" name="min_age"
                                value="{{ old('min_age', $event->min_age ?? 18) }}" required
                                style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                </div>
                <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Capacité maximum <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="number" min="1" name="capacity" value="{{ old('capacity', $event->capacity ?? 100) }}"
                                required
                                style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                            Statut <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="status" required
                            style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'">
                            @foreach (['draft' => 'Brouillon', 'published' => 'Publié', 'archived' => 'Archivé'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('status', $event->status ?: 'draft') === $val)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="muted" style="font-size: 12px; margin-top: 8px;">
                            <strong>Brouillon</strong> : Non visible publiquement |
                            <strong>Publié</strong> : Visible et réservable |
                            <strong>Archivé</strong> : Événement terminé
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 4: Tarifs -->
            <div class="step-content" data-step="4" style="display: none;">
                <div class="card" style="margin-bottom: 24px; padding: 32px;">
                    <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Tarifs des billets</h2>
                        <p class="muted" style="font-size: 14px;">Configurez les différents tarifs selon les périodes de vente.</p>
                    </div>

                    <div id="ticket-types-container">
                        @php
    $ticketTypes = old('ticket_types', $event->ticketTypes->toArray() ?? []);
    if (empty($ticketTypes)) {
        $ticketTypes = [
            [
                'name' => 'Early Bird',
                'price_cents' => '',
                'currency' => 'FCFA',
                'quantity_limit' => '',
                'sales_starts_at' => '',
                'sales_ends_at' => '',
                'is_active' => true,
                'sort_order' => 0,
            ]
        ];
    }
                        @endphp

                        @foreach($ticketTypes as $index => $ticketType)
                            <div class="ticket-type-item" data-index="{{ $index }}"
                                style="margin-bottom: 24px; padding: 24px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
                                @if (!empty($ticketType['id']))
                                    <input type="hidden" name="ticket_types[{{ $index }}][id]"
                                        value="{{ old('ticket_types.'.$index.'.id', $ticketType['id']) }}" />
                                @endif
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                    <div style="font-weight: 700; font-size: 16px; color: var(--we-text);">Tarif #{{ $index + 1 }}</div>
                                    @if($index > 0)
                                        <button type="button" onclick="removeTicketType(this)" class="btn secondary"
                                            style="padding: 8px 16px; font-size: 13px; background: #fee2e2; color: #991b1b; border-color: #fecaca;">
                                            Supprimer
                                        </button>
                                    @endif
                                </div>

                                <div style="margin-bottom: 16px;">
                                    <label
                                        style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                        Nom du tarif <span style="color: #dc2626;">*</span>
                                    </label>
                                    <input type="text" name="ticket_types[{{ $index }}][name]"
                                        value="{{ old("ticket_types.{$index}.name", $ticketType['name'] ?? '') }}" required
                                        placeholder="Ex: Early Bird, Normal, Dernière minute"
                                        style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                        onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                        onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                </div>

                                <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                                    <div>
                                        <label
                                            style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                            Prix (FCFA) <span style="color: #dc2626;">*</span>
                                        </label>
                                        <input type="number" name="ticket_types[{{ $index }}][price_cents]"
                                            value="{{ old("ticket_types.{$index}.price_cents", $ticketType['price_cents'] ?? '') }}"
                                            required min="0" step="100" placeholder="Ex: 5000"
                                            style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                        <input type="hidden" name="ticket_types[{{ $index }}][currency]" value="FCFA" />
                                    </div>
                                    <div>
                                        <label
                                            style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                            Limite de quantité (optionnel)
                                        </label>
                                        <input type="number" name="ticket_types[{{ $index }}][quantity_limit]"
                                            value="{{ old("ticket_types.{$index}.quantity_limit", $ticketType['quantity_limit'] ?? '') }}"
                                            min="1" placeholder="Ex: 50"
                                            style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                    </div>
                                </div>

                                <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                <div>
                                        <label
                                            style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                            Date de début de vente <span style="color: #dc2626;">*</span>
                                        </label>
                                        <input type="datetime-local" name="ticket_types[{{ $index }}][sales_starts_at]"
                                            value="{{ old("ticket_types.{$index}.sales_starts_at", isset($ticketType['sales_starts_at']) && $ticketType['sales_starts_at'] ? (\Carbon\Carbon::parse($ticketType['sales_starts_at'])->format('Y-m-d\\TH:i')) : '') }}"
                                            required
                                            style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                </div>
                <div>
                                        <label
                                            style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                            Date de fin de vente <span style="color: #dc2626;">*</span>
                                        </label>
                                        <input type="datetime-local" name="ticket_types[{{ $index }}][sales_ends_at]"
                                            value="{{ old("ticket_types.{$index}.sales_ends_at", isset($ticketType['sales_ends_at']) && $ticketType['sales_ends_at'] ? (\Carbon\Carbon::parse($ticketType['sales_ends_at'])->format('Y-m-d\\TH:i')) : '') }}"
                                            required
                                            style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="checkbox" name="ticket_types[{{ $index }}][is_active]" value="1"
                                        id="ticket_type_active_{{ $index }}" @checked(old("ticket_types.{$index}.is_active", $ticketType['is_active'] ?? true)) style="width: 18px; height: 18px; cursor: pointer;" />
                                    <label for="ticket_type_active_{{ $index }}"
                                        style="font-size: 14px; color: #334155; cursor: pointer; margin: 0;">
                                        Tarif actif
                                    </label>
                                    <input type="hidden" name="ticket_types[{{ $index }}][sort_order]" value="{{ $index }}" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="addTicketType()" class="btn secondary"
                        style="padding: 12px 24px; font-size: 14px;">
                        + Ajouter un tarif
                    </button>
                </div>
            </div>

            <!-- Étape 5: Description -->
            <div class="step-content" data-step="5" style="display: none;">
                <div class="card" style="margin-bottom: 24px; padding: 32px;">
                    <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Description</h2>
                        <p class="muted" style="font-size: 14px;">Informations supplémentaires sur votre soirée.</p>
            </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                            Thème
                        </label>
                        <input name="theme" value="{{ old('theme', $event->theme) }}" placeholder="Ex: Électro, Hip-Hop, Reggae..."
                            style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
            </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                            Description
                        </label>
                        <textarea name="description" rows="6"
                            placeholder="Décrivez l'ambiance, les artistes, les surprises de votre soirée..."
                            style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px; font-family: inherit; resize: vertical;"
                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'">{{ old('description', $event->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-top: 32px;">
                <button type="button" id="prev-btn" class="btn text-sm" style="padding: 14px 24px; display: none;">
                    ← Précédent
                </button>
                <div style="flex: 1;"></div>
                <button type="button" id="next-btn" class="btn text-sm" style="padding: 14px 24px;">
                    Suivant →
                </button>
                <button type="submit" id="submit-btn" class="btn text-sm border bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                    {{ $event->exists ? 'Enregistrer les modifications' : 'Créer la soirée' }}
                </button>
            </div>
        </form>

        <script>
            // Gestion du stepper
            let currentStep = 1;
            const totalSteps = 5;

            function updateStepDisplay() {
                // Masquer toutes les étapes
                document.querySelectorAll('.step-content').forEach(step => {
                    step.style.display = 'none';
                });

                // Afficher l'étape actuelle
                const currentStepElement = document.querySelector(`.step-content[data-step="${currentStep}"]`);
                if (currentStepElement) {
                    currentStepElement.style.display = 'block';
                }

                // Mettre à jour les indicateurs
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    const stepNum = index + 1;
                    const circle = indicator.querySelector('.step-circle');
                    const number = indicator.querySelector('.step-number');
                    const icon = indicator.querySelector('.step-icon');
                    const label = indicator.querySelector('.step-label');

                    if (stepNum < currentStep) {
                        // Étape complétée — icône SVG
                        circle.style.background = 'linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08))';
                        circle.style.borderColor = 'var(--we-primary)';
                        circle.style.color = 'var(--we-primary)';
                        number.style.display = 'none';
                        icon.style.display = 'flex';
                        label.style.color = 'var(--we-primary)';
                    } else if (stepNum === currentStep) {
                        // Étape actuelle — numéro
                        circle.style.background = 'var(--we-primary)';
                        circle.style.borderColor = 'var(--we-primary)';
                        circle.style.color = '#fff';
                        number.style.display = 'block';
                        icon.style.display = 'none';
                        label.style.color = 'var(--we-primary)';
                        label.style.fontWeight = '700';
                    } else {
                        // Étape future
                        circle.style.background = '#fff';
                        circle.style.borderColor = 'var(--we-border)';
                        circle.style.color = 'var(--we-muted)';
                        number.style.display = 'block';
                        icon.style.display = 'none';
                        label.style.color = 'var(--we-muted)';
                        label.style.fontWeight = '500';
                    }
                });

                // Mettre à jour les flèches
                document.querySelectorAll('.step-arrow').forEach((arrow, index) => {
                    const arrowStep = index + 1; // La flèche après l'étape N
                    const arrowPath = arrow.querySelector('path');

                    if (arrowStep < currentStep) {
                        // Flèche après une étape complétée
                        arrowPath.style.stroke = 'var(--we-primary)';
                    } else {
                        // Flèche après une étape non complétée
                        arrowPath.style.stroke = 'var(--we-border)';
                    }
                });

                // Gérer les boutons de navigation
                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');
                const submitBtn = document.getElementById('submit-btn');

                if (currentStep === 1) {
                    prevBtn.style.display = 'none';
                } else {
                    prevBtn.style.display = 'block';
                }

                if (currentStep === totalSteps) {
                    nextBtn.style.display = 'none';
                    submitBtn.style.display = 'block';
                } else {
                    nextBtn.style.display = 'block';
                    submitBtn.style.display = 'none';
                }

                // Scroll vers le haut
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            function validateStep(step) {
                const stepElement = document.querySelector(`.step-content[data-step="${step}"]`);
                if (!stepElement) return true;

                const requiredFields = stepElement.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.style.borderColor = '#dc2626';
                        field.style.boxShadow = '0 0 0 4px rgba(220, 38, 38, 0.1)';
                    } else {
                        field.style.borderColor = 'var(--we-border)';
                        field.style.boxShadow = '0 1px 2px rgba(15,23,42,0.03)';
                    }
                });

                return isValid;
            }

            document.getElementById('prev-btn').addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepDisplay();
                }
            });

            document.getElementById('next-btn').addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateStepDisplay();
                    }
                } else {
                    alert('Veuillez remplir tous les champs obligatoires avant de continuer.');
                }
            });

            // Initialiser l'affichage
            updateStepDisplay();

            // Gestion de l'image
            function previewHeroImage(input) {
                const container = document.getElementById('image-preview-container');
                const placeholder = document.getElementById('image-preview-placeholder');
                const newPreview = document.getElementById('new-image-preview');
                const currentPreview = document.getElementById('current-image-preview');
                const info = document.getElementById('image-preview-info');
                const overlay = document.getElementById('image-overlay');

                if (input.files && input.files[0]) {
                    const file = input.files[0];

                    if (file.size > 5 * 1024 * 1024) {
                        alert('Le fichier est trop volumineux. Taille maximum : 5MB');
                        input.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function (e) {
                        if (placeholder) placeholder.style.display = 'none';
                        if (currentPreview) currentPreview.style.display = 'none';
                        if (overlay) overlay.style.display = 'none';

                        newPreview.src = e.target.result;
                        newPreview.style.display = 'block';
                        container.style.border = '2px solid rgba(234, 88, 12, 0.3)';
                        container.style.background = '#fff';
                        container.style.borderStyle = 'solid';

                        const fileSize = (file.size / 1024 / 1024).toFixed(2);
                        info.textContent = `${file.name} (${fileSize} MB)`;
                        info.style.display = 'block';
                    };

                    reader.readAsDataURL(file);
                } else {
                    if (currentPreview && currentPreview.src && !currentPreview.src.includes('data:')) {
                        currentPreview.style.display = 'block';
                        newPreview.style.display = 'none';
                        container.style.border = '2px solid var(--we-border)';
                        container.style.background = '#fff';
                        container.style.borderStyle = 'solid';
                    } else if (placeholder) {
                        placeholder.style.display = 'block';
                        newPreview.style.display = 'none';
                        container.style.border = '2px dashed var(--we-border)';
                        container.style.background = '#fafafa';
                    }
                    info.style.display = 'none';
                }
            }

            function handleImagePreviewHover(container, isHovering) {
                const newPreview = container.querySelector('#new-image-preview');
                const currentPreview = container.querySelector('#current-image-preview');
                const overlay = container.querySelector('#image-overlay');
                const hasImage = (currentPreview && currentPreview.style.display !== 'none') ||
                    (newPreview && newPreview.style.display !== 'none');

                if (isHovering) {
                    if (hasImage && overlay) {
                        overlay.style.display = 'flex';
                    } else {
                        container.style.borderColor = 'rgba(234, 88, 12, 0.4)';
                        container.style.background = '#fff5f0';
                    }
                } else {
                    if (overlay) overlay.style.display = 'none';
                    if (!hasImage || (newPreview && newPreview.style.display === 'none')) {
                        if (currentPreview && currentPreview.style.display !== 'none') {
                            container.style.borderColor = 'var(--we-border)';
                            container.style.background = '#fff';
                        } else {
                            container.style.borderColor = 'var(--we-border)';
                            container.style.background = '#fafafa';
                        }
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const container = document.getElementById('image-preview-container');
                const currentPreview = document.getElementById('current-image-preview');

                if (currentPreview && currentPreview.src && !currentPreview.src.includes('data:')) {
                    container.style.border = '2px solid var(--we-border)';
                    container.style.background = '#fff';
                    container.style.borderStyle = 'solid';
                }
            });

            // Gestion des tarifs
            let ticketTypeIndex = {{ count($ticketTypes) }};

            function addTicketType() {
                const container = document.getElementById('ticket-types-container');
                const newIndex = ticketTypeIndex++;

                const ticketTypeHtml = `
                        <div class="ticket-type-item" data-index="${newIndex}" style="margin-bottom: 24px; padding: 24px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <div style="font-weight: 700; font-size: 16px; color: var(--we-text);">Tarif #${newIndex + 1}</div>
                                <button type="button" onclick="removeTicketType(this)" class="btn secondary" style="padding: 8px 16px; font-size: 13px; background: #fee2e2; color: #991b1b; border-color: #fecaca;">
                                    Supprimer
                                </button>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                    Nom du tarif <span style="color: #dc2626;">*</span>
                                </label>
                                <input type="text" name="ticket_types[${newIndex}][name]" required placeholder="Ex: Early Bird, Normal, Dernière minute"
                                       style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;" 
                                       onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                       onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                            </div>

                            <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                        Prix (FCFA) <span style="color: #dc2626;">*</span>
                                    </label>
                                    <input type="number" name="ticket_types[${newIndex}][price_cents]" required min="0" step="100" placeholder="Ex: 5000"
                                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;" 
                                           onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                           onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                    <input type="hidden" name="ticket_types[${newIndex}][currency]" value="FCFA" />
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                        Limite de quantité (optionnel)
                                    </label>
                                    <input type="number" name="ticket_types[${newIndex}][quantity_limit]" min="1" placeholder="Ex: 50"
                                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;" 
                                           onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                           onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                </div>
                            </div>

                            <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                        Date de début de vente <span style="color: #dc2626;">*</span>
                                    </label>
                                    <input type="datetime-local" name="ticket_types[${newIndex}][sales_starts_at]" required
                                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;" 
                                           onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                           onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                </div>
                                <div>
                                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                        Date de fin de vente <span style="color: #dc2626;">*</span>
                                    </label>
                                    <input type="datetime-local" name="ticket_types[${newIndex}][sales_ends_at]" required
                                           style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;" 
                                           onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                           onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; gap: 12px;">
                                <input type="checkbox" name="ticket_types[${newIndex}][is_active]" value="1" id="ticket_type_active_${newIndex}" checked
                                       style="width: 18px; height: 18px; cursor: pointer;" />
                                <label for="ticket_type_active_${newIndex}" style="font-size: 14px; color: #334155; cursor: pointer; margin: 0;">
                                    Tarif actif
                                </label>
                                <input type="hidden" name="ticket_types[${newIndex}][sort_order]" value="${newIndex}" />
                            </div>
                        </div>
                    `;

                container.insertAdjacentHTML('beforeend', ticketTypeHtml);
            }

            function removeTicketType(button) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce tarif ?')) {
                    const item = button.closest('.ticket-type-item');
                    item.remove();

                    const items = document.querySelectorAll('.ticket-type-item');
                    items.forEach((item, index) => {
                        const num = index + 1;
                        item.querySelector('div[style*="font-weight: 700"]').textContent = `Tarif #${num}`;

                        const inputs = item.querySelectorAll('input, select');
                        inputs.forEach(input => {
                            if (input.name) {
                                input.name = input.name.replace(/ticket_types\[\d+\]/, `ticket_types[${index}]`);
                            }
                            if (input.id) {
                                input.id = input.id.replace(/\d+/, index);
                            }
                        });
                        const labels = item.querySelectorAll('label');
                        labels.forEach(label => {
                            if (label.getAttribute('for')) {
                                label.setAttribute('for', label.getAttribute('for').replace(/\d+/, index));
                            }
                        });
                    });
                }
            }
        </script>
@endsection
