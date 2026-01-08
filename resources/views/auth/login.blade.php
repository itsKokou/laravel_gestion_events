@extends('layouts.app')

@section('title', 'Connexion · Win’s Events')

@section('content')
    <div class="card" style="max-width: 520px; margin: 0 auto;">
        <div style="font-size: 22px; font-weight: 850;">Connexion</div>
        <div class="muted" style="margin-top: 6px;">Accès réservé (admin / contrôleur).</div>

        @if ($errors->any())
            <div class="card" style="margin-top: 12px;">
                <div style="font-weight: 800; margin-bottom: 8px;">Erreurs</div>
                <ul class="error" style="line-height:1.55;">
                    @foreach ($errors->all() as $e)
                        <li>- {{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" style="margin-top: 14px;">
            @csrf
            <div class="grid">
                <div>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />
                </div>
                <div>
                    <label for="password">Mot de passe</label>
                    <input id="password" name="password" type="password" required />
                </div>
                <label style="display:flex; gap:10px; align-items:flex-start;">
                    <input type="checkbox" name="remember" value="1" style="width:auto; margin-top: 4px;" @checked(old('remember')) />
                    <span class="muted">Se souvenir de moi</span>
                </label>
                <button class="btn" type="submit">Se connecter</button>
            </div>
        </form>
    </div>
@endsection

