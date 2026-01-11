@extends('layouts.admin')

@section('title', 'Admin · Réservations')

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div
                    style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">
                    Gestion</div>
                <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">Réservations</h1>
                <p class="muted" style="font-size: 16px;">Liste de toutes les commandes et réservations.</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a class="btn secondary" href="{{ route('admin.dashboard') }}" style="padding: 12px 20px;">Dashboard</a>
                <a class="btn secondary" href="{{ route('admin.events.index') }}" style="padding: 12px 20px;">Soirées</a>
            </div>
        </div>
    </div>

    <!-- Tableau des réservations -->
    @if($orders->count() > 0)
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr
                            style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-bottom: 2px solid var(--we-border);">
                            <th
                                style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Commande</th>
                            <th
                                style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Soirée</th>
                            <th
                                style="padding: 16px 20px; text-align: center; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Statut</th>
                            <th
                                style="padding: 16px 20px; text-align: right; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Total</th>
                            <th
                                style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">
                                Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                                <tr style="border-top: 1px solid var(--we-border); transition: background 0.2s ease;"
                                    onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                                    <td style="padding: 20px;">
                                        <a href="{{ route('public.orders.show', $order) }}"
                                            style="display: inline-block; padding: 8px 14px; border-radius: 8px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)); color: var(--we-primary); font-weight: 700; font-size: 13px; text-decoration: none; font-family: ui-monospace, monospace; transition: transform 0.2s ease;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                            {{ $order->order_number }}
                                        </a>
                                        <div class="muted" style="font-size: 12px; margin-top: 8px;">{{ $order->customer_email }}</div>
                                        @if($order->customer_phone)
                                            <div class="muted" style="font-size: 12px;">{{ $order->customer_phone }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px;">
                                        <div style="font-weight: 800; font-size: 15px; color: var(--we-text); margin-bottom: 6px;">
                                            {{ $order->event?->name ?? 'N/A' }}</div>
                                        @if($order->event?->slug)
                                            <div class="muted" style="font-size: 12px; font-family: ui-monospace, monospace;">
                                                {{ $order->event->slug }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px; text-align: center;">
                                        @php
                                            $statusColors = [
                                                'pending_payment' => ['bg' => 'rgba(251, 191, 36, 0.1)', 'text' => '#d97706', 'label' => 'En attente'],
                                                'paid' => ['bg' => 'rgba(34, 197, 94, 0.1)', 'text' => '#16a34a', 'label' => 'Payée'],
                                                'cancelled' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Annulée'],
                                                'failed' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'text' => '#dc2626', 'label' => 'Échouée'],
                                            ];
                                            $status = $statusColors[$order->status] ?? $statusColors['pending_payment'];
                                        @endphp
                            <span
                                            style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; background: {{ $status['bg'] }}; color: {{ $status['text'] }}; text-transform: uppercase; letter-spacing: 0.5px;">
                                            {{ $status['label'] }}
                                        </span>
                                        @if($order->paid_at)
                                            <div class="muted" style="font-size: 11px; margin-top: 4px;">
                                                {{ $order->paid_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px; text-align: right;">
                                        <div style="font-weight: 800; font-size: 16px; color: var(--we-text);">
                                            {{ number_format($order->total_cents, 0, ',', ' ') }} {{ $order->currency }}
                                        </div>
                                        @if($order->subtotal_cents > 0 || $order->addons_total_cents > 0)
                                            <div class="muted" style="font-size: 11px; margin-top: 4px;">
                                                Billets: {{ number_format($order->subtotal_cents, 0, ',', ' ') }} {{ $order->currency }}
                                                @if($order->addons_total_cents > 0)
                                                    + Options: {{ number_format($order->addons_total_cents, 0, ',', ' ') }}
                                                    {{ $order->currency }}
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px;">
                                        <div style="font-weight: 600; font-size: 14px; color: var(--we-text);">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="muted" style="font-size: 12px;">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="card" style="padding: 64px 32px; text-align: center;">
            <div style="font-size: 64px; margin-bottom: 24px;">🎫</div>
            <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 12px;">Aucune réservation pour le moment</h3>
            <p class="muted"
                style="font-size: 16px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Les commandes et réservations apparaîtront ici une fois que vos soirées seront publiées et que les clients
                commenceront à réserver.
            </p>
            <a href="{{ route('admin.events.index') }}" class="btn secondary" style="padding: 14px 28px; font-size: 16px;">
                📅 Gérer les soirées
            </a>
        </div>
    @endif
@endsection