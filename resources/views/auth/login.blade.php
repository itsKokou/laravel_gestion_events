@extends('layouts.app')

@section('title', 'Connexion · Win\'s Events')

@section('content')
    <div style="max-width: 480px; margin: 0 auto; padding: 24px 0;">
        <!-- Header avec badge -->
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="margin-bottom: 12px; font-size: 14px; font-weight: 700; color: var(--we-primary); text-transform: uppercase; letter-spacing: 1px;">Accès sécurisé</div>
            <h1 style="font-size: 36px; font-weight: 900; margin-bottom: 12px; letter-spacing: -0.5px;">Connexion</h1>
            <p class="muted" style="font-size: 16px;">Accès réservé aux administrateurs et contrôleurs.</p>
        </div>

        <!-- Carte de connexion -->
        <div class="card" style="padding: 40px;">
            @if ($errors->any())
                <div style="padding: 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #dc2626; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 14px; flex-shrink: 0;">!</div>
                        <div style="font-weight: 700; color: #991b1b; font-size: 15px;">Erreur de connexion</div>
                    </div>
                    <ul style="margin-left: 36px; color: #7f1d1d; line-height: 1.6; font-size: 14px;">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <!-- Champ Email -->
                <div style="margin-bottom: 24px;">
                    <label for="email" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                        Adresse email
                    </label>
                    <div style="position: relative;">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            autocomplete="email"
                            placeholder="votre@email.com"
                            style="width: 100%; padding: 14px 16px; padding-left: 48px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;" 
                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'"
                        />
                        <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--we-muted); font-size: 18px;">✉</div>
                    </div>
                </div>

                <!-- Champ Mot de passe -->
                <div style="margin-bottom: 24px;">
                    <label for="password" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #334155;">
                        Mot de passe
                    </label>
                    <div style="position: relative;">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••"
                            style="width: 100%; padding: 14px 16px; padding-left: 48px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;" 
                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'"
                        />
                        <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--we-muted); font-size: 18px;">🔒</div>
                    </div>
                </div>

                <!-- Checkbox "Se souvenir de moi" -->
                <div style="margin-bottom: 32px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            value="1" 
                            style="width: 20px; height: 20px; cursor: pointer; accent-color: var(--we-primary);" 
                            @checked(old('remember'))
                        />
                        <span class="muted" style="font-size: 14px; user-select: none;">Se souvenir de moi</span>
                    </label>
                </div>

                <!-- Bouton de connexion -->
                <button 
                    class="btn" 
                    type="submit"
                    style="width: 100%; font-size: 16px; padding: 16px; justify-content: center; font-weight: 700;"
                    onmouseover="this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.transform='translateY(0)'"
                >
                    Se connecter
                </button>
            </form>
        </div>

        <!-- Informations supplémentaires -->
        <div style="text-align: center; margin-top: 32px;">
            <p class="muted" style="font-size: 13px; line-height: 1.6;">
                En cas de problème d'accès, contactez votre administrateur système.
            </p>
        </div>
    </div>

    <style>
        @media (max-width: 640px) {
            .card[style*="padding: 40px"] {
                padding: 32px 24px !important;
            }
            h1[style*="font-size: 36px"] {
                font-size: 28px !important;
            }
        }

        /* Animation d'entrée pour la carte */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card[style*="padding: 40px"] {
            animation: fadeInUp 0.4s ease-out;
        }
    </style>
@endsection
