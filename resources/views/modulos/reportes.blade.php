@extends('layouts.app')

@section('title', 'SCAPE - Reportes')

@section('content')
<section class="module-hero text-center text-white">
    <div class="container">
        <br>
        <h1>Reportes</h1>
        <p class="lead w-75 mx-auto text-light opacity-75">
            Reportes de asistencia, inventario, movimientos y alarmas en tiempo real con exportación PDF.
        </p>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
            <div class="card-custom text-center p-4">
                <i class="bi bi-bar-chart-line display-4 text-cyan"></i>
                <h5 class="text-white mt-3">En tiempo real</h5>
                <p class="text-secondary-custom small m-0">Datos actualizados constantemente.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-custom text-center p-4">
                <i class="bi bi-file-earmark-pdf display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Exportación PDF</h5>
                <p class="text-secondary-custom small m-0">Descarga reportes en formato PDF.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-custom text-center p-4">
                <i class="bi bi-exclamation-triangle display-4 text-cyan"></i>
                <h5 class="text-white mt-3">Alarmas</h5>
                <p class="text-secondary-custom small m-0">Alertas de eventos importantes.</p>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-center">
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card-custom p-4">
                <ul class="list-unstyled">
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Reportes de asistencia</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Reportes de inventario y equipos</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Movimientos y accesos</li>
                    <li class="text-white mb-3"><i class="bi bi-check2-circle text-cyan me-2"></i>Reportes de alarmas</li>
                    <li class="text-white"><i class="bi bi-check2-circle text-cyan me-2"></i>Exportación en PDF</li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="fw-bold h1 text-white mb-4">Información al instante</h2>
            <p class="text-secondary-custom mb-3">Consulta reportes en tiempo real o exporta la información que necesites. Desde reportes de asistencia hasta inventario completo, SCAPE te da visibilidad total de tu institución.</p>
            <p class="text-secondary-custom">Datos accesibles, exportables y siempre actualizados para la toma de decisiones.</p>
        </div>
    </div>
</section>
@endsection
