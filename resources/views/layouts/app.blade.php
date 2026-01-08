<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', "Win's Events"))</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji"; background:#0b0b0f; color:#fff; }
            a { color: inherit; }
            .container { max-width: 1000px; margin: 0 auto; padding: 24px; }
            .card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 16px; }
            .btn { display:inline-block; padding: 10px 14px; border-radius: 12px; background: #7c3aed; color: #fff; text-decoration:none; }
            .btn.secondary { background: rgba(255,255,255,0.12); }
            input, select, textarea { width: 100%; padding: 10px 12px; border-radius: 12px; border:1px solid rgba(255,255,255,0.16); background: rgba(0,0,0,0.25); color:#fff; }
            label { font-size: 13px; opacity: 0.9; }
            .grid { display:grid; gap: 12px; }
            .grid2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .muted { opacity: 0.75; }
            .error { color: #fecaca; }
        </style>
    @endif
</head>
<body>
    <div class="container">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom: 18px;">
            <div>
                <a href="{{ route('public.events.index') }}" style="text-decoration:none; font-weight:700; letter-spacing:0.2px;">
                    Win’s Events
                </a>
                <div class="muted" style="font-size: 13px;">Plateforme de gestion de soirées</div>
            </div>
            <div style="display:flex; gap:10px;">
                <a class="btn secondary" href="{{ route('public.events.index') }}">Soirées à venir</a>
                <a class="btn secondary" href="{{ route('scanner.home') }}">Scanner</a>
            </div>
        </div>

        @if (session('status'))
            <div class="card" style="margin-bottom: 12px;">{{ session('status') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
