<div class="form-grid">
    {{-- Campo: estudiante que se va a matricular --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Documento_matri">Estudiante</label>
        {{-- Selector con los estudiantes que aún no tienen matrícula --}}
        <select id="Documento_matri" name="Documento_matri" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un estudiante</option>
            {{-- Recorre los estudiantes disponibles y marca el seleccionado previamente --}}
            @forelse ($estudiantes as $estudiante)
                <option value="{{ $estudiante->Documento }}" @selected((string) old('Documento_matri') === (string) $estudiante->Documento)>
                    {{ $estudiante->Nom_usua }} &mdash; {{ $estudiante->Documento }}
                </option>
            @empty
                {{-- Mensaje cuando no hay estudiantes disponibles --}}
                <option value="" disabled>No hay estudiantes sin matrícula</option>
            @endforelse
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Documento_matri')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: grado del estudiante --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="grado">Grado</label>
        {{-- Entrada de texto obligatoria que conserva el valor previo --}}
        <input type="text" id="grado" name="grado" value="{{ old('grado') }}" maxlength="20" required>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('grado')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: fecha de la matrícula --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="fecha_matri">Fecha de matr&iacute;cula</label>
        {{-- Selector de fecha obligatorio que conserva el valor previo --}}
        <input type="date" id="fecha_matri" name="fecha_matri" value="{{ old('fecha_matri', now()->toDateString()) }}" required>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('fecha_matri')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: jornada escolar (solo se puede elegir del catálogo de jornadas) --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Jornada">Jornada</label>
        {{-- Selector con las jornadas registradas en la tabla jornada; no permite escribir un valor libre --}}
        <select id="Jornada" name="Jornada">
            {{-- Opción por defecto --}}
            <option value="">Sin definir</option>
            {{-- Recorre las jornadas disponibles y marca la seleccionada previamente --}}
            @forelse ($jornadas as $jornada)
                <option value="{{ $jornada->jornada }}" @selected(old('Jornada') === $jornada->jornada)>{{ $jornada->jornada }}</option>
            @empty
                {{-- Mensaje cuando el catálogo de jornadas está vacío --}}
                <option value="" disabled>No hay jornadas registradas</option>
            @endforelse
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Jornada')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: salón asignado (solo se puede elegir del catálogo de salones) --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Salon">Sal&oacute;n</label>
        {{-- Selector con los salones registrados en la tabla salones; no permite escribir un código libre --}}
        <select id="Salon" name="Salon" required>
            {{-- Opción por defecto --}}
            <option value="">Selecciona un salón</option>
            {{-- Recorre los salones disponibles y marca el seleccionado previamente --}}
            @forelse ($salones as $salon)
                <option value="{{ $salon->codigo }}" @selected(old('Salon') === $salon->codigo)>
                    {{ $salon->codigo }} &mdash; capacidad {{ $salon->capacidad }}
                </option>
            @empty
                {{-- Mensaje cuando el catálogo de salones está vacío --}}
                <option value="" disabled>No hay salones registrados</option>
            @endforelse
        </select>
        {{-- Ayuda: el salón no se escribe, se elige del catálogo --}}
        <span class="form-hint">El sal&oacute;n se elige del cat&aacute;logo, no se escribe libremente.</span>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Salon')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    {{-- Campo: sede donde se realiza la matrícula --}}
    <div class="form-field">
        {{-- Etiqueta del campo --}}
        <label for="Nit">Sede</label>
        {{-- Selector de sede opcional que conserva el valor previo --}}
        <select id="Nit" name="Nit">
            {{-- Opción por defecto --}}
            <option value="">Sin definir</option>
            {{-- Recorre las sedes disponibles y marca la seleccionada previamente --}}
            @foreach ($sedes as $sede)
                <option value="{{ $sede->Nit }}" @selected((string) old('Nit') === (string) $sede->Nit)>{{ $sede->nombre }}</option>
            @endforeach
        </select>
        {{-- Muestra el error de validación del campo si existe --}}
        @error('Nit')<span class="form-error">{{ $message }}</span>@enderror
    </div>
</div>

@php
    // Filas de acudientes del catálogo a mostrar: recupera las del reenvío o deja una vacía para escribir
    $filasExistentes = old('acudientes_existentes', []);
    $filasExistentes = count($filasExistentes) > 0 ? $filasExistentes : [null];
    // Filas de acudientes nuevos a mostrar: recupera las del reenvío o deja una vacía para escribir
    $filasNuevas = old('nuevos_acudientes', []);
    $filasNuevas = count($filasNuevas) > 0 ? $filasNuevas : [null];
    // Errores agrupados de cada bloque para poder mostrarlos juntos
    $erroresExistentes = $errors->getBag('acudientes_existentes')->all();
    $erroresNuevos = $errors->getBag('nuevos_acudientes')->all();
@endphp

<div class="form-section">
    {{-- Título de la sección de acudientes --}}
    <h2 class="form-section-title">Acudientes del estudiante</h2>
    {{-- Explicación de las dos opciones disponibles --}}
    <p class="form-section-text">
        Puedes agregar un acudiente que ya est&eacute; en el cat&aacute;logo o registrar uno nuevo.
        El parentesco se elige de la lista de la tabla <strong>parentesco</strong>.
    </p>

    {{-- Lista de los acudientes que el estudiante seleccionado ya tenía vinculados --}}
    <ul class="acudientes-vinculados" id="acudientesVinculados" hidden></ul>
    {{-- Aviso que se muestra cuando el estudiante no tiene acudientes previos --}}
    <p class="acudientes-vacio" id="acudientesVacio" hidden>
        Este estudiante a&uacute;n no tiene acudientes vinculados.
    </p>

    {{-- Bloque para agregar acudientes que ya están en el catálogo --}}
    <h3 class="acudientes-bloque-title">Agregar acudiente existente</h3>
    <div class="acudientes-rows" id="acudientesExistentesRows">
        {{-- Recorre las filas a mostrar, ya sean recuperadas del reenvío o la fila vacía inicial --}}
        @foreach ($filasExistentes as $indice => $fila)
            <div class="acudientes-row">
                {{-- Selector del acudiente tomado del catálogo compartido --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="acudienteExistente{{ $indice }}">Acudiente</label>
                    {{-- Lista el catálogo de acudientes ya registrados en el sistema --}}
                    <select id="acudienteExistente{{ $indice }}" name="acudientes_existentes[{{ $indice }}][documento]">
                        {{-- Opción por defecto --}}
                        <option value="">Selecciona un acudiente</option>
                        {{-- Recorre el catálogo de acudientes y marca el seleccionado previamente --}}
                        @forelse ($acudientes as $acudiente)
                            <option value="{{ $acudiente->Documento_acud }}" @selected((string) old("acudientes_existentes.{$indice}.documento") === (string) $acudiente->Documento_acud)>
                                {{ $acudiente->nombre }} &mdash; {{ $acudiente->Documento_acud }}
                            </option>
                        @empty
                            {{-- Mensaje cuando el catálogo de acudientes está vacío --}}
                            <option value="" disabled>No hay acudientes registrados</option>
                        @endforelse
                    </select>
                </div>

                {{-- Selector del parentesco tomado de la tabla parentesco --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="parentescoExistente{{ $indice }}">Parentesco</label>
                    {{-- Lista los parentescos activos del catálogo --}}
                    <select id="parentescoExistente{{ $indice }}" name="acudientes_existentes[{{ $indice }}][parentesco]">
                        {{-- Opción por defecto --}}
                        <option value="">Selecciona un parentesco</option>
                        {{-- Recorre los parentescos disponibles y marca el seleccionado previamente --}}
                        @forelse ($parentescos as $parentesco)
                            <option value="{{ $parentesco->id_parentesco }}" @selected((string) old("acudientes_existentes.{$indice}.parentesco") === (string) $parentesco->id_parentesco)>
                                {{ $parentesco->parentesco }}
                            </option>
                        @empty
                            {{-- Mensaje cuando el catálogo de parentescos está vacío --}}
                            <option value="" disabled>No hay parentescos registrados</option>
                        @endforelse
                    </select>
                </div>

                {{-- Botón que elimina la fila --}}
                <button type="button" class="acudientes-remove" data-acudientes-quitar>Quitar</button>
            </div>
        @endforeach
    </div>
    {{-- Muestra los errores de validación del bloque de acudientes existentes --}}
    @foreach ($erroresExistentes as $error)
        <span class="form-error">{{ $error }}</span>
    @endforeach
    {{-- Botón que agrega otra fila de acudiente existente --}}
    <div class="acudientes-actions">
        <button type="button" class="btn btn-ghost" data-acudientes-agregar="acudientesExistentesRows">Agregar otro acudiente</button>
    </div>

    {{-- Bloque para registrar un acudiente que aún no existe en el catálogo --}}
    <h3 class="acudientes-bloque-title">Crear nuevo acudiente</h3>
    <div class="acudientes-rows" id="acudientesNuevosRows">
        {{-- Recorre las filas a mostrar, ya sean recuperadas del reenvío o la fila vacía inicial --}}
        @foreach ($filasNuevas as $indice => $fila)
            <div class="acudientes-row nuevo">
                {{-- Documento de identidad del nuevo acudiente --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="nuevoDocumento{{ $indice }}">Documento</label>
                    {{-- Entrada de texto obligatoria que conserva el valor previo --}}
                    <input type="text" id="nuevoDocumento{{ $indice }}" name="nuevos_acudientes[{{ $indice }}][documento]" value="{{ old("nuevos_acudientes.{$indice}.documento") }}" maxlength="20" inputmode="numeric">
                </div>

                {{-- Nombre completo del nuevo acudiente --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="nuevoNombre{{ $indice }}">Nombre completo</label>
                    {{-- Entrada de texto obligatoria que conserva el valor previo --}}
                    <input type="text" id="nuevoNombre{{ $indice }}" name="nuevos_acudientes[{{ $indice }}][nombre]" value="{{ old("nuevos_acudientes.{$indice}.nombre") }}" maxlength="100">
                </div>

                {{-- Teléfono principal del nuevo acudiente --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="nuevoTelefono{{ $indice }}">Tel&eacute;fono</label>
                    {{-- Entrada de texto obligatoria que conserva el valor previo --}}
                    <input type="text" id="nuevoTelefono{{ $indice }}" name="nuevos_acudientes[{{ $indice }}][telefono]" value="{{ old("nuevos_acudientes.{$indice}.telefono") }}" maxlength="20">
                </div>

                {{-- Teléfono alterno del nuevo acudiente --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="nuevoTelefonoAlt{{ $indice }}">Tel&eacute;fono alterno</label>
                    {{-- Entrada de texto opcional que conserva el valor previo --}}
                    <input type="text" id="nuevoTelefonoAlt{{ $indice }}" name="nuevos_acudientes[{{ $indice }}][telefono_alt]" value="{{ old("nuevos_acudientes.{$indice}.telefono_alt") }}" maxlength="20">
                </div>

                {{-- Dirección del nuevo acudiente --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="nuevoDireccion{{ $indice }}">Direcci&oacute;n</label>
                    {{-- Entrada de texto opcional que conserva el valor previo --}}
                    <input type="text" id="nuevoDireccion{{ $indice }}" name="nuevos_acudientes[{{ $indice }}][direccion]" value="{{ old("nuevos_acudientes.{$indice}.direccion") }}" maxlength="150">
                </div>

                {{-- Correo del nuevo acudiente --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="nuevoCorreo{{ $indice }}">Correo</label>
                    {{-- Entrada de correo obligatoria que conserva el valor previo --}}
                    <input type="email" id="nuevoCorreo{{ $indice }}" name="nuevos_acudientes[{{ $indice }}][correo]" value="{{ old("nuevos_acudientes.{$indice}.correo") }}" maxlength="100">
                </div>

                {{-- Selector del parentesco tomado de la tabla parentesco --}}
                <div class="form-field">
                    {{-- Etiqueta del campo --}}
                    <label for="nuevoParentesco{{ $indice }}">Parentesco</label>
                    {{-- Lista los parentescos activos del catálogo --}}
                    <select id="nuevoParentesco{{ $indice }}" name="nuevos_acudientes[{{ $indice }}][parentesco]">
                        {{-- Opción por defecto --}}
                        <option value="">Selecciona un parentesco</option>
                        {{-- Recorre los parentescos disponibles y marca el seleccionado previamente --}}
                        @forelse ($parentescos as $parentesco)
                            <option value="{{ $parentesco->id_parentesco }}" @selected((string) old("nuevos_acudientes.{$indice}.parentesco") === (string) $parentesco->id_parentesco)>
                                {{ $parentesco->parentesco }}
                            </option>
                        @empty
                            {{-- Mensaje cuando el catálogo de parentescos está vacío --}}
                            <option value="" disabled>No hay parentescos registrados</option>
                        @endforelse
                    </select>
                </div>

                {{-- Botón que elimina la fila --}}
                <button type="button" class="acudientes-remove" data-acudientes-quitar>Quitar</button>
            </div>
        @endforeach
    </div>
    {{-- Muestra los errores de validación del bloque de acudientes nuevos --}}
    @foreach ($erroresNuevos as $error)
        <span class="form-error">{{ $error }}</span>
    @endforeach
    {{-- Botón que agrega otra fila de acudiente nuevo --}}
    <div class="acudientes-actions">
        <button type="button" class="btn btn-ghost" data-acudientes-agregar="acudientesNuevosRows">Agregar otro nuevo</button>
    </div>
</div>

<script>
    (function() {
        // Mapa de los acudientes que cada estudiante ya tenía vinculados antes de esta matrícula
        var mapaAcudientes = @json($acudientesPorEstudiante);

        /**
         * Renumera los campos de todas las filas de un bloque para que los índices
         * de los arrays marcados en el atributo name queden consecutivos.
         *
         * @param {HTMLElement} contenedor Bloque que contiene las filas repetibles
         */
        function renumerarFilas(contenedor) {
            // Recorre las filas del bloque en el orden en que aparecen
            Array.prototype.forEach.call(contenedor.querySelectorAll('.acudientes-row'), function(fila, indice) {
                // Solo renumera los campos que usan índices, es decir los del formulario
                Array.prototype.forEach.call(fila.querySelectorAll('[name]'), function(campo) {
                    campo.name = campo.name.replace(/^([^\[]+)\[\d+\]/, '$1[' + indice + ']');
                });
            });
        }

        /**
         * Añade una copia vacía de la última fila al final del bloque indicado.
         *
         * @param {string} idContenedor Identificador del bloque de filas
         */
        function agregarFila(idContenedor) {
            // Localiza el bloque de filas repetibles
            var contenedor = document.getElementById(idContenedor);

            // Si el bloque no existe no se hace nada
            if (!contenedor) {
                return;
            }

            // Toma la última fila como modelo para copiar su estructura
            var filas = contenedor.querySelectorAll('.acudientes-row');
            var ultima = filas[filas.length - 1];

            // Si no hay filas de las que copiar se cancela la operación
            if (!ultima) {
                return;
            }

            // Clona la fila conservando la estructura y las opciones de los selectores
            var nueva = ultima.cloneNode(true);

            // Limpia los valores de la copia para que quede en blanco
            Array.prototype.forEach.call(nueva.querySelectorAll('input'), function(entrada) {
                entrada.value = '';
            });

            // Reinicia los selectores a la opción vacía
            Array.prototype.forEach.call(nueva.querySelectorAll('select'), function(selector) {
                selector.value = '';
            });

            // Asigna identificadores nuevos para no duplicar los de la fila original
            Array.prototype.forEach.call(nueva.querySelectorAll('[id]'), function(campo) {
                campo.id = campo.id + 'Nuevo';
            });

            // Añade la fila al final del bloque y corrige la numeración de todas
            contenedor.appendChild(nueva);
            renumerarFilas(contenedor);
        }

        // Agrega una fila nueva cada vez que se pulsa un botón de agregar
        Array.prototype.forEach.call(document.querySelectorAll('[data-acudientes-agregar]'), function(boton) {
            boton.addEventListener('click', function() {
                agregarFila(boton.getAttribute('data-acudientes-agregar'));
            });
        });

        // Elimina la fila de acudiente a la que pertenece el botón pulsado
        document.addEventListener('click', function(evento) {
            // Solo atiende los botones marcados como quitar
            var boton = evento.target.closest('[data-acudientes-quitar]');

            if (!boton) {
                return;
            }

            // Obtiene el bloque de filas al que pertenece la fila eliminada
            var contenedor = boton.closest('.acudientes-rows');
            var fila = boton.closest('.acudientes-row');

            // Si el bloque queda sin filas se conserva una vacía para no romper el formulario
            if (contenedor && contenedor.querySelectorAll('.acudientes-row').length <= 1) {
                // Vacía los campos de la fila que se va a conservar
                Array.prototype.forEach.call(fila.querySelectorAll('input'), function(entrada) {
                    entrada.value = '';
                });

                Array.prototype.forEach.call(fila.querySelectorAll('select'), function(selector) {
                    selector.value = '';
                });

                return;
            }

            fila.remove();
            renumerarFilas(contenedor);
        });

        /**
         * Pinta las etiquetas de los acudientes que ya tenía el estudiante seleccionado.
         */
        function mostrarVinculados() {
            // Localiza el selector de estudiante y los contenedores de la lista
            var estudiante = document.getElementById('Documento_matri');
            var lista = document.getElementById('acudientesVinculados');
            var vacio = document.getElementById('acudientesVacio');

            // Si falta algún elemento no se hace nada
            if (!estudiante || !lista || !vacio) {
                return;
            }

            // Limpia la lista antes de volver a pintarla
            lista.innerHTML = '';

            // Busca los acudientes que ya tenía el estudiante seleccionado
            var vinculados = mapaAcudientes[estudiante.value] || [];

            // Muestra el aviso únicamente cuando hay un estudiante elegido
            vacio.hidden = estudiante.value === '' || vinculados.length > 0;

            // Crea una etiqueta por cada acudiente ya vinculado
            vinculados.forEach(function(acudiente) {
                // Construye la etiqueta con el nombre, el documento y el parentesco
                var item = document.createElement('li');
                item.className = 'acudientes-chip';
                item.textContent = acudiente.nombre + ' (' + acudiente.documento + ') — ' + (acudiente.parentesco || 'Sin parentesco');

                // Añade la etiqueta a la lista
                lista.appendChild(item);
            });

            // Muestra la lista solo cuando tiene contenido
            lista.hidden = vinculados.length === 0;
        }

        // Actualiza la lista de vinculados cada vez que se cambia el estudiante
        var selectorEstudiante = document.getElementById('Documento_matri');

        if (selectorEstudiante) {
            selectorEstudiante.addEventListener('change', mostrarVinculados);
            mostrarVinculados();
        }

        /**
         * Quita del envío las filas de acudientes que quedaron completamente en blanco,
         * porque el formulario siempre muestra una fila inicial de ejemplo.
         */
        function limpiarFilasVacias() {
            // Recorre los dos bloques de filas repetibles
            Array.prototype.forEach.call(document.querySelectorAll('.acudientes-rows'), function(contenedor) {
                // Revisa cada fila del bloque
                Array.prototype.forEach.call(contenedor.querySelectorAll('.acudientes-row'), function(fila) {
                    // Recolecta los valores escritos en la fila
                    var valores = Array.prototype.map.call(fila.querySelectorAll('[name]'), function(campo) {
                        return campo.value.trim();
                    });

                    // Si todos los campos están vacíos se elimina la fila
                    if (valores.every(function(valor) { return valor === ''; })) {
                        fila.remove();
                    }
                });

                // Renumera las filas que quedaron para que los índices sigan siendo consecutivos
                renumerarFilas(contenedor);
            });
        }

        // Limpia las filas vacías justo antes de enviar el formulario
        var formulario = document.querySelector('.acudientes-rows') ? document.querySelector('.acudientes-rows').closest('form') : null;

        if (formulario) {
            formulario.addEventListener('submit', limpiarFilasVacias);
        }
    })();
</script>
