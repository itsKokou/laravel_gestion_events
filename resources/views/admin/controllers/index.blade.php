@extends('layouts.admin')

@section('title', 'Admin · Contrôleurs')

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Gestion</div>
                <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">Contrôleurs</h1>
                <p class="muted" style="font-size: 16px;">Comptes autorisés à accéder au scanner pour contrôler les entrées.</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <!--<a class="btn secondary" href="{{ route('admin.dashboard') }}" style="padding: 12px 20px;">Dashboard</a>-->
                <a class="btn" href="{{ route('admin.controllers.create') }}" style="padding: 12px 20px;">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Ajouter un contrôleur
                </a>
            </div>
        </div>
    </div>

    <!-- Tableau des contrôleurs -->
    @if($controllers->count() > 0)
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-bottom: 2px solid var(--we-border);">
                            <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Nom</th>
                            <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Email</th>
                            <th style="padding: 16px 20px; text-align: center; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Rôles</th>
                            <th style="padding: 16px 20px; text-align: right; font-size: 13px; font-weight: 700; color: var(--we-text); text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($controllers as $user)
                            <tr style="border-top: 1px solid var(--we-border); transition: background 0.2s ease;"
                                onmouseover="this.style.background='#fafafa'"
                                onmouseout="this.style.background='transparent'">
                                <td style="padding: 20px;">
                                    <div style="font-weight: 800; font-size: 16px; color: var(--we-text);">{{ $user->name }}</div>
                                </td>
                                <td style="padding: 20px;">
                                    <div style="font-weight: 600; font-size: 14px; color: var(--we-text); margin-bottom: 4px;">{{ $user->email }}</div>
                                </td>
                                <td style="padding: 20px; text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                        @foreach ($user->roles as $role)
                                            @php
                                                $roleColors = [
                                                    'admin' => ['bg' => 'rgba(234, 88, 12, 0.1)', 'text' => '#ea580c'],
                                                    'controller' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'text' => '#3b82f6'],
                                                ];
                                                $roleColor = $roleColors[$role->slug] ?? ['bg' => 'rgba(148, 163, 184, 0.1)', 'text' => '#64748b'];
                                            @endphp
                                            <span style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; background: {{ $roleColor['bg'] }}; color: {{ $roleColor['text'] }}; text-transform: uppercase; letter-spacing: 0.5px;">
                                                {{ $role->slug }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="padding: 20px; text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap;">
                                        <form method="POST" action="{{ route('admin.controllers.reset_password', $user) }}" style="margin: 0;">
                                            @csrf
                                            <button class="btn secondary" type="submit" style="padding: 8px 16px; font-size: 13px;">
                                                🔑 Réinitialiser MDP
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.controllers.revoke', $user) }}" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn secondary" type="submit" 
                                                    style="padding: 8px 16px; font-size: 13px; background: #fef2f2; border-color: #fecaca; color: #dc2626;"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir retirer le rôle contrôleur à {{ $user->name }} ?')"
                                                    onmouseover="this.style.background='#fee2e2'"
                                                    onmouseout="this.style.background='#fef2f2'">
                                                ✕ Retirer rôle
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($controllers->hasPages())
            <div style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $controllers->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="card" style="padding: 64px 32px; text-align: center;">
            <div style="font-size: 64px; margin-bottom: 24px;">👥</div>
            <h3 style="font-size: 24px; font-weight: 900; margin-bottom: 12px;">Aucun contrôleur pour le moment</h3>
            <p class="muted" style="font-size: 16px; margin-bottom: 32px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Créez des comptes contrôleurs pour permettre à vos équipes d'accéder au scanner et de contrôler les entrées lors de vos soirées.
            </p>
            <a href="{{ route('admin.controllers.create') }}" class="btn" style="padding: 14px 28px; font-size: 16px;">
                ➕ Ajouter un contrôleur
            </a>
        </div>
    @endif
@endsection
