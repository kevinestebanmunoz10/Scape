{{-- Vista para restablecer la contraseña: extiende el layout principal --}}
@extends('layouts.app')

{{-- Título de la página --}}
@section('title', 'Scape - Restablecer contraseña')

{{-- Estilos adicionales propios de las pantallas de autenticación --}}
@push('styles')
    <!-- Fuente de iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Hoja de estilos específica para la pantalla de login -->
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

{{-- Inicio del bloque de contenido --}}
@section('content')
    <!-- Contenedor que centra la tarjeta de restablecimiento -->
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Nueva contraseña</h2>
            <p class="login-subtitle">Elige una contraseña segura</p>

            {{-- Muestra el error de validación del campo 'password' si existe --}}
            @if ($errors->has('password'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('password') }}
                </div>
            @endif

            {{-- Formulario que envía la nueva contraseña a la ruta nombrada password.store --}}
            <form method="POST" action="{{ route('password.store') }}" novalidate>
                {{-- Token CSRF obligatorio en todos los formularios POST --}}
                @csrf

                <!-- Campo de la nueva contraseña -->
                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input
                        type="password"
                        name="password"
                        minlength="8"
                        maxlength="255"
                        autocomplete="new-password"
                        required
                    >
                    <label>Nueva contraseña</label>
                </div>

                <!-- Campo de confirmación de la nueva contraseña -->
                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input
                        type="password"
                        name="password_confirmation"
                        minlength="8"
                        maxlength="255"
                        autocomplete="new-password"
                        required
                    >
                    <label>Confirmar contraseña</label>
                </div>

                <!-- Botón para restablecer la contraseña -->
                <button type="submit" class="btn-login">Restablecer contraseña</button>
                <!-- Botón que regresa a la pantalla de inicio de sesión -->
                <button type="button" class="btn-back" onclick="window.location.href='{{ route('login') }}'">Volver al inicio de sesión</button>
            </form>
        </div>
    </div>
{{-- Fin del bloque de contenido --}}
@endsection