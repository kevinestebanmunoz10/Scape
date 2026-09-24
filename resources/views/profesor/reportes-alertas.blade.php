@extends('profesor.panel')

@section('title', 'Profesor - Reportes y alertas')

@section('panel-content')
    {{-- Encabezado con la fecha actual --}}
    <div class="header-row">
        <div class="date-filter">
            Showing data:
            <div class="date-box">
                {{-- Muestra la fecha actual --}}
                {{ now()->format('d M, Y') }}
                {{-- Ícono decorativo de calendario --}}
                <svg viewBox="0 0 24 24" fill="none" stroke="#e5e9f0" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
            </div>
        </div>
    </div>

    {{-- Panel del módulo de reportes y alertas --}}
    <div class="panel">
        {{-- Encabezado del panel --}}
        <div class="panel-header">
            <div>
                {{-- Título del panel --}}
                <h2>Reportes y alertas</h2>
                {{-- Descripción de uso del módulo --}}
                <span class="date-range">Consulte reportes de asistencia y las alertas generadas por sus grupos.</span>
            </div>
        </div>
        {{-- Aviso de módulo en construcción --}}
        <p class="panel-empty">El módulo de reportes y alertas estará disponible próximamente.</p>
    </div>
@endsection