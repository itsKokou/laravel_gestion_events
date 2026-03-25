@extends('layouts.admin')

@section('title', 'Admin · Tableau de bord')

@section('content')
    <div class="mb-8">
        <p class="text-xs font-bold uppercase tracking-wider text-orange-600">Tableau de bord</p>
        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Vue d’ensemble</h1>
        <!-- <p class="mt-2 text-sm text-slate-600">Pilotez vos performances en un coup d’œil : ventes, revenus et dynamique.</p> -->
    </div>

    <div class="mb-10 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <a href="{{ route('admin.events.index') }}"
            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
            <div class="relative flex items-start gap-4">
                <span
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-2xl ring-1 ring-slate-200"
                    aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-orange-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                    </svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Événements</p>
                    <p class="mt-1 text-3xl font-black tabular-nums text-slate-900">{{ $eventsCount }}</p>
                    <p class="mt-2 text-xs text-slate-500">Catalogue des événements créés</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.orders.index') }}"
            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
            <div class="relative flex items-start gap-4">
                <span
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-2xl ring-1 ring-slate-200"
                    aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-yellow-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                    </svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Réservations</p>
                    <p class="mt-1 text-3xl font-black tabular-nums text-slate-900">{{ $ordersCount }}</p>
                    <p class="mt-2 text-xs text-slate-500">Toutes réservations confondues</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.orders.index') }}?status=paid"
            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md sm:col-span-2 xl:col-span-1">
            <div class="relative flex items-start gap-4">
                <span
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-2xl ring-1 ring-slate-200"
                    aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-emerald-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />
                    </svg>
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Commandes payées</p>
                    <p class="mt-1 text-3xl font-black tabular-nums text-slate-900">{{ $paidOrdersCount }}</p>
                    <p class="mt-2 text-sm text-slate-600">
                        @if ($ordersCount > 0)
                            {{ round(($paidOrdersCount / $ordersCount) * 100) }}% du total
                        @else
                            Aucune réservation
                        @endif
                    </p>
                </div>
            </div>
        </a>
    </div>

    @include('admin.analytics.panel')
@endsection

@push('scripts')
    <script>
        (function() {
            const url = @json(route('admin.dashboard.stats'));
            const revenueUrl = @json(route('admin.analytics.revenue'));
            const fmtMoney = (cents) =>
                new Intl.NumberFormat('fr-FR', {
                    maximumFractionDigits: 0
                }).format(Math.round(Number(cents))) + '\u00a0FCFA';
            const fmtInt = (n) => new Intl.NumberFormat('fr-FR').format(Number(n));

            function setVisible(id, show) {
                const el = document.getElementById(id);
                if (el) el.classList.toggle('hidden', !show);
            }

            function formatGrowth(value) {
                if (value === null || value === undefined) return 'Comparaison indisponible';
                const n = Number(value);
                const sign = n > 0 ? '+' : '';
                return `${sign}${n.toFixed(1)}% vs 30j précédents`;
            }

            function formatRevenueAxisLabel(ymd) {
                if (!ymd || typeof ymd !== 'string') return '';
                const p = ymd.split('-');
                if (p.length !== 3) return ymd;
                return `${p[2]}/${p[1]}`;
            }

            function parseYmdLocal(ymd) {
                const p = ymd.split('-').map(Number);
                if (p.length !== 3 || p.some((n) => Number.isNaN(n))) return null;
                return new Date(p[0], p[1] - 1, p[2], 12, 0, 0, 0);
            }

            function toYmdLocal(d) {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${day}`;
            }

            /** Semaine calendaire commençant le lundi (usage fréquent en France). */
            function startOfWeekMonday(d) {
                const x = new Date(d.getFullYear(), d.getMonth(), d.getDate(), 12, 0, 0, 0);
                const dow = x.getDay();
                const diff = dow === 0 ? -6 : 1 - dow;
                x.setDate(x.getDate() + diff);
                return x;
            }

            function endOfWeekSunday(monday) {
                const x = new Date(monday.getFullYear(), monday.getMonth(), monday.getDate(), 12, 0, 0, 0);
                x.setDate(x.getDate() + 6);
                return x;
            }

            /**
             * Regroupe la série quotidienne en barres hebdomadaires (CA et nombre de commandes cumulés).
             * @param {Array<{date: string, revenue_cents: number, orders_count: number}>} daily
             */
            function aggregateDailyToWeeks(daily) {
                const map = new Map();
                for (const item of daily) {
                    const d = parseYmdLocal(item.date);
                    if (!d) continue;
                    const mon = startOfWeekMonday(d);
                    const key = toYmdLocal(mon);
                    if (!map.has(key)) {
                        const sun = endOfWeekSunday(mon);
                        map.set(key, {
                            date: key,
                            week_end: toYmdLocal(sun),
                            revenue_cents: 0,
                            orders_count: 0,
                        });
                    }
                    const w = map.get(key);
                    w.revenue_cents += Number(item.revenue_cents || 0);
                    w.orders_count += Number(item.orders_count || 0);
                }
                return Array.from(map.values()).sort((a, b) => a.date.localeCompare(b.date));
            }

            function renderRevenueBars(dailySeries, period = '30d') {
                const weekly = period === '90d';
                const series = weekly ? aggregateDailyToWeeks(dailySeries || []) : (dailySeries || []);
                const container = document.getElementById('revenue-bars');
                if (!container) return;
                container.innerHTML = '';

                if (!Array.isArray(series) || series.length === 0) {
                    container.innerHTML =
                        '<p class="text-sm font-medium text-slate-500">Aucune donnée sur cette période.</p>';
                    return;
                }

                const max = Math.max(...series.map(item => Number(item.revenue_cents || 0)), 1);
                const step = weekly
                    ? Math.max(1, Math.ceil(series.length / 12))
                    : Math.max(1, Math.ceil(series.length / 20));

                series.forEach((item, idx) => {
                    const value = Number(item.revenue_cents || 0);
                    const h = Math.max(4, Math.round((value / max) * 180));
                    const bar = document.createElement('div');
                    bar.className = 'group relative flex-1 min-w-[8px] rounded-t-md bg-orange-300 hover:bg-orange-500 transition';
                    bar.style.height = `${h}px`;
                    if (weekly && item.week_end) {
                        bar.title =
                            `Semaine du ${formatRevenueAxisLabel(item.date)} au ${formatRevenueAxisLabel(item.week_end)} — ${fmtMoney(value)} (${fmtInt(item.orders_count || 0)} cmd)`;
                    } else {
                        bar.title = `${item.date}: ${fmtMoney(value)} (${fmtInt(item.orders_count || 0)} cmd)`;
                    }

                    if (idx % step === 0 || idx === series.length - 1) {
                        const label = document.createElement('span');
                        label.className =
                            'absolute -bottom-6 left-1/2 -translate-x-1/2 whitespace-nowrap text-[10px] font-semibold text-slate-400';
                        label.textContent = weekly
                            ? `Sem. ${formatRevenueAxisLabel(item.date)}`
                            : formatRevenueAxisLabel(item.date);
                        bar.appendChild(label);
                    }

                    container.appendChild(bar);
                });
            }

            async function loadRevenue(period = '30d') {
                const res = await fetch(`${revenueUrl}?period=${encodeURIComponent(period)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                if (!res.ok) throw new Error('bad revenue status');
                const data = await res.json();
                document.getElementById('revenue-period-total').textContent = fmtMoney(data.total_revenue_cents ?? 0);
                document.getElementById('revenue-period-orders').textContent = fmtInt(data.paid_orders_count ?? 0);
                renderRevenueBars(data.daily_breakdown || [], period);
            }

            async function loadAdminAnalytics() {
                setVisible('admin-analytics-loading', true);
                setVisible('admin-analytics-error', false);
                setVisible('admin-analytics-grid', false);
                try {
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });
                    if (!res.ok) throw new Error('bad status');
                    const data = await res.json();
                    const health = data.reservation_flow_health || {};
                    document.getElementById('stat-revenue').textContent = fmtMoney(data.total_revenue_cents ?? 0);
                    document.getElementById('stat-revenue-30d').textContent = fmtMoney(data.revenue_30d_cents ?? 0);
                    const revenuGrowth = formatGrowth(data.revenue_growth_percent);
                    document.getElementById('stat-revenue-growth').classList.toggle('!text-emerald-700', revenuGrowth !== 'Comparaison indisponible');
                    document.getElementById('stat-revenue-growth').textContent =revenuGrowth;
                    document.getElementById('stat-tickets').textContent = fmtInt(data.total_tickets_sold ?? 0);
                    document.getElementById('stat-aov').textContent = fmtMoney(data.avg_order_value_30d_cents ?? 0);
                    document.getElementById('stat-upcoming-events').textContent = fmtInt(data.upcoming_events ?? 0);
                    await loadRevenue('30d');
                    setVisible('admin-analytics-loading', false);
                    setVisible('admin-analytics-grid', true);
                } catch (e) {
                    setVisible('admin-analytics-loading', false);
                    setVisible('admin-analytics-error', true);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                loadAdminAnalytics();
                const btn = document.getElementById('admin-analytics-refresh');
                if (btn) btn.addEventListener('click', loadAdminAnalytics);
                document.querySelectorAll('.revenue-period-btn').forEach((btn) => {
                    btn.addEventListener('click', async function() {
                        document.querySelectorAll('.revenue-period-btn').forEach((el) => {
                            el.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
                            el.classList.add('text-slate-700');
                        });
                        this.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
                        this.classList.remove('text-slate-700');
                        await loadRevenue(this.dataset.revenuePeriod || '30d');
                    });
                });
                const h = window.location.hash;
                if (h === '#admin-analytics' || h === '#admin-revenue-trend') {
                    const t = document.querySelector(h);
                    if (t) t.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        })();
    </script>
@endpush
