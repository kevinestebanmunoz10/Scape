@extends('layouts.app')

@section('title', 'Scape - Restablecer contraseña')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

@section('content')
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Nueva contraseña</h2>
            <p class="login-subtitle">Elige una contraseña segura</p>

            @if ($errors->has('password'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('password') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" novalidate>
                @csrf

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

                <button type="submit" class="btn-login">Restablecer contraseña</button>
                <button type="button" class="btn-back" onclick="window.location.href='{{ route('login') }}'">Volver al inicio de sesión</button>
            </form>
        </div>
    </div>
@endsection