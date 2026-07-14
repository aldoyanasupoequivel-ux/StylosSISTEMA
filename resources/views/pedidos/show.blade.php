@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Detalle del Pedido: {{ $pedido->codigo_pedido }}</h2>
    <div>
        <a href="{{ route('pedidos.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver a Pedidos
        </a>
    </div>
</div>

<div class="row">
    <!-- Información General y Cliente -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-info-circle me-1"></i> Resumen</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="text-muted small d-block">Estado Actual</span>
                    <span class="badge bg-primary fs-6">{{ $pedido->estado }}</span>
                </div>
                <hr>
                <div class="mb-3">
                    <span class="text-muted small d-block">Cliente</span>
                    <a href="{{ route('clientes.show', $pedido->cliente->id_cliente) }}" class="fw-bold text-decoration-none text-dark">
                        {{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}
                    </a>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">DNI</span>
                    <span class="fw-semibold">{{ $pedido->cliente->dni }}</span>
                </div>
                <hr>
                <div class="mb-3">
                    <span class="text-muted small d-block">Fecha de Pedido</span>
                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}</span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Entrega Estimada</span>
                    <span class="fw-semibold {{ \Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->isPast() && $pedido->estado != 'Finalizado' ? 'text-danger' : '' }}">
                        {{ \Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->format('d/m/Y') }}
                    </span>
                </div>
                
                @if($pedido->observaciones)
                    <hr>
                    <div class="mb-3">
                        <span class="text-muted small d-block">Observaciones</span>
                        <p class="mb-0 small text-secondary">{{ $pedido->observaciones }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detalles, Pagos y Seguimiento -->
    <div class="col-lg-8">
        
        <!-- Prendas -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-list-check me-1"></i> Prendas del Pedido</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Descripción</th>
                                <th class="text-center">Cant.</th>
                                <th class="text-end">Precio Un.</th>
                                <th class="text-end px-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->detalles as $detalle)
                                <tr>
                                    <td class="px-4">{{ $detalle->descripcion_prenda }}</td>
                                    <td class="text-center">{{ $detalle->cantidad }}</td>
                                    <td class="text-end">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="text-end px-4 fw-semibold">S/ {{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                <td class="text-end px-4 fw-bold text-primary fs-5">S/ {{ number_format($pedido->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Actualizar Estado -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-arrow-repeat me-1"></i> Actualizar Estado</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pedidos.update', $pedido->id_pedido) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row align-items-end">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <label class="form-label text-muted small">Nuevo Estado</label>
                            <select name="estado" id="estadoSelect" class="form-select" required>
                                <option value="Registrado" {{ $pedido->estado == 'Registrado' ? 'selected' : '' }}>Registrado</option>
                                <option value="En Corte" {{ $pedido->estado == 'En Corte' ? 'selected' : '' }}>En Corte</option>
                                <option value="En Costura" {{ $pedido->estado == 'En Costura' ? 'selected' : '' }}>En Costura</option>
                                <option value="En Acabados" {{ $pedido->estado == 'En Acabados' ? 'selected' : '' }}>En Acabados</option>
                                <option value="Finalizado" {{ $pedido->estado == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                <option value="Entregado" {{ $pedido->estado == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label text-muted small">% de Avance</label>
                            <div class="input-group bg-light rounded">
                                <input type="number" name="porcentaje_avance" id="avanceInput" class="form-control bg-light" min="0" max="100" value="{{ $pedido->seguimientos->first()->porcentaje_avance ?? 0 }}" readonly>
                                <span class="input-group-text bg-light">%</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check2"></i> Actualizar</button>
                        </div>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const estadoSelect = document.getElementById('estadoSelect');
                        const avanceInput = document.getElementById('avanceInput');
                        
                        const porcentajes = {
                            'Registrado': 0,
                            'En Corte': 25,
                            'En Costura': 50,
                            'En Acabados': 75,
                            'Finalizado': 100,
                            'Entregado': 100
                        };

                        estadoSelect.addEventListener('change', function() {
                            if(porcentajes[this.value] !== undefined) {
                                avanceInput.value = porcentajes[this.value];
                            }
                        });
                    });
                </script>
            </div>
        </div>

    </div>
</div>

<!-- ==============================================
     SECCIÓN DE PAGOS
     ============================================== -->
<hr class="my-5 border-secondary opacity-25">

<h4 class="fw-bold mb-4 text-dark"><i class="bi bi-wallet2 me-2"></i> Registro de pagos</h4>
<div class="mb-4">
    <span class="fs-5 fw-semibold">Pedido:</span> <span class="fs-5 text-secondary">{{ $pedido->codigo_pedido }}</span>
</div>

<div class="row">
    <!-- Registrar Nuevo Pago (Izquierda) -->
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-dark">Registrar nuevo pago</h5>
                
                <form action="{{ route('pagos.store', $pedido->id_pedido) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Monto del pago (S/)</label>
                        <input type="number" name="monto" class="form-control" placeholder="0.00" step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Fecha del pago</label>
                        <input type="date" name="fecha_pago" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Observación / Método</label>
                        <input type="text" name="metodo_pago" class="form-control" placeholder="Ej: Adelanto inicial, Yape, Plin..." maxlength="30">
                    </div>

                    <button type="submit" class="btn btn-outline-dark w-100 py-2 fw-semibold">Registrar pago</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Resumen y Historial (Derecha) -->
    <div class="col-lg-8">
        
        @php
            $totalPagado = $pedido->pagos->sum('monto');
            $saldoPendiente = $pedido->total - $totalPagado;
        @endphp

        <!-- Resumen de Pagos -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row text-center text-md-start">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Monto total:</span>
                        <span class="fs-4 text-dark">S/ {{ number_format($pedido->total, 2) }}</span>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total pagado:</span>
                        <span class="fs-4 text-success">S/ {{ number_format($totalPagado, 2) }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">Saldo pendiente:</span>
                        <span class="fs-4 fw-bold {{ $saldoPendiente > 0 ? 'text-danger' : 'text-success' }}">
                            S/ {{ number_format($saldoPendiente, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de pagos -->
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-dark">Historial de pagos</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Fecha</th>
                                <th class="fw-semibold text-end">Monto</th>
                                <th class="fw-semibold px-4">Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedido->pagos()->orderBy('fecha_pago', 'desc')->get() as $pago)
                                <tr>
                                    <td class="text-muted">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                    <td class="text-end fw-semibold text-success">S/ {{ number_format($pago->monto, 2) }}</td>
                                    <td class="px-4 text-muted">{{ $pago->metodo_pago ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Aún no se han registrado pagos para este pedido.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .form-control {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.65rem 1rem;
    }
    .form-control:focus {
        border-color: #333;
        box-shadow: none;
    }
    .btn-outline-dark {
        border-color: #cbd5e1;
        color: #333;
    }
    .btn-outline-dark:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
    }
</style>
@endsection
