<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fitxaring | Gestor de Personal</title>
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
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
                transform: translateY(20px);
            }
            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
        }
    </style>
</head>

<body
    class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased min-h-screen bg-gradient-radial overflow-x-hidden selection:bg-blue-200 selection:text-blue-900">

    <nav
        class="absolute top-0 w-full p-6 lg:p-10 z-50 flex justify-between items-center max-w-7xl mx-auto left-0 right-0">
        <div class="flex items-center gap-3">
            <x-application-logo class="w-10 h-10" />
            <span class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Fitxaring</span>
        </div>

        <div>
            <!-- Botó d'inici eliminat de la nav per petició, mogut baix com a CTA -->
        </div>
    </nav>

    <main class="relative min-h-screen flex items-center pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">

            <!-- Esquerra: Copy i CTA -->
            <div class="max-w-2xl lg:pr-8 z-10 mx-auto text-center lg:text-left lg:mx-0">

                <h1
                    class="text-5xl sm:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-6 animate-fade-in-up delay-100 leading-[1.1]">
                    L'horari del teu equip <br class="hidden sm:block" />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">
                        sota control total
                    </span>
                </h1>

                <p
                    class="text-lg text-slate-600 dark:text-slate-400 mb-8 max-w-lg mx-auto lg:mx-0 mb-10 animate-fade-in-up delay-200">
                    Fitxaring digitalitza el control d'horaris, assignació de torns i el seguiment de presència en una
                    plataforma moderna que encanta als negocis i als empleats.
                </p>

                <div
                    class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 animate-fade-in-up delay-300">
                    @if (\Illuminate\Support\Facades\Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="font-semibold text-base bg-blue-600 hover:bg-indigo-600 text-white px-8 py-3.5 rounded-full shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5 hover:shadow-indigo-500/40 inline-flex items-center justify-center gap-2">
                                Anar al Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="font-semibold text-base bg-blue-600 hover:bg-indigo-600 text-white px-8 py-3.5 rounded-full shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5 hover:shadow-indigo-500/40 inline-flex items-center justify-center gap-2">
                                Iniciar Sessió
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Dreta: Elements visuals / App preview -->
            <div
                class="relative w-full h-[450px] sm:h-[550px] flex items-center justify-center animate-fade-in-up delay-300 mt-10 lg:mt-0">
                <!-- Cercle de fons blur -->
                <div class="absolute w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] bg-blue-500/20 rounded-full blur-3xl animate-float"
                    style="animation-duration: 9s"></div>

                <!-- Browser mockup -->
                <div class="relative w-full sm:w-[550px] h-full sm:h-[450px] bg-slate-50 dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-700/60 overflow-hidden flex flex-col z-10 animate-float"
                    style="animation-delay: 200ms;">
                    <!-- Browser Header -->
                    <div
                        class="bg-slate-200/50 dark:bg-slate-800/80 h-10 w-full flex items-center px-4 gap-2 border-b border-slate-200 dark:border-slate-800">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                        <div
                            class="mx-auto bg-white/60 dark:bg-slate-900 h-5 w-1/2 rounded-md border border-slate-300 dark:border-slate-700/50">
                        </div>
                    </div>

                    <!-- Browser Body (App Preview) -->
                    <div class="flex-1 flex overflow-hidden bg-white dark:bg-[#0a0f1d]">
                        <!-- Sidebar -->
                        <div
                            class="w-24 sm:w-32 border-r border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 p-4 flex flex-col gap-3">
                            <div
                                class="w-full h-8 bg-blue-600 rounded-lg shadow-sm shadow-blue-500/20 mb-3 flex items-center px-3 gap-2">
                                <div class="w-4 h-4 rounded-full bg-white opacity-80"></div>
                            </div>
                            <div class="w-3/4 h-3 bg-slate-200 dark:bg-slate-700/50 rounded-md"></div>
                            <div class="w-5/6 h-3 bg-slate-200 dark:bg-slate-700/50 rounded-md"></div>
                            <div class="w-4/5 h-3 bg-slate-200 dark:bg-slate-700/50 rounded-md"></div>
                        </div>
                        <!-- Main Content -->
                        <div class="flex-1 p-5 sm:p-6 flex flex-col gap-5 overflow-hidden">
                            <!-- Header Info -->
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="w-24 h-5 bg-slate-200 dark:bg-slate-700 rounded-md mb-2"></div>
                                    <div class="w-40 h-2 bg-slate-100 dark:bg-slate-700/50 rounded-md"></div>
                                </div>
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center border-2 border-white dark:border-slate-800">
                                </div>
                            </div>

                            <!-- Stats row -->
                            <div class="flex gap-3">
                                <div
                                    class="flex-1 h-20 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700 p-3 flex flex-col justify-between">
                                    <div class="w-12 h-2.5 bg-slate-200 dark:bg-slate-600 rounded-md"></div>
                                    <div class="flex items-end justify-between">
                                        <div class="w-10 h-6 bg-slate-800 dark:bg-white rounded-md"></div>
                                        <div class="w-6 h-4 bg-green-100 dark:bg-green-900/50 rounded-md"></div>
                                    </div>
                                </div>
                                <div
                                    class="flex-1 h-20 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700 p-3 flex flex-col justify-between">
                                    <div class="w-16 h-2.5 bg-slate-200 dark:bg-slate-600 rounded-md"></div>
                                    <div class="flex items-end justify-between">
                                        <div class="w-12 h-6 bg-slate-800 dark:bg-white rounded-md"></div>
                                        <div class="w-6 h-4 bg-red-100 dark:bg-red-900/50 rounded-md"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule abstract -->
                            <div
                                class="flex-1 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-100 dark:border-slate-700 flex flex-col">
                                <div
                                    class="h-10 border-b border-slate-100 dark:border-slate-700 flex items-center px-4 justify-between">
                                    <div class="w-20 h-3 bg-slate-200 dark:bg-slate-600 rounded-md"></div>
                                    <div class="w-4 h-4 bg-slate-200 dark:bg-slate-600 rounded-md"></div>
                                </div>
                                <div class="flex-1 p-3 flex flex-col gap-2 relative overflow-hidden">
                                    <div
                                        class="w-full h-12 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center px-3 border-l-4 border-blue-500">
                                        <div class="w-16 h-2 bg-blue-200 dark:bg-blue-800 rounded-md"></div>
                                    </div>
                                    <div
                                        class="w-full h-12 bg-purple-50 dark:bg-purple-900/20 rounded-lg flex items-center px-3 border-l-4 border-purple-500">
                                        <div class="w-20 h-2 bg-purple-200 dark:bg-purple-800 rounded-md"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>

</html>