@extends('front.layouts.app', ['title' => 'Productores | Pepichis'])

@php
    $producersMetaDescription = 'Conocé los productores de vinos importados por Pepichis y explorá etiquetas con identidad, origen y carácter.';
    $producerList = $producers->map(function ($producer, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $producer->name,
            'url' => route('front.producers.show', $producer->slug),
            'image' => $producer->image_path ? asset($producer->image_path) : null,
        ];
    })->values()->all();
    $producersCollectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Productores | Pepichis',
        'url' => route('front.producers.index'),
        'description' => $producersMetaDescription,
        'inLanguage' => 'es-AR',
        'mainEntity' => [
            '@type' => 'ItemList',
            'name' => 'Productores de Pepichis',
            'itemListElement' => $producerList,
        ],
    ];
@endphp

@section('meta_title', 'Productores | Pepichis')
@section('meta_description', $producersMetaDescription)
@section('meta_image', asset('pepichis_logo.png'))
@section('meta_type', 'website')
@section('canonical', route('front.producers.index'))

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($producersCollectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('front-home.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('front-home.js') }}"></script>
@endpush

@section('content')

    <section class="producers-section producers-section-page" id="productores">
        <div class="section-header">
            <h1 class="section-title" style="margin-top: 2rem;">Detrás de cada botella<br>hay una mirada única</h1>
        </div>

        <div class="producers-grid">
            @foreach ($producers as $producer)
                @php
                    $location = collect([$producer->city, $producer->state, $producer->country])->filter()->join(', ');
                    $description = \Illuminate\Support\Str::limit(trim(strip_tags($producer->short_description)), 250);
                    $producerAlt = trim($producer->name . ($location ? ', productor de vinos en ' . $location : ', productor de vinos'));
                @endphp
                <div class="producer-card-container">
                    <div class="producer-card-inner">
                        <div class="producer-card-face producer-card-front">
                            <div class="silhouette-wrapper">
                                <img class="silhouette-img" src="{{ asset($producer->image_path) }}" alt="{{ $producerAlt }}" loading="lazy">
                            </div>
                            <div class="front-label">
                                <div class="producer-region"><span class="highlight">{{ $location }}</span></div>
                                <h3><span class="highlight">{{ $producer->name }}</span></h3>
                            </div>
                        </div>
                        <div class="producer-card-face producer-card-back">
                            <div class="producer-content">
                                <div class="producer-region">{{ $location }}</div>
                                <h3>{{ $producer->name }}</h3>
                                <p>{{ $description }}</p>
                                <a href="{{ route('front.producers.show', $producer->slug) }}" class="producer-link" aria-label="Explorar vinos de {{ $producer->name }}">Explorar vinos de {{ $producer->name }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
