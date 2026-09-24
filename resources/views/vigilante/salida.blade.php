@extends('vigilante.panel')

@section('title', 'Vigilante - Registrar salida')

@section('panel-content')
    {{-- Encabezado con la fecha y hora actuales --}}
    <div class="header-row">
        <div class="date-filter">
            {{-- Muestra la fecha actual --}}
            {{ now()->format('d M, Y') }}
            <div class="date-box">
                {{-- Muestra la hora actual en formato de 24 horas --}}
                {{ now()->format('H:i') }}
                {{-- Ícono decorativo de reloj --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="#e5e9f0" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </div>
        </div>
    </div>

    {{-- Muestra un mensaje de éxito de sesión si existe --}}
    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif

    {{-- Muestra un mensaje de error de sesión si existe --}}
    @if (session('error'))
        <div class="alert alert-bad">{{ session('error') }}</div>
    @endif

    {{-- Muestra el primer error de validación si lo hay --}}
    @if ($errors->any())
        <div class="alert alert-bad">{{ $errors->first() }}</div>
    @endif

    {{-- Panel con el flujo de registro de salida --}}
    <section class="panel ingreso-panel">
        {{-- Encabezado del panel --}}
        <div class="panel-header">
            <div>
                {{-- Título del panel --}}
                <h2>Registrar salida</h2>
                {{-- Descripción de uso --}}
                <span class="date-range">Busca a la persona y confirma su egreso</span>
            </div>
            {{-- Enlace para regresar al inicio del vigilante --}}
            <a href="{{ route('vigilante.dashboard') }}" class="filter-clear">Volver</a>
        </div>

        {{-- Si la sesión contiene una persona encontrada, muestra la confirmación de salida --}}
        @if (session('persona'))
            {{-- Recupera los datos de la persona desde la sesión --}}
            @php($persona = session('persona'))
            {{-- Tarjeta de confirmación de la salida --}}
            <div class="confirm-card">
                <div>
                    {{-- Etiqueta que indica que se encontró a la persona --}}
                    <span class="confirm-label">PERSONA ENCONTRADA</span>
                    {{-- Nombre de la persona --}}
                    <div class="confirm-name">{{ $persona['nombre'] }}</div>
                    {{-- Tipo y documento de la persona --}}
                    <div class="confirm-meta">{{ ucfirst($persona['tipo']) }} &middot; Doc. {{ $persona['documento'] }}</div>
                    {{-- Fecha y hora del ingreso registrado de la persona --}}
                    <div class="confirm-meta">Ingres&oacute; el {{ \Carbon\Carbon::parse($persona['ingreso'])->format('d M, Y h:i a') }}</div>
                </div>
                {{-- Formulario que confirma y registra la salida --}}
                <form method="POST" action="{{ route('vigilante.salida.store') }}">
                    {{-- Token CSRF de Laravel --}}
                    @csrf
                    {{-- Campo oculto con el tipo de persona --}}
                    <input type="hidden" name="tipo" value="{{ $persona['tipo'] }}">
                    {{-- Campo oculto con el documento de la persona --}}
                    <input type="hidden" name="documento" value="{{ $persona['documento'] }}">
                    {{-- Botón que confirma la salida --}}
                    <button type="submit" class="date-apply btn-salida">Confirmar salida</button>
                </form>
            </div>
        @else
            {{-- Formulario de búsqueda de la persona por tipo y documento --}}
            <form class="ingreso-form" method="POST" action="{{ route('vigilante.salida.buscar') }}">
                {{-- Token CSRF de Laravel --}}
                @csrf

                {{-- Campo: tipo de persona a buscar --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="tipo">Tipo de persona</label>
                    {{-- Selector de tipo; conserva la opción seleccionada tras la validación --}}
                    <select name="tipo" id="tipo" class="filter-select">
                        <option value="usuario" @selected(old('tipo', 'usuario') === 'usuario')>Usuario</option>
                        <option value="estudiante" @selected(old('tipo') === 'estudiante')>Estudiante</option>
                        <option value="visitante" @selected(old('tipo') === 'visitante')>Visitante</option>
                    </select>
                </div>

                {{-- Campo: número de documento --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="documento">Documento</label>
                    {{-- Entrada del documento con foco automático; conserva el valor previo --}}
                    <input type="text" name="documento" id="documento" class="filter-input" value="{{ old('documento') }}" placeholder="N&uacute;mero de documento" autofocus>
                </div>

                {{-- Botón que ejecuta la búsqueda --}}
                <button type="submit" class="date-apply">Buscar</button>
            </form>
        @endif
    </section>
@endsection