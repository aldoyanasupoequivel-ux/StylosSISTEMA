@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Movimientos: {{ $material->nombre_material }}</h2>
    <div>
        <a href="{{ route('materiales.edit', $material->id_material) }}" class="btn btn-warning text-dark shadow-sm me-2">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="{{ route('materiales.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <!-- Resumen de Material y Formulario de Movimiento -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-info-circle me-1"></i> Resumen de Stock</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <h1 class="display-4 fw-bold {{ $material->stock_actual <= $material->stock_minimo ? 'text-danger' : 'text-success' }}">
                        {{ $material->stock_actual }}
                    </h1>
                    <p class="text-muted text-uppercase mb-0">{{ $material->unidad_medida }} en inventario</p>
                </div>
                <hr>
                <div class="mb-3">
                    <span class="text-muted small d-block">Stock Mínimo (Alerta)</span>
                    <span class="fw-semibold">{{ $material->stock_minimo }} {{ $material->unidad_medida }}</span>
                </div>
                @if($material->descripcion)
                    <div class="mb-3">
                        <span class="text-muted small d-block">Descripción</span>
                        <p class="mb-0 small text-secondary">{{ $material->descripcion }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabla de Movimientos -->
    <div class="col-lg-8">
        <!-- Formulario Rápido -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-plus-slash-minus me-1"></i> Registrar Movimiento</h6>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger p-2 small">{{ session('error') }}</div>
                @endif
                <form action="{{ route('movimientos.store', $material->id_material) }}" method="POST">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label text-muted small">Tipo</label>
                            <select name="tipo_movimiento" class="form-select" required>
                                <option value="Entrada">Entrada (+)</option>
                                <option value="Salida">Salida (-)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="form-label text-muted small">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" min="0.01" step="0.01" required placeholder="Ej: 5">
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label text-muted small">Observación</label>
                            <input type="text" name="observaciones" class="form-control" placeholder="Motivo (Opcional)">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Historial -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="bi bi-clock-history me-1"></i> Historial de Entradas y Salidas</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Fecha</th>
                                <th>Tipo</th>
                                <th class="text-end">Cantidad</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimientos as $mov)
                                <tr>
                                    <td class="px-4 text-muted small">{{ \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y h:i A') }}</td>
                                    <td>
                                        @if($mov->tipo_movimiento == 'Entrada')
                                            <span class="badge bg-success"><i class="bi bi-arrow-down-left-circle me-1"></i> Entrada</span>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-arrow-up-right-circle me-1"></i> Salida</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold {{ $mov->tipo_movimiento == 'Entrada' ? 'text-success' : 'text-danger' }}">
                                        {{ $mov->tipo_movimiento == 'Entrada' ? '+' : '-' }}{{ $mov->cantidad }}
                                    </td>
                                    <td class="text-muted small">{{ $mov->observaciones ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        No hay movimientos registrados para este material.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($movimientos->hasPages())
                <div class="card-footer bg-white py-3 border-top-0">
                    {{ $movimientos->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
