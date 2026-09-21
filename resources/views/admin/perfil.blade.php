@extends('admin.panel')

@section('title', 'Administrador - Mi perfil')

@section('panel-content')
    <div class="panel">
        <div class="panel-header">
            <h2>Mi perfil</h2>
        </div>
        <div class="prestamos-list">
            <div class="prestamo-item">
                <span>Documento</span>
                <span class="profile-value">{{ auth()->user()->Documento }}</span>
            </div>
            <div class="prestamo-item">
                <span>Nombre</span>
                <span class="profile-value">{{ auth()->user()->Nom_usua }}</span>
            </div>
            <div class="prestamo-item">
                <span>Correo</span>
                <span class="profile-value">{{ auth()->user()->email }}</span>
            </div>
            <div class="prestamo-item">
                <span>Tel&eacute;fono</span>
                <span class="profile-value">{{ auth()->user()->Telefono }}</span>
            </div>
            <div class="prestamo-item">
                <span>Rol</span>
                <span class="profile-value">{{ $rol }}</span>
            </div>
        </div>
    </div>
@endsection