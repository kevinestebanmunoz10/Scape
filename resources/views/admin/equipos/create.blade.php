@extends('admin.panel')

@section('title', 'Administrador - Nuevo equipo')

@section('panel-content')
    {{-- Encabezado de la página con título y acción de volver --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Nuevo equipo</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Registra un nuevo equipo en el sistema.</p>
        </div>
        {{-- Enlace para regresar al listado de equipos --}}
        <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de alta --}}
    <div class="panel form-panel">
        {{-- El formulario envía los datos por POST (admite subida de archivos) a la ruta de almacenamiento --}}
        <form method="POST" action="{{ route('admin.equipos.store') }}" enctype="multipart/form-data">
            {{-- Token CSRF de Laravel --}}
            @csrf

            {{-- Reutiliza el formulario de campos de equipo definido en _form --}}
            @include('admin.equipos._form')

            {{-- Acciones del formulario (cancelar o guardar) --}}
            <div class="form-actions">
                {{-- Enlace de cancelación que regresa al listado --}}
                <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Cancelar</a>
                {{-- Botón que envía el formulario para crear el equipo --}}
                <button type="submit" class="btn btn-primary">Guardar equipo</button>
            </div>
        </form>
    </div>
@endsection