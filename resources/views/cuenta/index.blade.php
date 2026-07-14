@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Mi Cuenta</h2>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Actualizar Datos</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('cuenta.usuario') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Nombre</label>
                        <input type="text" class="form-control bg-light" value="{{ $admin->nombre }} {{ $admin->apellido }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Correo Electrónico</label>
                        <input type="email" class="form-control bg-light" value="{{ $admin->correo }}" disabled>
                    </div>

                    <div class="mb-4">
                        <label for="usuario" class="form-label fw-semibold">Nombre de Usuario</label>
                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario', $admin->usuario) }}" required>
                        @error('usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Cambiar Contraseña</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('cuenta.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="password_actual" class="form-label fw-semibold">Contraseña Actual</label>
                        <input type="password" class="form-control @error('password_actual') is-invalid @enderror" id="password_actual" name="password_actual" required>
                        @error('password_actual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Nueva Contraseña</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-key me-1"></i> Actualizar Contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
