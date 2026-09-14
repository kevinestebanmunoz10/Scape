<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SCAPE - Control de Acceso Inteligente')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-3" href="{{ url('/') }}">
                <img src="{{ asset('imagenes/favicon.png') }}" alt="Logo SCAPE" width="55" height="55" class="rounded-circle d-inline-block align-text-top my-1" style="object-fit: cover;">SCAPE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link px-3" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#caracteristicas">Características</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#nosotros">Acerca de nosotros</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#contacto">Contáctanos</a></li>
                </ul>
                <a href="{{ url('/admin/login') }}" class="btn btn-outline-light rounded-pill px-4">Iniciar sesión</a>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer-scape-custom pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset('imagenes/favicon.png') }}" alt="Logo SCAPE" width="48" height="48" class="rounded-circle" style="object-fit: cover; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div>
                            <span class="footer-brand-title">SCAPE</span>
                            <span class="footer-brand-subtitle d-block">CONTROL DE ACCESO</span>
                        </div>
                    </div>
                    <p class="text-secondary-custom small pe-lg-4 mb-4">
                        Software de control de acceso inteligente para instituciones educativas que priorizan la seguridad.
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <span class="small text-secondary-custom"><i class="bi bi-envelope text-cyan me-2"></i> angel.montesanta@gmail.com</span>
                        <span class="small text-secondary-custom"><i class="bi bi-geo-alt text-cyan me-2"></i> Ibagué, Tolima — Colombia</span>
                    </div>
                </div>

                <div class="col-6 col-lg-3 offset-lg-1">
                    <h6 class="footer-section-title text-white fw-bold mb-3">NAVEGACIÓN</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#inicio" class="footer-link">Inicio</a></li>
                        <li><a href="#caracteristicas" class="footer-link">Características</a></li>
                        <li><a href="#nosotros" class="footer-link">Acerca de nosotros</a></li>
                        <li><a href="#contacto" class="footer-link">Contáctenos</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-3">
                    <h6 class="footer-section-title text-white fw-bold mb-3">MÓDULOS</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#" class="footer-link">Control de personas</a></li>
                        <li><a href="#" class="footer-link">Gestión de equipos</a></li>
                        <li><a href="#" class="footer-link">Visitantes</a></li>
                        <li><a href="#" class="footer-link">Reportes</a></li>
                    </ul>
                </div>
            </div>

            <div class="row pt-4 border-top border-dark-custom align-items-center text-center text-md-start">
                <div class="col-md-6">
                    <p class="text-secondary-custom small mb-md-0">&copy;2026 SCAPE Software. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="footer-link small me-3">Política de privacidad</a>
                    <a href="#" class="footer-link small">Términos de uso</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>