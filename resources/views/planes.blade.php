@extends('layouts.app')

@section('title', 'SCAPE - Planes')

@section('content')
<section class="planes-hero">
    <div class="container">
        <h1>Planes que se adaptan a ti</h1>
        <p>Elige el plan ideal para tu institución. Todos incluyen soporte técnico y actualizaciones.</p>
    </div>
</section>

<section class="planes-section">
    <div class="container">
        <div class="toggle-wrap">
            <span>Mensual</span>
            <div class="toggle-switch" id="billingToggle"></div>
            <span>Anual <span class="badge bg-success bg-opacity-25 text-success ms-1">-20%</span></span>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <div class="col">
                <div class="plan-card">
                    <h5 class="plan-title">Emprendedor</h5>
                    <div class="plan-monthly">
                        <div class="plan-price">$50.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <div class="plan-annual">
                        <div class="plan-price">$40.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$348</s> &nbsp;pagando $276/año</div>
                    </div>
                    <ul class="plan-features">
                        <li><i class="bi bi-check2-circle"></i> 1 sede</li>
                        <li><i class="bi bi-check2-circle"></i> 50 personas registradas</li>
                        <li><i class="bi bi-check2-circle"></i> Control de acceso básico</li>
                        <li><i class="bi bi-check2-circle"></i> Reportes mensuales</li>
                        <li><i class="bi bi-check2-circle"></i> Soporte por correo</li>
                    </ul>
                    <a href="#" class="btn-plan btn-plan-outline">Probar Gratis</a>
                </div>
            </div>

            {{-- SME --}}
            <div class="col">
                <div class="plan-card">
                    <h5 class="plan-title">PYME</h5>
                    <div class="plan-monthly">
                        <div class="plan-price">$70.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <div class="plan-annual">
                        <div class="plan-price">$60.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$708</s> &nbsp;pagando $564/año</div>
                    </div>
                    <ul class="plan-features">
                        <li><i class="bi bi-check2-circle"></i> 3 sedes</li>
                        <li><i class="bi bi-check2-circle"></i> 200 personas registradas</li>
                        <li><i class="bi bi-check2-circle"></i> Inventario de equipos</li>
                        <li><i class="bi bi-check2-circle"></i> Control de visitantes</li>
                        <li><i class="bi bi-check2-circle"></i> Reportes semanales</li>
                        <li><i class="bi bi-check2-circle"></i> Soporte prioritario</li>
                    </ul>
                    <a href="#" class="btn-plan btn-plan-outline">Probar Gratis</a>
                </div>
            </div>

            {{-- Pro --}}
            <div class="col">
                <div class="plan-card featured">
                    <h5 class="plan-title">Pro</h5>
                    <div class="plan-monthly">
                        <div class="plan-price">$90.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <div class="plan-annual">
                        <div class="plan-price">$80.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$1,188</s> &nbsp;pagando $948/año</div>
                    </div>
                    <ul class="plan-features">
                        <li><i class="bi bi-check2-circle"></i> 10 sedes</li>
                        <li><i class="bi bi-check2-circle"></i> 1,000 personas registradas</li>
                        <li><i class="bi bi-check2-circle"></i> Inventario completo</li>
                        <li><i class="bi bi-check2-circle"></i> Visitantes + notificaciones</li>
                        <li><i class="bi bi-check2-circle"></i> Reportes en tiempo real</li>
                        <li><i class="bi bi-check2-circle"></i> Soporte 24/7</li>
                        <li><i class="bi bi-check2-circle"></i> API de integración</li>
                    </ul>
                    <a href="#" class="btn-plan btn-plan-fill">Probar Gratis</a>
                </div>
            </div>

            {{-- Plus --}}
            <div class="col">
                <div class="plan-card">
                    <h5 class="plan-title">Plus</h5>
                    <div class="plan-monthly">
                        <div class="plan-price">$110.000 <span>/mes</span></div>
                        <div class="plan-price-annual">&nbsp;</div>
                    </div>
                    <div class="plan-annual">
                        <div class="plan-price">$100.000 <span>/mes</span></div>
                        <div class="plan-price-annual"><s>$2,148</s> &nbsp;pagando $1,716/año</div>
                    </div>
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
                    <a href="#" class="btn-plan btn-plan-outline">Probar Gratis</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('billingToggle').addEventListener('click', function() {
    this.classList.toggle('active');
    const monthly = document.querySelectorAll('.plan-monthly');
    const annual  = document.querySelectorAll('.plan-annual');
    const showAnnual = this.classList.contains('active');
    monthly.forEach(el => el.style.display = showAnnual ? 'none' : 'block');
    annual.forEach(el => el.style.display  = showAnnual ? 'block' : 'none');
});
</script>
@endpush
