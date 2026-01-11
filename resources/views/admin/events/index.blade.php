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
                <a class="btn" href="{{ route('admin.events.create') }}" style="padding: 10px 16px;">
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
    @endphp

    @if($events->count() > 0)
        <!-- Vue Liste -->
        <div id="list-view" class="view-container">
            <div class="card" style="padding: 0; overflow: hidden;">
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
                                    <td style="padding: 20px; text-align: right;">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap;">
                                            <a href="{{ route('admin.events.edit', $event) }}" class="btn secondary" style="padding: 8px 16px; font-size: 13px;">
                                                ✏️ Modifier
                                            </a>
                                            @if($event->status === 'published')
                                                <a href="{{ route('public.events.show', $event) }}" target="_blank" class="btn secondary" style="padding: 8px 16px; font-size: 13px;">
                                                    👁️ Voir
                                                </a>
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
                    <div class="card" style="padding: 0; background: #fff; border: 1px solid var(--we-border); cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; overflow: hidden;"
                         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(15,23,42,0.12)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'"
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
                            
                            <div style="display: flex; gap: 8px; flex-wrap: wrap; padding-top: 16px; border-top: 1px solid var(--we-border);">
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn secondary" style="padding: 8px 16px; font-size: 13px; flex: 1; text-align: center;" onclick="event.stopPropagation()">
                                    ✏️ Modifier
                                </a>
                                @if($event->status === 'published')
                                    <a href="{{ route('public.events.show', $event) }}" target="_blank" class="btn secondary" style="padding: 8px 16px; font-size: 13px;" onclick="event.stopPropagation()">
                                        👁️ Voir
                                    </a>
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
    @else
        <!-- État vide -->
        <div class="card" style="padding: 64px 32px; text-align: center;">
            <div style="font-size: 64px; margin-bottom: 24px;">📅</div>
            <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 12px;">Aucune soirée pour le moment</h3>
            <p class="muted" style="font-size: 16px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Créez votre première soirée pour commencer à vendre des billets et gérer vos événements.
            </p>
        </div>
    @endif

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

        // Restaurer la vue sauvegardée au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('success-popup');
            if (popup) {
                setTimeout(() => {
                    closeSuccessPopup();
                }, 5000);
            }

            // Restaurer la vue
            const savedView = localStorage.getItem('events-view') || 'list';
            switchView(savedView);
        });
    </script>
@endsection
