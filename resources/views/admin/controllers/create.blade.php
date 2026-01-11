@extends('layouts.admin')

@section('title', 'Admin · Ajouter un contrôleur')

@section('content')
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
            <div>
                <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Création</div>
                <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 8px; letter-spacing: -0.5px;">Ajouter un contrôleur</h1>
                <p class="muted" style="font-size: 16px;">Créez un compte ou attribuez le rôle contrôleur pour accéder au scanner.</p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a class="btn secondary" href="{{ route('admin.controllers.index') }}" style="padding: 12px 20px;">← Retour</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="margin-bottom: 24px; padding: 20px; background: #fef2f2; border-color: #fecaca;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                <div style="width: 24px; height: 24px; border-radius: 50%; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 14px; flex-shrink: 0;">!</div>
                <div style="font-weight: 700; color: #991b1b; font-size: 15px;">Erreurs de validation</div>
            </div>
            <ul style="margin-left: 36px; color: #7f1d1d; line-height: 1.8;">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.controllers.store') }}">
        @csrf

        <div class="card" style="padding: 32px; margin-bottom: 24px;">
            <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid var(--we-border);">
                <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 8px;">Informations du contrôleur</h2>
                <p class="muted" style="font-size: 14px;">Les informations nécessaires pour créer ou mettre à jour le compte.</p>
            </div>

            <div class="grid grid2" style="gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                        Nom complet
                    </label>
                    <input name="name" value="{{ old('name') }}" 
                           placeholder="Ex: Fatou Ndiaye"
                           style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;" 
                           onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                           onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                    <div class="muted" style="font-size: 12px; margin-top: 8px;">Optionnel - utilisé pour l'affichage</div>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                        Adresse email <span style="color: #dc2626;">*</span>
                    </label>
                    <input name="email" type="email" value="{{ old('email') }}" required 
                           placeholder="controleur@exemple.com"
                           style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;" 
                           onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                           onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                    <div class="muted" style="font-size: 12px; margin-top: 8px;">Si l'utilisateur existe déjà, le rôle sera ajouté</div>
                </div>
            </div>

            <div>
                <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                    Mot de passe
                </label>
                <input name="password" type="password" 
                       placeholder="Laisser vide pour générer automatiquement"
                       style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;" 
                       onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                       onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'" />
                <div class="muted" style="font-size: 12px; margin-top: 8px;">
                    <strong>Nouveau compte</strong> : Laisser vide pour générer un mot de passe automatique (envoyé par email)<br />
                    <strong>Compte existant</strong> : Renseigner pour réinitialiser le mot de passe
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <a href="{{ route('admin.controllers.index') }}" class="btn secondary" style="padding: 14px 24px;">Annuler</a>
            <button class="btn" type="submit" style="padding: 14px 32px; font-size: 16px;">
                ✨ Créer le contrôleur
            </button>
        </div>
    </form>
@endsection
