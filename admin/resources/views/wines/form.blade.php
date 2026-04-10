@extends('layouts.app', ['title' => $wine->exists ? 'Editar vino' : 'Nuevo vino'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">{{ $wine->exists ? 'Editar vino' : 'Nuevo vino' }}</h1>
            <p class="page-copy">Asociá el vino a un productor y cargá sus atributos.</p>
        </div>
        <a class="btn-secondary" href="{{ route('wines.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            Volver
        </a>
    </div>

    <form method="POST" action="{{ $wine->exists ? route('wines.update', $wine) : route('wines.store') }}" enctype="multipart/form-data" class="card stack">
        @csrf
        @if($wine->exists)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-field">
                <label for="producer_id">Productor</label>
                <select id="producer_id" name="producer_id" required>
                    <option value="">Seleccionar productor</option>
                    @foreach($producers as $producer)
                        <option value="{{ $producer->id }}" {{ (string) old('producer_id', $wine->producer_id) === (string) $producer->id ? 'selected' : '' }}>{{ $producer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field">
                <label for="name">Nombre</label>
                <input id="name" name="name" value="{{ old('name', $wine->name) }}" required>
            </div>
            <div class="form-field">
                <label for="image">Imagen</label>
                <input id="image" type="file" name="image" accept="image/*">
                @if($wine->image_path)
                    <div class="help">Imagen actual:</div>
                    <img src="{{ asset($wine->image_path) }}" alt="{{ $wine->name }}" class="thumb">
                @endif
            </div>
            <div class="form-field">
                <label class="checkbox-field">
                    <input type="checkbox" name="show_on_home" value="1" {{ old('show_on_home', $wine->show_on_home) ? 'checked' : '' }}>
                    <span>Mostrar en la home</span>
                </label>
                <div class="help">Si está activo, este vino puede aparecer en el bloque visual principal del home.</div>
            </div>

            @include('partials.rich-text-editor', [
                'id' => 'short_description',
                'name' => 'short_description',
                'label' => 'Descripción corta',
                'value' => $wine->short_description,
                'placeholder' => 'Texto breve del vino con énfasis y links si querés.',
                'hint' => 'Podés usar formato básico.',
            ])

            @include('partials.rich-text-editor', [
                'id' => 'long_description',
                'name' => 'long_description',
                'label' => 'Descripción larga',
                'value' => $wine->long_description,
                'placeholder' => 'Amplía la descripción del vino con formato básico.',
                'hint' => 'Se guardan negritas, cursivas, listas y links.',
            ])
        </div>

        <div class="card" style="padding: 20px;">
            <h2 class="section-title">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="m7 7 10 10" />
                    <path d="M7 17 17 7" />
                    <circle cx="7" cy="7" r="3" />
                    <circle cx="17" cy="17" r="3" />
                    <circle cx="17" cy="7" r="3" />
                    <circle cx="7" cy="17" r="3" />
                </svg>
                Características
            </h2>
            <div class="form-grid">
                @foreach($attributes as $attribute)
                    <div class="form-field">
                        <label for="attribute_{{ $attribute->id }}">{{ $attribute->name }}</label>
                        <select id="attribute_{{ $attribute->id }}" name="attribute_values[{{ $attribute->id }}]">
                            <option value="">Sin asignar</option>
                            @foreach($attribute->values as $value)
                                <option value="{{ $value->id }}" {{ (string) old('attribute_values.' . $attribute->id, $selectedAttributeValues[$attribute->id] ?? '') === (string) $value->id ? 'selected' : '' }}>
                                    {{ $value->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="actions">
            <button class="btn" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                    <path d="M5 12.5 10 17l9-10" />
                </svg>
                Guardar vino
            </button>
        </div>
    </form>
@endsection
