@extends('front.layouts.app', ['title' => $producer->name . ' | Vinos importados por Pepichis'])

@php
    $location = collect([$producer->city, $producer->state, $producer->country])->filter()->join(', ');
    $sizeClasses = ['size-small', 'size-large', '', 'size-magnum', 'size-large'];
    $seoDescription = \Illuminate\Support\Str::limit(trim(strip_tags($producer->short_description ?: $producer->long_description ?: 'Conocé la selección de vinos importados por Pepichis.')), 160);
    $producerUrl = route('front.producers.show', $producer->slug);
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Inicio',
                'item' => route('front.home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Productores',
                'item' => route('front.producers.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $producer->name,
                'item' => $producerUrl,
            ],
        ],
    ];
    $profileMainEntity = [
        '@type' => 'Organization',
        'name' => $producer->name,
        'description' => $seoDescription,
        'image' => $producer->image_path ? asset($producer->image_path) : null,
    ];
    if ($location) {
        $profileMainEntity['location'] = [
            '@type' => 'Place',
            'name' => $location,
        ];
    }
    $profileSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfilePage',
        'name' => $producer->name . ' | Pepichis',
        'url' => $producerUrl,
        'description' => $seoDescription,
        'inLanguage' => 'es-AR',
        'mainEntity' => $profileMainEntity,
    ];
    $wineListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Vinos de ' . $producer->name,
        'url' => $producerUrl . '#seleccion',
        'itemListElement' => $producer->wines->values()->map(function ($wine, $index) use ($producerUrl) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $wine->name,
                'url' => $producerUrl . '#seleccion',
                'image' => $wine->image_path ? asset($wine->image_path) : null,
            ];
        })->all(),
    ];
@endphp

@section('meta_title', $producer->name . ' | Vinos importados por Pepichis')
@section('meta_description', $seoDescription)
@section('meta_image', $producer->image_path ? asset($producer->image_path) : asset('pepichis_logo.png'))
@section('meta_type', 'article')
@section('canonical', $producerUrl)

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    <script type="application/ld+json">{!! json_encode($profileSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    <script type="application/ld+json">{!! json_encode($wineListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('front-producer.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('front-producer.js') }}"></script>
@endpush

@section('content')
    <section class="producer-hero">
        <div class="producer-hero-inner">
            <div class="producer-photo-side">
                <div class="producer-photo-wrapper">
                    <img class="producer-photo-main" src="{{ asset($producer->image_path) }}" alt="{{ $producer->name }}{{ $location ? ', productor de vinos en ' . $location : '' }}" width="500" height="667" fetchpriority="high">
                </div>
            </div>

            <div class="producer-info-side">
                <div class="breadcrumb">
                    <a href="{{ route('front.home') }}">Inicio</a> &nbsp;/&nbsp;
                    <a href="{{ route('front.producers.index') }}">Productores</a> &nbsp;/&nbsp;
                    {{ $producer->name }}
                </div>
                <div class="producer-region-tag">{{ $location }}</div>
                <h1 class="producer-name">{{ $producer->name }}</h1>
                <div class="producer-description">{!! $producer->long_description ?: nl2br(e($producer->short_description)) !!}</div>
                <div class="producer-meta">
                    <div class="meta-item">
                        <span class="meta-label">Productor</span>
                        <span class="meta-value">{{ $producer->name }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Región</span>
                        <span class="meta-value">{{ $producer->state ?: ($producer->city ?: '-') }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">País</span>
                        <span class="meta-value">{{ $producer->country ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="wines-section" id="seleccion">
        <div class="wines-header">
            <h2 class="wines-title">Nuestra Selección de la Bodega</h2>
        </div>

        <div class="wines-scattered">
            @foreach ($producer->wines->values() as $index => $wine)
                @php
                    $year = optional($wine->attributeValues->first(fn ($value) => in_array(optional($value->attribute)->name, ['Año', 'Anio'])))->value;
                    $format = optional($wine->attributeValues->first(fn ($value) => optional($value->attribute)->name === 'Formato'))->value;
                    $wineAlt = trim($wine->name . ' de ' . $producer->name . ($year ? ' ' . $year : ''));
                @endphp
                <div class="wine-scattered-item {{ $sizeClasses[$index % count($sizeClasses)] ?? '' }}">
                    <div class="scattered-bottle">
                        @if ($wine->image_path)
                            <img src="{{ asset($wine->image_path) }}" alt="{{ $wineAlt }}" loading="lazy">
                        @endif
                    </div>
                    <div class="scattered-label">
                        <span class="wine-name">{{ $wine->name }}</span>
                        @if ($year)
                            <span class="wine-year">{{ $year }}</span>
                        @endif
                        @if ($format && strtolower($format) !== 'botella')
                            <span class="wine-size-tag">{{ $format }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="wines-slider-dots" id="winesSliderDots"></div>
    </section>
@endsection
