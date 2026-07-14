<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Pedido - Stylo's S&R E.I.R.L.</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 4rem 0 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: #f8fafc;
            transform: skewY(-2deg);
        }
        .search-card {
            margin-top: -6rem;
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59,130,246,0.3);
        }
        .form-control {
            border-radius: 0.75rem;
            padding: 0.75rem 1.25rem;
        }
        .timeline {
            position: relative;
            padding-left: 2.5rem;
            margin-bottom: 0;
            list-style: none;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #e2e8f0;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }
        .timeline-item:nth-child(1) { animation-delay: 0.1s; }
        .timeline-item:nth-child(2) { animation-delay: 0.2s; }
        .timeline-item:nth-child(3) { animation-delay: 0.3s; }
        .timeline-item:nth-child(4) { animation-delay: 0.4s; }
        .timeline-item:nth-child(5) { animation-delay: 0.5s; }
        
        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.85rem;
            top: 0.25rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background-color: #3b82f6;
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        .result-card {
            border-radius: 1.5rem;
            animation: slideUp 0.6s ease-out 0.2s forwards;
            opacity: 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="container position-relative z-1">
            <h1 class="display-4 fw-bold mb-3 tracking-tight"><i class="bi bi-scissors me-2"></i>Stylo's S&R</h1>
            <p class="lead opacity-75 fw-light">Consulta el estado de tu confección en tiempo real</p>
        </div>
    </div>

    <div class="container mb-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card search-card mb-5 p-2">
                    <div class="card-body p-4 p-md-5">
                        
                        @if(session('error'))
                            <div class="alert alert-danger shadow-sm rounded-4 border-0 bg-danger text-white">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('consulta.buscar') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label for="codigo_pedido" class="form-label fw-bold text-secondary text-uppercase tracking-wider small">Ingresa tu Código de Pedido</label>
                                <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden">
                                    <span class="input-group-text bg-white border-0 text-primary ps-4"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control border-0 bg-white" id="codigo_pedido" name="codigo_pedido" placeholder="Ej: PED-1002" required value="{{ old('codigo_pedido') }}">
                                    <button class="btn btn-primary px-5 fw-bold text-uppercase" type="submit">Buscar</button>
                                </div>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted small">¿Eres personal autorizado? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Iniciar sesión</a></span>
                        </div>
                    </div>
                </div>

                @if(isset($pedido))
                    <div class="card shadow-lg border-0 result-card mb-4">
                        <div class="card-body p-5">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-4 border-bottom">
                                <div class="mb-3 mb-md-0">
                                    <span class="text-uppercase fw-bold text-primary small d-block mb-1">Pedido Encontrado</span>
                                    <h3 class="fw-bold mb-1 display-6">{{ $pedido->codigo_pedido }}</h3>
                                    <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm mt-2 fs-6">{{ $pedido->estado }}</span>
                                </div>
                                <div class="text-md-end bg-light p-3 rounded-4">
                                    <p class="text-muted small mb-1 text-uppercase fw-bold">Fecha de Pedido</p>
                                    <p class="fw-bold mb-0 text-dark fs-5">{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d M, Y') }}</p>
                                </div>
                            </div>

                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <div class="p-4 bg-light rounded-4 h-100 border border-white shadow-sm">
                                        <h6 class="fw-bold text-secondary mb-3 text-uppercase small"><i class="bi bi-person-badge text-primary me-2 fs-5"></i> Detalles del Cliente</h6>
                                        <p class="mb-1 fw-semibold text-dark fs-5">{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 bg-light rounded-4 h-100 border border-white shadow-sm">
                                        <h6 class="fw-bold text-secondary mb-3 text-uppercase small"><i class="bi bi-calendar-check text-primary me-2 fs-5"></i> Fechas Importantes</h6>
                                        <p class="mb-2"><span class="text-muted">Entrega Estimada:</span> <strong class="text-dark">{{ \Carbon\Carbon::parse($pedido->fecha_entrega_estimada)->format('d M, Y') }}</strong></p>
                                        @if($pedido->fecha_entrega_real)
                                            <p class="mb-0 text-success"><i class="bi bi-check-circle-fill me-1"></i> <strong>Entregado el:</strong> {{ \Carbon\Carbon::parse($pedido->fecha_entrega_real)->format('d M, Y') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold text-secondary mb-4 text-uppercase small"><i class="bi bi-tags text-primary me-2 fs-5"></i> Prendas Solicitadas</h6>
                            <div class="row g-3 mb-5">
                                @foreach($pedido->detalles as $detalle)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="border rounded-4 p-3 text-center position-relative shadow-sm hover-elevate transition-all">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary shadow-sm" style="font-size:0.9rem;">
                                                x{{ $detalle->cantidad }}
                                            </span>
                                            <i class="bi bi-bag-check text-secondary fs-2 mb-2 d-block opacity-50"></i>
                                            <span class="fw-semibold text-dark d-block">{{ $detalle->descripcion_prenda }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <h6 class="fw-bold text-secondary mb-4 text-uppercase small"><i class="bi bi-geo-alt text-primary me-2 fs-5"></i> Línea de Tiempo del Proceso</h6>
                            <div class="bg-light p-4 p-md-5 rounded-4 shadow-inner border border-white">
                                @if($pedido->seguimientos->count() > 0)
                                    <ul class="timeline">
                                        @foreach($pedido->seguimientos as $seguimiento)
                                            <li class="timeline-item">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold text-dark fs-5">{{ $seguimiento->estado }}</span>
                                                    <span class="badge bg-white text-primary border border-primary rounded-pill px-3">{{ $seguimiento->porcentaje_avance }}%</span>
                                                </div>
                                                <div class="text-muted small"><i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($seguimiento->fecha_actualizacion)->format('d M Y, h:i A') }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-hourglass-split text-muted fs-1 mb-3 d-block opacity-50"></i>
                                        <p class="text-muted mb-0">Aún no hay actualizaciones de seguimiento. Tu pedido acaba de ser registrado.</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <footer class="bg-white py-4 mt-auto border-top">
        <div class="container text-center">
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} Stylo's S&R E.I.R.L. - Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
