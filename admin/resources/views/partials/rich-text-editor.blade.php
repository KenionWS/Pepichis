<div class="form-field full">
    <label for="{{ $id }}_editor">{{ $label }}</label>
    <div class="rich-editor" data-rich-editor>
        <div class="rich-editor-toolbar">
            @if(!empty($blockFormats))
                <select data-action="formatBlock" title="Tipo de bloque">
                    @foreach($blockFormats as $tag => $blockLabel)
                        <option value="{{ $tag }}">{{ $blockLabel }}</option>
                    @endforeach
                </select>
            @endif
            <button type="button" data-command="bold" title="Negrita">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <path d="M7 5h6a4 4 0 0 1 0 8H7z" />
                    <path d="M7 13h7a4 4 0 0 1 0 8H7z" />
                </svg>
            </button>
            <button type="button" data-command="italic" title="Cursiva">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <path d="M15 4h-4" />
                    <path d="M13 20H9" />
                    <path d="m14 4-4 16" />
                </svg>
            </button>
            <button type="button" data-command="underline" title="Subrayado">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <path d="M7 4v6a5 5 0 0 0 10 0V4" />
                    <path d="M5 20h14" />
                </svg>
            </button>
            <button type="button" data-command="insertUnorderedList" title="Lista">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <circle cx="5" cy="7" r="1.5" />
                    <circle cx="5" cy="12" r="1.5" />
                    <circle cx="5" cy="17" r="1.5" />
                    <path d="M10 7h9" />
                    <path d="M10 12h9" />
                    <path d="M10 17h9" />
                </svg>
            </button>
            <button type="button" data-command="insertOrderedList" title="Lista numerada">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <path d="M4 7h1v4" />
                    <path d="M4 16h2l-2 3h2" />
                    <path d="M10 7h9" />
                    <path d="M10 12h9" />
                    <path d="M10 17h9" />
                </svg>
            </button>
            <button type="button" data-action="link" title="Insertar link">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <path d="M10 13a5 5 0 0 0 7.07 0l2.83-2.83a5 5 0 0 0-7.07-7.07L11 5" />
                    <path d="M14 11a5 5 0 0 0-7.07 0L4.1 13.83A5 5 0 1 0 11.17 20.9L13 19" />
                </svg>
            </button>
            <button type="button" data-command="unlink" title="Quitar link">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <path d="M9 15 5 19" />
                    <path d="m15 9 4-4" />
                    <path d="m8.5 8.5 7 7" />
                    <path d="M13 7a5 5 0 0 1 6 6" />
                    <path d="M7 13a5 5 0 0 1-2-4" />
                </svg>
            </button>
            <button type="button" data-action="clear" title="Limpiar formato">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <path d="m3 21 9-9" />
                    <path d="m13 4 7 7" />
                    <path d="M8 5h8" />
                    <path d="m6 9 4-4" />
                </svg>
            </button>
            @if(!empty($imageUploadUrl))
                <button type="button" data-action="image" title="Insertar imagen">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <circle cx="9" cy="10" r="1.5" />
                        <path d="m21 16-4.5-4.5L8 20" />
                    </svg>
                </button>
                <input
                    type="file"
                    accept="image/*"
                    class="rich-editor-hidden"
                    data-image-input
                    data-upload-url="{{ $imageUploadUrl }}"
                    data-upload-token="{{ csrf_token() }}"
                >
            @endif
        </div>
        <div
            id="{{ $id }}_editor"
            class="rich-editor-surface"
            contenteditable="true"
            data-placeholder="{{ $placeholder ?? 'Escribí acá...' }}"
        >{!! old($name, $value ?? '') !!}</div>
        <textarea id="{{ $id }}" name="{{ $name }}" class="rich-editor-hidden">{{ old($name, $value ?? '') }}</textarea>
    </div>
    @if(!empty($hint))
        <div class="help">{{ $hint }}</div>
    @endif
</div>
