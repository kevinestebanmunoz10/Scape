{{-- Renderiza las alertas de éxito/estado enviadas desde el controlador --}}
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

{{-- Renderiza las alertas de error enviadas desde el controlador --}}
@if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

{{-- Muestra todos los errores de validación del formulario, si existen --}}
@if ($errors->any())
    <div class="alert alert-error">
        <ul class="alert-list">
            {{-- Recorre la lista completa de errores de validación --}}
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif