@extends('admin.panel')

@section('title', 'Administrador - Tipos de permiso')

@section('panel-content')
    {{-- Encabezado de la página con título, subtítulo y acción de regreso --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Tipos de permiso</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Administra los tipos de permiso de salida disponibles.</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para regresar al registro de permisos --}}
            <a href="{{ route('admin.permisos') }}" class="btn btn-ghost">Volver a permisos</a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Panel con el catálogo de tipos de permiso --}}
    <div class="panel">
        {{-- Título del catálogo --}}
        <h2 class="catalogos-title">Cat&aacute;logo de tipos</h2>
        {{-- Formulario para registrar un nuevo tipo de permiso --}}
        <form method="POST" action="{{ route('admin.permisos.tipos.store') }}" class="catalogos-form">
            {{-- Token CSRF de Laravel --}}
            @csrf
            {{-- Campo de texto con el nombre del tipo --}}
            <div class="form-field">
                {{-- Etiqueta del campo --}}
                <label for="tipo">Nombre del tipo</label>
                {{-- Entrada del nombre del tipo de permiso --}}
                <input type="text" id="tipo" name="tipo" value="{{ old('tipo') }}" required maxlength="50" placeholder="Ej. Médico">
                {{-- Muestra el error de validación del campo si existe --}}
                @error('tipo')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            {{-- Botón que envía el formulario del tipo --}}
            <button type="submit" class="btn btn-primary">Agregar tipo</button>
        </form>
        {{-- Contenedor de la tabla de tipos --}}
        <div class="table-wrap">
            <table>
                {{-- Encabezados de columna --}}
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th class="cell-actions">Acciones</th>
                    </tr>
                </thead>
                {{-- Cuerpo: recorre la lista de tipos --}}
                <tbody>
                    @forelse ($tipos as $tipo)
                        <tr>
                            {{-- Nombre del tipo de permiso --}}
                            <td>{{ $tipo->tipo }}</td>
                            <td class="cell-actions">
                                {{-- Formulario que elimina el tipo con confirmación previa --}}
                                <form method="POST" action="{{ route('admin.permisos.tipos.destroy', $tipo) }}" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este tipo?');">
                                    {{-- Token CSRF de Laravel --}}
                                    @csrf
                                    {{-- Método HTTP DELETE simulado --}}
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn danger" title="Eliminar tipo">
                                        {{-- Ícono de papelera --}}
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- Mensaje cuando no hay tipos registrados --}}
                        <tr>
                            <td colspan="2" class="empty-cell">No hay tipos de permiso registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection