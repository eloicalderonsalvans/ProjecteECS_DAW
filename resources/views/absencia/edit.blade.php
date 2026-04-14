@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Editar Absència</h1>
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

        <form action="{{ route('absencies.update', $absencia->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Empleat/da</label>
                    <input type="text" disabled value="{{ $absencia->user->nom }} {{ $absencia->user->cognom }}" 
                           class="w-full border border-gray-200 rounded-lg p-2.5 bg-gray-50 text-gray-500 cursor-not-allowed font-medium">
                    <input type="hidden" name="user_id" value="{{ $absencia->user_id }}">
                </div>

                <div class="md:col-span-2">
                    <label for="motiu" class="block text-sm font-bold text-gray-700 mb-1">Motiu de l'absència</label>
                    <select name="motiu" id="motiu" required
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="Vacances" {{ old('motiu', $absencia->motiu) == 'Vacances' ? 'selected' : '' }}>🏖️ Vacances</option>
                        <option value="Baixa mèdica" {{ old('motiu', $absencia->motiu) == 'Baixa mèdica' ? 'selected' : '' }}>🏥 Baixa mèdica</option>
                        <option value="Assumptes propis" {{ old('motiu', $absencia->motiu) == 'Assumptes propis' ? 'selected' : '' }}>📋 Assumptes propis</option>
                        <option value="Formació" {{ old('motiu', $absencia->motiu) == 'Formació' ? 'selected' : '' }}>📚 Formació</option>
                        <option value="Altres" {{ old('motiu', $absencia->motiu) == 'Altres' ? 'selected' : '' }}>📝 Altres</option>
                    </select>
                </div>

                <div>
                    <label for="data_inici" class="block text-sm font-bold text-gray-700 mb-1">Data d'Inici</label>
                    <input type="date" name="data_inici" id="data_inici" 
                           value="{{ old('data_inici', \Carbon\Carbon::parse($absencia->data_inici)->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="data_fi" class="block text-sm font-bold text-gray-700 mb-1">Data Final</label>
                    <input type="date" name="data_fi" id="data_fi" 
                           value="{{ old('data_fi', \Carbon\Carbon::parse($absencia->data_fi)->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label for="aprobat_per" class="block text-sm font-bold text-gray-700 mb-1">Aprovat per</label>
                    <select name="aprobat_per" id="aprobat_per" 
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="">-- Pendent d'aprovar --</option>
                        @foreach($aprovadors as $aprovador)
                            @php $nomComplet = $aprovador->nom . ' ' . $aprovador->cognom; @endphp
                            <option value="{{ $nomComplet }}" {{ old('aprobat_per', $absencia->aprobat_per) == $nomComplet ? 'selected' : '' }}>
                                {{ $nomComplet }} ({{ $aprovador->role }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="estat" class="block text-sm font-bold text-gray-700 mb-1">Estat</label>
                    <select name="estat" id="estat" 
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50">
                        <option value="pendent" {{ old('estat', $absencia->estat) == 'pendent' ? 'selected' : '' }}>Pendent</option>
                        <option value="aprovada" {{ old('estat', $absencia->estat) == 'aprovada' ? 'selected' : '' }}>Aprovada</option>
                        <option value="rebutjada" {{ old('estat', $absencia->estat) == 'rebutjada' ? 'selected' : '' }}>Rebutjada</option>
                    </select>
                </div>

            </div>

            <div class="mt-10 flex justify-end gap-3 border-t pt-6">
                <a href="{{ route('absencies.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                    Cancel·lar
                </a>
                <button type="submit" 
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all">
                    Desar Canvis
                </button>
            </div>
        </form>
    </div>
@endsection
