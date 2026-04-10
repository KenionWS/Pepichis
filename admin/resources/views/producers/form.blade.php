@extends('layouts.app', ['title' => $producer->exists ? 'Editar productor' : 'Nuevo productor'])

@section('content')
    <div class="topbar">
        <div>
            <h1 class="page-title">{{ $producer->exists ? 'Editar productor' : 'Nuevo productor' }}</h1>
            <p class="page-copy">Completá la ficha y asigná una opción por característica.</p>
        </div>
        <a class="btn-secondary" href="{{ route('producers.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                <path d="M19 12H5" />
                <path d="m12 19-7-7 7-7" />
            </svg>
            Volver
        </a>
    </div>

    <form method="POST" action="{{ $producer->exists ? route('producers.update', $producer) : route('producers.store') }}" enctype="multipart/form-data" class="card stack">
        @csrf
        @if($producer->exists)
            @method('PUT')
        @endif

        <div class="form-grid">
            <div class="form-field">
                <label for="name">Nombre</label>
                <input id="name" name="name" value="{{ old('name', $producer->name) }}" required>
            </div>
            <div class="form-field">
                <label for="slug">URL amigable</label>
                <input id="slug" name="slug" value="{{ old('slug', $producer->slug) }}" placeholder="chiara-condello">
                <div class="help">Solo letras minúsculas, números y guiones.</div>
            </div>
            <div class="form-field">
                <label for="image">Imagen</label>
                <input id="image" type="file" name="image" accept="image/*">
                @if($producer->image_path)
                    <div class="help">Imagen actual:</div>
                    <img src="{{ asset($producer->image_path) }}" alt="{{ $producer->name }}" class="thumb">
                @endif
            </div>
            <div class="form-field">
                <label for="city">Ciudad</label>
                <input id="city" name="city" value="{{ old('city', $producer->city) }}">
            </div>
            <div class="form-field">
                <label for="state">Estado / Provincia</label>
                <input id="state" name="state" value="{{ old('state', $producer->state) }}">
            </div>
            <div class="form-field">
                <label for="country">País</label>
                <input id="country" name="country" value="{{ old('country', $producer->country) }}">
            </div>

            @include('partials.rich-text-editor', [
                'id' => 'short_description',
                'name' => 'short_description',
                'label' => 'Descripción corta',
                'value' => $producer->short_description,
                'placeholder' => 'Resumen breve con énfasis, links o listas si hace falta.',
                'hint' => 'Podés usar negrita, cursiva, listas y links.',
            ])

            @include('partials.rich-text-editor', [
                'id' => 'long_description',
                'name' => 'long_description',
                'label' => 'Descripción larga',
                'value' => $producer->long_description,
                'placeholder' => 'Desarrollá la historia del productor con formato básico.',
                'hint' => 'El contenido se guarda como HTML básico.',
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
                Guardar productor
            </button>
        </div>
    </form>
@endsection
