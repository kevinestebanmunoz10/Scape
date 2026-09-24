@extends('admin.panel')

@section('title', 'Administrador - Editar usuario')

@section('panel-content')
    {{-- Encabezado de la página con título y acción de volver --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Editar usuario</h1>
            {{-- Subtítulo que indica el usuario que se está editando --}}
            <p class="subtitle">Actualiza la informaci&oacute;n de {{ $usuario->Nom_usua }}.</p>
        </div>
        {{-- Enlace para regresar al listado de usuarios --}}
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Volver</a>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel que contiene el formulario de edición --}}
    <div class="panel form-panel">
        {{-- El formulario envía los datos por POST y Laravel lo interpreta como PUT --}}
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}">
            {{-- Token CSRF de Laravel --}}
            @csrf
            {{-- Método HTTP PUT simulado para actualización --}}
            @method('PUT')

            {{-- Reutiliza el formulario de campos de usuario definido en _form --}}
            @include('admin.usuarios._form')

            {{-- Acciones del formulario (cancelar o guardar) --}}
            <div class="form-actions">
                {{-- Enlace de cancelación que regresa al listado --}}
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Cancelar</a>
                {{-- Botón que envía el formulario para guardar los cambios --}}
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
@endsection