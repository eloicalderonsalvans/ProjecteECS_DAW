@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Nou Torn</h1>
            
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

        <form action="{{ route('torns.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="nom" class="block text-sm font-bold text-gray-700 mb-1">Nom del Torn</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required 
                           class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ex: Matí, Tarda, Reforç...">
                </div>

                <div>
                    <label for="descripcio" class="block text-sm font-bold text-gray-700 mb-1">Descripció</label>
                    <textarea name="descripcio" id="descripcio" rows="3" 
                              class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Detalls del torn...">{{ old('descripcio') }}</textarea>
                </div>

                <div>
                    <label for="color" class="block text-sm font-bold text-gray-700 mb-1">Color Identificatiu</label>
                    <div class="flex items-center space-x-4">
                        <input type="color" name="color" id="color" value="{{ old('color', '#3b82f6') }}" 
                               class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                        <span class="text-gray-500 text-sm italic italic">Aquest color servirà per diferenciar els torns al calendari.</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3 border-t pt-6">
                <a href="{{ route('torns.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                    Cancel·lar
                </a>
                
                <button type="submit" 
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all">
                    Guardar Torn
                </button>
            </div>
        </form>
    </div>
@endsection
