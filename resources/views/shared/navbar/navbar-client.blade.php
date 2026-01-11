@php($authUser = auth()->user())
@php($authUser?->loadMissing('roles'))

<!-- Navbar moderne pour le client -->
<nav style="background: #fff; border-bottom: 1px solid var(--we-border); box-shadow: 0 1px 3px rgba(15,23,42,0.08); position: sticky; top: 0; z-index: 100; margin-bottom: 32px;">
    <div class="container" style="padding: 16px 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px;">
            <!-- Logo et branding -->
            <a href="{{ route('public.events.index') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                @if(file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}" alt="Win's Events" style="height: 44px; width: auto; display: block;">
                @endif
                <div>
                    <div style="font-weight: 800; font-size: 18px; letter-spacing: -0.3px; color: var(--we-text); line-height: 1.2;">Win's Events</div>
                    <div style="font-size: 11px; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Plateforme de soirées</div>
                </div>
            </a>

            <!-- Navigation desktop -->
            <div style="display: flex; align-items: center; gap: 8px; flex: 1; justify-content: center;" class="nav-desktop">
                <a href="{{ route('public.events.index') }}" 
                   class="nav-link {{ request()->routeIs('public.events.*') || request()->routeIs('home') ? 'active' : '' }}"
                   style="padding: 10px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-text); transition: all 0.2s ease; position: relative;">
                    Soirées
                </a>
                <a href="{{ route('public.about') }}" 
                   class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}"
                   style="padding: 10px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-text); transition: all 0.2s ease; position: relative;">
                    À propos
                </a>
            </div>

            <!-- Actions utilisateur -->
            <div style="display: flex; align-items: center; gap: 8px;" class="nav-desktop">
                @auth
                    @if ($authUser && $authUser->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" 
                           class="btn secondary" 
                           style="padding: 10px 16px; font-size: 14px; white-space: nowrap;">
                            Admin
                        </a>
                    @endif
                    @if ($authUser && $authUser->hasAnyRole(['admin', 'controller']))
                        <a href="{{ route('scanner.home') }}" 
                           class="btn secondary" 
                           style="padding: 10px 16px; font-size: 14px; white-space: nowrap;">
                            Scanner
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button class="btn secondary" type="submit" style="padding: 10px 16px; font-size: 14px; white-space: nowrap;">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn secondary" style="padding: 10px 16px; font-size: 14px; white-space: nowrap;">
                        Connexion
                    </a>
                @endauth
            </div>

            <!-- Menu burger mobile -->
            <button id="mobile-menu-toggle" 
                    class="nav-mobile"
                    style="display: none; background: none; border: none; padding: 8px; cursor: pointer; color: var(--we-text); font-size: 24px;"
                    onclick="toggleMobileMenu()">
                ☰
            </button>
        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--we-border);" class="nav-mobile">
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('public.events.index') }}" 
                   style="padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 15px; font-weight: 600; color: var(--we-text); transition: background 0.2s;"
                   onmouseover="this.style.background='#f8f9fa'"
                   onmouseout="this.style.background='transparent'">
                    Soirées à venir
                </a>
                <a href="{{ route('public.about') }}" 
                   style="padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 15px; font-weight: 600; color: var(--we-text); transition: background 0.2s;"
                   onmouseover="this.style.background='#f8f9fa'"
                   onmouseout="this.style.background='transparent'">
                    À propos
                </a>
                @auth
                    @if ($authUser && $authUser->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" 
                           style="padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 15px; font-weight: 600; color: var(--we-text); transition: background 0.2s;"
                           onmouseover="this.style.background='#f8f9fa'"
                           onmouseout="this.style.background='transparent'">
                            Admin
                        </a>
                    @endif
                    @if ($authUser && $authUser->hasAnyRole(['admin', 'controller']))
                        <a href="{{ route('scanner.home') }}" 
                           style="padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 15px; font-weight: 600; color: var(--we-text); transition: background 0.2s;"
                           onmouseover="this.style.background='#f8f9fa'"
                           onmouseout="this.style.background='transparent'">
                            Scanner
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" 
                                style="width: 100%; text-align: left; padding: 12px 16px; border-radius: 10px; border: none; background: none; font-size: 15px; font-weight: 600; color: var(--we-text); cursor: pointer; transition: background 0.2s;"
                                onmouseover="this.style.background='#f8f9fa'"
                                onmouseout="this.style.background='transparent'">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                       style="padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 15px; font-weight: 600; color: var(--we-text); transition: background 0.2s;"
                       onmouseover="this.style.background='#f8f9fa'"
                       onmouseout="this.style.background='transparent'">
                        Connexion
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const toggle = document.getElementById('mobile-menu-toggle');
        if (menu.style.display === 'none' || !menu.style.display) {
            menu.style.display = 'block';
            toggle.innerHTML = '✕';
        } else {
            menu.style.display = 'none';
            toggle.innerHTML = '☰';
        }
    }

    // Gestion du responsive
    function handleResize() {
        const isMobile = window.innerWidth < 768;
        document.querySelectorAll('.nav-desktop').forEach(el => {
            el.style.display = isMobile ? 'none' : 'flex';
        });
        document.querySelectorAll('.nav-mobile').forEach(el => {
            el.style.display = isMobile ? 'block' : 'none';
        });
    }

    window.addEventListener('resize', handleResize);
    handleResize();

    // Styles pour les liens actifs
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.background = '#f8f9fa';
                this.style.color = 'var(--we-text)';
            }
        });
        link.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.background = 'transparent';
            }
        });
    });

    // Style pour les liens actifs
    if (!document.getElementById('nav-link-styles')) {
        const style = document.createElement('style');
        style.id = 'nav-link-styles';
        style.textContent = `
            .nav-link.active {
                background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)) !important;
                color: var(--we-primary) !important;
            }
            .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: 4px;
                left: 50%;
                transform: translateX(-50%);
                width: 24px;
                height: 3px;
                background: var(--we-primary);
                border-radius: 2px;
            }
            .nav-link:hover:not(.active) {
                background: #f8f9fa;
            }
        `;
        document.head.appendChild(style);
    }
</script>
