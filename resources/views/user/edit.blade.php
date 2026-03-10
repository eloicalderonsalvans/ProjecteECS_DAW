<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuari</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Usuari: {{ $user->nom }}</h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cognom</label>
                    <input type="text" name="cognom" value="{{ old('cognom', $user->cognom) }}" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">DNI</label>
                    <input type="text" name="DNI" value="{{ old('DNI', $user->DNI) }}" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nova Contrassenya (deixar en blanc per mantenir)</label>
                    <input type="password" name="contrassenya" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Data d'Alta</label>
                    <input type="date" name="data_alta" value="{{ old('data_alta', $user->data_alta->format('Y-m-d')) }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Departament</label>
                    <select name="department_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">Sense departament</option>
                        @foreach($department as $dept)
                            <option value="{{ $dept->id }}" {{ $user->department_id == $dept->id ? 'selected' : '' }}>
                                {{ $dept->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                    <select name="role" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Usuari</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>

                <div class="flex items-center mt-6">
                    <input type="checkbox" name="actiu" value="1" id="actiu" {{ $user->actiu ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="actiu" class="ml-2 block text-sm text-gray-900 font-medium">Usuari Actiu</label>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3 border-t pt-6">
                <a href="{{ route('users.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                    Cancel·lar
                </a>
                
                <button type="submit" 
                        class="px-8 py-2.5 bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold rounded-lg shadow-md transition-all">
                    Actualitzar Usuari
                </button>
            </div>
        </form>
    </div>

</body>
</html>