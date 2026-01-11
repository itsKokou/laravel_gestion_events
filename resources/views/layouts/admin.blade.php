<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', "Win's Events"))</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            :root {
                --we-bg: #faf8f6;
                --we-text: #1f1b18;
                --we-muted: #8b7355;
                --we-border: #f0e8e0;
                --we-card: #fff;
                --we-primary: #ea580c;
                --we-primary-hover: #d64407;
                --we-primary-soft: rgba(234, 88, 12, 0.12);
            }

            body {
                font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
                background: radial-gradient(900px 500px at 10% 0%, rgba(234, 88, 12, 0.08), transparent 60%),
                    radial-gradient(900px 500px at 90% 20%, rgba(245, 130, 32, 0.08), transparent 55%),
                    var(--we-bg);
                color: var(--we-text);
            }

            a {
                color: inherit;
            }

            .container {
                max-width: 1120px;
                margin: 0 auto;
                padding: 24px;
            }

            .card {
                background: var(--we-card);
                border: 1px solid var(--we-border);
                border-radius: 16px;
                padding: 16px;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 12px 30px rgba(15, 23, 42, 0.06);
            }

            .btn {
                font-size: 14px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 10px 14px;
                border-radius: 12px;
                background: var(--we-primary);
                border: 1px solid rgba(234, 88, 12, 0.22);
                color: #fff;
                font-weight: 400;
                letter-spacing: 0.1px;
                text-decoration: none;
                box-shadow: 0 10px 18px rgba(234, 88, 12, 0.18);
                cursor: pointer;
                transition: transform 120ms ease, background-color 120ms ease, box-shadow 120ms ease;
            }

            .btn:hover {
                background: var(--we-primary-hover);
                transform: translateY(-1px);
            }

            .btn:active {
                transform: translateY(0);
            }

            .btn.secondary {
                background: #fff;
                color: var(--we-text);
                border: 1px solid var(--we-border);
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            }

            .btn.secondary:hover {
                background: #f1f5f9;
            }

            input,
            select,
            textarea {
                width: 100%;
                padding: 10px 12px;
                border-radius: 12px;
                border: 1px solid var(--we-border);
                background: #fff;
                color: var(--we-text);
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
                outline: none;
                transition: border-color 120ms ease, box-shadow 120ms ease;
            }

            input:focus,
            select:focus,
            textarea:focus {
                border-color: rgba(234, 88, 12, 0.55);
                box-shadow: 0 0 0 4px var(--we-primary-soft);
            }

            label {
                font-size: 13px;
                font-weight: 600;
                color: #334155;
            }

            .grid {
                display: grid;
                gap: 12px;
            }

            .grid2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .muted {
                color: var(--we-muted);
            }

            .error {
                color: #b91c1c;
            }

            /* Layout admin avec sidebar */
            .admin-wrapper {
                display: flex;
                min-height: 100vh;
            }

            .admin-content {
                flex: 1;
                margin-left: 280px;
                padding: 32px;
            }

            @media (max-width: 768px) {
                .admin-content {
                    margin-left: 0;
                    padding: 24px 16px;
                    padding-top: 70px;
                }
            }
        </style>
    @endif
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar admin -->
        @include('shared.navbar.navbar-admin')

        <!-- Contenu principal -->
        <main class="admin-content">
            @if (session('status'))
                <div class="card"
                    style="margin-bottom: 24px; padding: 16px; background: linear-gradient(135deg, rgba(234, 88, 12, 0.05), rgba(245, 130, 32, 0.02)); border-color: rgba(234, 88, 12, 0.2);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 24px; height: 24px; border-radius: 50%; background: var(--we-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                            ✓</div>
                        <div style="color: var(--we-text); font-weight: 600;">{{ session('status') }}</div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>