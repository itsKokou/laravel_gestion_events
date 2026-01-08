@extends('layouts.app')

@section('title', 'Admin · Tableau de bord')

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 900;">Admin · Tableau de bord</div>
                <div class="muted">Accès réservé aux administrateurs.</div>
            </div>
            <div style="display:flex; gap:10px; align-items:flex-start;">
                <a class="btn secondary" href="{{ route('admin.events.index') }}">Soirées</a>
                <a class="btn secondary" href="{{ route('admin.orders.index') }}">Réservations</a>
                <a class="btn secondary" href="{{ route('admin.controllers.index') }}">Contrôleurs</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn" type="submit">Déconnexion</button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <div class="card">
            <div class="muted">Soirées</div>
            <div style="font-size: 26px; font-weight: 900;">{{ $eventsCount }}</div>
        </div>
        <div class="card">
            <div class="muted">Commandes</div>
            <div style="font-size: 26px; font-weight: 900;">{{ $ordersCount }}</div>
        </div>
        <div class="card">
            <div class="muted">Commandes payées</div>
            <div style="font-size: 26px; font-weight: 900;">{{ $paidOrdersCount }}</div>
        </div>
    </div>
@endsection

