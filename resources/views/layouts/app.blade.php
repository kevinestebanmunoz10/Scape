<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SCAPE - Control de Acceso Inteligente')</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/uploads/favicon.png') }}">

    <link href="https://fonts.cdnfonts.com/css/product-sans" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('storage/uploads/logo.png') }}" alt="Logo SCAPE" style="height: 50px; width: auto;">
            </a>

            @hasSection('panel-navbar')
                <span class="text-white small fw-semibold">{{ auth()->user()->Nom_usua }}</span>
            @else
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link px-3" href="{{ url('/') }}">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ url('/') }}#caracteristicas">Características</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ url('/') }}#nosotros">Acerca de nosotros</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ url('/') }}#contacto">Contáctanos</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ url('/planes') }}">Planes</a></li>

                    </ul>
                    @auth
                        <span class="text-white small fw-semibold d-none d-md-inline">{{ auth()->user()->Nom_usua }}</span>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4">Iniciar sesión</a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    @yield('content')

    <footer class="footer-scape-custom pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/uploads/logo.png') }}" alt="Logo SCAPE" style="height: 40px; width: auto;">
                    </div>
                    <p class="text-secondary-custom small pe-lg-4 mb-4">
                        Software de control de acceso inteligente para instituciones educativas que priorizan la seguridad.
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <span class="small text-secondary-custom"><i class="bi bi-envelope text-cyan me-2"></i>jsmenaca@gmail.com</span>
                        <span class="small text-secondary-custom"><i class="bi bi-geo-alt text-cyan me-2"></i> Ibagué, Tolima — Colombia</span>
                    </div>
                </div>

                <div class="col-6 col-lg-3 offset-lg-1">
                    <h6 class="footer-section-title text-white fw-bold mb-3">NAVEGACIÓN</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#inicio" class="footer-link">Inicio</a></li>
                        <li><a href="#caracteristicas" class="footer-link">Características</a></li>
                        <li><a href="#nosotros" class="footer-link">Acerca de nosotros</a></li>
                        <li><a href="{{ url('/planes') }}" class="footer-link">Planes</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-3">
                    <h6 class="footer-section-title text-white fw-bold mb-3">MÓDULOS</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ url('/modulos/personas') }}" class="footer-link">Control de personas</a></li>
                        <li><a href="{{ url('/modulos/equipos') }}" class="footer-link">Gestión de equipos</a></li>
                        <li><a href="{{ url('/modulos/visitantes') }}" class="footer-link">Visitantes</a></li>
                        <li><a href="{{ url('/modulos/reportes') }}" class="footer-link">Reportes</a></li>
                        <li><a href="{{ asset('storage/pdf/legal.pdf') }}" class="footer-link" target="_blank">Legal</a></li>
                        <li><a href="{{ asset('storage/pdf/privacidad.pdf') }}" class="footer-link" target="_blank">Política de tratamiento y protección de datos personales</a></li>
                    </ul>
                </div>
            </div>

            <div class="row pt-4 border-top border-dark-custom align-items-center text-center text-md-start">
                <div class="col-md-6">
                    <p class="text-secondary-custom small mb-md-0">&copy;2026 SCAPE Software. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="footer-link small">Términos de uso</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 80,
            once: true
        });
    </script>

    <div class="cookie-overlay" id="cookieOverlay"></div>
    <div class="cookie-banner" id="cookieBanner">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
            <p class="text-light small m-0">
                Usamos cookies para mejorar tu experiencia.
                <a href="#" class="text-cyan">Más información</a>
            </p>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-primary-custom" id="acceptCookies">Aceptar todas</button>
                <button class="btn btn-outline-light" id="customizeCookies">Personalizar cookies</button>
            </div>
        </div>
    </div>
    <div class="cookie-panel" id="cookiePanel">
        <div class="cookie-panel-box">
            <h4 class="text-white fw-bold mb-3">Preferencias de cookies</h4>
            <p class="text-light small mb-4">Elige qué tipos de cookies permitir. Las necesarias siempre activas.</p>
            <div class="cookie-option mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-white fw-bold small">Necesarias</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookieNecessary" checked disabled>
                    </div>
                </div>
                <p class="text-secondary-custom small m-0 mt-1">Imprescindables para el funcionamiento del sitio.</p>
            </div>
            <div class="cookie-option mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-white fw-bold small">Analíticas</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookieAnalytics">
                    </div>
                </div>
                <p class="text-secondary-custom small m-0 mt-1">Nos ayudan a entender cómo interactúas con la página.</p>
            </div>
            <div class="cookie-option mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-white fw-bold small">Funcionales</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookieFunctional">
                    </div>
                </div>
                <p class="text-secondary-custom small m-0 mt-1">Permiten recordar tus preferencias y personalizar la experiencia.</p>
            </div>
            <div class="cookie-option mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-white fw-bold small">Marketing</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cookieMarketing">
                    </div>
                </div>
                <p class="text-secondary-custom small m-0 mt-1">Usadas para mostrar publicidad relevante.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-custom flex-fill" id="saveCookies">Guardar preferencias</button>
                <button class="btn btn-outline-light" id="acceptCookiesPanel">Aceptar todas</button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var banner = document.getElementById('cookieBanner');
            var panel = document.getElementById('cookiePanel');
            var overlay = document.getElementById('cookieOverlay');
            var acceptBtn = document.getElementById('acceptCookies');
            var customizeBtn = document.getElementById('customizeCookies');
            var saveBtn = document.getElementById('saveCookies');
            var acceptPanelBtn = document.getElementById('acceptCookiesPanel');
            var analytics = document.getElementById('cookieAnalytics');
            var functional = document.getElementById('cookieFunctional');
            var marketing = document.getElementById('cookieMarketing');

            function hideAll() {
                banner.classList.add('hidden');
                panel.classList.remove('show');
                overlay.classList.add('hidden');
            }

            function savePreferences() {
                localStorage.setItem('cookiesAccepted', 'true');
                localStorage.setItem('cookiePrefs', JSON.stringify({
                    necessary: true,
                    analytics: analytics.checked,
                    functional: functional.checked,
                    marketing: marketing.checked
                }));
                hideAll();
            }

            if (localStorage.getItem('cookiesAccepted') === 'true') {
                hideAll();
            }

            acceptBtn.addEventListener('click', savePreferences);
            acceptPanelBtn.addEventListener('click', savePreferences);
            customizeBtn.addEventListener('click', function() {
                panel.classList.add('show');
                overlay.classList.remove('hidden');
            });
            saveBtn.addEventListener('click', savePreferences);
        })();
    </script>

    @stack('scripts')
</body>
</html>