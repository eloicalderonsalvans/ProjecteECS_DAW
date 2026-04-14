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

        {{-- Resum de dies de vacances --}}
        @if(!auth()->user()->isAdmin())
        <div class="mb-6 p-4 rounded-xl border-2 border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-emerald-800">🏖️ Dies de Vacances ({{ now()->year }})</p>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                              style="background-color: {{ $diesVacancesRestants > 10 ? '#d1fae5' : ($diesVacancesRestants > 0 ? '#fef3c7' : '#fee2e2') }}; color: {{ $diesVacancesRestants > 10 ? '#065f46' : ($diesVacancesRestants > 0 ? '#92400e' : '#991b1b') }};">
                            {{ $diesVacancesRestants }} dies restants
                        </span>
                    </div>
                    <div class="flex items-center gap-4 mt-1.5">
                        <span class="text-xs text-emerald-600">Consumits: <strong>{{ $diesVacancesConsumits }}</strong></span>
                        <span class="text-xs text-gray-400">|</span>
                        <span class="text-xs text-emerald-600">Total: <strong>{{ $diesVacancesTotal }}</strong></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        @php $pct = ($diesVacancesConsumits / $diesVacancesTotal) * 100; @endphp
                        <div class="h-2 rounded-full transition-all duration-500"
                             style="width: {{ min($pct, 100) }}%; background-color: {{ $pct < 60 ? '#10b981' : ($pct < 90 ? '#f59e0b' : '#ef4444') }};"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

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
