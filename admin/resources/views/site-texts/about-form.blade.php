@extends('layouts.app', ['title' => 'Nosotros'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">Nosotros</h1>
            <p class="page-copy">Edita el texto institucional que se muestra en el home. Puedes separar parrafos dejando una linea en blanco entre cada bloque.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('site-texts.about.update') }}" class="card stack">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-field">
                <label for="eyebrow">Bajada superior</label>
                <input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $siteText->eyebrow) }}" placeholder="Quienes somos">
            </div>

            <div class="form-field">
                <label for="title">Titulo</label>
                <input id="title" name="title" value="{{ old('title', $siteText->title) }}" required>
            </div>

            <div class="form-field full">
                <label for="body">Texto</label>
                <textarea id="body" name="body" rows="16" required>{{ old('body', $siteText->body) }}</textarea>
                <div class="help">El front respeta parrafos separados por una linea en blanco.</div>
            </div>
        </div>

        <div class="actions">
            <button class="btn" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M5 12.5 10 17l9-10" />
                </svg>
                Guardar seccion
            </button>
        </div>
    </form>
@endsection
