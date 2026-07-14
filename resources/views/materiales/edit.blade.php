@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-dark fw-bold">Editar Material</h2>
    <a href="{{ route('materiales.index') }}" class="btn btn-outline-dark bg-white">
        <i class="bi bi-arrow-left me-1"></i> Volver al Inventario
    </a>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <!-- Tarjeta de Editar Datos Básicos -->
        <div class="card shadow-sm border-0 rounded-0 h-100">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="m-0 fw-bold text-primary">Actualizar Datos de Material</h6>
            </div>
            <div class="card-body p-4">
                
                @if(session('success'))
                    <div class="alert alert-success p-2 small border-0 shadow-sm mb-4">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger p-2 small border-0 shadow-sm mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('materiales.update', $material->id_material) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <label for="nombre_material" class="form-label fw-semibold text-dark small">Nombre del Material <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre_material') is-invalid @enderror" id="nombre_material" name="nombre_material" value="{{ old('nombre_material', $material->nombre) }}" required>
                            @error('nombre_material') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="unidad_medida" class="form-label fw-semibold text-dark small">Unidad de Medida <span class="text-danger">*</span></label>
                            <select class="form-select @error('unidad_medida') is-invalid @enderror" id="unidad_medida" name="unidad_medida" required>
                                <option value="Metros" {{ old('unidad_medida', $material->unidad_medida) == 'Metros' ? 'selected' : '' }}>Metros</option>
                                <option value="Unidades" {{ old('unidad_medida', $material->unidad_medida) == 'Unidades' ? 'selected' : '' }}>Unidades</option>
                                <option value="Conos" {{ old('unidad_medida', $material->unidad_medida) == 'Conos' ? 'selected' : '' }}>Conos</option>
                                <option value="Cajas" {{ old('unidad_medida', $material->unidad_medida) == 'Cajas' ? 'selected' : '' }}>Cajas</option>
                                <option value="Metros Cuadrados" {{ old('unidad_medida', $material->unidad_medida) == 'Metros Cuadrados' ? 'selected' : '' }}>Metros Cuadrados</option>
                            </select>
                            @error('unidad_medida') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stock_minimo" class="form-label fw-semibold text-dark small">Stock Mínimo de Alerta <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock_minimo') is-invalid @enderror" id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', $material->stock_minimo) }}" required min="0" step="0.01">
                        @error('stock_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label fw-semibold text-dark small">Descripción (Opcional)</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $material->descripcion) }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning px-4 rounded-1"><i class="bi bi-save me-1"></i> Actualizar Material</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <!-- Tarjeta de Registrar Movimiento -->
        <div class="card shadow-sm border-0 rounded-0 h-100 bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-box-arrow-in-right me-1"></i> Registrar Movimiento (Gastos/Ingresos)</h6>
                
                <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white border rounded-1">
                    <span class="text-muted small fw-semibold text-uppercase">Stock Actual:</span>
                    <span class="fs-4 fw-bold text-primary">{{ number_format($material->stock_actual, 2) }} <small class="fs-6 text-muted">{{ $material->unidad_medida }}</small></span>
                </div>

                <form action="{{ route('movimientos.store', $material->id_material) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Tipo de Movimiento</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_movimiento" id="tipoEntrada" value="Entrada" required>
                                <label class="form-check-label text-success fw-semibold" for="tipoEntrada">
                                    <i class="bi bi-arrow-down-circle"></i> Ingreso (+ stock)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_movimiento" id="tipoSalida" value="Salida" required>
                                <label class="form-check-label text-danger fw-semibold" for="tipoSalida">
                                    <i class="bi bi-arrow-up-circle"></i> Gasto (- stock)
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cantidad" class="form-label fw-semibold text-dark small">Cantidad a mover <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="0.01" step="0.01" placeholder="Ej. 5.50" required>
                    </div>

                    <div class="mb-4">
                        <label for="observacion" class="form-label fw-semibold text-dark small">Motivo / Observación</label>
                        <input type="text" name="observacion" id="observacion" class="form-control" placeholder="Ej. Uso en pedido #0012, Compra a proveedor..." maxlength="100">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-1 py-2 fw-semibold">Guardar Movimiento</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select, .input-group-text {
        border-color: #cbd5e1;
        border-radius: 2px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #333;
        box-shadow: none;
    }
    .btn-outline-dark {
        border-radius: 2px;
    }
</style>
@endsection
