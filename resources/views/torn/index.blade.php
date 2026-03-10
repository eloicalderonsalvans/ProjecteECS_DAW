<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat de Torns</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Torns de Treball</h1>
            </div>

            <div class="flex items-center space-x-3">
               <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Inici
               </a>
               <a href="{{ route('torns.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nou Torn
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
                        <th class="px-6 py-3">Color</th>
                        <th class="px-6 py-3">Nom del Torn</th>
                        <th class="px-6 py-3">Descripció</th>
                        <th class="px-6 py-3 text-right">Accions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($torns as $torn)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                #{{ $torn->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="w-8 h-8 rounded-full border border-gray-300 shadow-sm" style="background-color: {{ $torn->color }}"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold uppercase">
                                {{ $torn->nom }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $torn->descripcio ?? 'Sense descripció' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    
                                    <a href="{{ route('torns.edit', $torn->id) }}" 
                                       class="inline-block px-4 py-2 bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold rounded-lg text-sm shadow-sm transition-colors">
                                        Editar
                                    </a>

                                    <form action="{{ route('torns.destroy', $torn->id) }}" method="POST" 
                                          onsubmit="return confirm('Estàs segur d\'eliminar aquest torn?')">
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

                    @if($torns->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No hi ha torns creats encara.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>