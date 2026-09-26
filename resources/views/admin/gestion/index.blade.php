@extends('admin.panel')

@section('title', 'Administrador - Gestión')

@section('panel-content')
    {{-- Encabezado de la página con el título del módulo de gestión --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Gesti&oacute;n</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Elige el módulo que quieres administrar.</p>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Cuadrícula con un botón por cada módulo de gestión --}}
    <section class="gestion-grid">
        {{-- Botón: gestión de matrículas --}}
        <a href="{{ route('admin.matriculas.index') }}" class="gestion-card">
            {{-- Título del módulo --}}
            <h3>Gestionar matr&iacute;culas</h3>
        </a>

        {{-- Botón: gestión de sedes --}}
        <a href="{{ route('admin.sedes.index') }}" class="gestion-card">
            {{-- Título del módulo --}}
            <h3>Gestionar sedes</h3>
        </a>

        {{-- Botón: gestión de salones (aún sin módulo propio, se muestra deshabilitado) --}}
        <span class="gestion-card disabled" aria-disabled="true">
            {{-- Título del módulo --}}
            <h3>Gestionar salones</h3>
        </span>
    </section>
@endsection
