@extends('admin.panel')

@section('title', 'Administrador - Editar equipo')

@section('panel-content')
    {{-- Encabezado de la página con título y acción de volver --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Editar equipo</h1>
            {{-- Subtítulo que indica el equipo que se está editando --}}
            <p class="subtitle">Actualiza la informaci&oacute;n del equipo {{ $equipo->serial_equi }}.</p>
        </div>
        {{-- Enlace para regresar al listado de equipos --}}
        <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de edición --}}
    <div class="panel form-panel">
        {{-- El formulario envía los datos por POST (admite subida de archivos) y Laravel lo interpreta como PUT --}}
        <form method="POST" action="{{ route('admin.equipos.update', $equipo) }}" enctype="multipart/form-data">
            {{-- Token CSRF de Laravel --}}
            @csrf
            {{-- Método HTTP PUT simulado para actualización --}}
            @method('PUT')

            {{-- Reutiliza el formulario de campos de equipo definido en _form --}}
            @include('admin.equipos._form')

            {{-- Acciones del formulario (cancelar o guardar) --}}
            <div class="form-actions">
                {{-- Enlace de cancelación que regresa al listado --}}
                <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Cancelar</a>
                {{-- Botón que envía el formulario para guardar los cambios --}}
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection