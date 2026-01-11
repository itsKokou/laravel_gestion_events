@extends('layouts.app')

@section('title', 'Réserver · ' . $event->name)

@section('content')
<div class="card" style="margin-bottom: 14px;">
    <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <div style="font-size: 22px; font-weight: 800;">Réserver</div>
            <div class="muted">{{ $event->name }} · {{ $event->starts_at->format('d/m/Y H:i') }}</div>
        </div>
        <div style="display:flex; gap:10px;">
            <a class="btn secondary" href="{{ route('public.events.show', $event) }}">Retour</a>
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

<form method="POST" action="{{ route('public.reservations.store', $event) }}" class="card">
    @csrf

    <div class="grid grid2">
        <div>
            <label for="customer_email">Email client</label>
            <input id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required />
        </div>
        <div>
            <label for="customer_phone">Téléphone (optionnel)</label>
            <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" />
        </div>
    </div>

    <div class="grid grid2" style="margin-top: 12px;">
        <div>
            <label for="ticket_type_id">Tarif</label>
            <select id="ticket_type_id" name="ticket_type_id" required>
                <option value="">— Choisir —</option>
                @foreach ($event->ticketTypes as $type)
                    <option value="{{ $type->id }}" @selected((string) old('ticket_type_id') === (string) $type->id)>
                        {{ $type->name }} — {{ number_format($type->price_cents, 0, ',', ' ') }} {{ $type->currency }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="quantity">Quantité</label>
            <input id="quantity" name="quantity" type="number" min="1" max="10" value="{{ old('quantity', 1) }}"
                required />
            <div class="muted" style="font-size: 12px; margin-top: 6px;">Vous devrez remplir les infos de chaque
                participant.</div>
        </div>
    </div>

    @if ($event->addons->count() > 0)
        <div style="margin-top: 14px;">
            <div style="font-weight: 800; margin-bottom: 8px;">Options</div>
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
                @foreach ($event->addons as $addon)
                    <label class="card" style="padding: 12px; display:flex; gap:10px; align-items:flex-start;">
                        <input type="checkbox" name="addons[]" value="{{ $addon->id }}" style="width:auto; margin-top: 4px;"
                            @checked(in_array($addon->id, (array) old('addons', []), false)) />
                        <div style="flex:1;">
                            <div style="font-weight: 750;">{{ $addon->name }}</div>
                            <div class="muted" style="font-size: 13px;">{{ number_format($addon->price_cents, 0, ',', ' ') }}
                                {{ $addon->currency }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    <div style="margin-top: 14px;">
        <div style="font-weight: 800; margin-bottom: 8px;">Participants</div>
        <div class="muted" style="margin-bottom: 10px;">
            Le nombre de blocs “participant” s’ajuste automatiquement à la quantité.
        </div>

        @php($attendees = old('attendees', [['first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '', 'birthdate' => '']]))
        <div id="attendeesContainer">
            @foreach ($attendees as $i => $a)
                <div class="card attendeeCard" data-index="{{ $i }}" style="margin-bottom: 12px;">
                    <div style="font-weight: 800; margin-bottom: 10px;">Billet #<span
                            class="attendeeNumber">{{ $i + 1 }}</span></div>
                    <div class="grid grid2">
                        <div>
                            <label>Prénom</label>
                            <input name="attendees[{{ $i }}][first_name]" value="{{ $a['first_name'] ?? '' }}" required />
                        </div>
                        <div>
                            <label>Nom</label>
                            <input name="attendees[{{ $i }}][last_name]" value="{{ $a['last_name'] ?? '' }}" required />
                        </div>
                    </div>
                    <div class="grid grid2" style="margin-top: 10px;">
                        <div>
                            <label>Email</label>
                            <input name="attendees[{{ $i }}][email]" value="{{ $a['email'] ?? '' }}" required />
                        </div>
                        <div>
                            <label>Téléphone (optionnel)</label>
                            <input name="attendees[{{ $i }}][phone]" value="{{ $a['phone'] ?? '' }}" />
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <label>Date de naissance</label>
                        <input type="date" name="attendees[{{ $i }}][birthdate]" value="{{ $a['birthdate'] ?? '' }}"
                            required />
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div style="margin-top: 12px;">
        <label style="display:flex; gap:10px; align-items:flex-start;">
            <input type="checkbox" name="agree_terms" value="1" style="width:auto; margin-top: 4px;" required
                @checked(old('agree_terms')) />
            <span class="muted">J’accepte les conditions générales de vente.</span>
        </label>
    </div>

    <div style="margin-top: 14px; display:flex; gap:10px; justify-content:flex-end;">
        <button class="btn" type="submit">Créer la commande</button>
    </div>
</form>

<script>
    const quantityInput = document.getElementById('quantity');
    const container = document.getElementById('attendeesContainer');

    function cardTemplate(i) {
        return `
                <div class="card attendeeCard" data-index="${i}" style="margin-bottom: 12px;">
                    <div style="font-weight: 800; margin-bottom: 10px;">Billet #<span class="attendeeNumber">${i + 1}</span></div>
                    <div class="grid grid2">
                        <div>
                            <label>Prénom</label>
                            <input name="attendees[${i}][first_name]" required />
                        </div>
                        <div>
                            <label>Nom</label>
                            <input name="attendees[${i}][last_name]" required />
                        </div>
                    </div>
                    <div class="grid grid2" style="margin-top: 10px;">
                        <div>
                            <label>Email</label>
                            <input name="attendees[${i}][email]" required />
                        </div>
                        <div>
                            <label>Téléphone (optionnel)</label>
                            <input name="attendees[${i}][phone]" />
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <label>Date de naissance</label>
                        <input type="date" name="attendees[${i}][birthdate]" required />
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