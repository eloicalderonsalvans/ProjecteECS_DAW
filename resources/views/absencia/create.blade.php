@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            @if(auth()->user()->isAdmin())
                <h1 class="text-2xl font-bold text-gray-800">Registrar Nova Absència</h1>
            @else
                <h1 class="text-2xl font-bold text-gray-800">Sol·licitar Absència</h1>
            @endif
        </div>

        {{-- Caixa informativa de dies de vacances --}}
        <div id="vacances-info" class="mb-6 p-4 rounded-xl border-2 border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-emerald-800">Dies de Vacances ({{ now()->year }})</p>
                    <div class="flex items-center gap-4 mt-1">
                        <span class="text-xs text-emerald-600">
                            Consumits: <strong id="dies-consumits">{{ $diesConsumits }}</strong> / {{ \App\Models\User::DIES_VACANCES_ANUALS }}
                        </span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full" id="dies-restants-badge"
                              style="background-color: {{ $diesRestants > 10 ? '#d1fae5' : ($diesRestants > 0 ? '#fef3c7' : '#fee2e2') }}; color: {{ $diesRestants > 10 ? '#065f46' : ($diesRestants > 0 ? '#92400e' : '#991b1b') }};">
                            {{ $diesRestants }} dies restants
                        </span>
                    </div>
                    {{-- Barra de progrés --}}
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        @php $percentatge = ($diesConsumits / \App\Models\User::DIES_VACANCES_ANUALS) * 100; @endphp
                        <div class="h-2 rounded-full transition-all duration-500"
                             id="vacances-progress-bar"
                             style="width: {{ min($percentatge, 100) }}%; background-color: {{ $percentatge < 60 ? '#10b981' : ($percentatge < 90 ? '#f59e0b' : '#ef4444') }};"></div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('absencies.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Selector d'usuari: només visible per a admins --}}
                @if(auth()->user()->isAdmin() && $users)
                    <div class="md:col-span-2">
                        <label for="user_id" class="block text-sm font-bold text-gray-700 mb-1">Empleat/da</label>
                        <select name="user_id" id="user_id" required 
                                class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                            <option value="" disabled selected>Selecciona un usuari...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nom }} {{ $user->cognom }} ({{ $user->DNI }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    {{-- Usuari normal: Mostrem el seu nom (no editable) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Sol·licitant</label>
                        <input type="text" disabled 
                               value="{{ auth()->user()->nom }} {{ auth()->user()->cognom }}" 
                               class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 cursor-not-allowed font-medium">
                    </div>
                @endif

                <div class="md:col-span-2">
                    <label for="motiu" class="block text-sm font-bold text-gray-700 mb-1">Motiu de l'absència</label>
                    <select name="motiu" id="motiu" required
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="" disabled {{ old('motiu') ? '' : 'selected' }}>Selecciona un motiu...</option>
                        <option value="Vacances" {{ old('motiu') == 'Vacances' ? 'selected' : '' }}>🏖️ Vacances</option>
                        <option value="Baixa mèdica" {{ old('motiu') == 'Baixa mèdica' ? 'selected' : '' }}>🏥 Baixa mèdica</option>
                        <option value="Assumptes propis" {{ old('motiu') == 'Assumptes propis' ? 'selected' : '' }}>📋 Assumptes propis</option>
                        <option value="Formació" {{ old('motiu') == 'Formació' ? 'selected' : '' }}>📚 Formació</option>
                        <option value="Altres" {{ old('motiu') == 'Altres' ? 'selected' : '' }}>📝 Altres</option>
                    </select>
                </div>

                {{-- Avís de vacances: visible quan motiu = Vacances --}}
                <div class="md:col-span-2 hidden" id="vacances-warning">
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-sm text-amber-700 font-medium" id="vacances-warning-text">
                            Recorda: tens <strong id="warning-dies-restants">{{ $diesRestants }}</strong> dies de vacances disponibles aquest any.
                        </p>
                    </div>
                </div>

                <div>
                    <label for="data_inici" class="block text-sm font-bold text-gray-700 mb-1">Data d'Inici</label>
                    <input type="date" name="data_inici" id="data_inici" value="{{ old('data_inici') }}" required
                           class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="data_fi" class="block text-sm font-bold text-gray-700 mb-1">Data Final</label>
                    <input type="date" name="data_fi" id="data_fi" value="{{ old('data_fi') }}" required
                           class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Comptador de dies sol·licitats --}}
                <div class="md:col-span-2 hidden" id="dies-sollicitats-box">
                    <div class="p-3 rounded-lg border flex items-center justify-between" id="dies-sollicitats-container">
                        <span class="text-sm font-medium text-gray-700">Dies sol·licitats:</span>
                        <span class="text-lg font-bold" id="dies-sollicitats-count">0</span>
                    </div>
                </div>

                {{-- Camp d'aprovació: Només visible per a admins --}}
                @if(auth()->user()->isAdmin() && $aprovadors)
                    <div class="md:col-span-2">
                        <label for="aprobat_per" class="block text-sm font-bold text-gray-700 mb-1">Aprovat per</label>
                        <select name="aprobat_per" id="aprobat_per" 
                                class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                            <option value="">-- Pendent d'aprovar --</option>
                            @foreach($aprovadors as $aprovador)
                                <option value="{{ $aprovador->nom }} {{ $aprovador->cognom }}" {{ old('aprobat_per') == ($aprovador->nom . ' ' . $aprovador->cognom) ? 'selected' : '' }}>
                                    {{ $aprovador->nom }} {{ $aprovador->cognom }} ({{ $aprovador->role }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1 italic">Si selecciones un aprovador, l'absència quedarà aprovada directament.</p>
                    </div>
                @else
                    {{-- Missatge informatiu per a l'usuari normal --}}
                    <div class="md:col-span-2">
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-700 font-medium">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                La teva sol·licitud serà revisada i aprovada per un administrador.
                            </p>
                        </div>
                    </div>
                @endif

            </div>

            <div class="mt-10 flex justify-end gap-3 border-t pt-6">
                <a href="{{ route('absencies.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                    Cancel·lar
                </a>
                <button type="submit" 
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all">
                    @if(auth()->user()->isAdmin())
                        Guardar Absència
                    @else
                        Enviar Sol·licitud
                    @endif
                </button>
            </div>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const motiuSelect = document.getElementById('motiu');
    const vacancesWarning = document.getElementById('vacances-warning');
    const dataInici = document.getElementById('data_inici');
    const dataFi = document.getElementById('data_fi');
    const diesSollicitats = document.getElementById('dies-sollicitats-box');
    const diesSollicitatsCount = document.getElementById('dies-sollicitats-count');
    const diesSollicitatsContainer = document.getElementById('dies-sollicitats-container');
    const warningDiesRestants = document.getElementById('warning-dies-restants');

    let currentDiesRestants = {{ $diesRestants }};

    // Mostrar/ocultar avís de vacances segons motiu
    motiuSelect.addEventListener('change', function() {
        if (this.value === 'Vacances') {
            vacancesWarning.classList.remove('hidden');
        } else {
            vacancesWarning.classList.add('hidden');
        }
        updateDiesSollicitats();
    });

    // Calcular dies sol·licitats
    function updateDiesSollicitats() {
        if (dataInici.value && dataFi.value) {
            const start = new Date(dataInici.value);
            const end = new Date(dataFi.value);
            if (end >= start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24)) + 1;
                diesSollicitatsCount.textContent = diffDays;
                diesSollicitats.classList.remove('hidden');

                // Canviar color segons disponibilitat (només per vacances)
                if (motiuSelect.value === 'Vacances') {
                    if (diffDays > currentDiesRestants) {
                        diesSollicitatsContainer.className = 'p-3 rounded-lg border border-red-300 bg-red-50 flex items-center justify-between';
                        diesSollicitatsCount.className = 'text-lg font-bold text-red-600';
                        diesSollicitatsCount.textContent = diffDays + ' ⚠️ Supera els dies disponibles!';
                    } else {
                        diesSollicitatsContainer.className = 'p-3 rounded-lg border border-emerald-300 bg-emerald-50 flex items-center justify-between';
                        diesSollicitatsCount.className = 'text-lg font-bold text-emerald-600';
                    }
                } else {
                    diesSollicitatsContainer.className = 'p-3 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-between';
                    diesSollicitatsCount.className = 'text-lg font-bold text-blue-600';
                }
            } else {
                diesSollicitats.classList.add('hidden');
            }
        } else {
            diesSollicitats.classList.add('hidden');
        }
    }

    dataInici.addEventListener('change', updateDiesSollicitats);
    dataFi.addEventListener('change', updateDiesSollicitats);

    // AJAX: actualitzar dies de vacances quan l'admin canvia d'empleat
    @if(auth()->user()->isAdmin())
    const userSelect = document.getElementById('user_id');
    if (userSelect) {
        userSelect.addEventListener('change', function() {
            const userId = this.value;
            if (!userId) return;

            fetch('/api/vacances/user/' + userId)
                .then(response => response.json())
                .then(data => {
                    currentDiesRestants = data.restants;

                    // Actualitzar la caixa de vacances
                    document.getElementById('dies-consumits').textContent = data.consumits;
                    
                    const badge = document.getElementById('dies-restants-badge');
                    badge.textContent = data.restants + ' dies restants';
                    badge.style.backgroundColor = data.restants > 10 ? '#d1fae5' : (data.restants > 0 ? '#fef3c7' : '#fee2e2');
                    badge.style.color = data.restants > 10 ? '#065f46' : (data.restants > 0 ? '#92400e' : '#991b1b');

                    // Actualitzar barra de progrés
                    const percentatge = (data.consumits / data.total) * 100;
                    const bar = document.getElementById('vacances-progress-bar');
                    bar.style.width = Math.min(percentatge, 100) + '%';
                    bar.style.backgroundColor = percentatge < 60 ? '#10b981' : (percentatge < 90 ? '#f59e0b' : '#ef4444');

                    // Actualitzar warning
                    warningDiesRestants.textContent = data.restants;

                    // Recalcular dies sol·licitats
                    updateDiesSollicitats();
                });
        });
    }
    @endif

    // Trigger motiu change if old value exists
    if (motiuSelect.value === 'Vacances') {
        vacancesWarning.classList.remove('hidden');
    }
    updateDiesSollicitats();
});
</script>
@endsection
