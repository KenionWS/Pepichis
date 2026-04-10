@extends('front.layouts.app', ['title' => 'Notas | Pepichis'])

@php
    $notesMetaDescription = "Art\u{00ED}culos, novedades e historias de Pepichis sobre productores, regiones, estilos y cultura del vino.";
    $notesCollectionSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Notas | Pepichis',
        'url' => route('front.notes.index'),
        'description' => $notesMetaDescription,
        'inLanguage' => 'es-AR',
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $notes->getCollection()->values()->map(function ($note, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $note->title,
                    'url' => route('front.notes.show', $note->slug),
                    'image' => $note->cover_image_path ? asset($note->cover_image_path) : null,
                ];
            })->all(),
        ],
    ];
@endphp

@section('meta_title', 'Notas | Pepichis')
@section('meta_description', $notesMetaDescription)
@section('meta_image', $featuredNote && $featuredNote->cover_image_path ? asset($featuredNote->cover_image_path) : asset('pepichis_logo.png'))
@section('meta_type', 'website')
@section('canonical', route('front.notes.index'))

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($notesCollectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('front-notes.css') }}">
@endpush

@section('content')
    <section class="notes-hero">
        <div class="notes-hero-inner">
            <div class="notes-kicker">Notas</div>
            <div class="notes-hero-grid">
                <div class="notes-hero-copy">
                    <h1>historias, novedades y contexto para tomar mejor</h1>
                    <p>Una secci&oacute;n editorial para profundizar en productores, regiones, cosechas y formas de mirar el vino desde la misma sensibilidad que organiza la selecci&oacute;n de Pepichis.</p>
                </div>

                @if($featuredNote)
                    <article class="featured-note-card">
                        <div class="featured-note-content">
                            <div>
                                <div class="note-meta">
                                    <span class="pill">Destacada</span>
                                    @if($featuredNote->published_at)
                                        <span>{{ $featuredNote->published_at->translatedFormat('d M Y') }}</span>
                                    @endif
                                </div>
                                <h2>{{ $featuredNote->title }}</h2>
                                <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($featuredNote->excerpt ?: $featuredNote->body)), 220) }}</p>
                            </div>
                            <a href="{{ route('front.notes.show', $featuredNote->slug) }}" class="note-link">Leer nota</a>
                        </div>
                        <div class="featured-note-media">
                            @if($featuredNote->cover_image_path)
                                <img src="{{ asset($featuredNote->cover_image_path) }}" alt="{{ $featuredNote->title }}" fetchpriority="high">
                            @endif
                        </div>
                    </article>
                @endif
            </div>
        </div>
    </section>

    <section class="notes-list-section">
        @if($notes->count())
            <div class="notes-grid">
                @foreach($notes as $note)
                    @continue($featuredNote && $notes->currentPage() === 1 && $featuredNote->id === $note->id)
                    <article class="note-card">
                        <div class="note-card-media">
                            @if($note->cover_image_path)
                                <img src="{{ asset($note->cover_image_path) }}" alt="{{ $note->title }}" loading="lazy">
                            @endif
                        </div>
                        <div class="note-card-content">
                            <div class="note-meta">
                                @if($note->published_at)
                                    <span>{{ $note->published_at->translatedFormat('d M Y') }}</span>
                                @endif
                            </div>
                            <h2>{{ $note->title }}</h2>
                            <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($note->excerpt ?: $note->body)), 180) }}</p>
                            <a href="{{ route('front.notes.show', $note->slug) }}" class="note-link">Leer m&aacute;s</a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($notes->hasPages())
                <div class="notes-pagination">
                    <a class="pagination-link {{ $notes->onFirstPage() ? 'disabled' : '' }}" href="{{ $notes->previousPageUrl() ?: '#' }}">Anterior</a>
                    <a class="pagination-link {{ $notes->hasMorePages() ? '' : 'disabled' }}" href="{{ $notes->nextPageUrl() ?: '#' }}">Siguiente</a>
                </div>
            @endif
        @else
            <div class="notes-empty">Todav&iacute;a no hay notas publicadas.</div>
        @endif
    </section>
@endsection
