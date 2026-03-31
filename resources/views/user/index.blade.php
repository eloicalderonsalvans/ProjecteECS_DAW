@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-sm">
        
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Usuaris del Sistema</h1>
            
            <div class="flex items-center gap-4">
                

                <a href="{{ route('users.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nou Usuari
                </a>
            </div>
        </div>

        <!-- Taula de llistat d'usuaris -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-700 uppercase text-sm">
                        <th class="p-4">Nom Complet</th>
                        <th class="p-4">DNI</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Departament</th>
                        <th class="p-4 text-center">Estat</th>
                        <th class="p-4 text-right">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="p-4">
                            <!-- Enllaç al detall de l'usuari -->
                            <a href="{{ route('users.show', $user->id) }}" class="text-blue-600 font-semibold hover:text-blue-800 hover:underline">
                                {{ $user->nom }} {{ $user->cognom }}
                            </a>
                        </td>
                        <td class="p-4 text-gray-600">{{ $user->DNI }}</td>
                        <td class="p-4 text-gray-600">{{ $user->email }}</td>
                        <td class="p-4 text-gray-600">
                            {{ $user->departament->nom ?? 'Sense assignar' }}
                        </td>
                        <td class="p-4 text-center">
                            <!-- Badge d'estat: verd si està actiu, vermell si no -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->actiu ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->actiu ? 'Actiu' : 'Inactiu' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <!-- Botons d'acció: Editar i Eliminar -->
                                <a href="{{ route('users.edit', $user->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                                    Editar
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                                      onsubmit="return confirm('Estàs segur que vols eliminar aquest usuari?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
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

        @if($users->isEmpty())
            <p class="text-center py-10 text-gray-500 italic">No hi ha usuaris registrats.</p>
        @endif
    </div>
@endsection
