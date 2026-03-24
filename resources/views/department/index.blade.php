@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Departaments</h1>
            </div>

            <div class="flex items-center space-x-3">
                

                <a href="{{ route('departments.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nou Departament
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Nom del Departament</th>
                        <th class="px-6 py-3 text-right">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($department as $dept)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $dept->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $dept->nom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    
                                    <a href="{{ route('departments.edit', $dept->id) }}" 
                                       class="inline-block px-4 py-2 bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                                        Editar
                                    </a>

                                    <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" 
                                          onsubmit="return confirm('Estàs segur?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-block px-4 py-2 bg-[#dc2626] hover:bg-[#b91c1c] text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
