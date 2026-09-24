@extends('rector.panel')

@section('title', 'Rector - Mi perfil')

@section('panel-content')
    {{-- Panel que muestra los datos del perfil del rector autenticado --}}
    <div class="panel">
        {{-- Encabezado del panel --}}
        <div class="panel-header">
            <h2>Mi perfil</h2>
        </div>
        {{-- Lista de campos del perfil --}}
        <div class="prestamos-list">
            {{-- Fila: documento del usuario --}}
            <div class="prestamo-item">
                <span>Documento</span>
                {{-- Muestra el documento del usuario autenticado --}}
                <span class="profile-value">{{ auth()->user()->Documento }}</span>
            </div>
            {{-- Fila: nombre completo del usuario --}}
            <div class="prestamo-item">
                <span>Nombre</span>
                {{-- Muestra el nombre del usuario autenticado --}}
                <span class="profile-value">{{ auth()->user()->Nom_usua }}</span>
            </div>
            {{-- Fila: correo electrónico del usuario --}}
            <div class="prestamo-item">
                <span>Correo</span>
                {{-- Muestra el correo del usuario autenticado --}}
                <span class="profile-value">{{ auth()->user()->email }}</span>
            </div>
            {{-- Fila: teléfono del usuario --}}
            <div class="prestamo-item">
                <span>Tel&eacute;fono</span>
                {{-- Muestra el teléfono del usuario autenticado --}}
                <span class="profile-value">{{ auth()->user()->Telefono }}</span>
            </div>
            {{-- Fila: rol asignado (proviene del controlador) --}}
            <div class="prestamo-item">
                <span>Rol</span>
                {{-- Muestra el rol del usuario --}}
                <span class="profile-value">{{ $rol }}</span>
            </div>
        </div>
    </div>
@endsection