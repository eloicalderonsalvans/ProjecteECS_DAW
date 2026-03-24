@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Registrar Nova Absència</h1>
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

                <div class="md:col-span-2">
                    <label for="aprobat_per" class="block text-sm font-bold text-gray-700 mb-1">Aprovat per</label>
                    <select name="aprobat_per" id="aprobat_per" 
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="">-- Pendent d'aprovar --</option>
                        @foreach($aprovadors as $aprovador)
                            <option value="{{ $aprovador->nom }} {{ $aprovador->cognom }}" {{ old('aprobat_per') == ($aprovador->nom . ' ' . $aprovador->cognom) ? 'selected' : '' }}>
                                {{ $aprovador->nom }} {{ $aprovador->cognom }} ({{ $aprovador->rol }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1 italic">Només Administradors, Caps o RRHH.</p>
                </div>

            </div>

            <div class="mt-10 flex justify-end gap-3 border-t pt-6">
                <a href="{{ route('absencies.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                    Cancel·lar
                </a>
                <button type="submit" 
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all">
                    Guardar Absència
                </button>
            </div>
        </form>
    </div>
@endsection
