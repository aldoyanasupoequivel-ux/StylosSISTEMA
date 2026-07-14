@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Gestión de Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Nuevo Cliente
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold text-primary">Lista de Clientes</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="px-4">ID</th>
                        <th>Nombre Completo</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th class="text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td class="px-4 fw-semibold text-secondary">#{{ str_pad($cliente->id_cliente, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                        {{ substr($cliente->nombre, 0, 1) }}
                                    </div>
                                    <div class="fw-semibold text-dark">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
                                </div>
                            </td>
                            <td>{{ $cliente->dni }}</td>
                            <td>{{ $cliente->telefono ?? '-' }}</td>
                            <td>{{ $cliente->correo ?? '-' }}</td>
                            <td class="text-end px-4">
                                <a href="{{ route('clientes.show', $cliente->id_cliente) }}" class="btn btn-sm btn-info text-white" title="Ver Detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-sm btn-warning text-dark mx-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Está seguro de eliminar este cliente? Esto podría afectar a sus pedidos.');">
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
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                No hay clientes registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($clientes->hasPages())
        <div class="card-footer bg-white py-3 border-top-0">
            {{ $clientes->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
