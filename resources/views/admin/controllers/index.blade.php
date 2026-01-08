@extends('layouts.app')

@section('title', 'Admin · Contrôleurs')

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 900;">Admin · Contrôleurs</div>
                <div class="muted">Comptes autorisés à accéder au scanner.</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="btn secondary" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="btn" href="{{ route('admin.controllers.create') }}">Ajouter un contrôleur</a>
            </div>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow:hidden;">
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align:left; background: rgba(255,255,255,0.06);">
                    <th style="padding: 12px;">Nom</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Rôles</th>
                    <th style="padding: 12px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($controllers as $user)
                    <tr style="border-top: 1px solid rgba(255,255,255,0.10);">
                        <td style="padding: 12px;">
                            <div style="font-weight: 850;">{{ $user->name }}</div>
                        </td>
                        <td style="padding: 12px;" class="muted">{{ $user->email }}</td>
                        <td style="padding: 12px;">
                            @foreach ($user->roles as $role)
                                <span class="card" style="display:inline-block; padding: 6px 10px; border-radius: 999px; margin-right:6px;">
                                    {{ $role->slug }}
                                </span>
                            @endforeach
                        </td>
                        <td style="padding: 12px; text-align:right;">
                            <form method="POST" action="{{ route('admin.controllers.revoke', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn secondary" type="submit">Retirer rôle</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 14px;">
        {{ $controllers->links() }}
    </div>
@endsection

