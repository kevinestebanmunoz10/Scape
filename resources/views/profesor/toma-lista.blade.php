@extends('profesor.panel')

@section('title', 'Profesor - Toma de lista')

@section('panel-content')
    <div class="header-row">
        <div class="date-filter">
            Showing data:
            <div class="date-box">
                {{ now()->format('d M, Y') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="#e5e9f0" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div>
                <h2>Toma de lista</h2>
                <span class="date-range">Seleccione una ficha y un grupo para registrar la asistencia del día.</span>
            </div>
        </div>
        <p class="panel-empty">El módulo de toma de lista estará disponible próximamente.</p>
    </div>
@endsection