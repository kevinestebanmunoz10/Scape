@extends('layouts.app')

@section('title', 'SCAPE - Control de Personas')

@section('content')
<section class="module-hero text-center text-white">
    <div class="container">
        <br>
        <h1>Control de Personas</h1>
        <p class="lead w-75 mx-auto text-light opacity-75">
            Registra, gestiona y visualiza el acceso de empleados, docentes y visitantes en tiempo real.
        </p>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
            <div class="card-custom text-center p-4">
                <i class="bi bi-people-fill display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Registro rápido</h5>
                <p class="text-secondary-custom small m-0">Crea perfiles de personal en segundos con QR del carnet.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-custom text-center p-4">
                <i class="bi bi-shield-check display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Autorización</h5>
                <p class="text-secondary-custom small m-0">Control de entrada y salida con permisos por rol.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-custom text-center p-4">
                <i class="bi bi-graph-up display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Visibilidad</h5>
                <p class="text-secondary-custom small m-0">Panel en tiempo real de quién entra, quién sale.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-center">
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="fw-bold h1 text-white mb-4">Gestión integral del personal</h2>
            <p class="text-secondary-custom mb-3">SCAPE centraliza toda la información del personal en un solo lugar. Desde el registro inicial hasta el control diario de accesos, todo accesible desde una interfaz intuitiva.</p>
            <p class="text-secondary-custom">Diseñado para instituciones educativas que necesitan saber en todo momento quiénes están en sus instalaciones.</p>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card-custom p-4">
                <ul class="list-unstyled">
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Registro de empleados, docentes y visitantes</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Control mediante QR del carnet</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Historial completo de accesos</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Notificaciones automáticas</li>
                    <li class="text-white"><i class="bi bi-check2-circle text-cyan me-2"></i>Filtros por sede, cargo y horario</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
