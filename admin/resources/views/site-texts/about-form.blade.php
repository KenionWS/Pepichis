@extends('layouts.app', ['title' => 'Nosotros'])

@section('content')
        <div class="topbar">
        <div>
            <h1 class="page-title">Nosotros</h1>
            <p class="page-copy">Edita la pagina institucional de Pepichis. Puedes aplicar estilos basicos para ordenar mejor el contenido en el front.</p>
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

            @include('partials.rich-text-editor', [
                'id' => 'body',
                'name' => 'body',
                'label' => 'Texto',
                'value' => $siteText->body,
                'placeholder' => 'Cuenta la historia de Pepichis con parrafos, subtitulos, citas y listas.',
                'hint' => 'Puedes usar parrafos, H2, H3, citas, listas, negrita, cursiva y links.',
                'blockFormats' => [
                    'p' => 'Parrafo',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'blockquote' => 'Cita',
                ],
            ])
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

@push('scripts')
    <style>
        .rich-editor-toolbar select {
            min-width: 120px;
            width: auto;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid rgba(150, 113, 94, 0.18);
            background: rgba(255, 255, 255, 0.92);
            font-size: 13px;
        }
    </style>
@endpush
