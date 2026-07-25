<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') · {{ config('app.name') }}</title>

    <!-- Deliberately self-contained: no Vite directive, no dependency on the
         compiled app bundle, so this still renders correctly even if that
         build is broken — which is exactly the moment a 500 page needs to work. -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg:#eef0f4; --surface:#ffffff; --border:#e5e8ee;
            --text:#1b1e26; --muted:#69707d; --faint:#9aa1ad;
            --accent:#6366f1; --accent-tint: color-mix(in srgb, #6366f1 13%, transparent);
            --shadow-lg: 0 6px 22px rgba(16,20,28,.09), 0 2px 6px rgba(16,20,28,.05);
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg:#0c0f13; --surface:#161a20; --border:#272d37;
                --text:#e9ebf0; --muted:#9aa2af; --faint:#69707f;
                --accent:#818cf8; --accent-tint: color-mix(in srgb, #818cf8 16%, transparent);
                --shadow-lg: 0 10px 34px rgba(0,0,0,.55);
            }
        }
        * { box-sizing: border-box; }
        html, body { margin: 0; }
        body {
            font-family: 'Nunito', system-ui, -apple-system, sans-serif;
            background: var(--bg); color: var(--text);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }
        .q-error-card {
            width: 100%; max-width: 440px;
            background: var(--surface); border: 1px solid var(--border); border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .q-error-brand {
            display: flex; align-items: center; gap: .5rem; width: fit-content;
            font-weight: 800; font-size: .82rem; letter-spacing: .01em; color: var(--muted);
            margin: 0 auto 2rem; text-decoration: none;
        }
        .q-error-badge {
            width: 22px; height: 22px; border-radius: 6px;
            background: var(--accent); color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: .72rem;
        }
        .q-error-icon {
            width: 56px; height: 56px; border-radius: 14px;
            background: var(--accent-tint); color: var(--accent);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .q-error-icon svg { width: 26px; height: 26px; fill: currentColor; }
        .q-error-eyebrow {
            font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            color: var(--faint); margin-bottom: .5rem;
        }
        .q-error-title { font-size: 1.35rem; font-weight: 800; letter-spacing: -.02em; margin: 0 0 .55rem; }
        .q-error-lead { color: var(--muted); font-size: .9rem; line-height: 1.55; margin: 0; }
        .q-error-actions { display: flex; gap: .6rem; justify-content: center; flex-wrap: wrap; margin-top: 1.75rem; }
        .q-error-btn {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .6rem 1.05rem; border-radius: 10px; border: 1px solid var(--border);
            background: var(--surface); color: var(--text); text-decoration: none;
            font: inherit; font-weight: 700; font-size: .82rem; cursor: pointer;
            transition: background .12s, border-color .12s, color .12s;
        }
        .q-error-btn:hover { background: var(--accent-tint); border-color: var(--accent); color: var(--accent); }
        .q-error-btn svg { width: 14px; height: 14px; fill: currentColor; }
        .q-error-support { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); }
        .q-error-mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            font-size: .78rem; color: var(--muted); word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="q-error-card">
        <a class="q-error-brand" href="{{ url('/') }}">
            <span class="q-error-badge">{{ Illuminate\Support\Str::substr(config('app.name'), 0, 1) }}</span>
            {{ config('app.name') }}
        </a>

        @hasSection('icon')
            <div class="q-error-icon">@yield('icon')</div>
        @endif

        <div class="q-error-eyebrow">Fehler @yield('code')</div>
        <h1 class="q-error-title">@yield('message')</h1>

        @hasSection('description')
            <p class="q-error-lead">@yield('description')</p>
        @endif

        <div class="q-error-actions">
            @yield('actions')
            <a class="q-error-btn" href="{{ url('/') }}">
                <svg viewBox="0 0 16 16"><use href="{{ asset('svg/bootstrap-icons.svg') }}#house"></use></svg>
                Zur Startseite
            </a>
        </div>

        @hasSection('support')
            <div class="q-error-support">
                @yield('support')
            </div>
        @endif
    </div>

@yield('scripts')
</body>
</html>
