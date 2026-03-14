<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat d'Absències</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-sm">
        
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Gestió d'Absències</h1>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Inici
                </a>

                <a href="{{ route('absencies.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nova Absència
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-700 uppercase text-xs font-semibold tracking-wide">
                        <th class="p-4">Usuari</th>
                        <th class="p-4">Motiu</th>
                        <th class="p-4">Data Inici</th>
                        <th class="p-4">Data Fi</th> <th class="p-4">Aprovat per</th>
                        <th class="p-4 text-right">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($absencies as $absencia)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="p-4 text-gray-900 font-medium">
                            {{ $absencia->user->nom ?? 'Desconegut' }} {{ $absencia->user->cognom ?? '' }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ $absencia->motiu }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($absencia->data_inici)->format('d/m/Y') }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($absencia->data_fi)->format('d/m/Y') }} </td>
                        <td class="p-4 text-gray-600 text-sm italic">
                            {{ $absencia->aprobat_per ?? 'Pendent' }}
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
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
</body>
</html>