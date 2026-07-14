<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylo's S&R E.I.R.L. - Sistema Web</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0f172a;
            --secondary-color: #334155;
            --accent-color: #3b82f6;
            --bg-color: #f8fafc;
            --text-color: #1e293b;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1e293b 100%);
            color: white;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .brand {
            padding: 1.75rem 1.5rem;
            font-size: 1.35rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            padding: 1.5rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-nav-item {
            padding: 0.25rem 1.25rem;
        }

        .sidebar-nav-link {
            color: #94a3b8;
            text-decoration: none;
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-nav-link i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .sidebar-nav-link:hover, .sidebar-nav-link.active {
            background-color: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            transform: translateX(5px);
        }

        .sidebar-nav-link:hover i, .sidebar-nav-link.active i {
            transform: scale(1.1);
        }

        /* Content Area */
        .main-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Top Navbar */
        .top-navbar {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .content-body {
            padding: 2.5rem;
            flex: 1;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }

        /* Buttons */
        .btn {
            border-radius: 0.75rem;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            box-shadow: 0 6px 8px -1px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100vh;
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .content-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

    @auth
        <div class="wrapper">
            <!-- Sidebar -->
            <aside class="sidebar">
                <a href="{{ route('dashboard') }}" class="brand">
                    <i class="bi bi-scissors"></i> Stylo's S&R
                </a>
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid"></i> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('pedidos.index') }}" class="sidebar-nav-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-check"></i> Pedidos
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('materiales.index') }}" class="sidebar-nav-link {{ request()->routeIs('materiales.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i> Inventario
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('alertas.index') }}" class="sidebar-nav-link {{ request()->routeIs('alertas.*') ? 'active' : '' }}">
                            <i class="bi bi-bell"></i> Alertas
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('reportes.index') }}" class="sidebar-nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                            <i class="bi bi-bar-chart"></i> Reportes
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('cuenta.index') }}" class="sidebar-nav-link {{ request()->routeIs('cuenta.*') ? 'active' : '' }}">
                            <i class="bi bi-person-gear"></i> Mi Cuenta
                        </a>
                    </li>
                </ul>
            </aside>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Top Navbar -->
                <header class="top-navbar">
                    <div>
                        <button class="btn btn-light d-md-none" id="sidebarToggle">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                {{ substr(Auth::user()->nombre, 0, 1) }}
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="{{ route('cuenta.index') }}"><i class="bi bi-person me-2"></i> Mi Cuenta</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="content-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Por favor corrige los errores indicados.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if(sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.toggle('show');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
