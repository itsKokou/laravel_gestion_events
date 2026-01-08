@extends('layouts.app')

@section('title', 'Admin · Réservations')

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 900;">Admin · Réservations</div>
                <div class="muted">Liste des commandes.</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="btn secondary" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="btn secondary" href="{{ route('admin.events.index') }}">Soirées</a>
            </div>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow:hidden;">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align:left; background: rgba(255,255,255,0.06);">
                    <th style="padding: 12px;">Commande</th>
                    <th style="padding: 12px;">Soirée</th>
                    <th style="padding: 12px;">Statut</th>
                    <th style="padding: 12px;">Total</th>
                    <th style="padding: 12px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr style="border-top: 1px solid rgba(255,255,255,0.10);">
                        <td style="padding: 12px;">
                            <a href="{{ route('public.orders.show', $order) }}" class="btn secondary" style="padding: 6px 10px;">
                                {{ $order->order_number }}
                            </a>
                            <div class="muted" style="font-size: 12px; margin-top: 6px;">{{ $order->customer_email }}</div>
                        </td>
                        <td style="padding: 12px;">
                            <div style="font-weight: 800;">{{ $order->event?->name }}</div>
                            <div class="muted" style="font-size: 12px;">{{ $order->event?->slug }}</div>
                        </td>
                        <td style="padding: 12px;">
                            <span class="card" style="display:inline-block; padding: 6px 10px; border-radius: 999px;">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td style="padding: 12px;" class="muted">
                            {{ number_format($order->total_cents / 100, 2, ',', ' ') }} {{ $order->currency }}
                        </td>
                        <td style="padding: 12px;" class="muted">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 14px;">
        {{ $orders->links() }}
    </div>
@endsection

