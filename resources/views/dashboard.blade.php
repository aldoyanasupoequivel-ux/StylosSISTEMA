@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Dashboard</h2>
    <div class="text-muted">
        <i class="bi bi-calendar3"></i> {{ now()->format('d M Y, h:i A') }}
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 border-start border-primary border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Pedidos</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalPedidos }}</div>
                    </div>
                    <div class="text-gray-300 fs-1">
                        <i class="bi bi-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 border-start border-success border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Ingresos (Mes)</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">S/ {{ number_format($ingresosMes, 2) }}</div>
                    </div>
                    <div class="text-gray-300 fs-1">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 border-start border-info border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">En Proceso</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $pedidosEnProceso }}</div>
                    </div>
                    <div class="text-gray-300 fs-1">
                        <i class="bi bi-gear"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card h-100 border-0 border-start border-warning border-4 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Alertas Stock</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $alertasStock }}</div>
                    </div>
                    <div class="text-gray-300 fs-1">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-primary">Pedidos Recientes</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4">Código</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-end px-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidosRecientes as $pedido)
                                <tr>
                                    <td class="px-4 fw-bold text-secondary">{{ $pedido->codigo_pedido }}</td>
                                    <td>{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d M, Y') }}</td>
                                    <td>
                                        @php
                                            $bg = match($pedido->estado) {
                                                'Registrado' => 'bg-secondary',
                                                'En Corte' => 'bg-info',
                                                'En Costura' => 'bg-warning text-dark',
                                                'En Acabados' => 'bg-primary',
                                                'Finalizado' => 'bg-success',
                                                'Entregado' => 'bg-dark',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $bg }}">{{ $pedido->estado }}</span>
                                    </td>
                                    <td class="text-end px-4 fw-semibold">S/ {{ number_format($pedido->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No hay pedidos recientes.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-2 text-center">
                <a href="{{ route('pedidos.index') }}" class="text-decoration-none fw-semibold">Ver todos los pedidos <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection
