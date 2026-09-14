@extends('layouts.app')

@section('title', 'Scape - Verificar código')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

@section('content')
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Verificar código</h2>
            <p class="login-subtitle">Ingresa el código de 8 dígitos que enviamos a tu correo</p>

            @if ($errors->has('code'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('code') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.verify') }}" novalidate>
                @csrf

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

                <button type="submit" class="btn-login">Verificar código</button>
                <button type="button" class="btn-back" onclick="window.location.href='{{ url('/admin/password/reset') }}'">Reenviar código a otro correo</button>
            </form>
        </div>
    </div>
@endsection