{{-- Botón que abre el menú lateral (sidebar) y muestra la capa de fondo oscura --}}
<button class="sidebar-toggle" type="button" aria-label="Abrir menú de navegación"
        onclick="document.querySelector('.sidebar').classList.add('open');document.querySelector('.sidebar-overlay').classList.add('show');">
    <!-- Icono de hamburguesa (tres líneas) -->
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
</button>
{{-- Capa oscura que al hacer clic cierra el menú lateral --}}
<div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open');this.classList.remove('show');"></div>

{{-- Script que cierra el menú lateral al hacer clic en cualquiera de sus enlaces --}}
@push('scripts')
    <script>
        // Recorre todos los enlaces del menú lateral
        document.querySelectorAll('.sidebar a').forEach(function (link) {
            // Agrega un evento de clic a cada enlace
            link.addEventListener('click', function () {
                // Cierra el menú lateral quitando la clase 'open'
                document.querySelector('.sidebar').classList.remove('open');
                // Oculta la capa de fondo quitando la clase 'show'
                document.querySelector('.sidebar-overlay').classList.remove('show');
            });
        });
    </script>
@endpush