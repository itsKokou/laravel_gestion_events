@extends('layouts.app')

@section('title', 'Admin · Soirées')

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 900;">Admin · Soirées</div>
                <div class="muted">Créer, modifier, publier.</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="btn secondary" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="btn" href="{{ route('admin.events.create') }}">Nouvelle soirée</a>
            </div>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow:hidden;">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align:left; background: rgba(255,255,255,0.06);">
                    <th style="padding: 12px;">Nom</th>
                    <th style="padding: 12px;">Date</th>
                    <th style="padding: 12px;">Statut</th>
                    <th style="padding: 12px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr style="border-top: 1px solid rgba(255,255,255,0.10);">
                        <td style="padding: 12px;">
                            <div style="font-weight: 800;">{{ $event->name }}</div>
                            <div class="muted" style="font-size: 12px;">{{ $event->slug }}</div>
                        </td>
                        <td style="padding: 12px;" class="muted">
                            {{ optional($event->starts_at)->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding: 12px;">
                            <span class="card" style="display:inline-block; padding: 6px 10px; border-radius: 999px;">
                                {{ $event->status }}
                            </span>
                        </td>
                        <td style="padding: 12px; text-align:right;">
                            <a class="btn secondary" href="{{ route('admin.events.edit', $event) }}">Éditer</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 14px;">
        {{ $events->links() }}
    </div>
@endsection

