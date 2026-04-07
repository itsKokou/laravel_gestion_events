{{-- Données chargées côté client depuis les endpoints JSON existants (pas de logique métier ici) --}}
<section id="admin-analytics" class="scroll-mt-24">
    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
      
        <button type="button" id="admin-analytics-refresh"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
            Actualiser
        </button>
    </div>

    <div id="admin-analytics-loading" class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 p-8 text-center text-sm text-slate-500">
        Chargement des indicateurs…
    </div>
    <div id="admin-analytics-error" class="hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">
        Impossible de charger les statistiques. Vérifiez votre session ou réessayez.
    </div>

    <div id="admin-analytics-grid" class="hidden space-y-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-500">Performance commerciale</p>
            <div class="mt-3 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-xl border border-orange-200/70 bg-orange-50/50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">CA total</p>
                    <p id="stat-revenue" class="mt-2 text-2xl font-black tabular-nums text-slate-900">—</p>
                    <p class="mt-2 text-xs font-medium text-slate-500">Cumul depuis le début</p>
                </div>
                <div class="rounded-xl border border-emerald-200/70 bg-emerald-50/50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">CA 30 jours</p>
                    <p id="stat-revenue-30d" class="mt-2 text-2xl font-black tabular-nums text-slate-900">—</p>
                    <p id="stat-revenue-growth" class="mt-2 text-sm font-semibold text-slate-500">—</p>
                </div>
                <div class="rounded-xl border border-sky-200/70 bg-sky-50/50 p-4 sm:col-span-2 xl:col-span-1">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Billets vendus (payés)</p>
                    <p id="stat-tickets" class="mt-2 text-2xl font-black tabular-nums text-slate-900">—</p>
                    <p class="mt-2 text-xs font-medium text-slate-500">Volume de billets réellement encaissés</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
            <p class="text-[11px] font-bold uppercase tracking-wide text-slate-500">Pilotage organisateur</p>
            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-violet-200/70 bg-violet-50/50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Panier moyen (30j)</p>
                    <p id="stat-aov" class="mt-2 text-2xl font-black tabular-nums text-slate-900">—</p>
                </div>
                <div class="rounded-xl border border-amber-200/70 bg-amber-50/50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Événements à venir</p>
                    <p id="stat-upcoming-events" class="mt-2 text-2xl font-black tabular-nums text-slate-900">—</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="admin-revenue-trend" class="scroll-mt-24 mt-10">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-lg font-extrabold tracking-tight text-slate-900">Évolution du chiffre d’affaires</h2>
                <p class="text-sm text-slate-600">Suivi des encaissements payés sur la période sélectionnée.</p>
            </div>
            <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50 p-1">
                <button type="button" data-revenue-period="7d"
                    class="revenue-period-btn rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-white">
                    7 jours
                </button>
                <button type="button" data-revenue-period="30d"
                    class="revenue-period-btn rounded-lg bg-white px-3 py-1.5 text-sm font-semibold text-slate-900 shadow-sm">
                    30 jours
                </button>
                <button type="button" data-revenue-period="90d"
                    class="revenue-period-btn rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700 hover:bg-white">
                    90 jours
                </button>
            </div>
        </div>

        <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/70 p-4">
            <div id="revenue-bars" class="flex h-52 items-end gap-1.5 sm:gap-2"></div>
            <div class="mt-10 flex flex-wrap items-center gap-4 text-sm">
                <p class="font-semibold text-slate-700">
                    Total période:
                    <span id="revenue-period-total" class="font-black text-slate-900">—</span>
                </p>
                <p class="font-semibold text-slate-700">
                    Commandes payées:
                    <span id="revenue-period-orders" class="font-black text-slate-900">—</span>
                </p>
            </div>
        </div>
    </div>
</section>
