@extends('admin.panel')

@section('title', 'Administrador - Detalle de sede')

@section('panel-content')
    {{-- Encabezado de la página con el nombre de la sede y acciones --}}
    <div class="page-header">
        <div>
            {{-- Nombre de la sede --}}
            <h1>{{ $sede->nombre }}</h1>
            {{-- Subtítulo con el NIT de la sede --}}
            <p class="subtitle">NIT {{ $sede->Nit }}</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para editar la sede --}}
            <a href="{{ route('admin.sedes.edit', $sede) }}" class="btn btn-primary">Editar</a>
            {{-- Enlace para regresar al listado --}}
            <a href="{{ route('admin.sedes.index') }}" class="btn btn-ghost">Volver</a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel principal que resume los datos de la sede --}}
    <div class="panel form-panel">
        <div class="prestamos-list">
            {{-- Fila: NIT --}}
            <div class="prestamo-item">
                <span>NIT</span>
                {{-- NIT de la sede --}}
                <span class="profile-value">{{ $sede->Nit }}</span>
            </div>
            {{-- Fila: nombre --}}
            <div class="prestamo-item">
                <span>Nombre</span>
                {{-- Nombre de la sede --}}
                <span class="profile-value">{{ $sede->nombre }}</span>
            </div>
            {{-- Fila: ciudad --}}
            <div class="prestamo-item">
                <span>Ciudad</span>
                {{-- Nombre y código postal de la ciudad (o guion medio si no tiene) --}}
                <span class="profile-value">{{ $sede->ciudad ? $sede->ciudad->Ciudad.' — '.$sede->ciudad->cod_Postal : '—' }}</span>
            </div>
            {{-- Fila: estado --}}
            <div class="prestamo-item">
                <span>Estado</span>
                {{-- Insignia que refleja si la sede está activa o inactiva --}}
                @if ($sede->esta_activa)
                    <span class="badge badge-active">Activa</span>
                @else
                    <span class="badge badge-inactive">Inactiva</span>
                @endif
            </div>
            {{-- Fila: total de matrículas --}}
            <div class="prestamo-item">
                <span>Matr&iacute;culas</span>
                {{-- Cantidad de matrículas registradas en la sede --}}
                <span class="profile-value">{{ $sede->matriculas->count() }}</span>
            </div>
        </div>

        {{-- Si la sede está activa, ofrece desactivarla; si no, reactivarla --}}
        @if ($sede->esta_activa)
            {{-- Formulario que desactiva la sede con confirmación previa --}}
            <form method="POST" action="{{ route('admin.sedes.destroy', $sede) }}"
                class="delete-block" onsubmit="return confirm('&iquest;Seguro que deseas desactivar esta sede?');">
                {{-- Token CSRF de Laravel --}}
                @csrf
                {{-- Método HTTP DELETE simulado --}}
                @method('DELETE')
                {{-- Botón para desactivar la sede --}}
                <button type="submit" class="btn btn-danger">Desactivar sede</button>
            </form>
        @else
            {{-- Formulario que reactiva la sede inactiva --}}
            <form method="POST" action="{{ route('admin.sedes.activar', $sede) }}" class="delete-block">
                {{-- Token CSRF de Laravel --}}
                @csrf
                {{-- Botón para activar la sede --}}
                <button type="submit" class="btn btn-primary">Activar sede</button>
            </form>
        @endif
    </div>

    {{-- Panel con el detalle de las matrículas realizadas en la sede --}}
    <div class="panel">
        <div class="panel-header">
            <h2>Matr&iacute;culas de la sede</h2>
        </div>
        <div class="table-wrap">
            <table>
                {{-- Encabezados de columna --}}
                <thead>
                    <tr>
                        <th>N&uacute;mero</th>
                        <th>Estudiante</th>
                        <th>Grado</th>
                        <th>Jornada</th>
                        <th>Sal&oacute;n</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                {{-- Cuerpo: recorre la lista de matrículas de la sede --}}
                <tbody>
                    @forelse ($sede->matriculas->sortByDesc('numero_matri') as $matricula)
                        <tr>
                            {{-- Número de la matrícula --}}
                            <td>{{ $matricula->numero_matri }}</td>
                            {{-- Estudiante matriculado (o guion medio si no tiene) --}}
                            <td>{{ $matricula->estudiante?->Nom_usua ?? '—' }}</td>
                            {{-- Grado del estudiante --}}
                            <td>{{ $matricula->grado }}</td>
                            {{-- Jornada escolar (o guion medio si no tiene) --}}
                            <td>{{ $matricula->Jornada ?? '—' }}</td>
                            {{-- Salón asignado (o guion medio si no tiene) --}}
                            <td>{{ $matricula->Salon ?? '—' }}</td>
                            {{-- Fecha de la matrícula --}}
                            <td>{{ $matricula->fecha_matri?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando la sede no tiene matrículas registradas --}}
                        <tr>
                            <td colspan="6" class="empty-cell">
                                Esta sede todav&iacute;a no tiene matr&iacute;culas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
