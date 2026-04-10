@extends('layouts.app', ['title' => 'Características'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">Características</h1>
            <p class="page-copy">Definí atributos y valores con una vista más clara para distinguir alcance, opciones y acciones rápidas.</p>
        </div>
        <a class="btn" href="{{ route('attributes.create') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M12 5v14" />
                <path d="M5 12h14" />
            </svg>
            Nueva característica
        </a>
    </div>

    <div class="card table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Alcance</th>
                    <th>Valores</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attributes as $attribute)
                    <tr>
                        <td><strong>{{ $attribute->name }}</strong></td>
                        <td>{{ \App\Models\Attribute::scopeOptions()[$attribute->scope] ?? $attribute->scope }}</td>
                        <td>
                            <div class="pill-list">
                                @foreach($attribute->values as $value)
                                    <span class="pill">{{ $value->value }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn-secondary" href="{{ route('attributes.edit', $attribute) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="m4 20 4.5-1 9-9-3.5-3.5-9 9L4 20Z" />
                                        <path d="m13.5 6.5 3.5 3.5" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('attributes.destroy', $attribute) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit" onclick="return confirm('¿Eliminar característica?')">
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
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <path d="m7 7 10 10" />
                                        <path d="M7 17 17 7" />
                                        <circle cx="7" cy="7" r="3" />
                                        <circle cx="17" cy="17" r="3" />
                                    </svg>
                                </div>
                                <div>No hay características cargadas.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
