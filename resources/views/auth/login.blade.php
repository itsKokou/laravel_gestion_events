@extends('layouts.public')

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
                        <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--we-muted);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-envelope">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
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
                            style="width: 100%; padding: 14px 16px; padding-left: 48px; padding-right: 48px; border-radius: 12px; border: 1px solid var(--we-border); background: #fff; color: var(--we-text); box-shadow: 0 1px 2px rgba(15,23,42,0.03); outline: none; transition: border-color 120ms ease, box-shadow 120ms ease; font-size: 15px;" 
                            onfocus="this.style.borderColor='rgba(234, 88, 12, 0.55)'; this.style.boxShadow='0 0 0 4px var(--we-primary-soft)'"
                            onblur="this.style.borderColor='var(--we-border)'; this.style.boxShadow='0 1px 2px rgba(15,23,42,0.03)'"
                        />
                        <div style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--we-muted); font-size: 18px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <button
                            type="button"
                            id="togglePasswordVisibility"
                            aria-label="Afficher le mot de passe"
                            aria-pressed="false"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; border: 1px solid transparent; background: transparent; color: var(--we-muted); cursor: pointer; transition: background 120ms ease, color 120ms ease, border-color 120ms ease;"
                            onmouseover="this.style.background='rgba(15,23,42,0.04)'; this.style.color='var(--we-text)';"
                            onmouseout="this.style.background='transparent'; this.style.color='var(--we-muted)';"
                        >
                            <span class="sr-only">Afficher le mot de passe</span>
                            <svg id="passwordEyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="passwordEyeOffIcon" class="hidden" xmlns="http://www.w3.org/2000/svg" width="20"
                                height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M10.733 5.08A10.42 10.42 0 0 1 12 5c6.5 0 10 7 10 7a18.16 18.16 0 0 1-2.195 3.258"></path>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3.5 7 10 7a10.42 10.42 0 0 0 5.08-1.267"></path>
                                <path d="M14.12 14.12A3 3 0 0 1 9.88 9.88"></path>
                                <path d="M1 1l22 22"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Checkbox "Se souvenir de moi" -->
                <div style="margin-bottom: 32px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            value="1" 
                            style="width: 15px; height: 15px; cursor: pointer; accent-color: var(--we-primary);" 
                            @checked(old('remember'))
                        />
                        <span class="muted" style="font-size: 14px; user-select: none;">Se souvenir de moi</span>
                    </label>
                </div>

                <!-- Bouton de connexion -->
                <div class="flex justify-center items-center">
                    <button 
                        class="btn btn-primary text-sm text-center px-20" 
                        type="submit"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'"
                    >
                        Se connecter
                    </button>
                </div>
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

    <script>
        (function () {
            const input = document.getElementById('password');
            const btn = document.getElementById('togglePasswordVisibility');
            const eye = document.getElementById('passwordEyeIcon');
            const eyeOff = document.getElementById('passwordEyeOffIcon');

            if (!input || !btn || !eye || !eyeOff) return;

            function setVisible(visible) {
                input.type = visible ? 'text' : 'password';
                btn.setAttribute('aria-pressed', visible ? 'true' : 'false');
                btn.setAttribute('aria-label', visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
                const sr = btn.querySelector('.sr-only');
                if (sr) sr.textContent = visible ? 'Masquer le mot de passe' : 'Afficher le mot de passe';
                eye.classList.toggle('hidden', visible);
                eyeOff.classList.toggle('hidden', !visible);
            }

            btn.addEventListener('click', function () {
                setVisible(input.type === 'password');
                input.focus();
            });
        })();
    </script>
@endsection
