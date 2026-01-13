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
                    {{ $event->exists ? 'Modifier la soirée' : 'Créer une soirée' }}
                </h1>
                <p class="muted" style="font-size: 16px;">Remplissez les informations essentielles de votre événement.</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a class="btn secondary" href="{{ route('admin.events.index') }}" style="padding: 12px 20px;">← Retour</a>
                @if ($event->exists)
                    <a class="btn secondary" href="{{ route('public.events.show', $event) }}" target="_blank"
                        style="padding: 12px 20px;">👁️ Voir public</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stepper Indicator -->
    <div class="card" style="margin-bottom: 24px; padding: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
            @php
                $steps = [
                    ['id' => 1, 'label' => 'Informations', 'icon' => '📝'],
                    ['id' => 2, 'label' => 'Date & Lieu', 'icon' => '📅'],
                    ['id' => 3, 'label' => 'Configuration', 'icon' => '⚙️'],
                    ['id' => 4, 'label' => 'Tarifs', 'icon' => '💰'],
                    ['id' => 5, 'label' => 'Description', 'icon' => '📄'],
                ];
            @endphp
            @foreach($steps as $index => $step)
                <div style="display: flex; align-items: center; flex: 1; min-width: 0;">
                    <div class="step-indicator" data-step="{{ $step['id'] }}"
                        style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1; position: relative;">
                        <div class="step-circle"
                            style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; transition: all 0.3s ease; border: 2px solid var(--we-border); background: #fff; color: var(--we-muted);">
                            <span class="step-number">{{ $step['id'] }}</span>
                            <span class="step-icon" style="display: none;">{{ $step['icon'] }}</span>
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
                    <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">📝 Informations de base</h2>
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
                    <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">📅 Date et lieu</h2>
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
                    <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">⚙️ Configuration</h2>
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
                    <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">💰 Tarifs des billets</h2>
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
                    <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">📄 Description</h2>
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
            <button type="button" id="prev-btn" class="btn secondary" style="padding: 14px 24px; display: none;">
                ← Précédent
            </button>
            <div style="flex: 1;"></div>
            <button type="button" id="next-btn" class="btn" style="padding: 14px 24px;">
                Suivant →
            </button>
            <button type="submit" id="submit-btn" class="btn" style="padding: 14px 32px; font-size: 16px; display: none;">
                {{ $event->exists ? '💾 Enregistrer les modifications' : '✨ Créer la soirée' }}
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
                    // Étape complétée
                    circle.style.background = 'linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08))';
                    circle.style.borderColor = 'var(--we-primary)';
                    circle.style.color = 'var(--we-primary)';
                    number.style.display = 'none';
                    icon.style.display = 'block';
                    label.style.color = 'var(--we-primary)';
                } else if (stepNum === currentStep) {
                    // Étape actuelle
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
