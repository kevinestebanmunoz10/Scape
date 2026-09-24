@extends('admin.panel')

@section('title', 'Administrador - Dashboard')

@section('panel-content')
    {{-- Encabezado con filtro de fechas para el rango de datos mostrado --}}
    <div class="header-row">
        {{-- Formulario GET que envía las fechas 'desde' y 'hasta' a la ruta del dashboard --}}
        <form class="date-filter" method="GET" action="{{ route('dashboard') }}">
            {{-- Etiqueta del filtro de fechas --}}
            <label for="desde">Mostrando datos:</label>
            <div class="date-box">
                {{-- Fecha inicial; no permite seleccionar una fecha posterior a 'hasta' --}}
                <input type="date" id="desde" name="desde" value="{{ $desde->format('Y-m-d') }}" max="{{ $hasta->format('Y-m-d') }}">
                {{-- Separador visual entre las fechas --}}
                <span class="date-sep">&mdash;</span>
                {{-- Fecha final; no permite seleccionar una fecha anterior a 'desde' --}}
                <input type="date" id="hasta" name="hasta" value="{{ $hasta->format('Y-m-d') }}" min="{{ $desde->format('Y-m-d') }}">
                {{-- Botón que aplica el filtro de fechas --}}
                <button type="submit" class="date-apply">Filtrar</button>
            </div>
        </form>
    </div>

    {{-- Cuadrícula de tarjetas con las estadísticas principales --}}
    <section class="stats-grid">
        {{-- Tarjeta: usuarios activos del sistema --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Usuarios activos</h3>
            {{-- Valor principal: cantidad de usuarios activos --}}
            <div class="value">{{ $usuariosActivos }}</div>
            {{-- Subtítulo: usuarios inactivos --}}
            <div class="sub">{{ $usuariosInactivos }} inactivos</div>
        </div>
        {{-- Tarjeta: total de accesos en el rango --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Accesos</h3>
            {{-- Valor principal: accesos registrados en el rango de fechas --}}
            <div class="value">{{ $accesosRango }}</div>
            {{-- Subtítulo: desglose de entradas y salidas --}}
            <div class="sub">{{ $entradas }} entradas | {{ $salidas }} salidas</div>
        </div>
        {{-- Tarjeta: préstamos actualmente activos --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Pr&eacute;stamos activos</h3>
            {{-- Valor principal: cantidad de préstamos activos --}}
            <div class="value">{{ $prestamosActivos }}</div>
            {{-- Subtítulo: préstamos vencidos --}}
            <div class="sub">{{ $prestamosVencidos }} Vencidos</div>
        </div>
        {{-- Tarjeta: visitantes en el rango --}}
        <div class="stat-card">
            {{-- Título de la tarjeta --}}
            <h3>Visitantes</h3>
            {{-- Valor principal: visitantes registrados en el rango --}}
            <div class="value">{{ $visitantesRango }}</div>
            {{-- Subtítulo vacío para mantener la altura consistente de la tarjeta --}}
            <div class="sub">&nbsp;</div>
        </div>
    </section>

    {{-- Cuadrícula inferior con los paneles informativos --}}
    <section class="bottom-grid">
        {{-- Panel: últimos accesos registrados --}}
        <div class="panel">
            {{-- Encabezado del panel --}}
            <div class="panel-header">
                <div>
                    {{-- Título del panel --}}
                    <h2>&Uacute;ltimos accesos</h2>
                    {{-- Rango de fechas de los datos mostrados --}}
                    <span class="date-range">Del {{ $desde->format('d M, Y') }} al {{ $hasta->format('d M, Y') }}</span>
                </div>
                {{-- Enlace al historial completo (aún sin destino definido) --}}
                <div class="link">Historial completo</div>
            </div>
            {{-- Tabla con los últimos accesos --}}
            <table>
                {{-- Encabezados de columna de la tabla --}}
                <thead>
                    <tr>
                        <th>Persona</th>
                        <th>Documento</th>
                        <th>Hora</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                {{-- Cuerpo: recorre la lista de últimos accesos --}}
                <tbody>
                    @forelse ($ultimosAccesos as $acceso)
                        <tr>
                            {{-- Nombre de la persona (o guion medio si está vacío) --}}
                            <td>{{ $acceso->nombre ?? '—' }}</td>
                            {{-- Documento de la persona (o guion medio si está vacío) --}}
                            <td>{{ $acceso->documento ?? '—' }}</td>
                            {{-- Hora del acceso formateada en formato de 12 horas --}}
                            <td>{{ \Carbon\Carbon::parse($acceso->fecha)->format('h:i a') }}</td>
                            {{-- Tipo de acceso (usuario, estudiante o visitante) --}}
                            <td>{{ $acceso->tipo }}</td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no existen accesos en el rango --}}
                        <tr>
                            <td colspan="4">No hay accesos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Panel: préstamos pendientes de devolución --}}
        <div class="panel">
            {{-- Encabezado del panel --}}
            <div class="panel-header">
                <h2>Pr&eacute;stamos por devolver</h2>
            </div>
            {{-- Lista de préstamos pendientes --}}
            <div class="prestamos-list">
                @forelse ($prestamosPendientes as $prestamo)
                    {{-- Bloque PHP: determina si la fecha tope del préstamo ya venció --}}
                    @php
                        $vencido = $prestamo->f_Prestamo < today();
                    @endphp
                    {{-- Cada préstamo pendiente con su descripción y estado visual --}}
                    <div class="prestamo-item">
                        {{-- Nombre descriptivo del equipo prestado (tipo, marca y serial) --}}
                        <span>{{ $prestamo->equipo?->tipo?->tipo ?? 'Equipo' }} {{ $prestamo->equipo?->marca?->marca ?? '' }} - {{ $prestamo->equipo?->serial_equi ?? 'SN' }}</span>
                        {{-- Ícono de estado: rojo si está vencido, verde si está al día --}}
                        <span class="status-icon {{ $vencido ? 'status-bad' : 'status-ok' }}">
                            @if ($vencido)
                                {{-- Ícono de "equis" para préstamo vencido --}}
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            @else
                                {{-- Ícono de "check" para préstamo en plazo --}}
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            @endif
                        </span>
                    </div>
                @empty
                    {{-- Mensaje cuando no hay préstamos pendientes de devolución --}}
                    <div class="prestamo-item">
                        <span>No hay pr&eacute;stamos por devolver</span>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection