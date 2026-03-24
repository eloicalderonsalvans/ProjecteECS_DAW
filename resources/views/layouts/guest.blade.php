<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Fitxaring') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'media',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
        <style type="text/tailwindcss">
            @layer utilities {
                .bg-gradient-radial {
                    background-image: radial-gradient(circle at top right, rgba(59, 130, 246, 0.2) 0%, transparent 40%),
                                      radial-gradient(circle at bottom left, rgba(139, 92, 246, 0.2) 0%, transparent 40%);
                }
                .glass-card {
                    background: rgba(255, 255, 255, 0.7);
                    backdrop-filter: blur(16px);
                    -webkit-backdrop-filter: blur(16px);
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
                }
                @media (prefers-color-scheme: dark) {
                    .glass-card {
                        background: rgba(30, 41, 59, 0.7);
                        border: 1px solid rgba(255, 255, 255, 0.05);
                        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
                    }
                }
            }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 dark:text-slate-100 antialiased bg-slate-50 dark:bg-slate-900 bg-gradient-radial flex flex-col justify-center items-center min-h-screen selection:bg-blue-200 selection:text-blue-900 relative overflow-hidden">
        
        <!-- Fons fosc blur radial extras -->
        <div class="absolute w-96 h-96 bg-blue-500/20 rounded-full blur-3xl top-0 left-0 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl bottom-0 right-0 translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

        <div class="mb-10 relative z-10 w-full flex justify-center">
            <a href="/" class="flex flex-col items-center gap-4 transition-transform hover:scale-105 active:scale-95">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-4xl shadow-xl shadow-blue-500/30">
                    F
                </div>
                <span class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white select-none">Fitxaring</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 glass-card sm:rounded-3xl shadow-2xl relative z-10 box-border mx-4 sm:mx-0">
            {{ $slot }}
        </div>

    </body>
</html>
