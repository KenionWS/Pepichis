@extends('layouts.app', ['title' => $menuItem->exists ? 'Editar item del menu' : 'Nuevo item del menu'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">{{ $menuItem->exists ? 'Editar item del menu' : 'Nuevo item del menu' }}</h1>
            <p class="page-copy">Gestiona enlaces del menu principal del sitio y define si apuntan a una seccion, una pagina interna o una URL externa.</p>
        </div>
        <a class="btn-secondary" href="{{ route('menu-items.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            Volver
        </a>
    </div>

    <form method="POST" action="{{ $menuItem->exists ? route('menu-items.update', $menuItem) : route('menu-items.store') }}" class="card stack">
        @csrf
        @if($menuItem->exists)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-field">
                <label for="label">Etiqueta</label>
                <input id="label" name="label" value="{{ old('label', $menuItem->label) }}" required>
            </div>

            <div class="form-field">
                <label for="item_type">Tipo de destino</label>
                <select id="item_type" name="item_type" data-menu-type-selector>
                    @foreach($typeOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('item_type', $menuItem->item_type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field full" data-menu-type-panel="{{ \App\Models\MenuItem::TYPE_HOME_SECTION }}">
                <label for="home_section">Seccion del home</label>
                <select id="home_section" name="home_section">
                    <option value="">Selecciona una seccion</option>
                    @foreach($homeSectionOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('home_section', $menuItem->item_type === \App\Models\MenuItem::TYPE_HOME_SECTION ? $menuItem->item_value : null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field full" data-menu-type-panel="{{ \App\Models\MenuItem::TYPE_ROUTE }}">
                <label for="route_name">Ruta interna</label>
                <select id="route_name" name="route_name">
                    <option value="">Selecciona una ruta</option>
                    @foreach($routeOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('route_name', $menuItem->item_type === \App\Models\MenuItem::TYPE_ROUTE ? $menuItem->item_value : null) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field full" data-menu-type-panel="{{ \App\Models\MenuItem::TYPE_EXTERNAL_URL }}">
                <label for="external_url">URL externa</label>
                <input id="external_url" name="external_url" type="url" value="{{ old('external_url', $menuItem->item_type === \App\Models\MenuItem::TYPE_EXTERNAL_URL ? $menuItem->item_value : null) }}" placeholder="https://instagram.com/pepichis.wines">
                <div class="help">Incluye https:// completo.</div>
            </div>

            <div class="form-field">
                <label style="display:flex; align-items:center; gap:10px; min-height:48px;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $menuItem->is_active) ? 'checked' : '' }}>
                    <span>Visible en el menu</span>
                </label>
            </div>

            <div class="form-field">
                <label style="display:flex; align-items:center; gap:10px; min-height:48px;">
                    <input type="checkbox" name="open_in_new_tab" value="1" {{ old('open_in_new_tab', $menuItem->open_in_new_tab) ? 'checked' : '' }}>
                    <span>Abrir en nueva pestaña</span>
                </label>
            </div>
        </div>

        <div class="actions">
            <button class="btn" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M5 12.5 10 17l9-10" />
                </svg>
                Guardar item
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            const selector = document.querySelector('[data-menu-type-selector]');
            const panels = document.querySelectorAll('[data-menu-type-panel]');

            if (!selector || !panels.length) {
                return;
            }

            const syncPanels = () => {
                panels.forEach((panel) => {
                    panel.style.display = panel.dataset.menuTypePanel === selector.value ? 'grid' : 'none';
                });
            };

            selector.addEventListener('change', syncPanels);
            syncPanels();
        })();
    </script>
@endpush
