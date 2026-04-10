<!DOCTYPE html>
<html lang="es">
<head>
    @php
        $siteName = 'Pepichis';
        $defaultTitle = 'Pepichis | Importamos vinos de productores que admiramos';
        $defaultDescription = "Importamos vinos de productores que admiramos: etiquetas con identidad, origen y car\u{00E1}cter para descubrir en Pepichis.";
        $metaTitle = trim($__env->yieldContent('meta_title', $title ?? $defaultTitle));
        $metaDescription = trim($__env->yieldContent('meta_description', $defaultDescription));
        $metaImage = trim($__env->yieldContent('meta_image', asset('pepichis_logo.png')));
        $canonicalUrl = trim($__env->yieldContent('canonical', url()->current()));
        $metaType = trim($__env->yieldContent('meta_type', request()->routeIs('front.home') ? 'website' : 'article'));
        $metaRobots = trim($__env->yieldContent('meta_robots', 'index,follow'));
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => route('front.home'),
            'logo' => asset('pepichis_logo.png'),
            'image' => asset('pepichis_logo.png'),
            'email' => 'wines@pepichis.com',
            'sameAs' => ['https://www.instagram.com/pepichis.wines/'],
            'description' => $defaultDescription,
        ];
        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => route('front.home'),
            'inLanguage' => 'es-AR',
        ];
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:locale" content="es_AR">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('front-base.css') }}">
    <script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
    @stack('structured_data')
    @stack('styles')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
</head>
<body>
    <header>
        <nav aria-label="Navegaci&oacute;n principal">
            <a href="{{ route('front.home') }}" class="logo">
                <img class="wine-glass-icon" src="{{ asset('pepichis_logo.png') }}" alt="Pepichis, importadora de vinos" width="120" height="120">
                pepichis
            </a>
            <ul class="nav-links">
                @foreach($menuItems as $menuItem)
                    <li>
                        <a href="{{ $menuItem->href }}" @if($menuItem->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                            {{ $menuItem->label }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Abrir men&uacute;">
                <span></span><span></span><span></span>
            </button>
        </nav>
        <div class="mobile-menu-overlay" id="mobileMenu">
            <ul class="mobile-nav-links">
                @foreach($menuItems as $menuItem)
                    <li>
                        <a href="{{ $menuItem->href }}" @if($menuItem->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                            {{ $menuItem->label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </header>

    @yield('content')

    <footer id="contacto">
        <div class="footer-content">
            <div class="footer-brand">
                <h3>pepichis</h3>
                <p>Importamos vinos de productores que admiramos.<br>Vinos con identidad, origen y car&aacute;cter.</p>
            </div>
            <div class="footer-links">
                <h4>Contacto</h4>
                <ul>
                    <li><a href="mailto:wines@pepichis.com.ar">wines@pepichis.com.ar</a></li>
                    <li><a href="https://www.instagram.com/pepichis.wines/" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ now()->year }} Pepichis. Todos los derechos reservados.</p>
            <a href="https://kenion.studio/" target="_blank" rel="noopener noreferrer" class="footer-credit">
                <span>desarrollo por</span>
                <img src="{{ asset('kenion.webp') }}" alt="Kenion Studio" loading="lazy" width="120" height="120">
            </a>
        </div>
    </footer>

    <script src="{{ asset('front-base.js') }}"></script>
    @stack('scripts')
</body>
</html>
