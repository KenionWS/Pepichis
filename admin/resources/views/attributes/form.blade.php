@extends('layouts.app', ['title' => $attribute->exists ? 'Editar característica' : 'Nueva característica'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">{{ $attribute->exists ? 'Editar característica' : 'Nueva característica' }}</h1>
            <p class="page-copy">Cargá los valores uno por línea. El sistema los usa para asignar en productores y vinos.</p>
        </div>
        <a class="btn-secondary" href="{{ route('attributes.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            Volver
        </a>
    </div>

    <form method="POST" action="{{ $attribute->exists ? route('attributes.update', $attribute) : route('attributes.store') }}" class="card stack">
        @csrf
        @if($attribute->exists)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-field">
                <label for="name">Nombre</label>
                <input id="name" name="name" value="{{ old('name', $attribute->name) }}" required>
            </div>
            <div class="form-field">
                <label for="scope">Alcance</label>
                <select id="scope" name="scope" required>
                    @foreach($scopeOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('scope', $attribute->scope ?: 'both') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field full">
                <label for="values_text">Valores</label>
                <textarea id="values_text" name="values_text">{{ old('values_text', $attribute->exists ? $attribute->values->pluck('value')->implode("\n") : '') }}</textarea>
                <div class="help">Ejemplo: California, Italia, Blend, 2023, Magnum.</div>
            </div>
        </div>

        <div class="actions">
            <button class="btn" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M5 12.5 10 17l9-10" />
                </svg>
                Guardar característica
            </button>
        </div>
    </form>
@endsection
