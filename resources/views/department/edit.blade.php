@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Editar Departament</h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('departments.update', $dept->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="space-y-6">
                <div>
                    <label for="nom" class="block text-sm font-bold text-gray-700 mb-1">Nom del Departament</label>
                    <input type="text" name="nom" id="nom" 
                           value="{{ old('nom', $dept->nom) }}" required 
                           class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label for="descripcio" class="block text-sm font-bold text-gray-700 mb-1">Descripció</label>
                    <textarea name="descripcio" id="descripcio" rows="3" 
                              class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                              placeholder="Sense descripció...">{{ old('descripcio', $dept->descripcio) }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('departments.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                    Cancel·lar
                </a>
                
                <button type="submit" 
                        class="px-8 py-2.5 bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold rounded-lg shadow-md transition-all">
                    Actualitzar Departament
                </button>
            </div>
        </form>
    </div>
@endsection
