@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Detalle del Cliente</h2>
    <div>
        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-warning text-dark shadow-sm me-2">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver a la Lista
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center p-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white mb-3 shadow-sm" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ substr($cliente->nombre, 0, 1) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $cliente->nombre }} {{ $cliente->apellido }}</h5>
                <p class="text-muted mb-3"><i class="bi bi-person-vcard me-1"></i> DNI: {{ $cliente->dni }}</p>
                <hr>
                <div class="text-start mt-3">
                    <p class="mb-2"><i class="bi bi-telephone text-primary me-2"></i> {{ $cliente->telefono ?? 'No registrado' }}</p>
                    <p class="mb-2"><i class="bi bi-envelope text-primary me-2"></i> {{ $cliente->correo ?? 'No registrado' }}</p>
                    <p class="mb-0"><i class="bi bi-geo-alt text-primary me-2"></i> {{ $cliente->direccion ?? 'No registrado' }}</p>
                </div>
            </div>
            <div class="card-footer bg-white border-0 text-center text-muted small pb-3">
                Registrado el {{ $cliente->created_at->format('d/m/Y') }}
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-bag-check me-1"></i> Historial de Pedidos</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="px-4">Código</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th class="text-end px-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cliente->pedidos as $pedido)
                                <tr>
                                    <td class="px-4 fw-semibold text-secondary">{{ $pedido->codigo_pedido }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}</td>
                                    <td class="fw-bold">S/ {{ number_format($pedido->total, 2) }}</td>
                                    <td><span class="badge bg-primary">{{ $pedido->estado }}</span></td>
                                    <td class="text-end px-4">
                                        <a href="#" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Este cliente aún no tiene pedidos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
