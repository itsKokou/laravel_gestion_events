@extends('layouts.admin')

@section('title', 'Admin · Soirées')

@section('content')
        <!-- Popup de succès temporaire -->
        @if (session('success'))
            <div id="success-popup" style="position: fixed; top: 24px; right: 24px; z-index: 10000; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(15,23,42,0.15); padding: 20px 24px; min-width: 320px; border: 1px solid var(--we-border); animation: slideInRight 0.3s ease-out;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(74, 222, 128, 0.08)); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #22c55e; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 14px;">✓</div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 15px; color: var(--we-text); margin-bottom: 4px;">Succès</div>
                        <div style="font-size: 14px; color: var(--we-muted);">{{ session('success') }}</div>
                    </div>
                    <button onclick="closeSuccessPopup()" style="background: none; border: none; color: var(--we-muted); cursor: pointer; font-size: 20px; padding: 4px; line-height: 1; transition: color 0.2s;" onmouseover="this.style.color='var(--we-text)'" onmouseout="this.style.color='var(--we-muted)'">×</button>
                </div>
            </div>
        @endif

        <!-- Header -->
        <div style="margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                <div>
                    <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Gestion</div>
                    <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">Soirées</h1>
                    <p class="muted" style="font-size: 16px;">Créez, modifiez et publiez vos événements.</p>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <!-- Sélecteur de vue -->
                    <div style="display: flex; gap: 4px; background: #f8f9fa; padding: 4px; border-radius: 10px; border: 1px solid var(--we-border);">
                        <button id="view-list-btn" onclick="switchView('list')" class="view-toggle-btn active" style="padding: 8px 16px; border-radius: 8px; border: none; background: transparent; color: var(--we-text); cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;">
                            Vue Liste
                        </button>
                        <button id="view-kanban-btn" onclick="switchView('kanban')" class="view-toggle-btn" style="padding: 8px 16px; border-radius: 8px; border: none; background: transparent; color: var(--we-muted); cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s;">
                            Vue Kanban
                        </button>
                    </div>
                        <a class="btn border border-orange-600 text-orange-600 text-sm hover:bg-orange-600 hover:text-white transition-colors" href="{{ route('admin.events.create') }}">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nouvelle soirée
                    </a>
                </div>
            </div>
        </div>

        @php
    use Illuminate\Support\Facades\Storage;
    $statusColors = [
        'draft' => ['bg' => 'rgba(148, 163, 184, 0.1)', 'text' => '#64748b', 'label' => 'Brouillon', 'border' => 'rgba(148, 163, 184, 0.3)'],
        'published' => ['bg' => 'rgba(34, 197, 94, 0.1)', 'text' => '#16a34a', 'label' => 'Publié', 'border' => 'rgba(34, 197, 94, 0.3)'],
        'archived' => ['bg' => 'rgba(100, 116, 139, 0.1)', 'text' => '#64748b', 'label' => 'Archivé', 'border' => 'rgba(100, 116, 139, 0.3)'],
    ];
    $eventsDetailPayload = $events->map(function ($event) use ($statusColors) {
        $st = $statusColors[$event->status] ?? $statusColors['draft'];

        return [
            'id' => $event->id,
            'name' => $event->name,
            'slug' => $event->slug,
            'status' => $event->status,
            'status_label' => $st['label'],
            'starts_at' => $event->starts_at?->toIso8601String(),
            'ends_at' => $event->ends_at?->toIso8601String(),
            'venue_name' => $event->venue_name,
            'venue_address' => $event->venue_address,
            'theme' => $event->theme,
            'description' => $event->description,
            'min_age' => $event->min_age,
            'capacity' => $event->capacity,
            'sold_tickets' => (int) $event->sold_tickets,
            'sales_ends_at' => $event->sales_ends_at?->toIso8601String(),
            'hero_image_url' => $event->hero_image_path ? Storage::url($event->hero_image_path) : null,
            'published_at' => $event->published_at?->toIso8601String(),
            'archived_at' => $event->archived_at?->toIso8601String(),
            'edit_url' => route('admin.events.edit', $event),
            'ticket_types' => $event->ticketTypes->map(fn($t) => [
                'name' => $t->name,
                'price_cents' => (int) $t->price_cents,
                'currency' => $t->currency,
                'quantity_limit' => $t->quantity_limit,
                'sold_tickets' => (int) $t->sold_tickets,
                'is_active' => (bool) $t->is_active,
                'sales_starts_at' => $t->sales_starts_at?->toIso8601String(),
                'sales_ends_at' => $t->sales_ends_at?->toIso8601String(),
            ])->values()->all(),
        ];
    })->values()->all();
        @endphp

            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="m-0 text-xs font-bold uppercase tracking-wider text-slate-500">Filtrer par statut</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.events.index') }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold no-underline transition {{ $statusFilter === null ? 'bg-orange-600 text-white shadow-sm ring-1 ring-orange-500/30' : 'border border-stone-200 bg-white text-stone-700 hover:border-orange-200 hover:bg-orange-50/60 hover:text-orange-900' }}">
                        Tous
                    </a>
                    <a href="{{ route('admin.events.index', ['status' => 'draft']) }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold no-underline transition {{ $statusFilter === 'draft' ? 'bg-slate-600 text-white shadow-sm ring-1 ring-slate-500/30' : 'border border-stone-200 bg-white text-stone-700 hover:border-slate-300 hover:bg-slate-50' }}">
                        Brouillon
                    </a>
                    <a href="{{ route('admin.events.index', ['status' => 'published']) }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold no-underline transition {{ $statusFilter === 'published' ? 'bg-emerald-600 text-white shadow-sm ring-1 ring-emerald-500/30' : 'border border-stone-200 bg-white text-stone-700 hover:border-emerald-200 hover:bg-emerald-50/70 hover:text-emerald-900' }}">
                        Publié
                    </a>
                    <a href="{{ route('admin.events.index', ['status' => 'archived']) }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold no-underline transition {{ $statusFilter === 'archived' ? 'bg-stone-700 text-white shadow-sm ring-1 ring-stone-600/30' : 'border border-stone-200 bg-white text-stone-700 hover:bg-stone-100' }}">
                        Archivé
                    </a>
                </div>
            </div>

            @if($events->isEmpty())
                <div class="card rounded-3xl border border-stone-100 bg-white p-12 text-center shadow-premium sm:p-16">
                    <div class="text-5xl sm:text-6xl" aria-hidden="true">📅</div>
                    @if($statusFilter !== null)
                        <h3 class="mt-4 text-xl font-black text-slate-900 sm:text-2xl">Aucune soirée pour ce filtre</h3>
                        <p class="mx-auto mt-2 max-w-md text-slate-600">
                            Essayez un autre statut ou affichez toutes les soirées.
                        </p>
                        <a href="{{ route('admin.events.index') }}"
                            class="btn btn-secondary mt-6 inline-flex text-sm">Voir toutes les soirées</a>
                    @else
                        <h3 class="mt-4 text-xl font-black text-slate-900 sm:text-2xl">Aucune soirée pour le moment</h3>
                        <p class="mx-auto mt-2 max-w-md text-slate-600">
                            Créez votre première soirée pour commencer à vendre des billets et gérer vos événements.
                        </p>
                    @endif
                </div>
            @else
            <!-- Vue Liste -->
            <div id="list-view" class="view-container">
                    <div class="overflow-hidden rounded-3xl border border-stone-100 bg-white shadow-premium transition-all duration-300" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-bottom: 2px solid var(--we-border);">
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Nom</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Date</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Lieu</th>
                                    <th style="padding: 16px 20px; text-align: center; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Statut</th>
                                    <th style="padding: 16px 20px; text-align: right; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    @php $status = $statusColors[$event->status] ?? $statusColors['draft']; @endphp
                                    <tr style="border-top: 1px solid var(--we-border); transition: background 0.2s ease;"
                                        onmouseover="this.style.background='#fafafa'"
                                        onmouseout="this.style.background='transparent'">
                                        <td style="padding: 20px;">
                                            <div style="font-weight: 800; font-size: 16px; color: var(--we-text); margin-bottom: 6px;">{{ $event->name }}</div>
                                            <div class="muted" style="font-size: 12px; font-family: ui-monospace, monospace;">{{ $event->slug }}</div>
                                        </td>
                                        <td style="padding: 20px;">
                                            <div style="font-weight: 600; font-size: 14px; color: var(--we-text); margin-bottom: 4px;">
                                                {{ optional($event->starts_at)->format('d/m/Y') }}
                                            </div>
                                            <div class="muted" style="font-size: 12px;">
                                                {{ optional($event->starts_at)->format('H:i') }} - {{ optional($event->ends_at)->format('H:i') }}
                                            </div>
                                        </td>
                                        <td style="padding: 20px;">
                                            <div style="font-weight: 600; font-size: 14px; color: var(--we-text); margin-bottom: 4px;">{{ $event->venue_name }}</div>
                                            <div class="muted" style="font-size: 12px;">{{ strlen($event->venue_address) > 40 ? substr($event->venue_address, 0, 40) . '...' : $event->venue_address }}</div>
                                        </td>
                                        <td style="padding: 20px; text-align: center;">
                                            <span style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; letter-spacing: 0.5px;">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td style="padding: 20px; text-align: end;">
                                            <div class="flex items-center gap-2 justify-end">
                                                    <button type="button" 
                                                        class="rounded-full cursor-pointer p-1.5 text-slate-600 transition-colors hover:bg-slate-200 hover:text-slate-900"
                                                        title="Aperçu des informations"
                                                        aria-label="Aperçu des informations"
                                                        data-event-id="{{ $event->id }}"
                                                        onclick="window.adminOpenEventDetailModal({{ $event->id }})">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                    </button>

                                                    <a href="{{ route('admin.events.edit', $event) }}" title="Éditer" aria-label="Éditer la soirée" class="p-1.5 rounded-full hover:bg-yellow-500/10 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-yellow-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                </a>
                                               
                                                    @if ($event->status !== 'archived')
                                                        <form id="archive-event-form-{{ $event->id }}" method="POST"
                                                            action="{{ route('admin.events.archive', $event) }}" class="m-0 inline">
                                                            @csrf
                                                            <button type="button"
                                                                class="rounded-full p-1.5 text-red-600 transition-colors cursor-pointer hover:bg-red-100 hover:text-red-700"
                                                                title="Archiver" aria-label="Archiver la soirée"
                                                                onclick='window.adminOpenArchiveConfirmModal(document.getElementById("archive-event-form-{{ $event->id }}"), @json($event->name))'>
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                    <div style="margin-top: 24px; display: flex; justify-content: center;">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>

            <!-- Vue Kanban -->
            <div id="kanban-view" class="view-container" style="display: none;">
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
                    @foreach ($events as $event)
                        @php $status = $statusColors[$event->status] ?? $statusColors['draft']; @endphp
                            <div class="overflow-hidden rounded-3xl border border-stone-100 bg-white shadow-premium transition-all duration-300 hover:shadow-premium-hover" style="padding: 0; background: #fff; border: 1px solid var(--we-border); transition: transform 0.2s ease, box-shadow 0.2s ease;"
                             onclick="window.location.href='{{ route('admin.events.edit', $event) }}'">
                            @if($event->hero_image_path)
                                <div style="width: 100%; height: 180px; overflow: hidden; background: #f0f0f0; position: relative;">
                                    <img src="{{ Storage::url($event->hero_image_path) }}" alt="{{ $event->name }}" 
                                         style="width: 100%; height: 100%; object-fit: cover;" />
                                    <!-- Badge de statut sur l'image -->
                                    <div style="position: absolute; top: 12px; right: 12px;">
                                        <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; letter-spacing: 0.5px; backdrop-filter: blur(8px); background: rgba(255,255,255,0.95); border: 1px solid {{ $status['border'] }};">
                                            {{ $status['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <!-- Badge de statut sans image -->
                                <div style="padding: 16px 20px; border-bottom: 1px solid var(--we-border); background: {{ $status['bg'] }}; display: flex; justify-content: flex-end;">
                                    <span style="display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid {{ $status['border'] }};">
                                        {{ $status['label'] }}
                                    </span>
                                </div>
                            @endif

                            <div style="padding: 20px;">
                                <div style="font-weight: 800; font-size: 18px; color: var(--we-text); margin-bottom: 12px; line-height: 1.3;">{{ $event->name }}</div>

                                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: var(--we-text);">
                                        <span style="font-size: 18px;">📅</span>
                                        <div>
                                            <div style="font-weight: 600;">{{ optional($event->starts_at)->format('d/m/Y') }}</div>
                                            <div style="font-size: 12px; color: var(--we-muted);">{{ optional($event->starts_at)->format('H:i') }} - {{ optional($event->ends_at)->format('H:i') }}</div>
                                        </div>
                                    </div>
                                    <div style="display: flex; align-items: flex-start; gap: 10px; font-size: 14px; color: var(--we-text);">
                                        <span style="font-size: 18px; flex-shrink: 0;">📍</span>
                                        <div>
                                            <div style="font-weight: 600;">{{ $event->venue_name }}</div>
                                            <div style="font-size: 12px; color: var(--we-muted);">{{ strlen($event->venue_address) > 50 ? substr($event->venue_address, 0, 50) . '...' : $event->venue_address }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: flex-end; gap: 8px; flex-wrap: wrap; padding-top: 16px; border-top: 1px solid var(--we-border);">
                                        <button type="button"
                                            class="btn secondary cursor-pointer"
                                            style="padding: 8px 20px; font-size: 13px;"
                                            onclick="event.stopPropagation(); window.adminOpenEventDetailModal({{ $event->id }})">
                                        Voir
                                        </button>
                                            
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn secondary text-yellow-500 border-yellow-500 hover:bg-yellow-600 hover:text-white transition-colors" style="padding: 8px 20px; font-size: 13px; text-align: center;" onclick="event.stopPropagation()">
                                        Éditer
                                    </a>

                                        @if ($event->status !== 'archived')
                                            <form id="archive-event-form-kanban-{{ $event->id }}" method="POST"
                                                action="{{ route('admin.events.archive', $event) }}" class="m-0 inline"
                                                onclick="event.stopPropagation()">
                                                @csrf
                                                <button type="button"
                                                    class="btn secondary text-red-500 border-red-500 hover:bg-red-600 hover:text-white transition-colors" style="padding: 8px 20px; font-size: 13px; text-align: center;"
                                                    title="Archiver" aria-label="Archiver la soirée"
                                                    onclick='event.stopPropagation(); window.adminOpenArchiveConfirmModal(document.getElementById("archive-event-form-kanban-{{ $event->id }}"), @json($event->name))'>
                                                    Archiver
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($events->hasPages())
                    <div style="margin-top: 24px; display: flex; justify-content: center;">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
            @endif

            <script type="application/json" id="admin-events-detail-json">@json($eventsDetailPayload)</script>

            <div id="event-detail-modal" class="fixed inset-0 z-[100] hidden" aria-hidden="true" role="dialog" aria-modal="true"
                aria-labelledby="event-detail-modal-title">
                <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm transition-opacity" data-event-detail-backdrop></div>
                <div
                    class="pointer-events-none relative z-10 mx-auto flex min-h-full max-h-[100dvh] items-center justify-center p-4 sm:p-6">
                    <div
                        class="pointer-events-auto relative flex max-h-[min(90dvh,900px)] w-full max-w-3xl flex-col overflow-hidden rounded-2xl border border-stone-200/80 bg-white shadow-2xl shadow-stone-900/10">
                        <button type="button"
                            class="absolute right-3 top-3 z-20 flex p-1 cursor-pointer items-center justify-center rounded-full border border-stone-200/90 bg-white text-slate-500 shadow-sm transition hover:bg-stone-50 hover:text-slate-900"
                            data-event-detail-close
                            aria-label="Fermer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div id="event-detail-modal-body" class="min-h-0 flex-1 overflow-y-auto overscroll-contain"></div>
                    </div>
                </div>
            </div>

            <div id="archive-confirm-modal" class="fixed inset-0 z-[105] hidden" aria-hidden="true" role="dialog" aria-modal="true"
                aria-labelledby="archive-confirm-title">
                <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-sm transition-opacity" data-archive-confirm-backdrop></div>
                <div
                    class="pointer-events-none relative z-10 mx-auto flex min-h-full items-center justify-center p-4 sm:p-6">
                    <div
                        class="pointer-events-auto w-full max-w-md rounded-2xl border border-stone-200/80 bg-white p-6 shadow-2xl shadow-stone-900/15 sm:p-8">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600 ring-1 ring-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </div>
                        <h2 id="archive-confirm-title" class="text-center text-lg font-black tracking-tight text-slate-900 sm:text-xl">
                            Archiver cette soirée ?
                        </h2>
                        <p class="mt-2 text-center text-sm leading-relaxed text-slate-600">
                            <span class="font-semibold text-slate-800" id="archive-confirm-event-name"></span>
                            <span class="block mt-1">Elle ne sera plus visible sur le site public. Vous pourrez toujours la consulter ou la modifier depuis l’administration.</span>
                        </p>
                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button type="button"
                                class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-stone-50 sm:w-auto"
                                data-archive-confirm-cancel>
                                Annuler
                            </button>
                            <button type="button" id="archive-confirm-submit"
                                class="inline-flex w-full cursor-pointer items-center justify-center rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-red-700 sm:w-auto">
                                Archiver
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <style>
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        </style>

        <style>
            .view-toggle-btn.active {
                background: #fff !important;
                color: var(--we-primary) !important;
                box-shadow: 0 2px 4px rgba(15,23,42,0.1);
            }

            .view-container {
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>

        <script>
            function closeSuccessPopup() {
                const popup = document.getElementById('success-popup');
                if (popup) {
                    popup.style.animation = 'slideOutRight 0.3s ease-out';
                    setTimeout(() => {
                        popup.remove();
                    }, 300);
                }
            }

            function switchView(view) {
                const listView = document.getElementById('list-view');
                const kanbanView = document.getElementById('kanban-view');
                const listBtn = document.getElementById('view-list-btn');
                const kanbanBtn = document.getElementById('view-kanban-btn');

                if (view === 'list') {
                    listView.style.display = 'block';
                    kanbanView.style.display = 'none';
                    listBtn.classList.add('active');
                    kanbanBtn.classList.remove('active');
                    localStorage.setItem('events-view', 'list');
                } else {
                    listView.style.display = 'none';
                    kanbanView.style.display = 'block';
                    kanbanBtn.classList.add('active');
                    listBtn.classList.remove('active');
                    localStorage.setItem('events-view', 'kanban');
                }
            }

                (function eventDetailModal() {
                    const fmtMoney = (cents, currency) =>
                        new Intl.NumberFormat('fr-FR', {
                            maximumFractionDigits: 0
                        }).format(Number(cents || 0)) + '\u00a0' + (currency || 'FCFA');

                    function escapeHtml(s) {
                        if (s == null || s === '') return '';
                        return String(s)
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                    }

                    function fmtDate(iso) {
                        if (!iso) return '—';
                        const d = new Date(iso);
                        if (Number.isNaN(d.getTime())) return '—';
                        return d.toLocaleDateString('fr-FR', {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    }

                    function fmtShortDate(iso) {
                        if (!iso) return '—';
                        const d = new Date(iso);
                        if (Number.isNaN(d.getTime())) return '—';
                        return d.toLocaleDateString('fr-FR', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                    }

                    function fmtTime(iso) {
                        if (!iso) return '';
                        const d = new Date(iso);
                        if (Number.isNaN(d.getTime())) return '';
                        return d.toLocaleTimeString('fr-FR', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }

                    function fmtDateTime(iso) {
                        if (!iso) return '—';
                        return fmtShortDate(iso) + ' · ' + fmtTime(iso);
                    }

                    function statusBadgeClass(status) {
                        if (status === 'published') {
                            return 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200/80';
                        }
                        if (status === 'archived') {
                            return 'bg-slate-100 text-slate-700 ring-1 ring-slate-200';
                        }
                        return 'bg-amber-50 text-amber-900 ring-1 ring-amber-200/80';
                    }

                    let detailById = {};

                    function parseDetailPayload() {
                        const el = document.getElementById('admin-events-detail-json');
                        if (!el || !el.textContent) return;
                        try {
                            const rows = JSON.parse(el.textContent);
                            detailById = {};
                            rows.forEach((row) => {
                                detailById[row.id] = row;
                            });
                        } catch (e) {
                            detailById = {};
                        }
                    }

                    function renderEventDetailHtml(e) {
                        const remaining = Math.max(0, Number(e.capacity || 0) - Number(e.sold_tickets || 0));
                        const ttRows = (e.ticket_types || [])
                            .map((t) => {
                                const limit =
                                    t.quantity_limit != null ? String(t.quantity_limit) : '∞';
                                const sold = Number(t.sold_tickets || 0);
                                const win =
                                    (t.sales_starts_at || t.sales_ends_at) ?
                                    `${fmtDateTime(t.sales_starts_at)} → ${fmtDateTime(t.sales_ends_at)}` :
                                    '—';
                                const active = t.is_active ?
                                    '<span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-800">Actif</span>' :
                                    '<span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-600">Inactif</span>';
                                return (
                                    '<tr class="border-t border-amber-100/80 align-top transition-colors hover:bg-amber-50/40">' +
                                    '<td class="px-3 py-3 text-sm font-semibold text-slate-900">' +
                                    escapeHtml(t.name) +
                                    ' ' +
                                    active +
                                    '</td>' +
                                    '<td class="px-3 py-3 text-right text-sm tabular-nums text-slate-800">' +
                                    fmtMoney(t.price_cents, t.currency) +
                                    '</td>' +
                                    '<td class="px-3 py-3 text-center text-sm tabular-nums text-slate-600">' +
                                    sold +
                                    ' / ' +
                                    limit +
                                    '</td>' +
                                    '<td class="hidden px-3 py-3 text-xs text-slate-500 sm:table-cell">' +
                                    escapeHtml(win) +
                                    '</td>' +
                                    '</tr>'
                                );
                            })
                            .join('');

                        const hero = e.hero_image_url ?
                            '<div class="relative h-44 w-full overflow-hidden bg-stone-100 sm:h-52">' +
                            '<img src="' +
                            escapeHtml(e.hero_image_url) +
                            '" alt="" class="h-full w-full object-cover" loading="lazy" />' +
                            '<div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>' +
                            '<div class="absolute bottom-4 left-4 right-4 flex flex-wrap items-end justify-between gap-2">' +
                            '<h2 id="event-detail-modal-title" class="text-xl font-black tracking-tight text-white drop-shadow sm:text-2xl">' +
                            escapeHtml(e.name) +
                            '</h2>' +
                            '<span class="shrink-0 rounded-full bg-white/95 px-3 py-1 text-xs font-bold uppercase tracking-wide text-slate-800 shadow-sm ring-1 ring-white/50">' +
                            escapeHtml(e.status_label) +
                            '</span>' +
                            '</div></div>' :
                            '<div class="border-b border-stone-100 bg-gradient-to-br from-orange-50/90 to-stone-50 px-6 py-5">' +
                            '<div class="flex flex-wrap items-start justify-between gap-3">' +
                            '<div class="min-w-0">' +
                            '<h2 id="event-detail-modal-title" class="text-xl font-black tracking-tight text-slate-900 sm:text-2xl">' +
                            escapeHtml(e.name) +
                            '</h2>' +
                            '<p class="mt-1 font-mono text-xs text-slate-500">' +
                            escapeHtml(e.slug) +
                            '</p></div>' +
                            '<span class="shrink-0 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide ' +
                            statusBadgeClass(e.status) +
                            '">' +
                            escapeHtml(e.status_label) +
                            '</span></div></div>';

                        const titleBlock = e.hero_image_url ?
                            '<div class="border-b border-orange-100/80 bg-gradient-to-r from-orange-50/90 to-amber-50/40 px-6 py-4">' +
                            '<p class="font-mono text-xs text-orange-900/70">' +
                            escapeHtml(e.slug) +
                            '</p></div>' :
                            '';

                        const desc = e.description ?
                            '<section class="px-4 py-4 sm:px-6">' +
                            '<div class="rounded-2xl border border-orange-200/70 bg-gradient-to-br from-orange-50/90 via-white to-amber-50/30 p-5 shadow-sm">' +
                            '<h3 class="text-[11px] font-bold uppercase tracking-wide text-orange-800">Description</h3>' +
                            '<div class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-slate-700">' +
                            escapeHtml(e.description) +
                            '</div></div></section>' :
                            '';

                        const theme = e.theme ?
                            '<section class="border-t border-stone-100 px-4 py-4 sm:px-6">' +
                            '<div class="rounded-2xl border border-violet-200/70 bg-violet-50/50 p-5">' +
                            '<h3 class="text-[11px] font-bold uppercase tracking-wide text-violet-800">Thème</h3>' +
                            '<p class="mt-1 text-sm font-medium text-slate-800">' +
                            escapeHtml(e.theme) +
                            '</p></div></section>' :
                            '';

                        return (
                            hero +
                            titleBlock +
                            '<div class="grid gap-3 border-b border-stone-100/80 bg-slate-50/30 p-4 sm:grid-cols-2">' +
                            '<section class="rounded-xl border border-sky-200/70 bg-sky-50/60 p-4 shadow-sm">' +
                            '<h3 class="text-[11px] font-bold uppercase tracking-wide text-sky-800">Date &amp; horaires</h3>' +
                            '<p class="mt-2 text-sm font-semibold text-slate-900">' +
                            fmtDate(e.starts_at) +
                            '</p>' +
                            '<p class="mt-1 text-sm text-sky-900/80">' +
                            fmtTime(e.starts_at) +
                            ' – ' +
                            fmtTime(e.ends_at) +
                            '</p></section>' +
                            '<section class="rounded-xl border border-violet-200/70 bg-violet-50/50 p-4 shadow-sm">' +
                            '<h3 class="text-[11px] font-bold uppercase tracking-wide text-violet-800">Lieu</h3>' +
                            '<p class="mt-2 text-sm font-semibold text-slate-900">' +
                            escapeHtml(e.venue_name || '—') +
                            '</p>' +
                            '<p class="mt-1 text-sm leading-relaxed text-violet-950/70">' +
                            escapeHtml(e.venue_address || '') +
                            '</p></section></div>' +
                            '<div class="grid gap-3 border-b border-stone-100/80 bg-gradient-to-r from-stone-50/50 to-orange-50/20 p-4 sm:grid-cols-3">' +
                            '<div class="rounded-xl border border-orange-200/70 bg-orange-50/50 px-4 py-4 shadow-sm">' +
                            '<p class="text-[11px] font-bold uppercase tracking-wide text-orange-800">Capacité</p>' +
                            '<p class="mt-1 text-lg font-black tabular-nums text-slate-900">' +
                            escapeHtml(String(e.capacity ?? '—')) +
                            '</p></div>' +
                            '<div class="rounded-xl border border-amber-200/70 bg-amber-50/50 px-4 py-4 shadow-sm">' +
                            '<p class="text-[11px] font-bold uppercase tracking-wide text-amber-900">Places réservées</p>' +
                            '<p class="mt-1 text-lg font-black tabular-nums text-slate-900">' +
                            escapeHtml(String(e.sold_tickets ?? 0)) +
                            '</p></div>' +
                            '<div class="rounded-xl border border-emerald-200/70 bg-emerald-50/60 px-4 py-4 shadow-sm">' +
                            '<p class="text-[11px] font-bold uppercase tracking-wide text-emerald-800">Reste</p>' +
                            '<p class="mt-1 text-lg font-black tabular-nums text-emerald-800">' +
                            remaining +
                            '</p></div></div>' +
                            '<div class="grid gap-3 border-b border-stone-100/80 p-4 sm:grid-cols-2">' +
                            '<div class="rounded-xl border border-rose-200/70 bg-rose-50/50 px-4 py-4 shadow-sm">' +
                            '<p class="text-[11px] font-bold uppercase tracking-wide text-rose-800">Âge minimum</p>' +
                            '<p class="mt-1 text-sm font-semibold text-slate-800">' +
                            (e.min_age != null ? escapeHtml(String(e.min_age)) + ' ans' : '—') +
                            '</p></div>' +
                            '<div class="rounded-xl border border-indigo-200/70 bg-indigo-50/50 px-4 py-4 shadow-sm">' +
                            '<p class="text-[11px] font-bold uppercase tracking-wide text-indigo-800">Fin des ventes (événement)</p>' +
                            '<p class="mt-1 text-sm font-semibold text-slate-800">' +
                            fmtDateTime(e.sales_ends_at) +
                            '</p></div></div>' +
                            (e.published_at || e.archived_at ?
                                '<div class="border-b border-slate-200/60 bg-slate-100/60 px-6 py-3 text-xs text-slate-600">' +
                                '<span class="border-l-4 border-slate-400 pl-3">' +
                                (e.published_at ?
                                    '<span class="font-medium text-slate-700">Publié le ' +
                                    fmtDateTime(e.published_at) +
                                    '</span>' :
                                    '') +
                                (e.published_at && e.archived_at ? ' · ' : '') +
                                (e.archived_at ?
                                    '<span class="font-medium text-slate-700">Archivé le ' +
                                    fmtDateTime(e.archived_at) +
                                    '</span>' :
                                    '') +
                                '</span></div>' :
                                '') +
                            theme +
                            desc +
                            '<section class="border-t border-amber-100/80 bg-gradient-to-b from-amber-50/40 to-white px-4 py-5 sm:px-6">' +
                            '<h3 class="text-[11px] font-bold uppercase tracking-wide text-amber-900">Tarifs</h3>' +
                            (ttRows ?
                                '<div class="mt-3 overflow-x-auto rounded-xl border border-amber-200/60 bg-white shadow-sm">' +
                                '<table class="w-full min-w-[520px] border-collapse text-left">' +
                                '<thead><tr class="bg-gradient-to-r from-orange-100/80 to-amber-100/60 text-[10px] font-bold uppercase tracking-wide text-orange-900">' +
                                '<th class="px-3 py-2.5">Tarif</th>' +
                                '<th class="px-3 py-2.5 text-right">Prix</th>' +
                                '<th class="px-3 py-2.5 text-center">Ventes</th>' +
                                '<th class="hidden px-3 py-2.5 sm:table-cell">Fenêtre de vente</th>' +
                                '</tr></thead><tbody class="bg-white">' +
                                ttRows +
                                '</tbody></table></div>' :
                                '<p class="mt-2 text-sm text-slate-500">Aucun tarif défini.</p>') +
                            '</section>' +
                            '<div class="sticky bottom-0 flex flex-col gap-4 border-t border-orange-100/80 bg-gradient-to-r from-orange-50/40 via-white to-stone-50/50 px-6 py-4 backdrop-blur sm:flex-row sm:justify-end">' +
                            '<button type="button" class="inline-flex cursor-pointer items-center justify-center rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-stone-50" data-event-detail-close>Fermer</button>' +
                            '<a href="' +
                            escapeHtml(e.edit_url) +
                            '" class="inline-flex items-center justify-center rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-orange-700">Éditer</a>' +
                            '</div>'
                        );
                    }

                    function openModal() {
                        const modal = document.getElementById('event-detail-modal');
                        if (!modal) return;
                        modal.classList.remove('hidden');
                        modal.setAttribute('aria-hidden', 'false');
                        syncBodyScrollLock();
                    }

                    function syncBodyScrollLock() {
                        const detail = document.getElementById('event-detail-modal');
                        const arch = document.getElementById('archive-confirm-modal');
                        const detailOpen = detail && !detail.classList.contains('hidden');
                        const archOpen = arch && !arch.classList.contains('hidden');
                        document.body.classList.toggle('overflow-hidden', detailOpen || archOpen);
                    }

                    function closeModal() {
                        const modal = document.getElementById('event-detail-modal');
                        if (!modal) return;
                        modal.classList.add('hidden');
                        modal.setAttribute('aria-hidden', 'true');
                        const body = document.getElementById('event-detail-modal-body');
                        if (body) body.innerHTML = '';
                        syncBodyScrollLock();
                    }

                    window.adminOpenEventDetailModal = function(id) {
                        parseDetailPayload();
                        const e = detailById[id];
                        const body = document.getElementById('event-detail-modal-body');
                        if (!e || !body) return;
                        body.innerHTML = renderEventDetailHtml(e);
                        openModal();
                    };

                    document.getElementById('event-detail-modal')?.addEventListener('click', function(ev) {
                        const t = ev.target;
                        if (!(t instanceof Element)) return;
                        if (t.closest('[data-event-detail-close]')) {
                            ev.preventDefault();
                            closeModal();
                        }
                    });

                    document.addEventListener('click', function(ev) {
                        const t = ev.target;
                        if (!(t instanceof Element)) return;
                        if (t.matches('[data-event-detail-backdrop]')) closeModal();
                        if (t.matches('[data-archive-confirm-backdrop]')) closeArchiveConfirmModal();
                    });

                    let archiveFormPending = null;

                    function closeArchiveConfirmModal() {
                        archiveFormPending = null;
                        const modal = document.getElementById('archive-confirm-modal');
                        if (modal) {
                            modal.classList.add('hidden');
                            modal.setAttribute('aria-hidden', 'true');
                        }
                        syncBodyScrollLock();
                    }

                    window.adminOpenArchiveConfirmModal = function(form, eventName) {
                        if (!form) return;
                        archiveFormPending = form;
                        const nameEl = document.getElementById('archive-confirm-event-name');
                        if (nameEl) {
                            nameEl.textContent = eventName != null ? String(eventName) : '';
                        }
                        const modal = document.getElementById('archive-confirm-modal');
                        if (modal) {
                            modal.classList.remove('hidden');
                            modal.setAttribute('aria-hidden', 'false');
                            syncBodyScrollLock();
                        }
                    };

                    document.getElementById('archive-confirm-submit')?.addEventListener('click', function() {
                        if (archiveFormPending) {
                            archiveFormPending.submit();
                        }
                    });

                    document.querySelector('[data-archive-confirm-cancel]')?.addEventListener('click', closeArchiveConfirmModal);

                    document.addEventListener('keydown', function(ev) {
                        if (ev.key !== 'Escape') return;
                        const arch = document.getElementById('archive-confirm-modal');
                        if (arch && !arch.classList.contains('hidden')) {
                            closeArchiveConfirmModal();
                            return;
                        }
                        closeModal();
                    });

                    parseDetailPayload();
                })();

            document.addEventListener('DOMContentLoaded', function() {
                const popup = document.getElementById('success-popup');
                if (popup) {
                    setTimeout(() => {
                        closeSuccessPopup();
                    }, 5000);
                }

                const savedView = localStorage.getItem('events-view') || 'list';
                switchView(savedView);
            });
        </script>
@endsection
