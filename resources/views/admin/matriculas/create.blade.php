@extends('admin.panel')

@section('title', 'Administrador - Nueva matrícula')

@section('panel-content')
    {{-- Encabezado de la página con título y acción de volver --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Nueva matr&iacute;cula</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Registra la matrícula de un estudiante y su salón asignado.</p>
        </div>
        {{-- Enlace para regresar al listado de matrículas --}}
        <a href="{{ route('admin.matriculas.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de alta --}}
    <div class="panel form-panel">
        {{-- El formulario envía los datos por POST a la ruta de almacenamiento --}}
        <form method="POST" action="{{ route('admin.matriculas.store') }}">
            {{-- Token CSRF de Laravel --}}
            @csrf

            {{-- Reutiliza el formulario de campos de matrícula definido en _form --}}
            @include('admin.matriculas._form')

            {{-- Acciones del formulario (cancelar o guardar) --}}
            <div class="form-actions">
                {{-- Enlace de cancelación que regresa al listado --}}
                <a href="{{ route('admin.matriculas.index') }}" class="btn btn-ghost">Cancelar</a>
                {{-- Botón que envía el formulario para crear la matrícula --}}
                <button type="submit" class="btn btn-primary">Guardar matrícula</button>
            </div>
        </form>
    </div>
@endsection
