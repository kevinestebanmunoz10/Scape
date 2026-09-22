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
            <p class="login-subtitle">Ingresa tus credenciales</p>

            @if ($errors->has('documento'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('documento') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-id-card"></i></span>
                    <input
                        type="text"
                        name="documento"
                        id="documento"
                        inputmode="numeric"
                        required
                        value="{{ old('documento') }}"
                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                        oninput="this.value=this.value.replace(/[^0-9]/g,''); validarDocumento();"
                    >
                    <label>Documento</label>
                    <div class="validation-feedback" id="feedback-documento"></div>
                </div>

                <div class="input-box">
                    <span class="icon"><i class="fa-solid fa-lock"></i></span>
                    <input
                        type="password"
                        name="contrasena"
                        id="contrasena"
                        minlength="6"
                        maxlength="255"
                        autocomplete="current-password"
                        required
                        oninput="validarContrasena();"
                    >
                    <label>Contraseña</label>
                    <div class="validation-feedback" id="feedback-contrasena"></div>
                </div>

                <div class="terms-checkbox">
                    <label class="terms-label">
                        <input
                            type="checkbox"
                            name="terminos"
                            value="1"
                            required
                        >
                        <span>Acepto términos y condiciones de <a href="{{ asset('storage/pdf/privacidad.pdf') }}" target="_blank" rel="noopener" class="terms-link">SCAPE</a></span>
                    </label>
                </div>

                <button type="submit" class="btn-login">Ingresar</button>

                <div class="login-links">
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        <i class="fa-solid fa-lock"></i>
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <button type="button" class="btn-back btn-back--login" onclick="window.location.href='{{ url('/') }}'">Volver al inicio</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // La validación en tiempo real se ejecuta con cada pulsación de tecla
    // porque los inputs llaman a validarDocumento() y validarContrasena() en su oninput.

    function validarDocumento() {
        var input = document.getElementById('documento');
        var feedback = document.getElementById('feedback-documento');

        // Validación en tiempo real del documento: debe ser solo números y no estar vacío.
        if (input.value.length === 0) {
            feedback.textContent = 'El documento es obligatorio.';
            feedback.className = 'validation-feedback invalid';
        } else if (!/^\d+$/.test(input.value)) {
            feedback.textContent = 'El documento debe contener solo números.';
            feedback.className = 'validation-feedback invalid';
        } else {
            feedback.textContent = '';
            feedback.className = 'validation-feedback';
        }
    }

    function validarContrasena() {
        var input = document.getElementById('contrasena');
        var feedback = document.getElementById('feedback-contrasena');
        var mensajes = [];

        // Validación en tiempo real: mínimo 6 caracteres.
        if (input.value.length < 6) {
            mensajes.push('Debe tener mínimo 6 caracteres.');
        }

        // Validación en tiempo real: debe incluir letras minúsculas (a-z).
        if (!/[a-z]/.test(input.value)) {
            mensajes.push('Debe incluir letras minúsculas.');
        }

        // Validación en tiempo real: debe incluir números (0-9).
        if (!/[0-9]/.test(input.value)) {
            mensajes.push('Debe incluir números.');
        }

        if (mensajes.length > 0) {
            feedback.textContent = mensajes.join(' ');
            feedback.className = 'validation-feedback invalid';
        } else {
            feedback.textContent = '';
            feedback.className = 'validation-feedback';
        }
    }
</script>
@endpush