@extends('layouts.app', ['title' => 'Productores'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">Productores</h1>
            <p class="page-copy">ABM completo con imagen, origen y atributos. Ahora tambi&eacute;n pod&eacute;s arrastrar productores para definir el orden exacto en que aparecen en el front.</p>
        </div>
        <div class="actions">
            <form method="POST" action="{{ route('producers.reorder') }}" id="producer-order-form" class="floating-action">
                @csrf
                <button class="btn-secondary" type="submit" id="save-order-button" disabled>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M5 12.5 9.5 17 19 7.5" />
                    </svg>
                    Guardar orden
                </button>
            </form>
            <a class="btn" href="{{ route('producers.create') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                Nuevo productor
            </a>
        </div>
    </div>

    <div class="card table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Ubicaci&oacute;n</th>
                    <th>Caracter&iacute;sticas</th>
                    <th>Vinos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="producer-sortable-body">
                @forelse($producers as $producer)
                    <tr draggable="true" data-producer-id="{{ $producer->id }}">
                        <td>
                            <div class="drag-cell">
                                <button class="drag-handle" type="button" aria-label="Mover {{ $producer->name }}">
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
                        <td>
                            @if($producer->image_path)
                                <img src="{{ asset($producer->image_path) }}" alt="{{ $producer->name }}" class="thumb">
                            @endif
                        </td>
                        <td>
                            <div class="row-title">
                                <strong>{{ $producer->name }}</strong>
                                <div class="help">{{ \Illuminate\Support\Str::limit(trim(strip_tags($producer->short_description)), 150) }}</div>
                            </div>
                        </td>
                        <td>{{ collect([$producer->city, $producer->state, $producer->country])->filter()->join(', ') }}</td>
                        <td>
                            <div class="pill-list">
                                @foreach($producer->attributeValues as $value)
                                    <span class="pill">{{ $value->attribute->name }}: {{ $value->value }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td><strong>{{ $producer->wines->count() }}</strong></td>
                        <td>
                            <div class="actions">
                                <a class="btn-secondary" href="{{ route('producers.edit', $producer) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="m4 20 4.5-1 9-9-3.5-3.5-9 9L4 20Z" />
                                        <path d="m13.5 6.5 3.5 3.5" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('producers.destroy', $producer) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit" onclick="return confirm('\u00BFEliminar productor?')">
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
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <circle cx="12" cy="8" r="4" />
                                        <path d="M16 20a4 4 0 0 0-8 0" />
                                    </svg>
                                </div>
                                <div>No hay productores cargados.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const tbody = document.getElementById('producer-sortable-body');
            const form = document.getElementById('producer-order-form');
            const saveButton = document.getElementById('save-order-button');

            if (!tbody || !form || !saveButton) {
                return;
            }

            let draggedRow = null;
            let initialOrder = '';

            const syncOrder = () => {
                form.querySelectorAll('input[name="ordered_ids[]"]').forEach((input) => input.remove());

                const ids = Array.from(tbody.querySelectorAll('tr[data-producer-id]')).map((row, index) => {
                    const counter = row.querySelector('.order-index');

                    if (counter) {
                        counter.textContent = String(index + 1);
                    }

                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'ordered_ids[]';
                    hidden.value = row.dataset.producerId;
                    form.appendChild(hidden);

                    return row.dataset.producerId;
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

            tbody.querySelectorAll('tr[data-producer-id]').forEach((row) => {
                row.addEventListener('dragstart', (event) => {
                    draggedRow = row;
                    row.classList.add('row-dragging');

                    if (event.dataTransfer) {
                        event.dataTransfer.effectAllowed = 'move';
                        event.dataTransfer.setData('text/plain', row.dataset.producerId);
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
@endpush
