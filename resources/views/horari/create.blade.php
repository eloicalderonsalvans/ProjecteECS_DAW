@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Capçalera -->
    <div class="mb-8">
        <a href="{{ route('horaris.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-4 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tornar al calendari
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Assignar Nous Torns</h1>
        <p class="text-slate-500 font-medium mt-1">Defineix el període i el tipus de torn per als empleats.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center shadow-sm animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden">
        <form action="{{ route('horaris.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Selecció d'Usuari -->
                <div class="space-y-2">
                    <label for="user_id" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Empleat
                    </label>
                    <select name="user_id" id="user_id" required class="block w-full pl-4 pr-10 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700 transition-all cursor-pointer">
                        <option value="">-- Selecciona un empleat --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->cognom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Selecció de Torn -->
                <div class="md:col-span-2 space-y-4">
                    <label class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tipus de Torn (Escull un)
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach($torns as $torn)
                            <label class="relative flex flex-col items-center group cursor-pointer">
                                <input type="radio" name="torn_id" value="{{ $torn->id }}" required class="peer sr-only">
                                <div class="w-full flex flex-col items-center p-6 bg-white border-2 border-slate-100 rounded-3xl peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:shadow-lg peer-checked:shadow-indigo-500/10 transition-all duration-300 group-hover:bg-slate-50 group-hover:border-slate-200">
                                    <div class="mb-4 w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform duration-300 group-hover:scale-110" style="background-color: {{ $torn->color }}20;">
                                        @php
                                            $nomTorn = strtolower($torn->nom);
                                            $icon = 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l.707.707M6.343 6.343l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z'; // Default sun
                                            if (str_contains($nomTorn, 'tarda')) $icon = 'M12 3v1.066c0 .588-.478 1.066-1.067 1.066A6.933 6.933 0 1017.868 11.1c0-.588.477-1.067 1.066-1.067H20a8 8 0 11-8-8V3z'; // Sunset/Moon
                                            if (str_contains($nomTorn, 'nit')) $icon = 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'; // Moon
                                            if (str_contains($nomTorn, 'reforç')) $icon = 'M13 10V3L4 14h7v7l9-11h-7z'; // Energy/Lightning
                                        @endphp
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="{{ $torn->color }}" style="stroke-width: 2.5;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-800 peer-checked:text-indigo-900 mb-1">{{ $torn->nom }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter peer-checked:text-indigo-500/80">Torn Oficial</span>
                                </div>
                                <div class="absolute -top-2 -right-2 bg-indigo-600 text-white rounded-full p-1 shadow-lg opacity-0 peer-checked:opacity-100 transition-opacity translate-y-2 peer-checked:translate-y-0 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Data Inici -->
                <div class="space-y-2">
                    <label for="data_inici" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Data d'Inici
                    </label>
                    <input type="date" name="data_inici" id="data_inici" required class="block w-full px-4 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700">
                </div>

                <!-- Data Fi -->
                <div class="space-y-2">
                    <label for="data_fi" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Data de Finalització
                    </label>
                    <input type="date" name="data_fi" id="data_fi" required class="block w-full px-4 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700">
                </div>
            </div>

            <!-- Ignorar caps de setmana -->
            <label for="ignorar_caps_setmana" style="display:flex; align-items:center; justify-content:space-between; padding:1rem; background:#fffbeb; border:1px solid #fcd34d; border-radius:1rem; cursor:pointer; gap:1rem;">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="padding:0.5rem; background:#fef3c7; border-radius:0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:1.25rem;height:1.25rem;color:#d97706;" fill="none" viewBox="0 0 24 24" stroke="#d97706">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:0.875rem; font-weight:700; color:#78350f; margin:0;">Ignorar caps de setmana</p>
                        <p style="font-size:0.75rem; color:#92400e; margin:0; margin-top:2px;">No s'assignaran torns els dissabtes ni els diumenges</p>
                    </div>
                </div>
                <input type="checkbox"
                       name="ignorar_caps_setmana"
                       id="ignorar_caps_setmana"
                       value="1"
                       {{ old('ignorar_caps_setmana') ? 'checked' : '' }}
                       style="width:1.25rem; height:1.25rem; accent-color:#f59e0b; cursor:pointer; flex-shrink:0;">
            </label>

            <!-- Botó Guardar -->
            <div class="pt-6">
                <button type="submit" class="w-full flex justify-center items-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Assignar Torn Hospitalari
                </button>
            </div>
        </form>
    </div>

    <!-- Informació extra -->
    <div class="mt-8 p-6 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-start gap-4">
        <div class="text-blue-500 mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <h4 class="text-blue-900 font-bold mb-1">Informació d'assignació</h4>
            <p class="text-blue-700/80 text-sm font-medium leading-relaxed">
                Aquesta acció crearà un calendari de torns per a l'empleat seleccionat en el rang de dates indicat.
                Si l'empleat ja té un torn assignat en aquestes dates, aquest s'actualitzarà amb el nou.
                Activa l'opció de <strong>"Ignorar caps de setmana"</strong> per saltar automàticament els dissabtes i diumenges.
            </p>
        </div>
    </div>
</div>
@endsection
