@extends('layouts.app')

@section('title', 'SCAPE - Gestión de Equipos')

@section('content')
<section class="module-hero text-center text-white">
    <div class="container">
        <br>
        <h1>Gestión de Equipos</h1>
        <p class="lead w-75 mx-auto text-light opacity-75">
            Trazabilidad completa de portátiles, material de informática y recursos tecnológicos.
        </p>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
            <div class="card-custom text-center p-4">
                <i class="bi bi-laptop display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Inventario</h5>
                <p class="text-secondary-custom small m-0">Control total de equipos con registro detallado.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-custom text-center p-4">
                <i class="bi bi-arrow-repeat display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Entradas y salidas</h5>
                <p class="text-secondary-custom small m-0">Registra asignación y devolución de equipos.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-custom text-center p-4">
                <i class="bi bi-search display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Trazabilidad</h5>
                <p class="text-secondary-custom small m-0">Historial de movimiento por equipo y responsable.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-center">
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card-custom p-4">
                <ul class="list-unstyled">
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Registro de portátiles y equipos</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Material de informática</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Control de entradas y salidas</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Registro de responsable actual</li>
                    <li class="text-white"><i class="bi bi-check2-circle text-cyan me-2"></i>Reportes de estado y disponibilidad</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="fw-bold h1 text-white mb-4">Trazabilidad total</h2>
            <p class="text-secondary-custom mb-3">Cada equipo queda registrado en el sistema con su historial completo. Sabrás siempre dónde está, quién lo usa y en qué estado se encuentra.</p>
            <p class="text-secondary-custom">Ideal para instituciones que manejan grandes inventarios tecnológicos y necesitan control total sin complicaciones.</p>
        </div>
    </div>
</section>
@endsection
