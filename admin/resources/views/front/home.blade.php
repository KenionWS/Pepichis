@extends('front.layouts.app', ['title' => 'Pepichis | Importamos vinos de productores independientes'])

@php
    $homeMetaDescription = "Importamos vinos de productores que admiramos. Descubr\u{00ED} etiquetas con identidad, origen y car\u{00E1}cter seleccionadas por Pepichis.";
    $homeItemList = $producers->map(function ($producer, $index) {
        return [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $producer->name,
            'url' => route('front.producers.show', $producer->slug),
            'image' => $producer->image_path ? asset($producer->image_path) : null,
        ];
    })->values()->all();
    $homePageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Pepichis',
        'url' => route('front.home'),
        'description' => $homeMetaDescription,
        'inLanguage' => 'es-AR',
    ];
    $homeListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Productores destacados de Pepichis',
        'itemListElement' => $homeItemList,
    ];
@endphp

@section('meta_title', 'Pepichis | Importamos vinos de productores independientes')
@section('meta_description', $homeMetaDescription)
@section('meta_image', asset('pepichis_logo.png'))
@section('meta_type', 'website')
@section('canonical', route('front.home'))

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($homePageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    <script type="application/ld+json">{!! json_encode($homeListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('front-home.css') }}">
    <link rel="stylesheet" href="{{ asset('front-notes.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('front-home.js') }}"></script>
@endpush

@section('content')
    <section class="hero" id="nosotros">
        <div class="hero-content">
            <h1 id="hero-typed"></h1>
            <p id="hero-typed-p"></p>
        </div>
        <div class="hero-illustration" id="hero-copas">
            <img src="{{ asset('copas.svg') }}" alt="Ilustraci&oacute;n de copas de vino de Pepichis" width="700" height="700" fetchpriority="high">
        </div>
    </section>

    <section class="split-intro" id="seleccion">
        <div class="bottles-sticky">
            <div class="bottles-grid">
                @foreach ($bottleItems as $index => $wine)
                    <div class="bottle-item" data-row="{{ ($index % 3) + 1 }}">
                        <img src="{{ asset($wine->image_path) }}" alt="{{ $wine->name }} de {{ $wine->producer->name }}" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="producers-section" id="productores">
        <div class="section-header">
            <h2 class="section-title">Detr&aacute;s de cada botella<br>hay una mirada &uacute;nica</h2>
        </div>

        <div class="producers-grid">
            @foreach ($producers as $producer)
                @php
                    $location = collect([$producer->city, $producer->state, $producer->country])->filter()->join(', ');
                    $description = \Illuminate\Support\Str::limit(trim(strip_tags($producer->short_description)), 190);
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

    @if($latestNotes->isNotEmpty())
        <section class="home-notes" id="notas">
            <div class="home-notes-inner">
                <div class="home-notes-head">
                    <div>
                        <div class="notes-kicker">Notas</div>
                        <h2 class="section-title">historias, novedades y contexto para seguir explorando</h2>
                    </div>
                    <a href="{{ route('front.notes.index') }}" class="note-link">Ver todas las notas</a>
                </div>

                <div class="home-notes-grid">
                    @foreach($latestNotes as $note)
                        <article class="home-note-card">
                            <div class="home-note-media">
                                @if($note->cover_image_path)
                                    <img src="{{ asset($note->cover_image_path) }}" alt="{{ $note->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="home-note-content">
                                <div class="note-meta">
                                    @if($note->published_at)
                                        <span>{{ $note->published_at->translatedFormat('d M Y') }}</span>
                                    @endif
                                </div>
                                <h3>{{ $note->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($note->excerpt ?: $note->body)), 160) }}</p>
                                <a href="{{ route('front.notes.show', $note->slug) }}" class="note-link">Leer nota</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
