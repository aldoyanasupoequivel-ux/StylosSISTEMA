@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-dark fw-bold">Inventario (materiales)</h2>
    <a href="{{ route('materiales.create') }}" class="btn btn-outline-dark bg-white">
        <i class="bi bi-plus-lg me-1"></i> Nuevo material
    </a>
</div>

<div class="card shadow-sm border-0 rounded-0">
    <div class="card-body p-4">
        
        <!-- Controles de búsqueda y filtros -->
        <div class="row mb-4">
            <div class="col-md-5 mb-3 mb-md-0">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Buscar material...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="todos">Todos</option>
                    <option value="ok">Stock OK</option>
                    <option value="bajo">Stock Bajo</option>
                </select>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success p-2 small border-0 shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="fw-bold py-3 px-3">Código</th>
                        <th class="fw-bold py-3">Material</th>
                        <th class="fw-bold py-3 text-center">Unidad</th>
                        <th class="fw-bold py-3 text-center">Stock Actual</th>
                        <th class="fw-bold py-3 text-center">Stock Mínimo</th>
                        <th class="fw-bold py-3 text-center">Estado</th>
                        <th class="fw-bold py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materiales as $material)
                        @php
                            $codigo = 'MAT-' . str_pad($material->id_material, 3, '0', STR_PAD_LEFT);
                            $isLowStock = $material->stock_actual <= $material->stock_minimo;
                        @endphp
                        <tr>
                            <td class="px-3">{{ $codigo }}</td>
                            <td>{{ $material->nombre }}</td>
                            <td class="text-center">{{ $material->unidad_medida == 'Unidades' ? 'und' : ($material->unidad_medida == 'Metros' ? 'm' : $material->unidad_medida) }}</td>
                            <td class="text-center">{{ number_format($material->stock_actual, 2) }}</td>
                            <td class="text-center">{{ number_format($material->stock_minimo, 2) }}</td>
                            <td class="text-center">
                                @if($isLowStock)
                                    <span class="badge border border-danger text-danger bg-danger bg-opacity-10 rounded-1 px-3 py-2 fw-semibold">Bajo</span>
                                @else
                                    <span class="badge border border-success text-success bg-success bg-opacity-10 rounded-1 px-3 py-2 fw-semibold">OK</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('materiales.edit', $material->id_material) }}" class="btn btn-sm btn-outline-dark rounded-1 px-3 py-1">Editar</a>
                                    <form action="{{ route('materiales.destroy', $material->id_material) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este material? Esto podría afectar a los movimientos registrados.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-1 px-3 py-1">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-box-seam fs-1 d-block mb-3 opacity-50"></i>
                                    No hay materiales registrados aún.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $materiales->links() }}
        </div>
    </div>
</div>

<style>
    .form-control, .form-select, .input-group-text {
        border-color: #333;
        border-radius: 2px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #333;
        box-shadow: none;
    }
    .table th, .table td {
        border-color: #333;
    }
    .table-light {
        background-color: #fff;
    }
    .btn-outline-dark {
        border-radius: 2px;
    }
</style>
@endsection
