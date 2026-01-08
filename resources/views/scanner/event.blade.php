@extends('layouts.app')

@section('title', 'Scanner · ' . $event->name)

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 850;">{{ $event->name }}</div>
                <div class="muted">{{ $event->starts_at->format('d/m/Y H:i') }} · {{ $event->venue_name }}</div>
            </div>
            <div class="card" style="padding: 10px 12px; border-radius: 12px;">
                <div class="muted" style="font-size: 12px;">Présents</div>
                <div style="font-weight: 900; font-size: 18px;">
                    <span id="presentCount">{{ $presentCount }}</span> / {{ $event->capacity }}
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 14px;">
        <div style="font-weight: 850; margin-bottom: 10px;">Scan (mode dev)</div>
        <div class="muted" style="margin-bottom: 10px;">
            Entrez le <strong>qr_token</strong> d’un billet (depuis la page commande) pour simuler le scan.
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <div style="flex: 1; min-width: min(420px, 100%);">
                <label for="qr_token">QR token</label>
                <input id="qr_token" placeholder="Coller le token ici…" />
            </div>
            <div style="display:flex; align-items:flex-end;">
                <button class="btn" id="scanBtn" type="button">Scanner</button>
            </div>
        </div>
    </div>

    <div id="resultCard" class="card" style="display:none;">
        <div id="resultTitle" style="font-weight: 900; font-size: 18px;"></div>
        <div id="resultMsg" class="muted" style="margin-top: 6px;"></div>
        <div id="resultMeta" class="muted" style="margin-top: 10px; font-size: 13px;"></div>
    </div>

    <script>
        const btn = document.getElementById('scanBtn');
        const input = document.getElementById('qr_token');
        const card = document.getElementById('resultCard');
        const title = document.getElementById('resultTitle');
        const msg = document.getElementById('resultMsg');
        const meta = document.getElementById('resultMeta');
        const presentCount = document.getElementById('presentCount');

        function show(result, message, extra = {}) {
            card.style.display = 'block';
            const map = {
                valid: {t: 'VALIDE', c: '#16a34a'},
                already_used: {t: 'DÉJÀ UTILISÉ', c: '#f59e0b'},
                invalid: {t: 'INVALIDE', c: '#ef4444'},
            };
            const s = map[result] || map.invalid;
            title.textContent = s.t;
            title.style.color = s.c;
            msg.textContent = message || '';
            meta.textContent = '';

            if (extra.attendee) {
                meta.textContent += `Participant: ${extra.attendee.name} (${extra.attendee.email})`;
            }
            if (extra.checked_in_at) {
                meta.textContent += (meta.textContent ? ' · ' : '') + `1er scan: ${extra.checked_in_at}`;
            }
            if (typeof extra.present_count !== 'undefined') {
                presentCount.textContent = extra.present_count;
            }
        }

        async function doScan() {
            const token = input.value.trim();
            if (!token) return;

            btn.disabled = true;
            try {
                const res = await fetch(@json(route('scanner.scan', $event)), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ qr_token: token }),
                });
                const data = await res.json().catch(() => ({}));
                show(data.result || 'invalid', data.message || 'Erreur', data);
            } catch (e) {
                show('invalid', 'Erreur réseau.');
            } finally {
                btn.disabled = false;
            }
        }

        btn.addEventListener('click', doScan);
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                doScan();
            }
        });
    </script>
@endsection

