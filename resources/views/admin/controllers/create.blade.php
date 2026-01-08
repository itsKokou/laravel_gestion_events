@extends('layouts.app')

@section('title', 'Admin · Ajouter un contrôleur')

@section('content')
    <div class="card" style="margin-bottom: 14px;">
        <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <div style="font-size: 22px; font-weight: 900;">Ajouter un contrôleur</div>
                <div class="muted">Crée un compte (ou attribue le rôle) pour accéder au scanner.</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="btn secondary" href="{{ route('admin.controllers.index') }}">Retour</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="margin-bottom: 14px;">
            <div style="font-weight: 800; margin-bottom: 8px;">Erreurs</div>
            <ul class="error" style="line-height:1.55;">
                @foreach ($errors->all() as $e)
                    <li>- {{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="card" method="POST" action="{{ route('admin.controllers.store') }}">
        @csrf

        <div class="grid grid2">
            <div>
                <label>Nom (optionnel)</label>
                <input name="name" value="{{ old('name') }}" placeholder="Ex: Fatou Ndiaye" />
            </div>
            <div>
                <label>Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required placeholder="ex: controleur@exemple.com" />
            </div>
        </div>

        <div style="margin-top: 12px;">
            <label>Mot de passe (optionnel)</label>
            <input name="password" type="password" placeholder="Si vide : mot de passe généré" />
            <div class="muted" style="font-size: 12px; margin-top: 6px;">
                Si l’utilisateur existe déjà : renseigner ici pour le réinitialiser, sinon laisser vide.
            </div>
        </div>

        <div style="margin-top: 14px; display:flex; justify-content:flex-end;">
            <button class="btn" type="submit">Enregistrer</button>
        </div>
    </form>
@endsection

