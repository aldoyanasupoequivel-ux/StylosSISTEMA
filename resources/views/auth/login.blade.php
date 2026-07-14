@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background: linear-gradient(135deg, #0f172a 0%, #334155 100%);">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 400px; border-radius: 1.5rem; overflow: hidden;">
        
        <!-- Decoration top -->
        <div style="height: 6px; background: linear-gradient(90deg, #3b82f6, #60a5fa);"></div>

        <div class="card-body p-5 bg-white">
            <div class="text-center mb-4 pb-2 border-bottom">
                <i class="bi bi-scissors text-primary display-4 mb-2 d-inline-block"></i>
                <h2 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Stylo's S&R</h2>
                <p class="text-muted small text-uppercase tracking-wider fw-bold mt-2">Acceso Administrativo</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger p-2 text-center small rounded-3 shadow-sm border-0">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="usuario" class="form-label text-muted small fw-bold">Usuario</label>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-person text-secondary"></i></span>
                        <input type="text" class="form-control border-0 bg-light py-2" id="usuario" name="usuario" required autofocus placeholder="Ingresa tu usuario">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label text-muted small fw-bold">Contraseña</label>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-secondary"></i></span>
                        <input type="password" class="form-control border-0 bg-light py-2" id="password" name="password" required placeholder="••••••••">
                    </div>
                </div>

                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary py-2 fw-bold text-uppercase rounded-3 shadow-sm" style="letter-spacing: 1px;">
                        Ingresar al Sistema
                    </button>
                </div>
                
                <div class="text-center mt-4 pt-3 border-top">
                    <a href="{{ route('consulta.index') }}" class="text-decoration-none text-muted small fw-bold hover-primary transition-all">
                        <i class="bi bi-arrow-left me-1"></i> Volver a consulta de pedidos
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hover-primary:hover {
        color: #3b82f6 !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection
