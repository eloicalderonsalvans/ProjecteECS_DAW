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
        <form action="{{ route('horaris.store') }}" method="POST" class="p-8 space-y-8" id="horaris-form">
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
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nom }} {{ $user->cognom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- OPCIÓ: Torn Rotatiu -->
                <div class="space-y-4 p-6 bg-indigo-50 border border-indigo-200 rounded-3xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-indigo-900 uppercase tracking-widest">Torn Rotatiu</p>
                            <p class="text-xs text-indigo-600 font-medium">Activa-ho per rotar entre diversos torns.</p>
                        </div>
                        
                        <label class="switch">
                            <input type="checkbox" name="is_rotative" value="1" id="is_rotative" onchange="syncRotationUI()">
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div id="rotation_settings" class="hidden space-y-4 pt-4 border-t border-indigo-100 animate-fade-in">
                        <div class="space-y-2">
                            <label for="rotation_weeks" class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Canviar de torn cada:</label>
                            <div class="flex items-center gap-3">
                                <input type="number" name="rotation_weeks" id="rotation_weeks" value="1" min="1" class="block w-20 px-3 py-2 border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl shadow-sm bg-white font-bold text-slate-700">
                                <span class="text-sm font-bold text-indigo-600">setmana(es)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selecció de Torn -->
                <div class="md:col-span-2 space-y-4">
                    <label id="torn_label" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tipus de Torn <span id="label_selection_type" class="ml-1 text-slate-400 font-medium">(Escull un)</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach($torns as $torn)
                            <label class="relative flex flex-col items-center group cursor-pointer h-full">
                                <input type="checkbox" name="torn_ids[]" value="{{ $torn->id }}" id="torn_{{ $torn->id }}" class="peer sr-only torn-checkbox" onchange="handleTornClick(this)">
                                
                                <div class="w-full flex flex-col items-center p-6 bg-white border-2 border-slate-100 rounded-3xl peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:shadow-lg peer-checked:shadow-indigo-500/10 transition-all duration-300 group-hover:bg-slate-50 group-hover:border-slate-200 h-full">
                                    <div class="mb-4 w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner" style="background-color: {{ $torn->color }}20;">
                                        @php
                                            $nomTorn = strtolower($torn->nom);
                                            $icon = 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l.707.707M6.343 6.343l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z';
                                            if (str_contains($nomTorn, 'tarda')) $icon = 'M12 3v1.066c0 .588-.478 1.066-1.067 1.066A6.933 6.933 0 1017.868 11.1c0-.588.477-1.067 1.066-1.067H20a8 8 0 11-8-8V3z';
                                            if (str_contains($nomTorn, 'nit')) $icon = 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z';
                                            if (str_contains($nomTorn, 'reforç')) $icon = 'M13 10V3L4 14h7v7l9-11h-7z';
                                        @endphp
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="{{ $torn->color }}" style="stroke-width: 2.5;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-extrabold text-slate-800 peer-checked:text-indigo-900 mb-1 text-center">{{ $torn->nom }}</span>
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

            <!-- Mode d'assignació -->
            <div class="space-y-6 p-8 bg-yellow-50 border border-yellow-200 rounded-3xl">
                <div class="pb-4">
                    <p class="text-sm font-bold text-yellow-900 uppercase tracking-widest">Temps d'assignació</p>
                    <p class="text-xs text-yellow-700 font-medium mt-1">Configura quant de temps durarà aquest horari.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Botó per l'assignació per dates -->
                    <label class="flex items-center gap-4 p-5 rounded-3xl border-2 border-yellow-200 bg-white hover:border-yellow-400 transition cursor-pointer group">
                        <input type="radio" name="assignment_type" value="dates" checked class="h-5 w-5 text-yellow-600 border-yellow-300 focus:ring-yellow-500" onchange="syncIndefiniteUI()">
                        <div class="flex-grow">
                            <div class="font-bold text-slate-900">Per rang de dates</div>
                            <p class="text-xs text-slate-500 font-medium">Defineix un inici i un final manualment.</p>
                        </div>
                    </label>

                    <!-- Botó d'assignació INDEFINIDA -->
                    <label class="flex items-center gap-4 p-5 rounded-3xl border-2 border-yellow-200 bg-white hover:border-yellow-400 transition cursor-pointer group">
                        <input type="radio" name="assignment_type" value="indefinite" id="btn_indefinite" class="h-5 w-5 text-yellow-600 border-yellow-300 focus:ring-yellow-500" onchange="syncIndefiniteUI()">
                        <input type="hidden" name="is_indefinite" id="is_indefinite" value="0">
                        <div class="flex-grow">
                            <div class="font-bold text-slate-900 text-sm">Assignació INDEFINIDA</div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Fins a 1 any vista</p>
                        </div>
                    </label>
                </div>

                <div class="pt-4 space-y-4 border-t border-yellow-100 mt-4">
                    <p class="text-sm font-bold text-yellow-900 uppercase tracking-widest">Dies de la setmana</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-3 p-4 rounded-3xl border border-yellow-200 bg-white transition cursor-pointer">
                            <input type="radio" name="assign_mode" value="all" checked class="h-4 w-4 text-yellow-600">
                            <div class="font-bold text-slate-900 text-sm">Tots els dies</div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-3xl border border-yellow-200 bg-white transition cursor-pointer">
                            <input type="radio" name="assign_mode" value="weekdays_only" class="h-4 w-4 text-yellow-600">
                            <div class="font-bold text-slate-900 text-sm">Laborables</div>
                        </label>
                        <label class="flex items-center gap-3 p-4 rounded-3xl border border-yellow-200 bg-white transition cursor-pointer">
                            <input type="radio" name="assign_mode" value="weekends_only" class="h-4 w-4 text-yellow-600">
                            <div class="font-bold text-slate-900 text-sm">Cap de setmana</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="data_inici" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Data d'Inici
                    </label>
                    <input type="date" name="data_inici" id="data_inici" required class="block w-full px-4 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700">
                </div>

                <div class="space-y-2" id="data_fi_container">
                    <label for="data_fi" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Data de Finalització
                    </label>
                    <input type="date" name="data_fi" id="data_fi" required class="block w-full px-4 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full flex justify-center items-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Confirmar Assignació
                </button>
            </div>
        </form>
    </div>
</div>

@section('styles')
<style>
.switch { position: relative; display: inline-block; width: 50px; height: 28px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
.slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: #4f46e5; }
input:checked + .slider:before { transform: translateX(22px); }
.animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
.hidden { display: none; }
</style>
@endsection

@section('scripts_body')
<script>
    function syncRotationUI() {
        const isRotative = document.getElementById('is_rotative').checked;
        const rotationSettings = document.getElementById('rotation_settings');
        const labelType = document.getElementById('label_selection_type');
        const checkboxes = document.querySelectorAll('.torn-checkbox');

        if (isRotative) {
            rotationSettings.classList.remove('hidden');
            labelType.textContent = '(Escull diversos)';
        } else {
            rotationSettings.classList.add('hidden');
            labelType.textContent = '(Escull un)';
            let found = false;
            checkboxes.forEach(c => {
                if (c.checked && !found) {
                    found = true;
                } else if (c.checked && found) {
                    c.checked = false;
                }
            });
        }
    }

    function handleTornClick(checkbox) {
        const isRotative = document.getElementById('is_rotative').checked;
        if (!isRotative && checkbox.checked) {
            const checkboxes = document.querySelectorAll('.torn-checkbox');
            checkboxes.forEach(c => {
                if (c !== checkbox) c.checked = false;
            });
        }
    }

    document.getElementById('horaris-form').addEventListener('submit', function(e) {
        const isRotative = document.getElementById('is_rotative').checked;
        const checks = document.querySelectorAll('.torn-checkbox:checked');
        
        if (checks.length === 0) {
            e.preventDefault();
            alert('Has de seleccionar almenys un torn.');
            return;
        }

        if (isRotative && checks.length < 2) {
            e.preventDefault();
            alert('Per a un torn rotatiu has de seleccionar almenys dos torns.');
            return;
        }
    });

    function syncIndefiniteUI() {
        const type = document.querySelector('input[name="assignment_type"]:checked').value;
        const dataFiContainer = document.getElementById('data_fi_container');
        const dataFiInput = document.getElementById('data_fi');
        const hiddenInput = document.getElementById('is_indefinite');

        if (type === 'indefinite') {
            dataFiContainer.classList.add('hidden');
            dataFiInput.required = false;
            dataFiInput.value = '';
            hiddenInput.value = '1';
        } else {
            dataFiContainer.classList.remove('hidden');
            dataFiInput.required = true;
            hiddenInput.value = '0';
        }
    }

    window.addEventListener('load', () => {
        syncRotationUI();
        syncIndefiniteUI();
    });
</script>
@endsection
@endsection
