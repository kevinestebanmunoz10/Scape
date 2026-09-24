{{-- Vista de la página de planes: extiende el layout principal --}}
@extends('layouts.app')

{{-- Título de la página --}}
@section('title', 'SCAPE - Planes')

{{-- Inicio del bloque de contenido --}}
@section('content')
<!-- Sección principal de presentación de los planes -->
<section class="planes-hero">
    <div class="container">
        <h1>Planes que se adaptan a ti</h1>
        <p>Elige el plan ideal para tu institución. Todos incluyen soporte técnico y actualizaciones.</p>
        <a href="#" class="btn-plan-hero">Solicita tu prueba gratis</a>
    </div>
</section>

<!-- Sección que lista las tarifas de los planes -->
<section class="planes-section">
    <div class="container">
        <!-- Interruptor para alternar entre precios mensuales y anuales -->
        <div class="toggle-wrap">
            <span>Mensual</span>
            <div class="toggle-switch" id="billingToggle"></div>
            <span>Anual <span class="badge bg-success bg-opacity-25 text-success ms-1">-20%</span></span>
        </div>

        <!-- Fila con las cuatro tarjetas de planes -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">

            <!-- Tarjeta del plan Emprendedor -->
            <div class="col">
                <div class="plan-card">
                    <h5 class="plan-title">Emprendedor</h5>
                    <!-- Precios mostrados cuando la facturación es mensual -->
                    <div class="plan-monthly">
                        <div class="plan-price">$50.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <!-- Precios mostrados cuando la facturación es anual -->
                    <div class="plan-annual">
                        <div class="plan-price">$40.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$348</s> &nbsp;pagando $400,000/año</div>
                    </div>
                    <!-- Lista de características incluidas -->
                    <ul class="plan-features">
                        <li><i class="bi bi-check2-circle"></i> 1 sede</li>
                        <li><i class="bi bi-check2-circle"></i> 50 personas registradas</li>
                        <li><i class="bi bi-check2-circle"></i> Control de acceso básico</li>
                        <li><i class="bi bi-check2-circle"></i> Reportes mensuales</li>
                        <li><i class="bi bi-check2-circle"></i> Soporte por correo</li>
                    </ul>
                    <!-- Botón de compra del plan -->
                    <a href="#" class="btn-plan btn-plan-outline">COMPRAR</a>
                </div>
            </div>

            {{-- SME --}}
            <!-- Tarjeta del plan PYME -->
            <div class="col">
                <div class="plan-card">
                    <h5 class="plan-title">PYME</h5>
                    <!-- Precios mostrados cuando la facturación es mensual -->
                    <div class="plan-monthly">
                        <div class="plan-price">$70.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <!-- Precios mostrados cuando la facturación es anual -->
                    <div class="plan-annual">
                        <div class="plan-price">$60.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$708</s> &nbsp;pagando $600,000/año</div>
                    </div>
                    <!-- Lista de características incluidas -->
                    <ul class="plan-features">
                        <li><i class="bi bi-check2-circle"></i> 3 sedes</li>
                        <li><i class="bi bi-check2-circle"></i> 200 personas registradas</li>
                        <li><i class="bi bi-check2-circle"></i> Inventario de equipos</li>
                        <li><i class="bi bi-check2-circle"></i> Control de visitantes</li>
                        <li><i class="bi bi-check2-circle"></i> Reportes semanales</li>
                        <li><i class="bi bi-check2-circle"></i> Soporte prioritario</li>
                    </ul>
                    <!-- Botón de compra del plan -->
                    <a href="#" class="btn-plan btn-plan-outline">COMPRAR</a>
                </div>
            </div>

            {{-- Pro --}}
            <!-- Tarjeta del plan Pro (plan destacado) -->
            <div class="col">
                <div class="plan-card featured">
                    <h5 class="plan-title">Pro</h5>
                    <!-- Precios mostrados cuando la facturación es mensual -->
                    <div class="plan-monthly">
                        <div class="plan-price">$90.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <!-- Precios mostrados cuando la facturación es anual -->
                    <div class="plan-annual">
                        <div class="plan-price">$80.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$1,188</s> &nbsp;pagando $800,000/año</div>
                    </div>
                    <!-- Lista de características incluidas -->
                    <ul class="plan-features">
                        <li><i class="bi bi-check2-circle"></i> 10 sedes</li>
                        <li><i class="bi bi-check2-circle"></i> 1,000 personas registradas</li>
                        <li><i class="bi bi-check2-circle"></i> Inventario completo</li>
                        <li><i class="bi bi-check2-circle"></i> Visitantes + notificaciones</li>
                        <li><i class="bi bi-check2-circle"></i> Reportes en tiempo real</li>
                        <li><i class="bi bi-check2-circle"></i> Soporte 24/7</li>
                        <li><i class="bi bi-check2-circle"></i> API de integración</li>
                    </ul>
                    <!-- Botón de compra del plan -->
                    <a href="#" class="btn-plan btn-plan-fill">COMPRAR</a>
                </div>
            </div>

            {{-- Plus --}}
            <!-- Tarjeta del plan Plus -->
            <div class="col">
                <div class="plan-card">
                    <h5 class="plan-title">Plus</h5>
                    <!-- Precios mostrados cuando la facturación es mensual -->
                    <div class="plan-monthly">
                        <div class="plan-price">$110.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <!-- Precios mostrados cuando la facturación es anual -->
                    <div class="plan-annual">
                        <div class="plan-price">$100.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$2,148</s> &nbsp;pagando $1,000,000/año</div>
                    </div>
                    <!-- Lista de características incluidas -->
                    <ul class="plan-features">
                        <li><i class="bi bi-check2-circle"></i> Sedes ilimitadas</li>
                        <li><i class="bi bi-check2-circle"></i> Personas ilimitadas</li>
                        <li><i class="bi bi-check2-circle"></i> Todas las funcionalidades</li>
                        <li><i class="bi bi-check2-circle"></i> Alertas y alarmas avanzadas</li>
                        <li><i class="bi bi-check2-circle"></i> Reportes + exportación PDF</li>
                        <li><i class="bi bi-check2-circle"></i> Soporte 24/7 dedicado</li>
                        <li><i class="bi bi-check2-circle"></i> API + personalización</li>
                        <li><i class="bi bi-check2-circle"></i> Capacitación incluida</li>
                    </ul>
                    <!-- Botón de compra del plan -->
                    <a href="#" class="btn-plan btn-plan-outline">COMPRAR</a>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Fin del bloque de contenido --}}
@endsection

{{-- Script que controla el interruptor de facturación mensual/anual --}}
@push('scripts')
<script>
// Escucha los clics sobre el interruptor de facturación
document.getElementById('billingToggle').addEventListener('click', function() {
    // Alterna la clase 'active' que marca el estado de facturación anual
    this.classList.toggle('active');
    // Selecciona todos los bloques de precios mensuales y anuales
    const monthly = document.querySelectorAll('.plan-monthly');
    const annual  = document.querySelectorAll('.plan-annual');
    // 'showAnnual' es verdadero cuando el interruptor está activo
    const showAnnual = this.classList.contains('active');
    // Muestra u oculta los precios mensuales según el estado seleccionado
    monthly.forEach(el => el.style.display = showAnnual ? 'none' : 'block');
    // Muestra u oculta los precios anuales según el estado seleccionado
    annual.forEach(el => el.style.display  = showAnnual ? 'block' : 'none');
});
</script>
@endpush