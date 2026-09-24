{{-- Vista para recuperar la contraseña: extiende el layout principal --}}
@extends('layouts.app')

{{-- Título de la página --}}
@section('title', 'Scape - Recuperar contraseña')

{{-- Estilos adicionales propios de las pantallas de autenticación --}}
@push('styles')
    <!-- Fuente de iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Hoja de estilos específica para la pantalla de login -->
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

{{-- Inicio del bloque de contenido --}}
@section('content')
    <!-- Contenedor que centra la tarjeta de recuperación -->
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Recuperar contraseña</h2>
            <p class="login-subtitle">Ingresa tu correo y te enviaremos un código de 8 dígitos</p>

            {{-- Muestra un mensaje de estado (por ejemplo, correo enviado) si existe --}}
            @if (session('status'))
                <div class="alert alert-success login-alert" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Muestra el error de validación del campo 'email' si existe --}}
            @if ($errors->has('email'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('email') }}
                </div>
            @endif

            {{-- Formulario que envía el correo a la ruta nombrada password.email --}}
            <form method="POST" action="{{ route('password.email') }}" novalidate>
                {{-- Token CSRF obligatorio en todos los formularios POST --}}
                @csrf

                <!-- Campo de entrada del correo electrónico -->
                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                    <input
                        type="email"
                        name="email"
                        autocomplete="email"
                        required
                        value="{{ old('email') }}"
                    >
                    <label>Correo electrónico</label>
                </div>

                <!-- Botón para solicitar el envío del código -->
                <button type="submit" class="btn-login">Enviar código</button>
                <!-- Botón que regresa a la pantalla de inicio de sesión -->
                <button type="button" class="btn-back" onclick="window.location.href='{{ route('login') }}'">Volver al inicio de sesión</button>
            </form>
        </div>
    </div>
{{-- Fin del bloque de contenido --}}
@endsection