@extends('layouts.admin')

@section('title', 'Admin · Tableau de bord')

@section('content')
    <!-- Header avec badge -->
    <div style="margin-bottom: 15px;">
        <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Tableau de bord</div>
    </div>

    <!-- Statistiques avec design premium -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.3px;">Vue d'ensemble</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
            <!-- Carte Soirées -->
            <a href="{{ route('admin.events.index') }}" class="card" style="padding: 28px; position: relative; overflow: hidden; transition: transform 0.2s ease, box-shadow 0.2s ease; text-decoration: none; display: block; cursor: pointer;"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                <div style="position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)); border-radius: 0 16px 0 100px; opacity: 0.5;"></div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <div style="width: 56px; height: 56px; border-radius: 14px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 28px; flex-shrink: 0;">📅</div>
                    <div style="flex: 1;">
                        <div class="muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Soirées</div>
                        <div style="font-size: 32px; font-weight: 900; color: var(--we-text); line-height: 1;">{{ $eventsCount }}</div>
                    </div>
                </div>
                <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--we-primary); margin-top: 8px;">
                    Voir toutes <span>→</span>
                </div>
            </a>

            <!-- Carte Réservations -->
            <a href="{{ route('admin.orders.index') }}" class="card" style="padding: 28px; position: relative; overflow: hidden; transition: transform 0.2s ease, box-shadow 0.2s ease; text-decoration: none; display: block; cursor: pointer;"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                <div style="position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(96, 165, 250, 0.05)); border-radius: 0 16px 0 100px; opacity: 0.5;"></div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <div style="width: 56px; height: 56px; border-radius: 14px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(96, 165, 250, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 28px; flex-shrink: 0;">🎫</div>
                    <div style="flex: 1;">
                        <div class="muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Réservation</div>
                        <div style="font-size: 32px; font-weight: 900; color: var(--we-text); line-height: 1;">{{ $ordersCount }}</div>
                    </div>
                </div>
                <div style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--we-primary); margin-top: 8px;">
                    Voir toutes <span>→</span>
                </div>
            </a>

            <!-- Carte Réservations payées -->
            <a href="{{ route('admin.orders.index') }}?status=paid" class="card" style="padding: 28px; position: relative; overflow: hidden; transition: transform 0.2s ease, box-shadow 0.2s ease; text-decoration: none; display: block; cursor: pointer;"
               onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,0.12)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'">
                <div style="position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(74, 222, 128, 0.05)); border-radius: 0 16px 0 100px; opacity: 0.5;"></div>
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <div style="width: 56px; height: 56px; border-radius: 14px; background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(74, 222, 128, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 28px; flex-shrink: 0;">✅</div>
                    <div style="flex: 1;">
                        <div class="muted" style="font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Payées</div>
                        <div style="font-size: 32px; font-weight: 900; color: var(--we-text); line-height: 1;">{{ $paidOrdersCount }}</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: var(--we-muted); margin-top: 8px;">
                    @if($ordersCount > 0)
                        {{ round(($paidOrdersCount / $ordersCount) * 100) }}% du total
                    @else
                        Aucune réservation
                    @endif
                </div>
            </a>
        </div>
    </div>

    <!-- Actions rapides -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 24px; font-weight: 900; margin-bottom: 20px; letter-spacing: -0.3px;">Actions rapides</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
            <a href="{{ route('admin.events.create') }}" class="card" style="padding: 24px; text-decoration: none; transition: transform 0.2s ease, box-shadow 0.2s ease; display: block; cursor: pointer; border: 2px solid transparent;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(15,23,42,0.1)'; this.style.borderColor='rgba(234, 88, 12, 0.2)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'; this.style.borderColor='transparent'">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.15), rgba(245, 130, 32, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">➕</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 4px;">Créer une soirée</div>
                        <div class="muted" style="font-size: 13px;">Ajouter un nouvel événement</div>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.controllers.create') }}" class="card" style="padding: 24px; text-decoration: none; transition: transform 0.2s ease, box-shadow 0.2s ease; display: block; cursor: pointer; border: 2px solid transparent;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(15,23,42,0.1)'; this.style.borderColor='rgba(234, 88, 12, 0.2)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'; this.style.borderColor='transparent'">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(96, 165, 250, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">👤</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 4px;">Ajouter un contrôleur</div>
                        <div class="muted" style="font-size: 13px;">Créer un compte contrôleur</div>
                    </div>
                </div>
            </a>
            <a href="{{ route('public.events.index') }}" target="_blank" class="card" style="padding: 24px; text-decoration: none; transition: transform 0.2s ease, box-shadow 0.2s ease; display: block; cursor: pointer; border: 2px solid transparent;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(15,23,42,0.1)'; this.style.borderColor='rgba(234, 88, 12, 0.2)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.04), 0 12px 30px rgba(15,23,42,0.06)'; this.style.borderColor='transparent'">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, rgba(148, 163, 184, 0.15), rgba(203, 213, 225, 0.08)); display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">🌐</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; font-size: 16px; color: var(--we-text); margin-bottom: 4px;">Voir le site public</div>
                        <div class="muted" style="font-size: 13px;">Ouvrir dans un nouvel onglet</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
