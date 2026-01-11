@php($authUser = auth()->user())
@php($authUser?->loadMissing('roles'))

<!-- Bouton menu mobile -->
<button id="admin-sidebar-toggle" 
        class="admin-mobile-toggle"
        onclick="toggleAdminSidebar()"
        style="display: none; position: fixed; top: 16px; left: 16px; z-index: 1001; background: #fff; border: 1px solid var(--we-border); border-radius: 10px; padding: 10px; cursor: pointer; box-shadow: 0 2px 8px rgba(15,23,42,0.1); font-size: 20px; color: var(--we-text);">
    ☰
</button>

<!-- Overlay pour mobile -->
<div id="admin-sidebar-overlay" 
     class="admin-mobile-overlay"
     onclick="toggleAdminSidebar()"
     style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 999; opacity: 0; transition: opacity 0.3s ease;"></div>

<!-- Sidebar verticale pour l'admin -->
<aside id="admin-sidebar" 
       class="admin-sidebar"
       style="position: fixed; left: 0; top: 0; bottom: 0; width: 280px; background: #fff; border-right: 1px solid var(--we-border); box-shadow: 2px 0 8px rgba(15,23,42,0.04); z-index: 1000; display: flex; flex-direction: column; transition: transform 0.3s ease;">
    <!-- Logo et branding -->
    <div style="padding: 24px 20px; border-bottom: 1px solid var(--we-border); flex-shrink: 0;">
        <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
            @if(file_exists(public_path('logo.png')))
                <img src="{{ asset('logo.png') }}" alt="Win's Events" style="height: 40px; width: auto; display: block;">
            @endif
            <div>
                <div style="font-weight: 800; font-size: 16px; letter-spacing: -0.3px; color: var(--we-text); line-height: 1.2;">Win's Events</div>
                <div style="font-size: 10px; color: var(--we-muted); text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Administration</div>
            </div>
        </a>
    </div>

    <!-- Navigation (scrollable) -->
    <nav style="padding: 16px 12px; flex: 1; overflow-y: auto; overflow-x: hidden;">
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-text); transition: all 0.2s ease; position: relative;">
                <span style="font-size: 20px;">📊</span>
                <span>Tableau de bord</span>
            </a>

            <!-- Soirées -->
            <a href="{{ route('admin.events.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-text); transition: all 0.2s ease; position: relative;">
                <span style="font-size: 20px;">📅</span>
                <span>Soirées</span>
            </a>

            <!-- Réservations -->
            <a href="{{ route('admin.orders.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-text); transition: all 0.2s ease; position: relative;">
                <span style="font-size: 20px;">🎫</span>
                <span>Réservations</span>
            </a>

            <!-- Contrôleurs -->
            <a href="{{ route('admin.controllers.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.controllers.*') ? 'active' : '' }}"
               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-text); transition: all 0.2s ease; position: relative;">
                <span style="font-size: 20px;">👥</span>
                <span>Contrôleurs</span>
            </a>
        </div>

        <!-- Séparateur -->
        <div style="margin: 16px 0; height: 1px; background: var(--we-border);"></div>

        <!-- Liens externes -->
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <a href="{{ route('public.events.index') }}" 
               target="_blank"
               class="sidebar-link"
               style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-muted); transition: all 0.2s ease;">
                <span style="font-size: 20px;">🌐</span>
                <span>Site public</span>
                <span style="margin-left: auto; font-size: 12px;">↗</span>
            </a>

            @if ($authUser && $authUser->hasAnyRole(['admin', 'controller']))
                <a href="{{ route('scanner.home') }}" 
                   class="sidebar-link"
                   style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; text-decoration: none; font-size: 14px; font-weight: 600; color: var(--we-muted); transition: all 0.2s ease;">
                    <span style="font-size: 20px;">📱</span>
                    <span>Scanner</span>
                </a>
            @endif
        </div>
    </nav>

    <!-- Footer avec déconnexion (fixe en bas) -->
    <div style="padding: 16px 12px; border-top: 1px solid var(--we-border); background: #fff; flex-shrink: 0;">
        @if ($authUser)
            <div style="padding: 12px 16px; margin-bottom: 8px; border-radius: 10px; background: rgba(234, 88, 12, 0.05);">
                <div style="font-size: 13px; font-weight: 700; color: var(--we-text); margin-bottom: 4px;">{{ $authUser->name }}</div>
                <div style="font-size: 11px; color: var(--we-muted);">
                    @if ($authUser->hasRole('admin'))
                        Administrateur
                    @elseif ($authUser->hasRole('controller'))
                        Contrôleur
                    @endif
                </div>
            </div>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" 
                    class="sidebar-link"
                    style="width: 100%; display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 10px; border: none; background: none; text-align: left; font-size: 14px; font-weight: 600; color: #b91c1c; cursor: pointer; transition: all 0.2s ease;">
                <span style="font-size: 20px;">🚪</span>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>

<style>
    .sidebar-link {
        position: relative;
    }

    .sidebar-link:hover:not(.active) {
        background: #f8f9fa !important;
    }

    .sidebar-link.active {
        background: linear-gradient(135deg, rgba(234, 88, 12, 0.1), rgba(245, 130, 32, 0.05)) !important;
        color: var(--we-primary) !important;
    }

    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 24px;
        background: var(--we-primary);
        border-radius: 0 2px 2px 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-sidebar {
            transform: translateX(-100%);
        }

        .admin-sidebar.open {
            transform: translateX(0);
        }

        .admin-mobile-overlay.show {
            display: block !important;
            opacity: 1 !important;
        }

        .admin-mobile-toggle {
            display: block !important;
        }
    }
</style>

<script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('admin-sidebar-overlay');
        const toggle = document.getElementById('admin-sidebar-toggle');
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
        
        if (sidebar.classList.contains('open')) {
            toggle.innerHTML = '✕';
        } else {
            toggle.innerHTML = '☰';
        }
    }

    // Fermer la sidebar au clic sur un lien en mobile
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(() => {
                    toggleAdminSidebar();
                }, 100);
            }
        });
    });
</script>
