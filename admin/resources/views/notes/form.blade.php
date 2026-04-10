@extends('layouts.app', ['title' => $note->exists ? 'Editar nota' : 'Nueva nota'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">{{ $note->exists ? 'Editar nota' : 'Nueva nota' }}</h1>
            <p class="page-copy">Crea contenido editorial con foco en posicionamiento, lectura clara y estetica consistente con el sitio.</p>
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
                <label for="title">Titulo</label>
                <input id="title" name="title" value="{{ old('title', $note->title) }}" required>
            </div>
            <div class="form-field">
                <label for="slug">URL amigable</label>
                <input id="slug" name="slug" value="{{ old('slug', $note->slug) }}" placeholder="como-elegir-vinos-para-otono">
                <div class="help">Si lo dejas vacio, se genera desde el titulo.</div>
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
                <label for="published_at">Fecha de publicacion</label>
                <input
                    id="published_at"
                    type="datetime-local"
                    name="published_at"
                    value="{{ old('published_at', optional($note->published_at)->format('Y-m-d\\TH:i')) }}"
                >
                <div class="help">Si publicas y no eliges fecha, se usa la actual.</div>
            </div>
            <div class="form-field">
                <label for="is_published">Estado</label>
                <label class="checkbox-field">
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
                'blockFormats' => [
                    'p' => 'Parrafo',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'blockquote' => 'Cita',
                ],
            ])

            @include('partials.rich-text-editor', [
                'id' => 'body',
                'name' => 'body',
                'label' => 'Contenido',
                'value' => $note->body,
                'placeholder' => 'Desarrolla la nota con subtitulos, parrafos, listas, links e imagenes.',
                'hint' => 'Puedes elegir etiquetas de bloque e insertar imagenes dentro del contenido.',
                'blockFormats' => [
                    'p' => 'Parrafo',
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'blockquote' => 'Cita',
                ],
                'imageUploadUrl' => route('notes.editor-image.store'),
            ])

            <div class="form-field full">
                <label for="seo_title">SEO title</label>
                <input id="seo_title" name="seo_title" value="{{ old('seo_title', $note->seo_title) }}" placeholder="Opcional. Si queda vacio, usamos el titulo de la nota.">
            </div>
            <div class="form-field full">
                <label for="seo_description">SEO description</label>
                <textarea id="seo_description" name="seo_description" rows="4" placeholder="Descripcion para Google y redes.">{{ old('seo_description', $note->seo_description) }}</textarea>
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
    <script>
        (function () {
            async function uploadInlineImage(input, file) {
                const formData = new FormData();
                formData.append('image', file);

                const response = await fetch(input.dataset.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': input.dataset.uploadToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('No se pudo subir la imagen.');
                }

                return response.json();
            }

            function enhanceNoteEditors() {
                document.querySelectorAll('[data-rich-editor]').forEach((editor) => {
                    if (editor.dataset.noteEnhanced === 'true') {
                        return;
                    }

                    const surface = editor.querySelector('.rich-editor-surface');
                    const textarea = editor.querySelector('.rich-editor-hidden');
                    const imageInput = editor.querySelector('[data-image-input]');
                    let savedRange = null;

                    if (!surface || !textarea) {
                        return;
                    }

                    const sync = () => {
                        textarea.value = surface.innerHTML.trim();
                    };

                    const saveSelection = () => {
                        const selection = window.getSelection();

                        if (selection && selection.rangeCount > 0) {
                            savedRange = selection.getRangeAt(0).cloneRange();
                        }
                    };

                    const restoreSelection = () => {
                        if (!savedRange) {
                            return;
                        }

                        const selection = window.getSelection();

                        if (!selection) {
                            return;
                        }

                        selection.removeAllRanges();
                        selection.addRange(savedRange);
                    };

                    editor.querySelectorAll('[data-action="formatBlock"]').forEach((select) => {
                        select.addEventListener('change', () => {
                            restoreSelection();
                            document.execCommand('formatBlock', false, '<' + select.value + '>');
                            surface.focus();
                            saveSelection();
                            sync();
                        });
                    });

                    editor.querySelectorAll('[data-action="image"]').forEach((button) => {
                        button.addEventListener('click', () => {
                            saveSelection();
                            if (imageInput) {
                                imageInput.click();
                            }
                        });
                    });

                    if (imageInput) {
                        imageInput.addEventListener('change', async () => {
                            const file = imageInput.files && imageInput.files[0];

                            if (!file) {
                                return;
                            }

                            try {
                                const payload = await uploadInlineImage(imageInput, file);
                                const alt = window.prompt('Texto alternativo de la imagen', '') || '';

                                restoreSelection();
                                document.execCommand('insertHTML', false, '<p><img src="' + payload.url + '" alt="' + alt.replace(/"/g, '&quot;') + '"></p>');
                                surface.focus();
                                saveSelection();
                                sync();
                            } catch (error) {
                                window.alert(error.message || 'No se pudo subir la imagen.');
                            } finally {
                                imageInput.value = '';
                            }
                        });
                    }

                    surface.addEventListener('mouseup', saveSelection);
                    surface.addEventListener('keyup', saveSelection);
                    surface.addEventListener('focus', saveSelection);

                    const form = editor.closest('form');
                    if (form) {
                        form.addEventListener('submit', sync);
                    }

                    editor.dataset.noteEnhanced = 'true';
                });
            }

            document.addEventListener('DOMContentLoaded', enhanceNoteEditors);
        })();
    </script>
@endpush
