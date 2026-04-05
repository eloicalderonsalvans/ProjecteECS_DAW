@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-sm">
        
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            @if(auth()->user()->isAdmin())
                <h1 class="text-2xl font-bold text-gray-800">Gestió d'Absències</h1>
            @else
                <h1 class="text-2xl font-bold text-gray-800">Les meves Absències</h1>
            @endif
            
            <div class="flex items-center gap-4">
                <a href="{{ route('absencies.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    @if(auth()->user()->isAdmin())
                        Nova Absència
                    @else
                        Sol·licitar Absència
                    @endif
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-700 uppercase text-xs font-semibold tracking-wide">
                        @if(auth()->user()->isAdmin())
                            <th class="p-4">Usuari</th>
                        @endif
                        <th class="p-4">Motiu</th>
                        <th class="p-4">Data Inici</th>
                        <th class="p-4">Data Fi</th>
                        <th class="p-4">Estat</th>
                        @if(auth()->user()->isAdmin())
                            <th class="p-4">Aprovat per</th>
                        @endif
                        <th class="p-4 text-right">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($absencies as $absencia)
                    <tr class="hover:bg-blue-50 transition-colors">
                        @if(auth()->user()->isAdmin())
                            <td class="p-4 text-gray-900 font-medium">
                                {{ $absencia->user->nom ?? 'Desconegut' }} {{ $absencia->user->cognom ?? '' }}
                            </td>
                        @endif
                        <td class="p-4 text-gray-600">
                            {{ $absencia->motiu }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($absencia->data_inici)->format('d/m/Y') }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($absencia->data_fi)->format('d/m/Y') }}
                        </td>
                        <td class="p-4">
                            @switch($absencia->estat)
                                @case('aprovada')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Aprovada
                                    </span>
                                    @break
                                @case('rebutjada')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        Rebutjada
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        <svg class="w-3 h-3 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        Pendent
                                    </span>
                            @endswitch
                        </td>
                        @if(auth()->user()->isAdmin())
                            <td class="p-4 text-gray-600 text-sm italic">
                                {{ $absencia->aprobat_per ?? '—' }}
                            </td>
                        @endif
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                {{-- Botons d'ADMIN: Aprovar / Rebutjar / Editar / Eliminar --}}
                                @if(auth()->user()->isAdmin())
                                    @if($absencia->estat === 'pendent')
                                        <form action="{{ route('absencies.aprovar', $absencia->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm"
                                                    title="Aprovar absència">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Aprovar
                                            </button>
                                        </form>
                                        <form action="{{ route('absencies.rebutjar', $absencia->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-md transition-colors shadow-sm"
                                                    title="Rebutjar absència">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Rebutjar
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('absencies.edit', $absencia->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('absencies.destroy', $absencia->id) }}" method="POST" 
                                          onsubmit="return confirm('Estàs segur que vols eliminar aquesta absència?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                                            Eliminar
                                        </button>
                                    </form>

                                {{-- Botons d'USUARI NORMAL: Només cancel·lar si està pendent --}}
                                @else
                                    @if($absencia->estat === 'pendent')
                                        <form action="{{ route('absencies.destroy', $absencia->id) }}" method="POST" 
                                              onsubmit="return confirm('Estàs segur que vols cancel·lar aquesta sol·licitud?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                                                Cancel·lar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic px-3 py-1.5">—</span>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($absencies->isEmpty())
            <p class="text-center py-10 text-gray-500 italic">No hi ha cap absència registrada.</p>
        @endif
    </div>
@endsection
