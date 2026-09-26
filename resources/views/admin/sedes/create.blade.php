@extends('admin.panel')

@section('title', 'Administrador - Nueva sede')

@section('panel-content')
    {{-- Encabezado de la página con el título del formulario --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Nueva sede</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Registra una sede de la instituci&oacute;n y su ubicaci&oacute;n.</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para regresar al listado sin guardar --}}
            <a href="{{ route('admin.sedes.index') }}" class="btn btn-ghost">Volver</a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de alta --}}
    <div class="panel form-panel">
        {{-- Formulario que envía los datos al controlador para crear la sede --}}
        <form method="POST" action="{{ route('admin.sedes.store') }}">
            {{-- Token CSRF de Laravel --}}
            @csrf

            {{-- Incluye los campos compartidos con el formulario de edición --}}
            @include('admin.sedes._form')

            {{-- Acciones del formulario --}}
            <div class="page-header-actions" style="margin-top:22px;">
                <button type="submit" class="btn btn-primary">Registrar sede</button>
                <a href="{{ route('admin.sedes.index') }}" class="btn btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
