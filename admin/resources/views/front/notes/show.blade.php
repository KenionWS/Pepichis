@extends('front.layouts.app', ['title' => ($note->seo_title ?: $note->title) . ' | Pepichis'])

@php
    $noteDescription = \Illuminate\Support\Str::limit(trim(strip_tags($note->seo_description ?: $note->excerpt ?: $note->body)), 160);
    $noteUrl = route('front.notes.show', $note->slug);
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $note->title,
        'description' => $noteDescription,
        'url' => $noteUrl,
        'datePublished' => optional($note->published_at)->toIso8601String(),
        'dateModified' => optional($note->updated_at)->toIso8601String(),
        'image' => $note->cover_image_path ? [asset($note->cover_image_path)] : [],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Pepichis',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('pepichis_logo.png'),
            ],
        ],
        'mainEntityOfPage' => $noteUrl,
        'inLanguage' => 'es-AR',
    ];
    $noteBreadcrumbSchema = [
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
                'name' => 'Notas',
                'item' => route('front.notes.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $note->title,
                'item' => $noteUrl,
            ],
        ],
    ];
@endphp

@section('meta_title', $note->seo_title ?: $note->title . ' | Pepichis')
@section('meta_description', $noteDescription)
@section('meta_image', $note->cover_image_path ? asset($note->cover_image_path) : asset('pepichis_logo.png'))
@section('meta_type', 'article')
@section('canonical', $noteUrl)

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    <script type="application/ld+json">{!! json_encode($noteBreadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('front-notes.css') }}">
@endpush

@section('content')
    <section class="note-hero">
        <div class="note-hero-inner">
            <div class="note-kicker">Notas</div>
            <div class="note-hero-grid">
                <div class="note-hero-copy">
                    <div class="note-date">
                        {{ $note->published_at ? $note->published_at->translatedFormat('d \\d\\e F \\d\\e Y') : 'Sin fecha de publicaci&oacute;n' }}
                    </div>
                    <h1>{{ $note->title }}</h1>
                    @if($note->excerpt)
                        <div class="note-excerpt">{!! $note->excerpt !!}</div>
                    @endif
                </div>
                <div class="note-hero-media">
                    @if($note->cover_image_path)
                        <img src="{{ asset($note->cover_image_path) }}" alt="{{ $note->title }}" fetchpriority="high">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="note-body-section">
        <div class="note-body-shell">
            <article class="note-body">
                {!! $note->body !!}
            </article>
        </div>
    </section>

    @if($relatedNotes->isNotEmpty())
        <section class="note-related-section">
            <div class="notes-kicker">Seguir leyendo</div>
            <div class="related-notes-grid">
                @foreach($relatedNotes as $relatedNote)
                    <article class="related-note-card">
                        @if($relatedNote->cover_image_path)
                            <img src="{{ asset($relatedNote->cover_image_path) }}" alt="{{ $relatedNote->title }}" loading="lazy" style="height:230px;">
                        @endif
                        <div class="note-card-content">
                            <div class="note-meta">
                                @if($relatedNote->published_at)
                                    <span>{{ $relatedNote->published_at->translatedFormat('d M Y') }}</span>
                                @endif
                            </div>
                            <h3>{{ $relatedNote->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($relatedNote->excerpt ?: $relatedNote->body)), 150) }}</p>
                            <a href="{{ route('front.notes.show', $relatedNote->slug) }}" class="note-link">Leer nota</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
