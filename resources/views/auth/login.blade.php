{{-- Vista de inicio de sesión: extiende el layout principal --}}
@extends('layouts.app')

{{-- Título de la página --}}
@section('title', 'Scape - Iniciar sesión')

{{-- Estilos adicionales propios de las pantallas de autenticación --}}
@push('styles')
    <!-- Fuente de iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Hoja de estilos específica para la pantalla de login -->
    <link rel="stylesheet" href="{{ asset('css/styles_login.css') }}">
@endpush

{{-- Inicio del bloque de contenido --}}
@section('content')
    <!-- Contenedor que centra la tarjeta de inicio de sesión -->
    <div class="login-wrapper">
        <div class="login-box">
            <h2>Iniciar sesión</h2>
            <p class="login-subtitle">Ingresa tus credenciales</p>

            {{-- Muestra el error de validación del campo 'documento' si existe --}}
            @if ($errors->has('documento'))
                <div class="alert alert-danger login-alert" role="alert">
                    {{ $errors->first('documento') }}
                </div>
            @endif

            {{-- Formulario que envía las credenciales a la ruta nombrada login --}}
            <form method="POST" action="{{ route('login') }}" novalidate>
                {{-- Token CSRF obligatorio en todos los formularios POST --}}
                @csrf
                <!-- Campo de entrada del documento de identidad (solo números) -->
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
                    <!-- Espacio para mostrar el mensaje de validación en tiempo real -->
                    <div class="validation-feedback" id="feedback-documento"></div>
                </div>

                <!-- Campo de entrada de la contraseña -->
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
                    <!-- Espacio para mostrar el mensaje de validación en tiempo real -->
                    <div class="validation-feedback" id="feedback-contrasena"></div>
                </div>

                <!-- Casilla obligatoria de aceptación de términos y condiciones -->
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

                <!-- Botón para enviar el formulario de inicio de sesión -->
                <button type="submit" class="btn-login">Ingresar</button>

                <!-- Enlace a la recuperación de contraseña -->
                <div class="login-links">
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        <i class="fa-solid fa-lock"></i>
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <!-- Botón que regresa a la página de inicio -->
                <button type="button" class="btn-back btn-back--login" onclick="window.location.href='{{ url('/') }}'">Volver al inicio</button>
            </form>
        </div>
    </div>
{{-- Fin del bloque de contenido --}}
@endsection

{{-- Scripts de validación del formulario de inicio de sesión --}}
@push('scripts')
<script>
    // La validación en tiempo real se ejecuta con cada pulsación de tecla
    // porque los inputs llaman a validarDocumento() y validarContrasena() en su oninput.

    // Valida en tiempo real el campo del documento
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

    // Valida en tiempo real el campo de la contraseña
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

        // Si hay mensajes de error se muestran en el feedback; si no, se limpia
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