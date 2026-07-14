@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Alertas</h2>
</div>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-body">
        <form action="{{ route('alertas.index') }}" method="GET" class="d-flex align-items-center justify-content-center gap-3">
            <span class="text-muted fw-semibold">Mostrar pedidos que vencen en los próximos:</span>
            <select name="dias" class="form-select w-auto shadow-none" onchange="this.form.submit()">
                <option value="3" {{ $dias == 3 ? 'selected' : '' }}>3</option>
                <option value="5" {{ $dias == 5 ? 'selected' : '' }}>5</option>
                <option value="7" {{ $dias == 7 ? 'selected' : '' }}>7</option>
                <option value="15" {{ $dias == 15 ? 'selected' : '' }}>15</option>
                <option value="30" {{ $dias == 30 ? 'selected' : '' }}>30</option>
            </select>
            <span class="text-muted fw-semibold">días</span>
        </form>
    </div>
</div>

<div class="row">
    <!-- Pedidos Próximos a Vencer -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 h-100 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                <h6 class="m-0 fw-bold text-primary">Pedidos próximos a vencer</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Código</th>
                                <th class="py-3">Cliente</th>
                                <th class="py-3 text-center">Entrega Estimada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidosProximos as $pedido)
                                <tr>
                                    <td class="px-4 py-3 fw-bold">
                                        <a href="{{ route('pedidos.show', $pedido->id_pedido) }}" class="text-primary text-decoration-none">
                                            {{ $pedido->codigo_pedido }}
                                        </a>
                                    </td>
                                    <td class="py-3">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-1 fw-semibold">
                                            {{ \Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">No hay pedidos próximos a vencer en el rango seleccionado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center py-4 bg-light border-top">
                    <a href="{{ route('pedidos.index') }}" class="btn btn-sm btn-outline-primary px-4 rounded-1">Ver todos los pedidos</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Materiales con stock bajo -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 h-100 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                <h6 class="m-0 fw-bold text-danger">Materiales con stock bajo</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Material</th>
                                <th class="py-3 text-center">Stock Actual</th>
                                <th class="py-3 text-center">Stock Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materialesBajos as $material)
                                <tr>
                                    <td class="px-4 py-3 fw-bold">
                                        <a href="{{ route('materiales.edit', $material->id_material) }}" class="text-primary text-decoration-none">
                                            {{ $material->nombre }}
                                        </a>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge border border-danger text-danger bg-danger bg-opacity-10 rounded-1 px-3 py-2 fw-semibold">
                                            {{ number_format($material->stock_actual, 2) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center text-muted">{{ number_format($material->stock_minimo, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">Todos los materiales tienen stock suficiente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center py-4 bg-light border-top">
                    <a href="{{ route('materiales.index') }}" class="btn btn-sm btn-outline-primary px-4 rounded-1">Ver inventario completo</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
