<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Llistat d'Usuaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Inici
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Usuaris del Sistema</h1>
        </div>

        <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow-md hover:bg-blue-700 transition-colors">
            + Nou Usuari
        </a>
    </div>
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
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->actiu ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->actiu ? 'Actiu' : 'Inactiu' }}
                </span>
            </td>

            <td class="p-4">
                <div class="flex justify-end gap-2">
                    <a href="{{ route('users.edit', $user->id) }}" 
                       class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-md transition-colors shadow-sm">
                        Editar
                    </a>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                          onsubmit="return confirm('Estàs segur que vols eliminar aquest usuari? Aquesta acció no es pot desfer.')">
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

        @if($users->isEmpty())
            <p class="text-center py-4 text-gray-500">No hi ha usuaris registrats.</p>
        @endif
    </div>
</body>
</html>