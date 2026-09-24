@extends('profesor.panel')

@section('title', 'Profesor - Exportar')

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

    {{-- Panel del módulo de exportación --}}
    <div class="panel">
        {{-- Encabezado del panel --}}
        <div class="panel-header">
            <div>
                {{-- Título del panel --}}
                <h2>Exportar</h2>
                {{-- Descripción de uso del módulo --}}
                <span class="date-range">Exporte las asistencias, inasistencias y justificantes en PDF o Excel.</span>
            </div>
        </div>
        {{-- Aviso de módulo en construcción --}}
        <p class="panel-empty">El módulo de exportación estará disponible próximamente.</p>
    </div>
@endsection