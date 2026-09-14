@extends('layouts.app')

@section('title', 'Scape - Iniciar sesión')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

@section('content')
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Iniciar sesión</h2>
            <p class="login-subtitle">Acceso exclusivo para administradores</p>

            @if (! empty($error))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $error }}
                </div>
            @endif

            <form method="POST" novalidate>
                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-id-card"></i></span>
                    <input
                        type="text"
                        name="documento"
                        inputmode="numeric"
                        required
                        value="{{ old('documento') }}"
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                    >
                    <label>Documento</label>
                </div>

                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input
                        type="password"
                        name="contrasena"
                        minlength="4"
                        maxlength="255"
                        autocomplete="current-password"
                        required
                    >
                    <label>Contraseña</label>
                </div>

                <button type="submit" class="btn-login">Ingresar</button>
                <button type="button" class="btn-back" onclick="window.location.href='{{ url('/') }}'">Volver al inicio</button>
            </form>
        </div>
    </div>
@endsection