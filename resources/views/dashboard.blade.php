<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-500">
                {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold mb-2">
                        Hola, {{ auth()->user()->nom }}! 👋
                    </h3>
                    <p class="text-gray-600 italic">
                        {{ __("Benvingut de nou al teu panell de control.") }}
                    </p>
                </div>
            </div>

            {{-- SECCIÓ EXCLUSIVA ADMIN: Resum d'absències pendents --}}
            @if(auth()->user()->isAdmin())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('absencies.index') }}"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-amber-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-3xl font-extrabold text-amber-600">{{ $absenciesPendents ?? 0 }}</p>
                                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Absències Pendents</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('users.index') }}"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-blue-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Gestió d'Usuaris</p>
                                <p class="text-xs text-gray-400 mt-0.5">Crear, editar i administrar</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('horaris.index') }}"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Calendari d'Horaris</p>
                                <p class="text-xs text-gray-400 mt-0.5">Assignar i consultar torns</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">La teva Informació</h4>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-1 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Nom:</span>
                            <span class="text-gray-800">{{ auth()->user()->nom }} {{ auth()->user()->cognom }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Email:</span>
                            <span class="text-gray-800">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500 font-medium">DNI:</span>
                            <span class="text-gray-800">{{ auth()->user()->DNI }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Dades Professionals</h4>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-1 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Rol:</span>
                            @if(auth()->user()->isAdmin())
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded uppercase">
                                    Administrador
                                </span>
                            @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded uppercase">
                                    {{ auth()->user()->role }}
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">Departament:</span>
                            <span class="text-gray-800 font-semibold text-right">
                                {{ auth()->user()->departament->nom ?? 'Sense assignar'}}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500 font-medium">Alta:</span>
                            <span class="text-gray-800">{{ auth()->user()->data_alta->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓ: Dies de Vacances (per a tots els usuaris) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">🏖️ Dies de Vacances
                    ({{ now()->year }})</h4>

                <div class="flex items-center gap-6">
                    {{-- Gràfic circular visual --}}
                    <div class="relative w-24 h-24 flex-shrink-0">
                        @php
                            $pct = $diesVacancesTotal > 0 ? ($diesVacancesConsumits / $diesVacancesTotal) * 100 : 0;
                            $circumference = 2 * 3.14159 * 40;
                            $dashOffset = $circumference - ($circumference * min($pct, 100) / 100);
                            $color = $pct < 60 ? '#10b981' : ($pct < 90 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#e5e7eb" stroke-width="8" />
                            <circle cx="50" cy="50" r="40" fill="none" stroke="{{ $color }}" stroke-width="8"
                                stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $dashOffset }}"
                                stroke-linecap="round" class="transition-all duration-700" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-lg font-extrabold"
                                style="color: {{ $color }}">{{ $diesVacancesRestants }}</span>
                        </div>
                    </div>

                    {{-- Detalls --}}
                    <div class="flex-1 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Dies restants</span>
                            <span class="text-xl font-extrabold"
                                style="color: {{ $diesVacancesRestants > 10 ? '#065f46' : ($diesVacancesRestants > 0 ? '#92400e' : '#991b1b') }};">{{ $diesVacancesRestants }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Dies consumits</span>
                            <span class="text-sm font-bold text-gray-700">{{ $diesVacancesConsumits }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full transition-all duration-500"
                                style="width: {{ min($pct, 100) }}%; background-color: {{ $color }};"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>0</span>
                            <span>{{ $diesVacancesTotal }} dies totals</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓ EXCLUSIVA USUARI NORMAL: Accesos ràpids --}}
            @if(!auth()->user()->isAdmin())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('horaris.index') }}"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-indigo-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-700">El meu Calendari</p>
                                <p class="text-xs text-gray-400 mt-0.5">Consulta els teus torns assignats</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('absencies.create') }}"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 hover:border-emerald-300 hover:shadow-md transition-all group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-700">Sol·licitar Absència</p>
                                <p class="text-xs text-gray-400 mt-0.5">Demana dies lliures o baixa</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>