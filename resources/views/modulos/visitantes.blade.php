@extends('layouts.app')

@section('title', 'SCAPE - Control de Visitantes')

@section('content')
<section class="module-hero text-center text-white">
    <div class="container">
        <br>
        <h1>Visitantes</h1>
        <p class="lead w-75 mx-auto text-light opacity-75">
            Pre-registro, autorización y control de visitantes externos con notificaciones automáticas.
        </p>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
            <div class="card-custom text-center p-4">
                <i class="bi bi-person-plus display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Pre-registro</h5>
                <p class="text-secondary-custom small m-0">Los visitantes se registran antes de llegar.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-custom text-center p-4">
                <i class="bi bi-bell display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Notificaciones</h5>
                <p class="text-secondary-custom small m-0">Alertas automáticas al responsable.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-custom text-center p-4">
                <i class="bi bi-shield display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Seguridad</h5>
                <p class="text-secondary-custom small m-0">Validación de identidad al ingreso.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-center">
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="fw-bold h1 text-white mb-4">Control inteligente de visitantes</h2>
            <p class="text-secondary-custom mb-3">Los visitantes externos pueden ser pre-registrados por el responsable antes de su llegada. Al llegar, el sistema valida su identidad y notifica automáticamente al funcionario que recibe.</p>
            <p class="text-secondary-custom">Garantiza la seguridad de tu institución con un flujo de trabajo sencillo y eficiente.</p>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card-custom p-4">
                <ul class="list-unstyled">
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Pre-registro de externos</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Autorización de entrada</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Notificación automática al responsable</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Registro de horario de entrada y salida</li>
                    <li class="text-white"><i class="bi bi-check2-circle text-cyan me-2"></i>Historial de visitantes por fecha</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
