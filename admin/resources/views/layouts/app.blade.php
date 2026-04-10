<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pepichis Admin' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        :root {
            --bg: #f7f0e7;
            --bg-soft: #fbf6f0;
            --card: rgba(255, 250, 243, 0.88);
            --wine: #6d1834;
            --wine-dark: #421020;
            --wine-soft: #a44767;
            --text: #2f2a28;
            --muted: #716864;
            --line: rgba(150, 113, 94, 0.18);
            --accent: #dce78d;
            --accent-2: #f4d8b1;
            --danger: #a1273f;
            --shadow: 0 22px 45px rgba(77, 27, 40, 0.08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(220, 231, 141, 0.38), transparent 28%),
                radial-gradient(circle at 80% 20%, rgba(244, 216, 177, 0.32), transparent 24%),
                linear-gradient(180deg, #fbf6f0 0%, #f3e6db 100%);
            color: var(--text);
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(109, 24, 52, 0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(109, 24, 52, 0.02) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
            z-index: 0;
        }
        a { color: inherit; text-decoration: none; }
        .shell {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 290px 1fr;
            min-height: 100vh;
        }
        .sidebar {
            position: relative;
            background:
                radial-gradient(circle at 20% 10%, rgba(220, 231, 141, 0.16), transparent 30%),
                linear-gradient(180deg, #4d1225 0%, #34101a 100%);
            color: #f8efe6;
            padding: 28px 22px;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }
        .brand-lockup {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }
        .brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(220, 231, 141, 0.26), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.12);
        }
        .brand-icon svg {
            width: 28px;
            height: 28px;
            stroke: #fbf7e9;
        }
        .brand {
            font-size: 29px;
            font-weight: 700;
            letter-spacing: -0.03em;
            margin: 0;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            color: #f6ead8;
            background: rgba(255, 255, 255, 0.08);
        }
        .eyebrow-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 0 4px rgba(220, 231, 141, 0.12);
        }
        .subtitle {
            color: #dbc8cf;
            margin: 0 0 28px;
            line-height: 1.6;
            font-size: 14px;
        }
        .profile-card {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 26px;
            padding: 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }
        .profile-badge {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: rgba(220, 231, 141, 0.18);
            color: #fff7d0;
            font-weight: 700;
        }
        .profile-card small {
            display: block;
            color: #ccb5bd;
            margin-bottom: 2px;
        }
        .nav {
            display: grid;
            gap: 10px;
        }
        .nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 14px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.04);
            transition: 0.2s ease;
        }
        .nav a:hover {
            transform: translateX(2px);
            background: rgba(255, 255, 255, 0.08);
        }
        .nav a.active {
            background: linear-gradient(135deg, rgba(220, 231, 141, 0.24), rgba(255, 255, 255, 0.12));
            color: #f9ffd1;
            border: 1px solid rgba(220, 231, 141, 0.18);
        }
        .nav svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            flex: 0 0 auto;
        }
        .logout {
            margin-top: 24px;
        }
        .logout button {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: 0;
            border-radius: 14px;
            padding: 13px 14px;
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            cursor: pointer;
        }
        .logout svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
        }
        .content {
            padding: 34px;
        }
        .content-shell {
            max-width: 1260px;
            margin: 0 auto;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }
        .page-title {
            margin: 0;
            font-size: 34px;
            letter-spacing: -0.04em;
        }
        .page-copy {
            color: var(--muted);
            margin: 8px 0 0;
            max-width: 720px;
            line-height: 1.6;
        }
        .page-copy strong {
            color: var(--wine);
        }
        .card, .metric {
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow);
        }
        .card { padding: 24px; }
        .metric-grid, .two-col { display: grid; gap: 20px; }
        .metric-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 24px;
        }
        .metric {
            position: relative;
            overflow: hidden;
            padding: 22px;
        }
        .metric::after {
            content: '';
            position: absolute;
            inset: auto -20% -30% auto;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(220, 231, 141, 0.35), transparent 65%);
        }
        .metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: rgba(109, 24, 52, 0.08);
            color: var(--wine);
        }
        .metric-icon svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
        }
        .metric span {
            display: block;
            color: var(--muted);
            margin-bottom: 10px;
            font-size: 14px;
        }
        .metric strong {
            font-size: 40px;
            color: var(--wine);
            letter-spacing: -0.04em;
        }
        .two-col { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .card-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            font-size: 21px;
        }
        .section-title svg {
            width: 20px;
            height: 20px;
            stroke: var(--wine);
        }
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 16px 12px;
            border-bottom: 1px solid rgba(150, 113, 94, 0.12);
            vertical-align: middle;
            text-align: left;
        }
        tbody tr:hover { background: rgba(255, 255, 255, 0.35); }
        th {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.09em;
        }
        .thumb {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            object-fit: cover;
            background: linear-gradient(135deg, #eee1d5, #e7d5ca);
            box-shadow: inset 0 0 0 1px rgba(109, 24, 52, 0.06);
        }
        .row-title {
            display: grid;
            gap: 5px;
        }
        .row-title strong {
            font-size: 15px;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: nowrap;
        }
        .floating-action {
            position: fixed;
            right: 34px;
            bottom: 28px;
            z-index: 1200;
            opacity: 0;
            transform: translateY(18px);
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .floating-action.is-visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        .floating-action .btn,
        .floating-action .btn-secondary {
            box-shadow: 0 18px 30px rgba(109, 24, 52, 0.22);
        }
        .drag-cell {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .drag-handle {
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: #efe4d9;
            color: var(--wine-dark);
            cursor: grab;
        }
        .drag-handle svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }
        tr.row-dragging {
            opacity: 0.45;
        }
        tr.row-drop-target {
            background: rgba(220, 231, 141, 0.24);
        }
        .btn, .btn-secondary, .btn-danger {
            border: 0;
            border-radius: 14px;
            padding: 10px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }
        .btn svg, .btn-secondary svg, .btn-danger svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }
        .btn {
            background: linear-gradient(135deg, var(--wine), #8d3455);
            color: #fff;
            box-shadow: 0 14px 24px rgba(109, 24, 52, 0.16);
        }
        .btn-secondary {
            background: #efe4d9;
            color: var(--wine-dark);
        }
        .btn-danger {
            background: #f5d7dc;
            color: var(--danger);
        }
        .stack { display: grid; gap: 16px; }
        .list-stack {
            display: grid;
            gap: 14px;
        }
        .list-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.44);
            border: 1px solid rgba(150, 113, 94, 0.1);
        }
        .list-item-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(109, 24, 52, 0.08);
            color: var(--wine);
            flex: 0 0 auto;
        }
        .list-item-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }
        .form-field {
            display: grid;
            gap: 8px;
        }
        .form-field.full { grid-column: 1 / -1; }
        .rich-editor {
            border: 1px solid rgba(150, 113, 94, 0.18);
            border-radius: 18px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
        }
        .rich-editor-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px;
            border-bottom: 1px solid rgba(150, 113, 94, 0.14);
            background: rgba(239, 228, 217, 0.65);
        }
        .rich-editor-toolbar button {
            border: 0;
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.85);
            color: var(--wine-dark);
        }
        .rich-editor-toolbar button:hover {
            background: #fff;
        }
        .rich-editor-toolbar svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
        }
        .rich-editor-surface {
            min-height: 130px;
            padding: 14px;
            line-height: 1.65;
            outline: none;
        }
        .rich-editor-surface:empty::before {
            content: attr(data-placeholder);
            color: #9b8d85;
        }
        .rich-editor-hidden {
            display: none;
        }
        label {
            font-size: 14px;
            font-weight: 700;
        }
        input, select, textarea {
            width: 100%;
            border: 1px solid rgba(150, 113, 94, 0.18);
            border-radius: 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.9);
            font: inherit;
            color: var(--text);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: rgba(109, 24, 52, 0.32);
            box-shadow: 0 0 0 4px rgba(109, 24, 52, 0.08);
            transform: translateY(-1px);
        }
        textarea { min-height: 130px; resize: vertical; }
        .help, .pill {
            color: var(--muted);
            font-size: 10px;
        }
        .pill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #efe4d9;
            border-radius: 999px;
            padding: 7px 11px;
            border: 1px solid rgba(109, 24, 52, 0.05);
        }
        .pill::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: var(--wine-soft);
        }
        .flash, .errors {
            margin-bottom: 18px;
            padding: 15px 16px;
            border-radius: 16px;
        }
        .flash {
            background: #ecf5df;
            color: #465e11;
            border: 1px solid #d4e6b0;
        }
        .errors {
            background: #f7dde1;
            color: #7d2134;
            border: 1px solid #ecc2c9;
        }
        .empty {
            color: var(--muted);
            padding: 12px 0;
        }
        .empty-state {
            display: grid;
            place-items: center;
            gap: 12px;
            padding: 28px;
            text-align: center;
            color: var(--muted);
        }
        .empty-state-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: rgba(109, 24, 52, 0.08);
            color: var(--wine);
        }
        .empty-state-icon svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
        }
        .login-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px;
            position: relative;
            z-index: 1;
            background-color: #fdc9e4;
        }
        .login-card {
            width: min(490px, 100%);
            background: rgba(255, 250, 243, 0.92);
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: 34px;
            box-shadow: 0 28px 60px rgba(71, 16, 33, 0.1);
        }
        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        .login-brand .brand-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(109, 24, 52, 0.11), rgba(220, 231, 141, 0.3));
        }
        @media (max-width: 960px) {
            .shell { grid-template-columns: 1fr; }
            .sidebar { padding-bottom: 16px; }
            .metric-grid, .two-col, .form-grid { grid-template-columns: 1fr; }
            .content { padding: 22px; }
            .floating-action {
                position: static;
            }
        }
    </style>
</head>
<body>
    @isset($loginView)
        @yield('content')
    @else
        @php($adminName = session('admin_user_name', 'Admin'))
        <div class="shell">
            <aside class="sidebar">
                <div class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    Panel interno
                </div>

                <div class="profile-card">
                    <div class="profile-badge">{{ strtoupper(substr($adminName, 0, 1)) }}</div>
                    <div>
                        <small>Sesión iniciada</small>
                        <strong>{{ $adminName }}</strong>
                    </div>
                </div>

                <nav class="nav">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M3 13.5 12 4l9 9.5" />
                            <path d="M5 11.5V20h14v-8.5" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('producers.index') }}" class="{{ request()->routeIs('producers.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M16 20a4 4 0 0 0-8 0" />
                            <circle cx="12" cy="8" r="4" />
                            <path d="M20 19a3.5 3.5 0 0 0-3-3.46" />
                            <path d="M17 4.5a3.5 3.5 0 0 1 0 7" />
                        </svg>
                        <span>Productores</span>
                    </a>
                    <a href="{{ route('attributes.index') }}" class="{{ request()->routeIs('attributes.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="m7 7 10 10" />
                            <path d="M7 17 17 7" />
                            <circle cx="7" cy="7" r="3" />
                            <circle cx="17" cy="17" r="3" />
                            <circle cx="17" cy="7" r="3" />
                            <circle cx="7" cy="17" r="3" />
                        </svg>
                        <span>Características</span>
                    </a>
                    <a href="{{ route('wines.index') }}" class="{{ request()->routeIs('wines.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M7 3h10" />
                            <path d="M9 3v6a3 3 0 0 0 6 0V3" />
                            <path d="M12 12v8" />
                            <path d="M8 21h8" />
                        </svg>
                        <span>Vinos</span>
                    </a>
                    <a href="{{ route('notes.index') }}" class="{{ request()->routeIs('notes.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M6 4h9l3 3v13H6z" />
                            <path d="M15 4v4h4" />
                            <path d="M9 12h6" />
                            <path d="M9 16h6" />
                        </svg>
                        <span>Notas</span>
                    </a>
                    <a href="{{ route('menu-items.index') }}" class="{{ request()->routeIs('menu-items.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M4 7h16" />
                            <path d="M4 12h12" />
                            <path d="M4 17h9" />
                            <path d="m16 15 4 4" />
                            <path d="m20 15-4 4" />
                        </svg>
                        <span>Menu</span>
                    </a>
                </nav>

                <div class="logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <path d="m16 17 5-5-5-5" />
                                <path d="M21 12H9" />
                            </svg>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </aside>

            <main class="content">
                <div class="content-shell">
                    @if(session('success'))
                        <div class="flash">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="errors">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    @endisset

    <script>
        (function () {
            function initRichEditors(scope) {
                const root = scope || document;
                const editors = root.querySelectorAll('[data-rich-editor]:not([data-rich-editor-ready])');

                editors.forEach((editor) => {
                    const surface = editor.querySelector('.rich-editor-surface');
                    const textarea = editor.querySelector('.rich-editor-hidden');

                    if (!surface || !textarea) {
                        return;
                    }

                    const sync = () => {
                        textarea.value = surface.innerHTML.trim();
                    };

                    editor.querySelectorAll('[data-command]').forEach((button) => {
                        button.addEventListener('click', () => {
                            document.execCommand(button.dataset.command, false, null);
                            surface.focus();
                            sync();
                        });
                    });

                    editor.querySelectorAll('[data-action]').forEach((button) => {
                        button.addEventListener('click', () => {
                            if (button.dataset.action === 'link') {
                                const url = window.prompt('Pegá la URL del link');
                                if (url) {
                                    document.execCommand('createLink', false, url);
                                }
                            }

                            if (button.dataset.action === 'clear') {
                                document.execCommand('removeFormat', false, null);
                                document.execCommand('unlink', false, null);
                            }

                            surface.focus();
                            sync();
                        });
                    });

                    surface.addEventListener('input', sync);

                    const form = editor.closest('form');
                    if (form) {
                        form.addEventListener('submit', sync);
                    }

                    editor.dataset.richEditorReady = 'true';
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                initRichEditors(document);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
