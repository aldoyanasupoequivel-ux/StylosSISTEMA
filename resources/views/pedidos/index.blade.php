@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Gestión de Pedidos</h2>
    <a href="{{ route('pedidos.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Registrar Pedido
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold text-primary">Lista de Pedidos</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="px-4">Código</th>
                        <th>Cliente</th>
                        <th>Fecha de Entrega</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th class="text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td class="px-4 fw-bold text-secondary">{{ $pedido->codigo_pedido }}</td>
                            <td>{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->format('d/m/Y') }}
                                @if(\Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->isPast() && $pedido->estado != 'Finalizado')
                                    <span class="badge bg-danger ms-1">Atrasado</span>
                                @endif
                            </td>
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
                            <td class="fw-semibold">S/ {{ number_format($pedido->total, 2) }}</td>
                            <td class="text-end px-4">
                                <a href="{{ route('pedidos.show', $pedido->id_pedido) }}" class="btn btn-sm btn-info text-white" title="Gestionar / Ver Detalles">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <form action="{{ route('pedidos.destroy', $pedido->id_pedido) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar pedido? Esto borrará sus seguimientos, detalles y pagos.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                No hay pedidos registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pedidos->hasPages())
        <div class="card-footer bg-white py-3 border-top-0">
            {{ $pedidos->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
