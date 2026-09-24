{{-- Formulario reutilizable para registrar o editar un permiso de salida --}}

{{-- Cuadrícula de campos del formulario --}}
<div class="form-grid">
    {{-- Campo: estudiante (selector con búsqueda por nombre o documento) --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Documento_estu">Estudiante</label>
        {{-- Campo de texto que filtra los estudiantes de la lista desplegable --}}
        <input type="search" id="estudianteBusqueda" class="select-search" placeholder="Filtrar por nombre o documento..." autocomplete="off">
        {{-- Selector de estudiantes matriculados --}}
        <select id="Documento_estu" name="Documento_estu" class="select-filter" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un estudiante</option>
            {{-- Recorre los estudiantes matriculados y marca el seleccionado --}}
            @forelse ($estudiantes as $estudiante)
                <option value="{{ $estudiante->Documento }}" @selected((string) $documentoEstu === (string) $estudiante->Documento)>
                    {{-- Nombre y documento de cada opción --}}
                    {{ $estudiante->Nom_usua }} &mdash; {{ $estudiante->Documento }}
                </option>
            @empty
                {{-- Mensaje cuando no hay estudiantes matriculados --}}
                <option value="" disabled>No hay estudiantes matriculados</option>
            @endforelse
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Documento_estu')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: acudiente (selector con búsqueda por nombre o documento) --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Documento_acud">Acudiente que solicita el permiso</label>
        {{-- Campo de texto que filtra los acudientes de la lista desplegable --}}
        <input type="search" id="acudienteBusqueda" class="select-search" placeholder="Filtrar por nombre o documento..." autocomplete="off">
        {{-- Selector de acudientes registrados --}}
        <select id="Documento_acud" name="Documento_acud" class="select-filter" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un acudiente</option>
            {{-- Opción informativa cuando el estudiante no tiene acudiente relacionado --}}
            <option value="" class="acudiente-sin-relacion" disabled hidden>Este estudiante no tiene acudientes registrados</option>
            {{-- Recorre los acudientes y marca el seleccionado --}}
            @forelse ($acudientes as $acudiente)
                <option value="{{ $acudiente->Documento_acud }}"
                    @selected((string) $documentoAcud === (string) $acudiente->Documento_acud)
                    data-estudiantes="{{ implode(' ', $acudienteEstudiantes->get($acudiente->Documento_acud, [])) }}">
                    {{-- Nombre y documento de cada opción --}}
                    {{ $acudiente->nombre }} &mdash; {{ $acudiente->Documento_acud }}
                </option>
            @empty
                {{-- Mensaje cuando no hay acudientes registrados --}}
                <option value="" disabled>No hay acudientes registrados</option>
            @endforelse
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Documento_acud')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: tipo de permiso --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="id_tipo_permiso">Tipo de permiso</label>
        {{-- Selector de tipo de permiso obligatorio --}}
        <select id="id_tipo_permiso" name="id_tipo_permiso" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un tipo</option>
            {{-- Recorre los tipos disponibles y marca el seleccionado --}}
            @foreach ($tipos as $tipo)
                <option value="{{ $tipo->id_tipo_permiso }}" @selected((string) $idTipo === (string) $tipo->id_tipo_permiso)>{{ $tipo->tipo }}</option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('id_tipo_permiso')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: descripción del permiso (ancho completo) --}}
    <div class="form-field full">
        {{-- Etiqueta del campo --}}
        <label for="Descripcion">Descripci&oacute;n del permiso</label>
        {{-- Área de texto con la descripción del permiso --}}
        <textarea id="Descripcion" name="Descripcion" rows="4" maxlength="2000" placeholder="Describe el motivo del permiso de salida..." required>{{ $descripcion }}</textarea>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Descripcion')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            // Referencia a los selectores de estudiante y acudiente
            var estudianteSelect = document.getElementById('Documento_estu');
            var acudienteSelect = document.getElementById('Documento_acud');

            // Ubica el campo de búsqueda del acudiente dentro del mismo bloque
            var acudienteField = acudienteSelect && acudienteSelect.closest('.form-field');
            var acudienteBusqueda = acudienteField && acudienteField.querySelector('input.select-search');

            // Opción informativa cuando el estudiante no tiene acudientes relacionados
            var sinRelacion = acudienteSelect && acudienteSelect.querySelector('option.acudiente-sin-relacion');

            // Filtra los acudientes según el estudiante seleccionado y el texto buscado
            function filtrarAcudientes() {
                if (!acudienteSelect) {
                    return;
                }

                // Documento del estudiante elegido (vacío si aún no se elige)
                var estudiante = estudianteSelect ? String(estudianteSelect.value) : '';
                // Término de búsqueda normalizado
                var query = acudienteBusqueda ? acudienteBusqueda.value.trim().toLowerCase() : '';
                // Cantidad de opciones visibles
                var visibles = 0;

                // Recorre todas las opciones del selector de acudiente
                Array.prototype.forEach.call(acudienteSelect.options, function(option) {
                    // La opción informativa se controla al final
                    if (option.classList.contains('acudiente-sin-relacion')) {
                        return;
                    }

                    // La opción vacía o deshabilitada siempre permanece visible
                    if (option.value === '' || option.disabled) {
                        option.hidden = false;
                        return;
                    }

                    // Documentos de los estudiantes vinculados a este acudiente
                    var estudiantes = (option.dataset.estudiantes || '').split(' ').filter(Boolean);
                    // Solo es visible si el estudiante elegido está relacionado con este acudiente
                    var coincideEstudiante = estudiantes.indexOf(estudiante) !== -1;
                    // Solo es visible si también coincide con el texto buscado
                    var coincideTexto = query === '' || option.text.toLowerCase().indexOf(query) !== -1;

                    option.hidden = !(coincideEstudiante && coincideTexto);

                    if (!option.hidden) {
                        visibles++;
                    }
                });

                // Muestra el aviso si el estudiante no tiene acudientes relacionados
                if (sinRelacion) {
                    sinRelacion.hidden = visibles > 0;
                }

                // Reinicia la selección si el acudiente elegido quedó oculto
                var seleccionado = acudienteSelect.options[acudienteSelect.selectedIndex];
                if (seleccionado && seleccionado.hidden) {
                    acudienteSelect.value = '';
                }
            }

            // Recorre cada selector con filtro de la página
            document.querySelectorAll('select.select-filter').forEach(function(select) {
                // Ubica el campo de búsqueda dentro del mismo bloque del selector
                var field = select.closest('.form-field');
                var input = field && field.querySelector('input.select-search');

                // Si falta alguno de los elementos, se ignora el bloque
                if (!input || !select) {
                    return;
                }

                // Escucha los cambios de texto en el campo de búsqueda
                input.addEventListener('input', function() {
                    // El acudiente usa el filtro integrado con el estudiante
                    if (select === acudienteSelect) {
                        filtrarAcudientes();
                        return;
                    }

                    // Normaliza la búsqueda en minúsculas y sin espacios al inicio/final
                    var query = input.value.trim().toLowerCase();

                    // Recorre todas las opciones del selector
                    Array.prototype.forEach.call(select.options, function(option) {
                        // La opción vacía o deshabilitada siempre permanece visible
                        if (option.value === '' || option.disabled) {
                            option.hidden = false;
                            return;
                        }

                        // Oculta la opción si no coincide con el texto buscado
                        option.hidden = query !== '' && option.text.toLowerCase().indexOf(query) === -1;
                    });
                });
            });

            // Al elegir un estudiante se actualizan los acudientes relacionados
            if (estudianteSelect) {
                estudianteSelect.addEventListener('change', filtrarAcudientes);
            }

            // Aplica el filtro al cargar (por ejemplo en la edición de un permiso)
            filtrarAcudientes();
        })();
    </script>
@endpush