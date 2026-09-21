@extends('layouts.app')

@section('title', 'Scape - Recuperar contraseña')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

@section('content')
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Recuperar contraseña</h2>
            <p class="login-subtitle">Ingresa tu correo y te enviaremos un código de 8 dígitos</p>

            @if (session('status'))
                <div class="alert alert-success login-alert" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('email'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf

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

                <button type="submit" class="btn-login">Enviar código</button>
                <button type="button" class="btn-back" onclick="window.location.href='{{ route('login') }}'">Volver al inicio de sesión</button>
            </form>
        </div>
    </div>
@endsection