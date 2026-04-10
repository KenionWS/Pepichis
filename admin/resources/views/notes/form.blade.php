@extends('layouts.app', ['title' => $note->exists ? 'Editar nota' : 'Nueva nota'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">{{ $note->exists ? 'Editar nota' : 'Nueva nota' }}</h1>
            <p class="page-copy">Cre&aacute; contenido editorial con foco en posicionamiento, lectura clara y est&eacute;tica consistente con el sitio.</p>
        </div>
        <a class="btn-secondary" href="{{ route('notes.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            Volver
        </a>
    </div>

    <form method="POST" action="{{ $note->exists ? route('notes.update', $note) : route('notes.store') }}" enctype="multipart/form-data" class="card stack">
        @csrf
        @if($note->exists)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-field">
                <label for="title">T&iacute;tulo</label>
                <input id="title" name="title" value="{{ old('title', $note->title) }}" required>
            </div>
            <div class="form-field">
                <label for="slug">URL amigable</label>
                <input id="slug" name="slug" value="{{ old('slug', $note->slug) }}" placeholder="como-elegir-vinos-para-otono">
                <div class="help">Si lo dej&aacute;s vac&iacute;o, se genera desde el t&iacute;tulo.</div>
            </div>
            <div class="form-field">
                <label for="cover_image">Imagen de portada</label>
                <input id="cover_image" type="file" name="cover_image" accept="image/*">
                @if($note->cover_image_path)
                    <div class="help">Portada actual:</div>
                    <img src="{{ asset($note->cover_image_path) }}" alt="{{ $note->title }}" class="thumb">
                @endif
            </div>
            <div class="form-field">
                <label for="published_at">Fecha de publicaci&oacute;n</label>
                <input
                    id="published_at"
                    type="datetime-local"
                    name="published_at"
                    value="{{ old('published_at', optional($note->published_at)->format('Y-m-d\\TH:i')) }}"
                >
                <div class="help">Si public&aacute;s y no eleg&iacute;s fecha, se usa la actual.</div>
            </div>
            <div class="form-field">
                <label for="is_published">Estado</label>
                <label style="display:flex; align-items:center; gap:10px; min-height:48px;">
                    <input id="is_published" type="checkbox" name="is_published" value="1" {{ old('is_published', $note->is_published) ? 'checked' : '' }}>
                    <span>Publicada</span>
                </label>
            </div>

            @include('partials.rich-text-editor', [
                'id' => 'excerpt',
                'name' => 'excerpt',
                'label' => 'Bajada / extracto',
                'value' => $note->excerpt,
                'placeholder' => 'Resumen breve de la nota para cards, SEO y encabezado.',
                'hint' => 'Conviene que sea corto y muy claro.',
            ])

            @include('partials.rich-text-editor', [
                'id' => 'body',
                'name' => 'body',
                'label' => 'Contenido',
                'value' => $note->body,
                'placeholder' => 'Desarroll&aacute; la nota con subt&iacute;tulos, p&aacute;rrafos, listas y links.',
                'hint' => 'Us&aacute; bloques claros y subt&iacute;tulos para mejorar lectura y SEO.',
            ])

            <div class="form-field full">
                <label for="seo_title">SEO title</label>
                <input id="seo_title" name="seo_title" value="{{ old('seo_title', $note->seo_title) }}" placeholder="Opcional. Si queda vac&iacute;o, usamos el t&iacute;tulo de la nota.">
            </div>
            <div class="form-field full">
                <label for="seo_description">SEO description</label>
                <textarea id="seo_description" name="seo_description" rows="4" placeholder="Descripci&oacute;n para Google y redes.">{{ old('seo_description', $note->seo_description) }}</textarea>
            </div>
        </div>

        <div class="actions">
            <button class="btn" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M5 12.5 10 17l9-10" />
                </svg>
                Guardar nota
            </button>
        </div>
    </form>
@endsection
