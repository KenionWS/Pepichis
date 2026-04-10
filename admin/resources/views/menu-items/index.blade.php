@extends('layouts.app', ['title' => 'Menu'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">Menu</h1>
            <p class="page-copy">Administra los links visibles en la cabecera del sitio. Puedes enlazar a secciones del home, paginas internas o URLs externas.</p>
        </div>
        <div class="actions">
            <form method="POST" action="{{ route('menu-items.reorder') }}" id="menu-order-form" class="floating-action">
                @csrf
                <button class="btn-secondary" type="submit" id="save-menu-order-button" disabled>
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                        <path d="M5 12.5 9.5 17 19 7.5" />
                    </svg>
                    Guardar orden
                </button>
            </form>
            <a class="btn" href="{{ route('menu-items.create') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M12 5v14" />
                    <path d="M5 12h14" />
                </svg>
                Nuevo item
            </a>
        </div>
    </div>

    <div class="card table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Etiqueta</th>
                    <th>Tipo</th>
                    <th>Destino</th>
                    <th>Estado</th>
                    <th>Nueva pestaña</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="menu-sortable-body">
                @forelse($menuItems as $menuItem)
                    <tr draggable="true" data-menu-item-id="{{ $menuItem->id }}">
                        <td>
                            <div class="drag-cell">
                                <button class="drag-handle" type="button" aria-label="Mover {{ $menuItem->label }}">
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
                        <td><strong>{{ $menuItem->label }}</strong></td>
                        <td>{{ $typeOptions[$menuItem->item_type] ?? $menuItem->item_type }}</td>
                        <td><code>{{ $menuItem->item_value }}</code></td>
                        <td>
                            <span class="pill" style="{{ $menuItem->is_active ? 'background: rgba(220,231,141,.35); color:#4d6312;' : 'background: rgba(128,0,32,.1); color:#800020;' }}">
                                {{ $menuItem->is_active ? 'Activo' : 'Oculto' }}
                            </span>
                        </td>
                        <td>{{ $menuItem->open_in_new_tab ? 'Si' : 'No' }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn-secondary" href="{{ route('menu-items.edit', $menuItem) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="m4 20 4.5-1 9-9-3.5-3.5-9 9L4 20Z" />
                                        <path d="m13.5 6.5 3.5 3.5" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('menu-items.destroy', $menuItem) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit" onclick="return confirm('¿Eliminar item del menu?')">
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
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </div>
                                <div>No hay items en el menu.</div>
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
            const tbody = document.getElementById('menu-sortable-body');
            const form = document.getElementById('menu-order-form');
            const saveButton = document.getElementById('save-menu-order-button');

            if (!tbody || !form || !saveButton) {
                return;
            }

            let draggedRow = null;
            let initialOrder = '';

            const syncOrder = () => {
                form.querySelectorAll('input[name="ordered_ids[]"]').forEach((input) => input.remove());

                const ids = Array.from(tbody.querySelectorAll('tr[data-menu-item-id]')).map((row, index) => {
                    const counter = row.querySelector('.order-index');

                    if (counter) {
                        counter.textContent = String(index + 1);
                    }

                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'ordered_ids[]';
                    hidden.value = row.dataset.menuItemId;
                    form.appendChild(hidden);

                    return row.dataset.menuItemId;
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

            tbody.querySelectorAll('tr[data-menu-item-id]').forEach((row) => {
                row.addEventListener('dragstart', (event) => {
                    draggedRow = row;
                    row.classList.add('row-dragging');

                    if (event.dataTransfer) {
                        event.dataTransfer.effectAllowed = 'move';
                        event.dataTransfer.setData('text/plain', row.dataset.menuItemId);
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
