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
                    <input type="text" name="motiu" id="motiu" value="{{ old('motiu') }}" required 
                           placeholder="Ex: Vacances, Baixa mèdica, Assumptes propis..."
                           class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
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
@endsection
