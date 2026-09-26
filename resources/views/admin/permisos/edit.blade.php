@extends('admin.panel')

@section('title', 'Administrador - Editar permiso')

@section('panel-content')
    {{-- Encabezado de la página con título y acción de volver --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Editar permiso</h1>
            {{-- Subtítulo que indica el propósito de la edición --}}
            <p class="subtitle">Actualiza la informaci&oacute;n del permiso de salida.</p>
        </div>
        {{-- Enlace para regresar al listado de permisos --}}
        <a href="{{ route('admin.permisos') }}" class="btn btn-ghost">Volver</a>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de edición --}}
    <div class="panel form-panel">
        {{-- El formulario envía los datos por POST y Laravel lo interpreta como PUT --}}
        <form method="POST" action="{{ route('admin.permisos.update', $permiso) }}">
            {{-- Token CSRF de Laravel --}}
            @csrf
            {{-- Método HTTP PUT simulado para actualización --}}
            @method('PUT')

            {{-- Reutiliza los campos del formulario con los valores actuales del permiso --}}
            @include('admin.permisos._form', [
                'documentoEstu' => $documentoEstu,
                'documentoAcud' => $documentoAcud,
                'idTipo' => $idTipo,
                'descripcion' => $descripcion,
                'acudienteEstudiantes' => $acudienteEstudiantes,
            ])

            {{-- Acciones del formulario (cancelar o guardar) --}}
            <div class="form-actions">
                {{-- Enlace de cancelación que regresa al listado --}}
                <a href="{{ route('admin.permisos') }}" class="btn btn-ghost">Cancelar</a>
                {{-- Botón que envía el formulario para guardar los cambios --}}
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection