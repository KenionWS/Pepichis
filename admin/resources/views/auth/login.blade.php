@extends('layouts.app', ['title' => 'Ingresar', 'loginView' => true])

@section('content')
    <div class="login-shell">
        <div class="login-card">
            <div class="login-brand">
                <div>
                    <div class="eyebrow" style="margin:0 0 6px; background:rgba(109,24,52,0.08); color:#6d1834;">
                        <span class="eyebrow-dot" style="background:#6d1834; box-shadow:none;"></span>
                        Acceso al panel
                    </div>
                    <h1 class="page-title" style="font-size:32px;">Administrador Pepichis</h1>
                </div>
            </div>

            @if($errors->any())
                <div class="errors">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="stack">
                @csrf
                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-field">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <button class="btn" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M15 3h4a2 2 0 0 1 2 2v4" />
                        <path d="m10 14 11-11" />
                        <path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5" />
                    </svg>
                    Ingresar
                </button>
            </form>
        </div>
    </div>
@endsection
