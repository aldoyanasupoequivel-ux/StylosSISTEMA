@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-5">
                <h3 class="fw-bold mb-4 text-dark">Nuevo pedido</h3>
                
                <form action="{{ route('pedidos.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-semibold text-dark">Nombre completo</label>
                            <input type="text" name="nombre_completo" class="form-control" placeholder="Ingrese nombre completo" required value="{{ old('nombre_completo') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">DNI</label>
                            <input type="text" name="dni" class="form-control" placeholder="Ingrese DNI" required value="{{ old('dni') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Ingrese teléfono" required value="{{ old('telefono') }}">
                    </div>

                    <h5 class="fw-bold mb-3 mt-4 text-dark">Detalle del pedido</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Descripción y especificaciones</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Detalle las prendas, telas, tallas, medidas, color, etc." required>{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-semibold text-dark">Monto total (S/)</label>
                            <input type="number" name="total" class="form-control" placeholder="0.00" step="0.01" min="0" required value="{{ old('total') }}">
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label fw-semibold text-dark">Adelanto (S/)</label>
                            <input type="number" name="adelanto" class="form-control" placeholder="0.00" step="0.01" min="0" value="{{ old('adelanto') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">Fecha estimada</label>
                            <input type="date" name="fecha_entrega_estimada" class="form-control" required value="{{ old('fecha_entrega_estimada') }}">
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary w-100 py-2 fw-semibold">Cancelar</a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold text-white">Guardar pedido</button>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">El código del pedido se generará automáticamente</small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
    }
    .btn {
        border-radius: 6px;
    }
    .btn-outline-secondary {
        border-color: #cbd5e1;
        color: #475569;
    }
    .btn-outline-secondary:hover {
        background-color: #f8fafc;
        color: #1e293b;
        border-color: #94a3b8;
    }
</style>
@endsection
