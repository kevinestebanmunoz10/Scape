<button class="sidebar-toggle" type="button" aria-label="Abrir menú de navegación"
        onclick="document.querySelector('.sidebar').classList.add('open');document.querySelector('.sidebar-overlay').classList.add('show');">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
</button>
<div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('open');this.classList.remove('show');"></div>

@push('scripts')
    <script>
        document.querySelectorAll('.sidebar a').forEach(function (link) {
            link.addEventListener('click', function () {
                document.querySelector('.sidebar').classList.remove('open');
                document.querySelector('.sidebar-overlay').classList.remove('show');
            });
        });
    </script>
@endpush