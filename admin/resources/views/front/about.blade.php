@extends('front.layouts.app', ['title' => 'Pepichis | Nosotros'])

@php
    $aboutPageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'name' => 'Nosotros | Pepichis',
        'url' => route('front.about'),
        'description' => $aboutMetaDescription,
        'inLanguage' => 'es-AR',
    ];
@endphp

@section('meta_title', 'Pepichis | Nosotros')
@section('meta_description', $aboutMetaDescription)
@section('meta_image', asset('pepichis_logo.png'))
@section('meta_type', 'website')
@section('canonical', route('front.about'))

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($aboutPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
@endpush

@push('styles')
    <style>
        .about-page {
            min-height: 100vh;
            padding: 10rem 6rem 6rem;
            background:
                radial-gradient(circle at top left, rgba(255, 231, 214, 0.58), transparent 28%),
                radial-gradient(circle at 85% 12%, rgba(219, 205, 255, 0.24), transparent 20%),
                linear-gradient(180deg, var(--pink) 0%, #f5ede4 100%);
        }

        .about-page-shell {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(260px, 0.92fr) minmax(0, 1.4fr);
            gap: 3.5rem;
            align-items: start;
        }

        .about-page-intro {
            position: sticky;
            top: 8rem;
        }

        .about-page-eyebrow {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--wine-red);
            font-size: 0.72rem;
            letter-spacing: 0.34rem;
            text-transform: uppercase;
        }

        .about-page-title {
            margin: 0 0 1.35rem;
            font-family: "TAN-NIMBUS", serif;
            font-size: 3.3rem;
            line-height: 1.18;
            font-style: italic;
            font-weight: 400;
            text-transform: lowercase;
            color: var(--wine-red);
        }

        .about-page-lead {
            max-width: 28rem;
            margin: 0;
            color: rgba(83, 46, 44, 0.78);
            font-size: 0.84rem;
            line-height: 1.9;
            letter-spacing: 0.14rem;
            text-transform: uppercase;
        }

        .about-page-body {
            display: grid;
            gap: 1.3rem;
        }

        .about-page-block {
            padding: 1.75rem 1.85rem;
            border-radius: 28px;
            border: 1px solid rgba(109, 24, 52, 0.08);
            background: rgba(255, 250, 243, 0.82);
            box-shadow: 0 18px 36px rgba(109, 24, 52, 0.05);
            color: var(--charcoal);
        }

        .about-page-block > :first-child {
            margin-top: 0;
        }

        .about-page-block > :last-child {
            margin-bottom: 0;
        }

        .about-page-block p,
        .about-page-block ul,
        .about-page-block ol,
        .about-page-block blockquote {
            margin: 0 0 1rem;
            font-size: 1.03rem;
            line-height: 1.9;
        }

        .about-page-block h2,
        .about-page-block h3 {
            margin: 0 0 1rem;
            font-family: "TAN-NIMBUS", serif;
            font-style: italic;
            font-weight: 400;
            text-transform: lowercase;
            color: var(--wine-red);
        }

        .about-page-block h2 {
            font-size: 2rem;
            line-height: 1.3;
        }

        .about-page-block h3 {
            font-size: 1.5rem;
            line-height: 1.4;
        }

        .about-page-block ul,
        .about-page-block ol {
            padding-left: 1.35rem;
        }

        .about-page-block li + li {
            margin-top: 0.45rem;
        }

        .about-page-block blockquote {
            padding-left: 1.2rem;
            border-left: 2px solid rgba(109, 24, 52, 0.18);
            font-family: "TAN-NIMBUS", serif;
            font-style: italic;
            color: var(--wine-red);
        }

        .about-page-block a {
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 0.18rem;
        }

        @media (max-width: 1024px) {
            .about-page {
                padding: 8.5rem 2rem 4rem;
            }

            .about-page-shell {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .about-page-intro {
                position: static;
            }
        }

        @media (max-width: 600px) {
            .about-page {
                padding: 7.5rem 1.5rem 3.5rem;
            }

            .about-page-title {
                font-size: 2.35rem;
            }

            .about-page-lead {
                max-width: none;
                font-size: 0.76rem;
                letter-spacing: 0.11rem;
            }

            .about-page-block {
                padding: 1.3rem 1.15rem;
                border-radius: 22px;
            }

            .about-page-block p,
            .about-page-block ul,
            .about-page-block ol,
            .about-page-block blockquote {
                font-size: 0.95rem;
                line-height: 1.8;
            }

            .about-page-block h2 {
                font-size: 1.55rem;
            }

            .about-page-block h3 {
                font-size: 1.28rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $aboutBody = trim($aboutSection->body);
        $aboutHasHtml = $aboutBody !== strip_tags($aboutBody);
    @endphp

    <section class="about-page">
        <div class="about-page-shell">
            <div class="about-page-intro">
                @if($aboutSection->eyebrow)
                    <span class="about-page-eyebrow">{{ $aboutSection->eyebrow }}</span>
                @endif
                <h1 class="about-page-title">{{ $aboutSection->title }}</h1>
                <p class="about-page-lead">Importamos desde la admiracion, la curiosidad y el deseo de acercar botellas con identidad real a mesas que saben apreciarlas.</p>
            </div>

            <div class="about-page-body">
                <article class="about-page-block">
                    @if($aboutHasHtml)
                        {!! $aboutSection->body !!}
                    @else
                        @foreach(preg_split("/\r\n\r\n|\n\n|\r\r/", $aboutBody) as $paragraph)
                            @continue(trim($paragraph) === '')
                            <p>{{ trim($paragraph) }}</p>
                        @endforeach
                    @endif
                </article>
            </div>
        </div>
    </section>
@endsection
