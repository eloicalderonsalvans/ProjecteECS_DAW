<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de personal - Fitxaring</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --bg-body: #f3f4f6;
            --bg-nav: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --primary-light: #e0e7ff;
            --primary-dark: #4338ca;
            --danger-light: #fee2e2;
            --danger: #ef4444;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        /* Barra de navegación */
        .navbar {
            background-color: var(--bg-nav);
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            position: sticky;
            top: 0;
        }

        .nav-group {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        /* Insignia del usuario */
        .user-badge {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Enlaces de navegación */
        .nav-links {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            background-color: var(--bg-body);
            color: var(--text-main);
        }

        /* Botón de salir */
        .btn-logout {
            color: var(--danger);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            display: inline-block;
        }

        .btn-logout:hover {
            background-color: var(--danger-light);
        }

        /* Contenedor principal */
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-group">
            <span class="user-badge">{{ Auth::user()->nom }}</span>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Inici</a>
                <a href="{{ route('users.index') }}">Usuaris</a>
                <a href="{{ route('departments.index') }}">Departaments</a>
                <a href="{{ route('fixatges.index') }}">Fitxar</a>
                <a href="{{ route('horaris.index') }}">Horaris</a>
                <a href="{{ route('absencies.index') }}">Absències</a>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <a href="#" class="btn-logout" onclick="event.preventDefault(); this.closest('form').submit();">Sortir</a>
        </form>
    </nav>

    <main class="main-content">
        @yield('content') {{ $slot ?? '' }} 
    </main>
</body>
</html>