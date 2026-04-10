@extends('layouts.app', ['title' => 'Vinos'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">Vinos</h1>
            <p class="page-copy">Pod&eacute;s buscar por nombre, acotar por productor o por caracter&iacute;stica, y cuando est&aacute;s viendo un productor puntual reordenar sus etiquetas con drag &amp; drop.</p>
        </div>
        <div class="actions">
            @if($canReorder)
                <form method="POST" action="{{ route('wines.reorder') }}" id="wine-order-form" class="floating-action">
                    @csrf
                    <input type="hidden" name="producer_id" value="{{ $filters['producer_id'] }}">
                    <button class="btn-secondary" type="submit" id="save-wine-order-button" disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <path d="M5 12.5 9.5 17 19 7.5" />
                        </svg>
                        Guardar orden
                    </button>
                </form>
            @endif

            <a class="btn" href="{{ route('wines.create') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                Nuevo vino
            </a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('wines.index') }}" class="form-grid">
            <div class="form-field">
                <label for="filter-name">Nombre</label>
                <input id="filter-name" type="text" name="name" value="{{ $filters['name'] }}" placeholder="Buscar por nombre de vino">
            </div>

            <div class="form-field">
                <label for="filter-producer">Productor</label>
                <select id="filter-producer" name="producer_id">
                    <option value="">Todos los productores</option>
                    @foreach($producers as $producer)
                        <option value="{{ $producer->id }}" {{ (string) $filters['producer_id'] === (string) $producer->id ? 'selected' : '' }}>
                            {{ $producer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="filter-attribute">Caracter&iacute;stica</label>
                <select id="filter-attribute" name="attribute_value_id">
                    <option value="">Todas las caracter&iacute;sticas</option>
                    @foreach($attributeValues as $attributeValue)
                        <option value="{{ $attributeValue->id }}" {{ (string) $filters['attribute_value_id'] === (string) $attributeValue->id ? 'selected' : '' }}>
                            {{ $attributeValue->attribute->name }}: {{ $attributeValue->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field" style="align-self: end;">
                <div class="actions">
                    <button class="btn" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3.5-3.5" />
                        </svg>
                        Filtrar
                    </button>
                    <a class="btn-secondary" href="{{ route('wines.index') }}">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>

        @if($filters['producer_id'] && ! $canReorder)
            <p class="help" style="margin-top: 14px;">El drag &amp; drop se habilita cuando filtr&aacute;s solo por productor, sin nombre ni caracter&iacute;stica adicional.</p>
        @elseif($canReorder)
            <p class="help" style="margin-top: 14px;">Arrastr&aacute; las filas para definir el orden de los vinos de este productor en el admin y en su detalle p&uacute;blico.</p>
        @endif
    </div>

    <div class="card table-wrap">
        <table>
            <thead>
                <tr>
                    @if($canReorder)
                        <th>Orden</th>
                    @endif
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Productor</th>
                    <th>Home</th>
                    <th>Caracter&iacute;sticas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="wine-sortable-body">
                @forelse($wines as $wine)
                    <tr @if($canReorder) draggable="true" data-wine-id="{{ $wine->id }}" @endif>
                        @if($canReorder)
                            <td>
                                <div class="drag-cell">
                                    <button class="drag-handle" type="button" aria-label="Mover {{ $wine->name }}">
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="9" cy="6" r="1"></circle>
                                            <circle cx="15" cy="6" r="1"></circle>
                                            <circle cx="9" cy="12" r="1"></circle>
                                            <circle cx="15" cy="12" r="1"></circle>
                                            <circle cx="9" cy="18" r="1"></circle>
                                            <circle cx="15" cy="18" r="1"></circle>
                                        </svg>
                                    </button>
                                    <span class="help order-index">{{ $loop->iteration }}</span>
                                </div>
                            </td>
                        @endif
                        <td>
                            @if($wine->image_path)
                                <img src="{{ asset($wine->image_path) }}" alt="{{ $wine->name }}" class="thumb">
                            @endif
                        </td>
                        <td>
                            <div class="row-title">
                                <strong>{{ $wine->name }}</strong>
                                <div class="help">{{ \Illuminate\Support\Str::limit(trim(strip_tags($wine->short_description)), 150) }}</div>
                            </div>
                        </td>
                        <td>{{ optional($wine->producer)->name }}</td>
                        <td>
                            <span class="pill" style="{{ $wine->show_on_home ? 'background: rgba(220,231,141,.35); color:#4d6312;' : 'background: rgba(128,0,32,.1); color:#800020;' }}">
                                {{ $wine->show_on_home ? 'Visible' : 'Oculto' }}
                            </span>
                        </td>
                        <td>
                            <div class="pill-list">
                                @foreach($wine->attributeValues as $value)
                                    <span class="pill">{{ $value->attribute->name }}: {{ $value->value }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn-secondary" href="{{ route('wines.edit', $wine) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="m4 20 4.5-1 9-9-3.5-3.5-9 9L4 20Z" />
                                        <path d="m13.5 6.5 3.5 3.5" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('wines.destroy', $wine) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit" onclick="return confirm('\u00BFEliminar vino?')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                            <path d="M3 6h18" />
                                            <path d="M8 6V4h8v2" />
                                            <path d="m19 6-1 14H6L5 6" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $canReorder ? 7 : 6 }}">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="M7 3h10" />
                                        <path d="M9 3v6a3 3 0 0 0 6 0V3" />
                                        <path d="M12 12v8" />
                                    </svg>
                                </div>
                                <div>No hay vinos cargados con esos filtros.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    @if($canReorder)
        <script>
            (function () {
                const tbody = document.getElementById('wine-sortable-body');
                const form = document.getElementById('wine-order-form');
                const saveButton = document.getElementById('save-wine-order-button');

                if (!tbody || !form || !saveButton) {
                    return;
                }

                let draggedRow = null;
                let initialOrder = '';

                const syncOrder = () => {
                    form.querySelectorAll('input[name="ordered_ids[]"]').forEach((input) => input.remove());

                    const ids = Array.from(tbody.querySelectorAll('tr[data-wine-id]')).map((row, index) => {
                        const counter = row.querySelector('.order-index');

                        if (counter) {
                            counter.textContent = String(index + 1);
                        }

                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'ordered_ids[]';
                        hidden.value = row.dataset.wineId;
                        form.appendChild(hidden);

                        return row.dataset.wineId;
                    });

                    return ids.join(',');
                };

                const enableSave = () => {
                    saveButton.disabled = false;
                    saveButton.classList.remove('btn-secondary');
                    saveButton.classList.add('btn');
                    form.classList.add('is-visible');
                };

                const disableSave = () => {
                    saveButton.disabled = true;
                    saveButton.classList.remove('btn');
                    saveButton.classList.add('btn-secondary');
                    form.classList.remove('is-visible');
                };

                tbody.querySelectorAll('tr[data-wine-id]').forEach((row) => {
                    row.addEventListener('dragstart', (event) => {
                        draggedRow = row;
                        row.classList.add('row-dragging');

                        if (event.dataTransfer) {
                            event.dataTransfer.effectAllowed = 'move';
                            event.dataTransfer.setData('text/plain', row.dataset.wineId);
                        }
                    });

                    row.addEventListener('dragend', () => {
                        row.classList.remove('row-dragging');
                        tbody.querySelectorAll('.row-drop-target').forEach((target) => target.classList.remove('row-drop-target'));

                        const currentOrder = syncOrder();

                        if (currentOrder !== initialOrder) {
                            enableSave();
                        } else {
                            disableSave();
                        }
                    });

                    row.addEventListener('dragover', (event) => {
                        event.preventDefault();

                        if (!draggedRow || draggedRow === row) {
                            return;
                        }

                        const bounds = row.getBoundingClientRect();
                        const shouldInsertAfter = event.clientY > bounds.top + (bounds.height / 2);

                        tbody.querySelectorAll('.row-drop-target').forEach((target) => target.classList.remove('row-drop-target'));
                        row.classList.add('row-drop-target');

                        if (shouldInsertAfter) {
                            row.after(draggedRow);
                        } else {
                            row.before(draggedRow);
                        }
                    });
                });

                initialOrder = syncOrder();
                disableSave();
            })();
        </script>
    @endif
@endpush
