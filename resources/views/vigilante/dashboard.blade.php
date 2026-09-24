@extends('vigilante.panel')

@section('title', 'Vigilante - Inicio')

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

    {{-- Acciones rápidas del vigilante (entrada, salida e historial) --}}
    <section class="vigilante-actions">
        {{-- Tarjeta de acción: registrar una entrada --}}
        <a href="{{ route('vigilante.entrada') }}" class="action-card entrada">
            {{-- Ícono de flecha hacia adentro --}}
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/></svg>
            ENTRADA
            {{-- Pista de lo que hace esta acción --}}
            <span class="action-hint">Registrar ingreso</span>
        </a>
        {{-- Tarjeta de acción: registrar una salida --}}
        <a href="{{ route('vigilante.salida') }}" class="action-card salida">
            {{-- Ícono de flecha hacia afuera --}}
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            SALIDA
            {{-- Pista de lo que hace esta acción --}}
            <span class="action-hint">Registrar egreso</span>
        </a>
        {{-- Tarjeta de acción: consultar historial (aún sin destino definido) --}}
        <a href="#" class="action-card historial">
            {{-- Ícono de historial con reloj --}}
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/><path d="M12 8v4l3 2"/></svg>
            HISTORIAL
            {{-- Pista de lo que hace esta acción --}}
            <span class="action-hint">Consultar movimientos</span>
        </a>
    </section>
@endsection