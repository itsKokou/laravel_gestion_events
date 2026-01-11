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
        enctype="multipart/form-data">
        @csrf
        @if ($event->exists)
            @method('PUT')
        @endif

        <!-- Section Informations de base -->
        <div class="card" style="margin-bottom: 24px; padding: 32px;">
            <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Informations de base</h2>
                <p class="muted" style="font-size: 14px;">Les informations essentielles de votre soirée.</p>
            </div>

            <!-- Champ input file caché -->
            <input type="file" name="hero_image" id="hero-image-input" accept="image/jpeg,image/jpg,image/png,image/webp"
                style="display: none;" onchange="previewHeroImage(this)" />

            <div class="grid grid2" style="gap: 24px; align-items: flex-start;">
                <!-- Colonne gauche : Nom -->
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

                <!-- Colonne droite : Aperçu de l'image (cliquable) -->
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
                        <!-- Overlay pour indiquer qu'on peut cliquer -->
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

        <!-- Section Date et lieu -->
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

        <!-- Section Configuration -->
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

        <!-- Section Tarifs -->
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
                                'currency' => 'XOF',
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
                                <input type="hidden" name="ticket_types[{{ $index }}][currency]" value="XOF" />
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

        <!-- Section Description -->
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

        <!-- Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <a href="{{ route('admin.events.index') }}" class="btn secondary" style="padding: 14px 24px;">Annuler</a>
            <button class="btn" type="submit" style="padding: 14px 32px; font-size: 16px;">
                {{ $event->exists ? '💾 Enregistrer les modifications' : '✨ Créer la soirée' }}
            </button>
        </div>
    </form>

    <script>
        function previewHeroImage(input) {
            const container = document.getElementById('image-preview-container');
            const placeholder = document.getElementById('image-preview-placeholder');
            const newPreview = document.getElementById('new-image-preview');
            const currentPreview = document.getElementById('current-image-preview');
            const info = document.getElementById('image-preview-info');
            const overlay = document.getElementById('image-overlay');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Vérifier la taille du fichier (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Taille maximum : 5MB');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {
                    // Masquer le placeholder et l'image actuelle
                    if (placeholder) placeholder.style.display = 'none';
                    if (currentPreview) currentPreview.style.display = 'none';
                    if (overlay) overlay.style.display = 'none';

                    // Afficher la nouvelle image
                    newPreview.src = e.target.result;
                    newPreview.style.display = 'block';
                    container.style.border = '2px solid rgba(234, 88, 12, 0.3)';
                    container.style.background = '#fff';
                    container.style.borderStyle = 'solid';

                    // Afficher les informations du fichier
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    info.textContent = `${file.name} (${fileSize} MB)`;
                    info.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                // Si aucun fichier, réafficher l'image actuelle ou le placeholder
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

        // Initialiser l'aperçu si une image existe déjà
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
                                <input type="hidden" name="ticket_types[${newIndex}][currency]" value="XOF" />
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

                // Réindexer les éléments restants
                const items = document.querySelectorAll('.ticket-type-item');
                items.forEach((item, index) => {
                    const num = index + 1;
                    item.querySelector('div[style*="font-weight: 700"]').textContent = `Tarif #${num}`;

                    // Mettre à jour les noms des champs
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