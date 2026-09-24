@extends('admin.panel')

@section('title', 'Administrador - Catálogos de equipos')

@section('panel-content')
    {{-- Encabezado de la página con título, subtítulo y acción de regreso --}}
    <div class="page-header">
        <div>
            {{-- Título principal de la página --}}
            <h1>Cat&aacute;logos de equipos</h1>
            {{-- Subtítulo descriptivo --}}
            <p class="subtitle">Administra las marcas y los tipos de equipo disponibles.</p>
        </div>
        <div class="page-header-actions">
            {{-- Enlace para regresar al listado de equipos --}}
            <a href="{{ route('admin.equipos.index') }}" class="btn btn-ghost">Volver a equipos</a>
        </div>
    </div>

    {{-- Incluye los mensajes flash (éxito/error) si los hay --}}
    @include('partials.alertas')

    {{-- Cuadrícula que coloca los catálogos de marcas y tipos lado a lado --}}
    <div class="catalogos-grid">
        {{-- Panel de marcas --}}
        <div class="panel">
            {{-- Título del catálogo de marcas --}}
            <h2 class="catalogos-title">Marcas</h2>
            {{-- Formulario para registrar una nueva marca --}}
            <form method="POST" action="{{ route('admin.equipos.catalogos.marcas.store') }}" class="catalogos-form">
                {{-- Token CSRF de Laravel --}}
                @csrf
                {{-- Campo de texto con el nombre de la marca --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="marca">Nombre de la marca</label>
                    {{-- Entrada del nombre de la marca --}}
                    <input type="text" id="marca" name="marca" value="{{ old('marca') }}" required maxlength="50" placeholder="Ej. HP">
                    {{-- Muestra el error de validación del campo si existe --}}
                    @error('marca')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                {{-- Botón que envía el formulario de la marca --}}
                <button type="submit" class="btn btn-primary">Agregar marca</button>
            </form>
            {{-- Contenedor de la tabla de marcas --}}
            <div class="table-wrap">
                <table>
                    {{-- Encabezados de columna --}}
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th class="cell-actions">Acciones</th>
                        </tr>
                    </thead>
                    {{-- Cuerpo: recorre la lista de marcas --}}
                    <tbody>
                        @forelse ($marcas as $marca)
                            <tr>
                                {{-- Nombre de la marca --}}
                                <td>{{ $marca->marca }}</td>
                                <td class="cell-actions">
                                    {{-- Formulario que elimina la marca con confirmación previa --}}
                                    <form method="POST" action="{{ route('admin.equipos.catalogos.marcas.destroy', $marca) }}" onsubmit="return confirm('&iquest;Seguro que deseas eliminar esta marca?');">
                                        {{-- Token CSRF de Laravel --}}
                                        @csrf
                                        {{-- Método HTTP DELETE simulado --}}
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn danger" title="Eliminar marca">
                                            {{-- Ícono de papelera --}}
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            {{-- Mensaje cuando no hay marcas registradas --}}
                            <tr>
                                <td colspan="2" class="empty-cell">No hay marcas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Panel de tipos de equipo --}}
        <div class="panel">
            {{-- Título del catálogo de tipos de equipo --}}
            <h2 class="catalogos-title">Tipos de equipo</h2>
            {{-- Formulario para registrar un nuevo tipo de equipo --}}
            <form method="POST" action="{{ route('admin.equipos.catalogos.tipos.store') }}" class="catalogos-form">
                {{-- Token CSRF de Laravel --}}
                @csrf
                {{-- Campo de texto con el nombre del tipo --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="tipo">Nombre del tipo</label>
                    {{-- Entrada del nombre del tipo de equipo --}}
                    <input type="text" id="tipo" name="tipo" value="{{ old('tipo') }}" required maxlength="50" placeholder="Ej. Port&aacute;til">
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
                                {{-- Nombre del tipo de equipo --}}
                                <td>{{ $tipo->tipo }}</td>
                                <td class="cell-actions">
                                    {{-- Formulario que elimina el tipo con confirmación previa --}}
                                    <form method="POST" action="{{ route('admin.equipos.catalogos.tipos.destroy', $tipo) }}" onsubmit="return confirm('&iquest;Seguro que deseas eliminar este tipo?');">
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
                                <td colspan="2" class="empty-cell">No hay tipos de equipo registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection