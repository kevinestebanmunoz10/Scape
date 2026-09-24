@extends('admin.panel')

@section('title', 'Administrador - Detalle de usuario')

@section('panel-content')
    {{-- Encabezado de la página con el nombre del usuario y acciones --}}
    <div class="page-header">
        <div>
            {{-- Nombre del usuario --}}
            <h1>{{ $usuario->Nom_usua }}</h1>
            {{-- Subtítulo con el documento del usuario --}}
            <p class="subtitle">Documento {{ $usuario->Documento }}</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para editar el usuario --}}
            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-primary">Editar</a>
            {{-- Enlace para regresar al listado --}}
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Volver</a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel principal que resume los datos del usuario --}}
    <div class="panel form-panel">
        <div class="prestamos-list">
            {{-- Fila: documento --}}
            <div class="prestamo-item">
                <span>Documento</span>
                {{-- Documento del usuario --}}
                <span class="profile-value">{{ $usuario->Documento }}</span>
            </div>
            {{-- Fila: nombre --}}
            <div class="prestamo-item">
                <span>Nombre</span>
                {{-- Nombre del usuario --}}
                <span class="profile-value">{{ $usuario->Nom_usua }}</span>
            </div>
            {{-- Fila: correo --}}
            <div class="prestamo-item">
                <span>Correo</span>
                {{-- Correo del usuario --}}
                <span class="profile-value">{{ $usuario->email }}</span>
            </div>
            {{-- Fila: teléfono --}}
            <div class="prestamo-item">
                <span>Tel&eacute;fono</span>
                {{-- Teléfono del usuario --}}
                <span class="profile-value">{{ $usuario->Telefono }}</span>
            </div>
            {{-- Fila: código QR --}}
            <div class="prestamo-item">
                <span>C&oacute;digo QR</span>
                {{-- Código QR del usuario --}}
                <span class="profile-value">{{ $usuario->QR }}</span>
            </div>
            {{-- Fila: ciudad --}}
            <div class="prestamo-item">
                <span>Ciudad</span>
                {{-- Código postal de la ciudad del usuario --}}
                <span class="profile-value">{{ $usuario->cod_postal }}</span>
            </div>
            {{-- Fila: rol --}}
            <div class="prestamo-item">
                <span>Rol</span>
                {{-- Rol del usuario (o guion medio si no tiene) --}}
                <span class="profile-value">{{ $usuario->rol?->rol ?? '—' }}</span>
            </div>
            {{-- Fila: estado --}}
            <div class="prestamo-item">
                <span>Estado</span>
                <span>
                    {{-- Según el estado numérico (1 = activo) muestra una insignia u otra --}}
                    @if ((int) $usuario->estado?->estado === 1)
                        <span class="badge badge-active">Activo</span>
                    @else
                        <span class="badge badge-inactive">Inactivo</span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Bloque de acción para desactivar o activar el usuario según su estado --}}
        @if ((int) $usuario->estado?->estado === 1)
            {{-- Formulario que desactiva el usuario con confirmación previa --}}
            <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}"
                class="delete-block" onsubmit="return confirm('&iquest;Seguro que deseas desactivar este usuario?');">
                {{-- Token CSRF de Laravel --}}
                @csrf
                {{-- Método HTTP DELETE simulado --}}
                @method('DELETE')
                {{-- Botón para desactivar el usuario --}}
                <button type="submit" class="btn btn-danger">Desactivar usuario</button>
            </form>
        @else
            {{-- Formulario que reactiva el usuario --}}
            <form method="POST" action="{{ route('admin.usuarios.activar', $usuario) }}" class="delete-block">
                {{-- Token CSRF de Laravel --}}
                @csrf
                {{-- Botón para activar el usuario --}}
                <button type="submit" class="btn btn-primary">Activar usuario</button>
            </form>
        @endif
    </div>
@endsection