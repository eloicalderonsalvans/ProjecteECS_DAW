<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de personal - Fitxaring</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Permet injectar CSS o scripts específics de cada vista -->
    @yield('styles')
    @yield('scripts_head')
    
    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-nav: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --primary-dark: #1e40af;
            --danger: #ef4444;
            --danger-light: #fef2f2;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        /* Barra de navegació */
        .navbar {
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 0.75rem 2rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        .nav-group {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        /* Insignia de l'usuari */
        .user-badge {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.85rem;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .role-badge {
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
            border-radius: 0.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .role-badge--admin {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .role-badge--user {
            background: #e2e8f0;
            color: #64748b;
        }

        /* Enllaços de navegació */
        .nav-links {
            display: flex;
            gap: 0.25rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            padding: 0.5rem 0.85rem;
            border-radius: 0.5rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
        }

        .nav-links a:hover {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        .nav-links a.active {
            color: var(--primary);
            background-color: var(--primary-light);
        }

        /* Botó de sortir */
        .btn-logout {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f1f5f9;
        }

        .btn-logout:hover {
            color: var(--danger);
            background-color: var(--danger-light);
        }

        /* Contenidor principal */
        .main-content {
            max-width: 1280px;
            margin: 6rem auto 2rem;
            padding: 0 2rem;
        }

        /* Missatge d'error per permisos */
        .alert-error {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: 0 0.5rem 0.5rem 0;
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Barra de navegació principal -->
    <nav class="navbar">
        <div class="nav-group">
            <!-- Nom de l'usuari autenticat + badge de rol -->
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="user-badge">{{ \Illuminate\Support\Facades\Auth::user()->nom }}</span>
                @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                    <span class="role-badge role-badge--admin">Admin</span>
                @else
                    <span class="role-badge role-badge--user">Empleat</span>
                @endif
            </div>
            
            <!-- Enllaços de navegació condicionals segons el rol -->
            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Inici</a>

                @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                    {{-- Navegació completa per a administradors --}}
                    <a href="{{ route('users.index') }}">Usuaris</a>
                    <a href="{{ route('departments.index') }}">Departaments</a>
                    <a href="{{ route('horaris.index') }}">Horaris</a>
                    <a href="{{ route('absencies.index') }}">Absències</a>
                    <a href="{{ route('torns.index') }}">Torns</a>
                @else
                    {{-- Navegació simplificada per a usuaris normals --}}
                    <a href="{{ route('horaris.index') }}">El meu Calendari</a>
                    <a href="{{ route('absencies.index') }}">Les meves Absències</a>
                @endif
            </div>
        </div>
        
        <!-- Botó de tancar sessió (Logout) -->
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <a href="#" class="btn-logout" onclick="event.preventDefault(); this.closest('form').submit();">Sortir</a>
        </form>
    </nav>

    <!-- Contingut principal injectat des de les vistes -->
    <main class="main-content">
        {{-- Missatge d'error de permisos --}}
        @if(session('error'))
            <div class="alert-error">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        @yield('content') {{ $slot ?? '' }} 
    </main>

    <!-- Injecció de scripts específics per a vistes que ho necessitin -->
    @yield('scripts_body')
</body>
</html>