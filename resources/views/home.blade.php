@extends('layouts.app')

@section('title', 'SCAPE - Control de Acceso Inteligente')

@section('content')
    <header id="inicio" class="hero-section text-center text-white">
        <div class="container" data-aos="fade-up">
            <h1 class="titulo-impacto">
                Control total <span>de acceso e inventario</span><br>en tu institución
            </h1>
            <p class="lead w-75 mx-auto text-light opacity-75 mb-5">
                SCAPE centraliza el registro de personal, equipos y visitantes en una sola plataforma.
                Pensado para instituciones que necesitan seguridad real sin complicaciones.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#caracteristicas" class="btn btn-primary-custom">Conocer características</a>
                <a href="#nosotros" class="btn btn-outline-light btn-lg rounded-pill px-4">Acerca de nosotros</a>
            </div>
        </div>
    </header>

    <section class="container mb-5">
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                <div class="p-4 card-custom text-center">
                    <p class="text-secondary-custom mb-1 small">Accesos hoy</p>
                    <h2 class="display-4 fw-bold text-white">847</h2>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 card-custom text-center">
                    <p class="text-secondary-custom mb-1 small">Puertas activas</p>
                    <h2 class="display-4 fw-bold text-info">24/24</h2>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 card-custom text-center">
                    <p class="text-secondary-custom mb-1 small">Alarmas</p>
                    <h2 class="display-4 fw-bold text-danger">3</h2>
                </div>
            </div>
        </div>
    </section>

    <section id="caracteristicas" class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold h1 text-white">Funcionalidades Principales</h2>
            <div class="linea-decorativa"></div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col" data-aos="fade-up" data-aos-delay="0">
                <a href="{{ url('/modulos/personas') }}" class="p-4 card-func d-block text-decoration-none" style="color: inherit;">
                    <div class="icon-box"><i class="bi bi-people-fill"></i></div>
                    <h5 class="text-white">Registro de personal</h5>
                    <p class="text-secondary-custom small m-0">Empleados, docentes y visitantes en un solo directorio. Control mediante QR del carnet.</p>
                </a>
            </div>
            <div class="col" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ url('/modulos/equipos') }}" class="p-4 card-func d-block text-decoration-none" style="color: inherit;">
                    <div class="icon-box"><i class="bi bi-laptop"></i></div>
                    <h5 class="text-white">Inventario de equipos</h5>
                    <p class="text-secondary-custom small m-0">Trazabilidad completa de portátiles y material de informática. Registro de entradas y salidas.</p>
                </a>
            </div>
            <div class="col" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ url('/modulos/visitantes') }}" class="p-4 card-func d-block text-decoration-none" style="color: inherit;">
                    <div class="icon-box"><i class="bi bi-shield-check"></i></div>
                    <h5 class="text-white">Control de visitantes</h5>
                    <p class="text-secondary-custom small m-0">Pre-registro de externos y autorización de entrada. Notificación automática al responsable.</p>
                </a>
            </div>
        </div>
    </section>

    <section id="nosotros" class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-right">
                <span class="text-cyan text-uppercase fw-bold tracking-wider small mb-3">Acerca de nosotros</span>
                <h2 class="titulo-nosotros-bold mb-4">
                    Construido por personas <br>
                    <span class="text-gradient-cyan">que entienden la educación</span>
                </h2>

                <p class="text-secondary-custom mb-4">
                    SCAPE nació de la necesidad real de una institución educativa en Ibagué que no encontraba una herramienta de control de acceso adaptada a su contexto. Somos un equipo joven, comprometido con la tecnología aplicada a la seguridad escolar.
                </p>
                <p class="text-secondary-custom">
                    Nuestro objetivo es simple: que cada rector, coordinador y vigilante tenga visibilidad total de quién entra, quién sale y qué equipos se mueven, sin necesidad de conocimientos técnicos avanzados.
                </p>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="card-equipo-container p-4">
                    <span class="text-cyan-light text-uppercase fw-bold small mb-3 d-block">Nuestro Equipo</span>
                    <p class="text-secondary-custom small mb-4">
                        Estudiantes de desarrollo de software con una visión clara: tecnología útil, simple y segura para la educación colombiana.
                    </p>

                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="role-card p-3 d-flex align-items-center gap-3">
                            <div class="avatar-box">KM</div>
                            <div>
                                <h6 class="text-white fw-bold mb-0">Kevin Muñoz</h6>
                                <span class="text-secondary-custom text-xs">PHP · MySQL</span>
                            </div>
                        </div>

                        <div class="role-card p-3 d-flex align-items-center gap-3">
                            <div class="avatar-box">JM</div>
                            <div>
                                <h6 class="text-white fw-bold mb-0">Julián Meñaca</h6>
                                <span class="text-secondary-custom text-xs">HTML · CSS · JS</span>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex flex-wrap gap-2 pt-3 border-top border-dark-custom">
                        <span class="badge-tech">PHP</span>
                        <span class="badge-tech">MySQL</span>
                        <span class="badge-tech">HTML</span>
                        <span class="badge-tech">CSS</span>
                        <span class="badge-tech">XAMPP</span>
                        <span class="badge-tech">Git</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold h1 text-white">Preguntas Frecuentes</h2>
            <div class="linea-decorativa"></div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <span class="text-cyan text-uppercase fw-bold tracking-wider small mb-3 d-block">General</span>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="0">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-g1">
                        <span class="text-white fw-bold">¿Qué es SCAPE?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-g1" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">SCAPE es una plataforma de control de acceso e inventario diseñada para instituciones educativas. Registra personal, equipos y visitantes de forma centralizada y sin complicaciones.</p>
                    </div>
                </div>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="100">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-g2">
                        <span class="text-white fw-bold">¿Necesito conocimientos técnicos?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-g2" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">No. SCAPE fue pensado para rectores, coordinadores y vigilantes. La interfaz es intuitiva y no requiere experiencia técnica para usarla.</p>
                    </div>
                </div>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="200">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-g3">
                        <span class="text-white fw-bold">¿Cuánto cuesta?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-g3" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">Ofrecemos planes desde $29/mes hasta $179/mes según el tamaño de tu institución. Todos incluyen soporte y actualizaciones. Puedes probar gratis antes de contratar.</p>
                    </div>
                </div>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="300">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-g4">
                        <span class="text-white fw-bold">¿Mis datos están seguros?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-g4" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">Sí. Utilizamos cifrado de datos, autenticación segura y copias de seguridad automáticas. La seguridad es nuestra prioridad número uno.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <span class="text-cyan text-uppercase fw-bold tracking-wider small mb-3 d-block">Por producto</span>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="0">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-p1">
                        <span class="text-white fw-bold">¿Cómo funciona el control de acceso?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-p1" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">Cada persona registra su entrada y salida mediante código QR o tarjeta. El sistema queda visible en tiempo real para administradores y vigilantes.</p>
                    </div>
                </div>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="100">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-p2">
                        <span class="text-white fw-bold">¿Puedo registrar equipos múltiples?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-p2" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">Sí. El módulo de inventario permite registrar portátiles, equipos de laboratorio y material de informática, controlando entradas, salidas y responsable actual.</p>
                    </div>
                </div>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="200">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-p3">
                        <span class="text-white fw-bold">¿Cómo se manejan los visitantes?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-p3" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">Los visitantes pueden ser pre-registrados por el responsable. Al llegar, se valida su identidad y se notifica automáticamente al funcionario que recibe.</p>
                    </div>
                </div>

                <div class="card-custom mb-3" data-aos="fade-up" data-aos-delay="300">
                    <button class="faq-toggle w-100 text-start bg-transparent border-0 p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#faq-p4">
                        <span class="text-white fw-bold">¿Qué reportes genera el sistema?</span>
                        <i class="bi bi-plus-lg text-accent-custom"></i>
                    </button>
                    <div id="faq-p4" class="collapse px-3 pb-3">
                        <p class="text-secondary-custom small m-0">SCAPE genera reportes de asistencia, inventario, movimientos y alarmas. Puedes exportarlos en PDF o consultarlos en tiempo real desde el panel.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto" class="container py-5">
        <div class="card card-custom px-5 py-4" data-aos="zoom-in">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="fw-bold h1 text-white mb-4">Contáctanos</h2>
                    <p class="text-secondary-custom mb-4">¿Tienes dudas o necesitas una demostración personalizada? Nuestro equipo de soporte técnico 24/7 está listo para ayudarte.</p>
                    <ul class="list-unstyled mt-4 contact-list">
                        <li class="mb-3 text-secondary-custom"><i class="bi bi-envelope-fill text-accent-custom me-2"></i> soporte@scape.com</li>
                        <li class="mb-3 text-secondary-custom"><i class="bi bi-telephone-fill text-accent-custom me-2"></i> +57 (601) 123 4567</li>
                        <li class="text-secondary-custom"><i class="bi bi-geo-alt-fill text-accent-custom me-2"></i> Ibagué, Tolima</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Tu nombre">
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Tu correo electrónico">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="¿En qué podemos ayudarte?"></textarea>
                        </div>
                        <button type="button" class="btn btn-primary-custom w-100">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection