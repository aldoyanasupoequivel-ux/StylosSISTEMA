@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Registrar Material</h2>
    <a href="{{ route('materiales.index') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Volver al Inventario
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Datos del Material/Insumo</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('materiales.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <label for="nombre_material" class="form-label fw-semibold">Nombre del Material <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre_material') is-invalid @enderror" id="nombre_material" name="nombre_material" value="{{ old('nombre_material') }}" required placeholder="Ej. Tela Casimir, Hilo Negro, Botones...">
                            @error('nombre_material') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="unidad_medida" class="form-label fw-semibold">Unidad de Medida <span class="text-danger">*</span></label>
                            <select class="form-select @error('unidad_medida') is-invalid @enderror" id="unidad_medida" name="unidad_medida" required>
                                <option value="Metros" {{ old('unidad_medida') == 'Metros' ? 'selected' : '' }}>Metros</option>
                                <option value="Unidades" {{ old('unidad_medida') == 'Unidades' ? 'selected' : '' }}>Unidades</option>
                                <option value="Conos" {{ old('unidad_medida') == 'Conos' ? 'selected' : '' }}>Conos</option>
                                <option value="Cajas" {{ old('unidad_medida') == 'Cajas' ? 'selected' : '' }}>Cajas</option>
                                <option value="Metros Cuadrados" {{ old('unidad_medida') == 'Metros Cuadrados' ? 'selected' : '' }}>Metros Cuadrados</option>
                            </select>
                            @error('unidad_medida') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="stock_actual" class="form-label fw-semibold">Stock Inicial (Actual) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock_actual') is-invalid @enderror" id="stock_actual" name="stock_actual" value="{{ old('stock_actual', 0) }}" required min="0" step="0.01">
                            @error('stock_actual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stock_minimo" class="form-label fw-semibold">Stock Mínimo de Alerta <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock_minimo') is-invalid @enderror" id="stock_minimo" name="stock_minimo" value="{{ old('stock_minimo', 0) }}" required min="0" step="0.01">
                            <div class="form-text">Te avisaremos cuando el stock llegue a este nivel.</div>
                            @error('stock_minimo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label fw-semibold">Descripción (Opcional)</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Guardar Material</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
