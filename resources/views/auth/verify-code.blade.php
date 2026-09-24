{{-- Vista para verificar el código de restablecimiento: extiende el layout principal --}}
@extends('layouts.app')

{{-- Título de la página --}}
@section('title', 'Scape - Verificar código')

{{-- Estilos adicionales propios de las pantallas de autenticación --}}
@push('styles')
    <!-- Fuente de iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Hoja de estilos específica para la pantalla de login -->
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

{{-- Inicio del bloque de contenido --}}
@section('content')
    <!-- Contenedor que centra la tarjeta de verificación -->
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Verificar código</h2>
            <p class="login-subtitle">Ingresa el código de 8 dígitos que enviamos a tu correo</p>

            {{-- Muestra el error de validación del campo 'code' si existe --}}
            @if ($errors->has('code'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('code') }}
                </div>
            @endif

            {{-- Formulario que envía el código a la ruta nombrada password.verify --}}
            <form method="POST" action="{{ route('password.verify') }}" novalidate>
                {{-- Token CSRF obligatorio en todos los formularios POST --}}
                @csrf

                <!-- Campo de entrada del código numérico de 8 dígitos -->
                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-shield-halved"></i></span>
                    <input
                        type="text"
                        name="code"
                        inputmode="numeric"
                        maxlength="8"
                        pattern="[0-9]{8}"
                        required
                        autofocus
                        placeholder=""
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    >
                    <label>Código de verificación</label>
                </div>

                <!-- Botón para verificar el código -->
                <button type="submit" class="btn-login">Verificar código</button>
                <!-- Botón que redirige a la pantalla de recuperación de contraseña -->
                <button type="button" class="btn-back" onclick="window.location.href='{{ url('/admin/password/reset') }}'">Reenviar código a otro correo</button>
            </form>
        </div>
    </div>
{{-- Fin del bloque de contenido --}}
@endsection