@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Reportes</h2>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-pills mb-4" id="reportesTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold rounded-pill px-4 me-2 {{ $tab == 'ingresos' ? 'active shadow-sm' : 'text-muted' }}" 
                id="ingresos-tab" data-bs-toggle="tab" data-bs-target="#ingresos" type="button" role="tab" 
                onclick="document.getElementById('tab-input').value = 'ingresos'">
            <i class="bi bi-cash-stack me-2"></i> Reporte de Ingresos
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-semibold rounded-pill px-4 {{ $tab == 'pedidos' ? 'active shadow-sm' : 'text-muted' }}" 
                id="pedidos-tab" data-bs-toggle="tab" data-bs-target="#pedidos" type="button" role="tab"
                onclick="document.getElementById('tab-input-pedidos').value = 'pedidos'">
            <i class="bi bi-journal-check me-2"></i> Reporte de Pedidos
        </button>
    </li>
</ul>

<div class="tab-content" id="reportesTabContent">
    
    <!-- ==============================================
         TAB: INGRESOS
         ============================================== -->
    <div class="tab-pane fade {{ $tab == 'ingresos' ? 'show active' : '' }}" id="ingresos" role="tabpanel">
        
        <!-- Filtros Ingresos -->
        <div class="card border-0 shadow-sm mb-4 d-print-none">
            <div class="card-body">
                <form action="{{ route('reportes.index') }}" method="GET" class="d-flex flex-wrap align-items-end gap-3">
                    <input type="hidden" name="tab" id="tab-input" value="ingresos">
                    <div>
                        <label class="form-label text-muted small fw-semibold">Fecha inicial</label>
                        <input type="date" name="fecha_inicio" class="form-control bg-light border-0" value="{{ $fechaInicio }}" required>
                    </div>
                    <div>
                        <label class="form-label text-muted small fw-semibold">Fecha final</label>
                        <input type="date" name="fecha_fin" class="form-control bg-light border-0" value="{{ $fechaFin }}" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary shadow-sm px-4 fw-semibold"><i class="bi bi-funnel me-1"></i> Generar reporte</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tarjetas Resumen Ingresos -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card bg-primary text-white border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold text-white-50 mb-2">Total ingresos generados</h6>
                                <h2 class="display-6 fw-bold mb-0">S/ {{ number_format($totalIngresos, 2) }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="bi bi-wallet2 fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white border-0 h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold text-white-50 mb-2">Nº de pagos registrados</h6>
                                <h2 class="display-6 fw-bold mb-0">{{ $cantidadPagos }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                <i class="bi bi-receipt fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla Ingresos -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="m-0 fw-bold text-primary">Detalle de Ingresos</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0" id="tabla-ingresos">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Fecha</th>
                                <th class="py-3">Código Pedido</th>
                                <th class="py-3">Cliente</th>
                                <th class="px-4 py-3 text-end">Monto (S/)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pagos as $pago)
                                <tr>
                                    <td class="px-4 py-3 text-muted">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                    <td class="py-3 fw-semibold text-dark">{{ $pago->pedido->codigo_pedido ?? 'N/A' }}</td>
                                    <td class="py-3">{{ $pago->pedido->cliente->nombre ?? '' }} {{ $pago->pedido->cliente->apellido ?? '' }}</td>
                                    <td class="px-4 py-3 text-end fw-bold text-success">S/ {{ number_format($pago->monto, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No hay ingresos registrados en este periodo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end gap-2 d-print-none">
            <button onclick="exportTableToCSV('tabla-ingresos', 'reporte_ingresos.csv')" class="btn btn-outline-success shadow-sm px-4 fw-semibold"><i class="bi bi-filetype-csv me-1"></i> Exportar a CSV</button>
            <button onclick="window.print()" class="btn btn-outline-danger shadow-sm px-4 fw-semibold"><i class="bi bi-file-pdf me-1"></i> Exportar a PDF</button>
        </div>
    </div>

    <!-- ==============================================
         TAB: PEDIDOS
         ============================================== -->
    <div class="tab-pane fade {{ $tab == 'pedidos' ? 'show active' : '' }}" id="pedidos" role="tabpanel">
        
        <!-- Filtros Pedidos -->
        <div class="card border-0 shadow-sm mb-4 d-print-none">
            <div class="card-body">
                <form action="{{ route('reportes.index') }}" method="GET" class="d-flex flex-wrap align-items-end gap-3">
                    <input type="hidden" name="tab" id="tab-input-pedidos" value="pedidos">
                    <div>
                        <label class="form-label text-muted small fw-semibold">Fecha inicial</label>
                        <input type="date" name="fecha_inicio" class="form-control bg-light border-0" value="{{ $fechaInicio }}" required>
                    </div>
                    <div>
                        <label class="form-label text-muted small fw-semibold">Fecha final</label>
                        <input type="date" name="fecha_fin" class="form-control bg-light border-0" value="{{ $fechaFin }}" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary shadow-sm px-4 fw-semibold"><i class="bi bi-funnel me-1"></i> Generar reporte</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tarjetas Resumen Pedidos -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 mb-5">
            @php
                $colors = [
                    'Total pedidos' => 'primary',
                    'Pendientes' => 'danger',
                    'En Proceso' => 'warning',
                    'Listos entrega' => 'info',
                    'Entregados' => 'success',
                    'Cancelados' => 'secondary'
                ];
            @endphp
            @foreach(['Total pedidos', 'Pendientes', 'En Proceso', 'Listos entrega', 'Entregados', 'Cancelados'] as $estado)
            <div class="col">
                <div class="card border-0 h-100 shadow-sm text-center">
                    <div class="card-body p-3">
                        <h6 class="text-muted mb-2 small fw-bold text-uppercase">{{ $estado }}</h6>
                        <h3 class="fw-bold mb-0 text-{{ $colors[$estado] }}">{{ $statsPedidos[$estado] }}</h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Gráfico Pedidos -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-md-5 mb-4 mb-md-0 text-center">
                        <div style="max-width: 320px; margin: 0 auto;">
                            <canvas id="pedidosChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5 class="fw-bold text-gray-800 mb-4">Distribución por Estados</h5>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle shadow-sm" style="width: 16px; height: 16px; background-color: #f87171;"></div>
                                    <span class="text-dark fw-semibold">Pendiente</span>
                                </div>
                                <span class="badge bg-danger rounded-pill px-3">{{ $statsPedidos['Pendientes'] }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle shadow-sm" style="width: 16px; height: 16px; background-color: #fbbf24;"></div>
                                    <span class="text-dark fw-semibold">En Proceso</span>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill px-3">{{ $statsPedidos['En Proceso'] }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle shadow-sm" style="width: 16px; height: 16px; background-color: #60a5fa;"></div>
                                    <span class="text-dark fw-semibold">Listos entrega</span>
                                </div>
                                <span class="badge bg-info text-dark rounded-pill px-3">{{ $statsPedidos['Listos entrega'] }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle shadow-sm" style="width: 16px; height: 16px; background-color: #34d399;"></div>
                                    <span class="text-dark fw-semibold">Entregados</span>
                                </div>
                                <span class="badge bg-success rounded-pill px-3">{{ $statsPedidos['Entregados'] }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle shadow-sm" style="width: 16px; height: 16px; background-color: #9ca3af;"></div>
                                    <span class="text-dark fw-semibold">Cancelados</span>
                                </div>
                                <span class="badge bg-secondary rounded-pill px-3">{{ $statsPedidos['Cancelados'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end d-print-none">
            <button onclick="window.print()" class="btn btn-outline-danger shadow-sm px-4 fw-semibold"><i class="bi bi-file-pdf me-1"></i> Exportar a PDF</button>
        </div>
    </div>
</div>

<style>
    .form-control { border-radius: 2px; }
    @media print {
        body * { visibility: hidden; }
        .main-content, .main-content * { visibility: visible; }
        .main-content { position: absolute; left: 0; top: 0; width: 100%; }
        .sidebar, .top-navbar, .nav-tabs, .d-print-none { display: none !important; }
        .tab-content > .tab-pane { display: block !important; opacity: 1 !important; visibility: visible !important; }
        /* Ocultar la pestaña no activa en impresión */
        #ingresos:not(.active) { display: none !important; }
        #pedidos:not(.active) { display: none !important; }
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Chart.js
        const ctx = document.getElementById('pedidosChart');
        if (ctx) {
            const data = [
                {{ $statsPedidos['Pendientes'] }},
                {{ $statsPedidos['En Proceso'] }},
                {{ $statsPedidos['Listos entrega'] }},
                {{ $statsPedidos['Entregados'] }},
                {{ $statsPedidos['Cancelados'] }}
            ];
            
            // Si todos están en 0, mostrar un gris claro
            const total = data.reduce((a, b) => a + b, 0);
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Pendientes', 'En Proceso', 'Listos entrega', 'Entregados', 'Cancelados'],
                    datasets: [{
                        data: total === 0 ? [1] : data,
                        backgroundColor: total === 0 ? ['#f3f4f6'] : [
                            '#f87171', // Rojo - Pendiente
                            '#fbbf24', // Amarillo - En Proceso
                            '#60a5fa', // Azul - Listos
                            '#34d399', // Verde - Entregados
                            '#9ca3af'  // Gris - Cancelados
                        ],
                        borderColor: '#333',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if(total === 0) return 'Sin datos';
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    let val = context.raw;
                                    let percentage = Math.round((val / total) * 100);
                                    return label + val + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });

    // Función simple para exportar a CSV
    function exportTableToCSV(tableId, filename) {
        var csv = [];
        var table = document.getElementById(tableId);
        var rows = table.querySelectorAll("tr");
        
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            for (var j = 0; j < cols.length; j++) 
                row.push('"' + cols[j].innerText.replace(/"/g, '""') + '"');
            csv.push(row.join(","));        
        }

        var csvFile = new Blob([csv.join("\n")], {type: "text/csv;charset=utf-8;"});
        var downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
@endpush
@endsection
