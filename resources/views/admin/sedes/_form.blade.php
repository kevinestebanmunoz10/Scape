@php($sede = $sede ?? null)

{{-- Cuadrícula de campos del formulario de sede (alta y edición) --}}
<div class="form-grid">
    {{-- Campo: NIT de la sede --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Nit">NIT</label>
        {{-- Entrada numérica obligatoria que conserva el valor previo --}}
        <input type="text" id="Nit" name="Nit" inputmode="numeric"
            value="{{ old('Nit', $sede?->Nit) }}" maxlength="9" required>
        {{-- Ayuda contextual sobre el formato esperado --}}
        <span class="form-hint">Identificador &uacute;nico de 9 d&iacute;gitos.</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Nit')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: nombre de la sede --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="nombre">Nombre</label>
        {{-- Entrada de texto obligatoria que conserva el valor previo --}}
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $sede?->nombre) }}" maxlength="150" required>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('nombre')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: ciudad donde está ubicada la sede --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="cod_postal">Ciudad</label>
        {{-- Selector de ciudad obligatorio --}}
        <select id="cod_postal" name="cod_postal" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona una ciudad</option>
            {{-- Recorre las ciudades disponibles y marca la correspondiente a la sede --}}
            @foreach ($ciudades as $ciudad)
                <option value="{{ $ciudad->cod_Postal }}" @selected((string) old('cod_postal', $sede?->cod_postal) === (string) $ciudad->cod_Postal)>
                    {{ $ciudad->Ciudad }} &mdash; {{ $ciudad->cod_Postal }}
                </option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('cod_postal')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: estado de la sede --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="id_Estado">Estado</label>
        {{-- Selector de estado; las sedes nuevas nacen activas si no se indica lo contrario --}}
        <select id="id_Estado" name="id_Estado">
            {{-- Opción por defecto: activa --}}
            <option value="1" @selected((string) old('id_Estado', $sede?->id_Estado ?? 1) === '1')>Activa</option>
            <option value="2" @selected((string) old('id_Estado', $sede?->id_Estado ?? 1) === '2')>Inactiva</option>
        </select>
        {{-- Ayuda sobre el significado del estado --}}
        <span class="form-hint">Las sedes inactivas no aparecen en el formulario de matr&iacute;cula.</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('id_Estado')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>
