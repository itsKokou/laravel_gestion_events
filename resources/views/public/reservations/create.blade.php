@extends('layouts.app')

@section('title', 'Réserver · ' . $event->name)

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Réservation</div>
                <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">Réserver votre place</h1>
                <p class="muted" style="font-size: 16px;">{{ $event->name }} · {{ $event->starts_at->format('d/m/Y H:i') }}</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a class="btn secondary" href="{{ route('public.events.show', $event) }}" style="padding: 12px 20px;">← Retour</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="margin-bottom: 24px; padding: 20px; background: #fef2f2; border-color: #fecaca;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 24px; height: 24px; border-radius: 50%; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 14px; flex-shrink: 0;">!</div>
                <div style="font-weight: 700; color: #991b1b; font-size: 15px;">Erreurs de validation</div>
            </div>
            <ul style="margin-left: 36px; color: #7f1d1d; line-height: 1.8;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('public.reservations.store', $event) }}" class="card" style="padding: 32px;">
        @csrf

        <!-- Section Informations de contact -->
        <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid var(--we-border);">
            <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 16px; letter-spacing: -0.3px;">📧 Informations de contact</h2>
            <div class="grid grid2" style="gap: 20px;">
                <div>
                    <label for="customer_email" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                        Email <span style="color: #dc2626;">*</span>
                    </label>
                    <input id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required
                        placeholder="exemple@email.com"
                        style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                        onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                        onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                </div>
                <div>
                    <label for="customer_phone" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                        Téléphone (optionnel)
                    </label>
                    <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                        placeholder="+33 6 12 34 56 78"
                        style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                        onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                        onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                </div>
            </div>
        </div>

        <!-- Section Tarif et quantité -->
        <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid var(--we-border);">
            <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 16px; letter-spacing: -0.3px;">💰 Tarif et quantité</h2>
            
            <!-- Tarif disponible -->
            <div style="margin-bottom: 24px; padding: 24px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-radius: 12px; border: 2px solid rgba(234, 88, 12, 0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1;">
                        <div style="display: inline-block; padding: 4px 12px; border-radius: 8px; background: var(--we-primary); color: #fff; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                            Tarif actuel
                        </div>
                        <div style="font-size: 22px; font-weight: 800; color: var(--we-text); margin-bottom: 8px;">{{ $activeTicketType->name }}</div>
                        @if($activeTicketType->sales_starts_at && $activeTicketType->sales_ends_at)
                            <div style="font-size: 14px; color: var(--we-muted); display: flex; align-items: center; gap: 6px;">
                                <span>📅</span>
                                <span>Valide du {{ $activeTicketType->sales_starts_at->format('d/m/Y H:i') }} au {{ $activeTicketType->sales_ends_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @else
                            <div style="font-size: 14px; color: var(--we-muted); display: flex; align-items: center; gap: 6px;">
                                <span>✅</span>
                                <span>Tarif disponible en permanence</span>
                            </div>
                        @endif
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 32px; font-weight: 900; color: var(--we-primary); line-height: 1;">
                            {{ number_format($activeTicketType->price_cents, 0, ',', ' ') }} <span style="font-size: 18px;">{{ $activeTicketType->currency }}</span>
                        </div>
                        <div style="font-size: 12px; color: var(--we-muted); margin-top: 4px;">par billet</div>
                    </div>
                </div>
            </div>

            <div>
                <label for="quantity" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                    Nombre de billets <span style="color: #dc2626;">*</span>
                </label>
                <input id="quantity" name="quantity" type="number" min="1" max="10" value="{{ old('quantity', 1) }}"
                    required
                    style="width: 100%; max-width: 200px; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;"
                    onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                    onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                <div class="muted" style="font-size: 12px; margin-top: 8px;">Vous devrez remplir les informations de chaque participant (maximum 10 billets).</div>
            </div>
        </div>

        <!-- Section Options -->
        @if ($event->addons->count() > 0)
            <div style="margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid var(--we-border);">
                <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 16px; letter-spacing: -0.3px;">✨ Options supplémentaires</h2>
                <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    @foreach ($event->addons as $addon)
                        <label class="card" style="padding: 16px; display: flex; gap: 12px; align-items: flex-start; cursor: pointer; transition: all 0.2s ease; border: 2px solid var(--we-border);"
                            onmouseover="this.style.borderColor='rgba(234, 88, 12, 0.3)'; this.style.boxShadow='0 4px 12px rgba(15,23,42,0.08)'"
                            onmouseout="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04)'">
                            <input type="checkbox" name="addons[]" value="{{ $addon->id }}" style="width: 20px; height: 20px; margin-top: 2px; cursor: pointer;"
                                @checked(in_array($addon->id, (array) old('addons', []), false)) />
                            <div style="flex: 1;">
                                <div style="font-weight: 700; font-size: 15px; color: var(--we-text); margin-bottom: 4px;">{{ $addon->name }}</div>
                                <div class="muted" style="font-size: 13px;">{{ number_format($addon->price_cents, 0, ',', ' ') }} {{ $addon->currency }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Section Participants -->
        <div style="margin-bottom: 32px;">
            <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 16px; letter-spacing: -0.3px;">👥 Participants</h2>
            <p class="muted" style="margin-bottom: 20px; font-size: 14px;">
                Le nombre de formulaires de participants s'ajuste automatiquement selon la quantité sélectionnée.
            </p>

            @php($attendees = old('attendees', [['first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'birthdate' => '']]))
            <div id="attendeesContainer">
                @foreach ($attendees as $i => $a)
                    <div class="card attendeeCard" data-index="{{ $i }}" style="margin-bottom: 20px; padding: 24px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08)); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--we-primary);">
                                {{ $i + 1 }}
                            </div>
                            <div style="font-weight: 800; font-size: 18px; color: var(--we-text);">Billet #<span class="attendeeNumber">{{ $i + 1 }}</span></div>
                        </div>
                        <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                    Prénom <span style="color: #dc2626;">*</span>
                                </label>
                                <input name="attendees[{{ $i }}][first_name]" value="{{ $a['first_name'] ?? '' }}" required
                                    placeholder="Jean"
                                    style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                    onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                    onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                    Nom <span style="color: #dc2626;">*</span>
                                </label>
                                <input name="attendees[{{ $i }}][last_name]" value="{{ $a['last_name'] ?? '' }}" required
                                    placeholder="Dupont"
                                    style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                    onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                    onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                            </div>
                        </div>
                        <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                    Email <span style="color: #dc2626;">*</span>
                                </label>
                                <input name="attendees[{{ $i }}][email]" value="{{ $a['email'] ?? '' }}" required
                                    placeholder="jean.dupont@email.com"
                                    style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                    onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                    onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                    Téléphone (optionnel)
                                </label>
                                <input name="attendees[{{ $i }}][phone]" value="{{ $a['phone'] ?? '' }}"
                                    placeholder="+33 6 12 34 56 78"
                                    style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                    onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                    onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                            </div>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Date de naissance <span style="color: #dc2626;">*</span>
                            </label>
                            <input type="date" name="attendees[{{ $i }}][birthdate]" value="{{ $a['birthdate'] ?? '' }}"
                                required
                                style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section Conditions -->
        <div style="margin-bottom: 32px; padding: 20px; background: #fafafa; border-radius: 12px; border: 1px solid var(--we-border);">
            <label style="display: flex; gap: 12px; align-items: flex-start; cursor: pointer;">
                <input type="checkbox" name="agree_terms" value="1" style="width: 20px; height: 20px; margin-top: 2px; cursor: pointer; flex-shrink: 0;" required
                    @checked(old('agree_terms')) />
                <span style="font-size: 14px; color: var(--we-text); line-height: 1.6;">
                    J'accepte les <strong>conditions générales de vente</strong> et confirme que toutes les informations fournies sont exactes.
                </span>
            </label>
        </div>

        <!-- Section Récapitulatif -->
        <div style="margin-bottom: 32px; padding: 24px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-radius: 16px; border: 2px solid rgba(234, 88, 12, 0.2);">
            <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 20px; letter-spacing: -0.3px;">📋 Récapitulatif</h2>
            
            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(234, 88, 12, 0.1);">
                    <div>
                        <div style="font-weight: 600; color: var(--we-text); margin-bottom: 4px;">{{ $activeTicketType->name }}</div>
                        <div style="font-size: 13px; color: var(--we-muted);">
                            <span id="quantity-display">1</span> billet<span id="quantity-plural" style="display: none;">s</span>
                        </div>
                    </div>
                    <div style="font-weight: 700; color: var(--we-text);">
                        <span id="subtotal-display">{{ number_format($activeTicketType->price_cents, 0, ',', ' ') }}</span> {{ $activeTicketType->currency }}
                    </div>
                </div>
                
                <div id="addons-summary" style="display: none;">
                    <!-- Les options seront ajoutées ici dynamiquement -->
                </div>
            </div>

            <div style="padding-top: 20px; border-top: 2px solid rgba(234, 88, 12, 0.2);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 18px; font-weight: 800; color: var(--we-text);">Total</div>
                    <div id="total-display" style="font-size: 32px; font-weight: 900; color: var(--we-primary); line-height: 1;">
                        {{ number_format($activeTicketType->price_cents, 0, ',', ' ') }} <span style="font-size: 20px;">{{ $activeTicketType->currency }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; padding-top: 24px; border-top: 2px solid var(--we-border);">
            <a href="{{ route('public.events.show', $event) }}" class="btn secondary" style="padding: 14px 24px;">Annuler</a>
            <button class="btn" type="submit" style="padding: 14px 32px; font-size: 16px;">
                Réserver
            </button>
        </div>
    </form>

<script>
    const quantityInput = document.getElementById('quantity');
    const container = document.getElementById('attendeesContainer');
    const totalDisplay = document.getElementById('total-display');
    const subtotalDisplay = document.getElementById('subtotal-display');
    const quantityDisplay = document.getElementById('quantity-display');
    const quantityPlural = document.getElementById('quantity-plural');
    const addonsSummary = document.getElementById('addons-summary');
    const unitPrice = {{ $activeTicketType->price_cents }};
    const currency = '{{ $activeTicketType->currency }}';
    
    const addons = @json($event->addons->map(fn($addon) => ['id' => $addon->id, 'name' => $addon->name, 'price' => $addon->price_cents]));

    function formatPrice(price) {
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    function updateTotal() {
        const quantity = parseInt(quantityInput.value || '1', 10);
        const subtotal = unitPrice * quantity;
        
        // Mettre à jour l'affichage de la quantité
        quantityDisplay.textContent = quantity;
        quantityPlural.style.display = quantity > 1 ? 'inline' : 'none';
        
        // Calculer le total des options sélectionnées
        const selectedAddons = Array.from(document.querySelectorAll('input[name="addons[]"]:checked'))
            .map(checkbox => parseInt(checkbox.value));
        
        let addonsTotal = 0;
        addonsSummary.innerHTML = '';
        
        if (selectedAddons.length > 0) {
            selectedAddons.forEach(addonId => {
                const addon = addons.find(a => a.id === addonId);
                if (addon) {
                    addonsTotal += addon.price;
                    const addonRow = document.createElement('div');
                    addonRow.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(234, 88, 12, 0.1);';
                    addonRow.innerHTML = `
                        <div style="font-weight: 600; color: var(--we-text);">${addon.name}</div>
                        <div style="font-weight: 700; color: var(--we-text);">${formatPrice(addon.price)} ${currency}</div>
                    `;
                    addonsSummary.appendChild(addonRow);
                }
            });
            addonsSummary.style.display = 'block';
        } else {
            addonsSummary.style.display = 'none';
        }
        
        const total = subtotal + addonsTotal;
        
        // Mettre à jour les affichages
        subtotalDisplay.textContent = formatPrice(subtotal);
        totalDisplay.innerHTML = `${formatPrice(total)} <span style="font-size: 20px;">${currency}</span>`;
    }
    
    // Initialiser le total au chargement
    updateTotal();

    quantityInput.addEventListener('input', updateTotal);
    quantityInput.addEventListener('change', updateTotal);
    
    // Mettre à jour le total quand les options changent
    document.querySelectorAll('input[name="addons[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateTotal);
    });

    function cardTemplate(i) {
        return `
                <div class="card attendeeCard" data-index="${i}" style="margin-bottom: 20px; padding: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08)); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--we-primary);">
                            ${i + 1}
                        </div>
                        <div style="font-weight: 800; font-size: 18px; color: var(--we-text);">Billet #<span class="attendeeNumber">${i + 1}</span></div>
                    </div>
                    <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Prénom <span style="color: #dc2626;">*</span>
                            </label>
                            <input name="attendees[${i}][first_name]" required placeholder="Jean"
                                   style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                   onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                   onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Nom <span style="color: #dc2626;">*</span>
                            </label>
                            <input name="attendees[${i}][last_name]" required placeholder="Dupont"
                                   style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                   onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                   onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                    </div>
                    <div class="grid grid2" style="gap: 16px; margin-bottom: 16px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Email <span style="color: #dc2626;">*</span>
                            </label>
                            <input name="attendees[${i}][email]" required placeholder="jean.dupont@email.com"
                                   style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                   onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                   onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                                Téléphone (optionnel)
                            </label>
                            <input name="attendees[${i}][phone]" placeholder="+33 6 12 34 56 78"
                                   style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                                   onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                                   onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                        </div>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                            Date de naissance <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="date" name="attendees[${i}][birthdate]" required
                               style="width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 14px;"
                               onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                               onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                    </div>
                </div>
            `;
    }

    function syncAttendees() {
        const qty = Math.max(1, Math.min(10, parseInt(quantityInput.value || '1', 10)));
        quantityInput.value = qty;

        const current = container.querySelectorAll('.attendeeCard');
        const currentCount = current.length;

        if (currentCount < qty) {
            for (let i = currentCount; i < qty; i++) {
                container.insertAdjacentHTML('beforeend', cardTemplate(i));
            }
        } else if (currentCount > qty) {
            for (let i = currentCount - 1; i >= qty; i--) {
                current[i].remove();
            }
        }
    }

    quantityInput.addEventListener('change', syncAttendees);
    quantityInput.addEventListener('input', syncAttendees);
    syncAttendees();
</script>
@endsection