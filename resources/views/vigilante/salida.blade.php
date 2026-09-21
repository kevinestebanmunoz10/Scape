@extends('vigilante.panel')

@section('title', 'Vigilante - Registrar salida')

@section('panel-content')
    <div class="header-row">
        <div class="date-filter">
            {{ now()->format('d M, Y') }}
            <div class="date-box">
                {{ now()->format('H:i') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="#e5e9f0" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-ok">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-bad">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-bad">{{ $errors->first() }}</div>
    @endif

    <section class="panel ingreso-panel">
        <div class="panel-header">
            <div>
                <h2>Registrar salida</h2>
                <span class="date-range">Busca a la persona y confirma su egreso</span>
            </div>
            <a href="{{ route('vigilante.dashboard') }}" class="filter-clear">Volver</a>
        </div>

        @if (session('persona'))
            @php($persona = session('persona'))
            <div class="confirm-card">
                <div>
                    <span class="confirm-label">PERSONA ENCONTRADA</span>
                    <div class="confirm-name">{{ $persona['nombre'] }}</div>
                    <div class="confirm-meta">{{ ucfirst($persona['tipo']) }} &middot; Doc. {{ $persona['documento'] }}</div>
                    <div class="confirm-meta">Ingres&oacute; el {{ \Carbon\Carbon::parse($persona['ingreso'])->format('d M, Y h:i a') }}</div>
                </div>
                <form method="POST" action="{{ route('vigilante.salida.store') }}">
                    @csrf
                    <input type="hidden" name="tipo" value="{{ $persona['tipo'] }}">
                    <input type="hidden" name="documento" value="{{ $persona['documento'] }}">
                    <button type="submit" class="date-apply btn-salida">Confirmar salida</button>
                </form>
            </div>
        @else
            <form class="ingreso-form" method="POST" action="{{ route('vigilante.salida.buscar') }}">
                @csrf

                <div class="form-field">
                    <label for="tipo">Tipo de persona</label>
                    <select name="tipo" id="tipo" class="filter-select">
                        <option value="usuario" @selected(old('tipo', 'usuario') === 'usuario')>Usuario</option>
                        <option value="estudiante" @selected(old('tipo') === 'estudiante')>Estudiante</option>
                        <option value="visitante" @selected(old('tipo') === 'visitante')>Visitante</option>
                    </select>
                </div>

                <div class="form-field">
                    <label for="documento">Documento</label>
                    <input type="text" name="documento" id="documento" class="filter-input" value="{{ old('documento') }}" placeholder="N&uacute;mero de documento" autofocus>
                </div>

                <button type="submit" class="date-apply">Buscar</button>
            </form>
        @endif
    </section>
@endsection
