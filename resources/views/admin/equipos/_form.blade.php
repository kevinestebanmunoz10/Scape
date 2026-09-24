@php($equipo = $equipo ?? null)

{{-- Cuadrícula de campos del formulario de equipo (alta y edición) --}}
<div class="form-grid">
    {{-- Campo: serial del equipo --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="serial_equi">Serial</label>
        {{-- En edición el serial está deshabilitado; en alta es obligatorio y conserva el valor previo --}}
        <input type="text" id="serial_equi" name="serial_equi"
            value="{{ old('serial_equi', $equipo?->serial_equi) }}" maxlength="50" @disabled($equipo) @required(! $equipo)>
        {{-- Ayuda contextual: indica si el serial es modificable --}}
        <span class="form-hint">{{ $equipo ? 'El serial no se puede modificar.' : 'Identificador &uacute;nico del equipo.' }}</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('serial_equi')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: tipo de equipo --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="id_t_equip">Tipo de equipo</label>
        {{-- Selector de tipo obligatorio --}}
        <select id="id_t_equip" name="id_t_equip" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un tipo</option>
            {{-- Recorre los tipos disponibles y marca el correspondiente al equipo --}}
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo->id_t_equip }}" @selected((string) old('id_t_equip', $equipo?->id_t_equip) === (string) $tipo->id_t_equip)>{{ $tipo->tipo }}</option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('id_t_equip')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: marca del equipo --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="id_Marca">Marca</label>
        {{-- Selector de marca obligatorio --}}
        <select id="id_Marca" name="id_Marca" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona una marca</option>
            {{-- Recorre las marcas disponibles y marca la correspondiente al equipo --}}
            @foreach ($marcas as $marca)
                <option value="{{ $marca->id_marca }}" @selected((string) old('id_Marca', $equipo?->id_Marca) === (string) $marca->id_marca)>{{ $marca->marca }}</option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('id_Marca')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: color del equipo --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Color">Color</label>
        {{-- Entrada opcional que conserva el valor previo --}}
        <input type="text" id="Color" name="Color" value="{{ old('Color', $equipo?->Color) }}" maxlength="30">
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Color')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: imagen del equipo (archivo) --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="imagen">Imagen</label>
        {{-- Selector de archivos que solo acepta imagen JPG, PNG o WEBP --}}
        <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/webp">
        {{-- Ayuda sobre formatos y tamaño permitido --}}
        <span class="form-hint">Opcional. JPG, PNG o WEBP de hasta 2&nbsp;MB.</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('imagen')<span class="form-error">{{ $message }}</span>@enderror
        {{-- Si el equipo ya tiene imagen, la muestra como vista previa --}}
        @if ($equipo?->imagen)
            {{-- Construye la URL de la imagen: externa o desde el storage local --}}
            <img src="{{ str_starts_with($equipo->imagen, 'http') ? $equipo->imagen : asset('storage/'.$equipo->imagen) }}"
                alt="Imagen del equipo" class="equipo-img-preview">
        @endif
    </div>

    {{-- Campo: usuario asignado (ocupa el ancho completo) --}}
    <div class="form-field full">
        {{-- Etiqueta del campo --}}
        <label for="Documento">Usuario asignado</label>
        {{-- Campo de texto que filtra los usuarios de la lista desplegable --}}
        <input type="text" id="usuarioBusqueda" class="select-search" placeholder="Filtrar usuario por nombre o documento..." autocomplete="off">
        {{-- Selector del usuario asignado al equipo --}}
        <select id="Documento" name="Documento" class="select-filter">
            {{-- Opción por defecto: sin asignar --}}
            <option value="">Sin asignar</option>
            {{-- Recorre los usuarios y marca el asignado al equipo --}}
            @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->Documento }}" @selected((string) old('Documento', $equipo?->Documento) === (string) $usuario->Documento)>
                    {{-- Nombre y documento de cada opción --}}
                    {{ $usuario->Nom_usua }} &mdash; {{ $usuario->Documento }}
                </option>
            @endforeach
        </select>
        {{-- Ayuda sobre la asignación opcional --}}
        <span class="form-hint">Opcional. Usuario al que se le asigna este equipo.</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Documento')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>

@push('scripts')
    {{-- Script que permite filtrar los usuarios del selector escribiendo en el campo de búsqueda --}}
    <script>
        (function() {
            // Referencia al campo de búsqueda de usuarios
            var input = document.getElementById('usuarioBusqueda');
            // Referencia al selector de usuarios
            var select = document.getElementById('Documento');

            // Si falta alguno de los elementos, no hace nada
            if (!input || !select) {
                return;
            }

            // Escucha los cambios de texto en el campo de búsqueda
            input.addEventListener('input', function() {
                // Normaliza la búsqueda en minúsculas y sin espacios al inicio/final
                var query = input.value.trim().toLowerCase();

                // Recorre todas las opciones del selector
                Array.prototype.forEach.call(select.options, function(option) {
                    // La opción vacía ("Sin asignar") siempre permanece visible
                    if (option.value === '') {
                        option.hidden = false;
                        return;
                    }

                    // Oculta la opción si no coincide con el texto buscado
                    option.hidden = query !== '' && option.text.toLowerCase().indexOf(query) === -1;
                });
            });
        })();
    </script>
@endpush