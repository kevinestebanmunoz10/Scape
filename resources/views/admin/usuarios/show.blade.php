@extends('admin.panel')

@section('title', 'Administrador - Detalle de usuario')

@section('panel-content')
    <div class="page-header">
        <div>
            <h1>{{ $usuario->Nom_usua }}</h1>
            <p class="subtitle">Documento {{ $usuario->Documento }}</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-primary">Editar</a>
            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-ghost">Volver</a>
        </div>
    </div>

    @include('partials.alertas')

    <div class="panel form-panel">
        <div class="prestamos-list">
            <div class="prestamo-item">
                <span>Documento</span>
                <span class="profile-value">{{ $usuario->Documento }}</span>
            </div>
            <div class="prestamo-item">
                <span>Nombre</span>
                <span class="profile-value">{{ $usuario->Nom_usua }}</span>
            </div>
            <div class="prestamo-item">
                <span>Correo</span>
                <span class="profile-value">{{ $usuario->email }}</span>
            </div>
            <div class="prestamo-item">
                <span>Tel&eacute;fono</span>
                <span class="profile-value">{{ $usuario->Telefono }}</span>
            </div>
            <div class="prestamo-item">
                <span>C&oacute;digo QR</span>
                <span class="profile-value">{{ $usuario->QR }}</span>
            </div>
            <div class="prestamo-item">
                <span>Ciudad</span>
                <span class="profile-value">{{ $usuario->cod_postal }}</span>
            </div>
            <div class="prestamo-item">
                <span>Rol</span>
                <span class="profile-value">{{ $usuario->rol?->rol ?? '&mdash;' }}</span>
            </div>
            <div class="prestamo-item">
                <span>Estado</span>
                <span>
                    @if ((int) $usuario->estado?->estado === 1)
                        <span class="badge badge-active">Activo</span>
                    @else
                        <span class="badge badge-inactive">Inactivo</span>
                    @endif
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}"
            class="delete-block" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este usuario?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar usuario</button>
        </form>
    </div>
@endsection
