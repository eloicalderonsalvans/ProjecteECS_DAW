<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Torn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center justify-between mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Editar Torn</h1>
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

        <form action="{{ route('torns.update', $torn->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="nom" class="block text-sm font-bold text-gray-700 mb-1">Nom del Torn</label>
                    <input type="text" name="nom" id="nom" value="{{ old('nom', $torn->nom) }}" required 
                           class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 transition-all uppercase">
                </div>

                <div>
                    <label for="descripcio" class="block text-sm font-bold text-gray-700 mb-1">Descripció</label>
                    <textarea name="descripcio" id="descripcio" rows="3" 
                              class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                              placeholder="Detalls del torn...">{{ old('descripcio', $torn->descripcio) }}</textarea>
                </div>

                <div>
                    <label for="color" class="block text-sm font-bold text-gray-700 mb-1">Color Identificatiu</label>
                    <div class="flex items-center space-x-4">
                        <input type="color" name="color" id="color" value="{{ old('color', $torn->color) }}" 
                               class="h-12 w-24 border border-gray-300 rounded cursor-pointer p-1">
                        <div class="text-sm text-gray-500 italic">
                            Aquest color apareixerà als quadrants i horaris.
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3 border-t pt-6">
                <a href="{{ route('torns.index') }}" 
                   class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">
                    Cancel·lar
                </a>
                
                <button type="submit" 
                        class="px-8 py-2.5 bg-[#f59e0b] hover:bg-[#d97706] text-white font-bold rounded-lg shadow-md transition-all">
                    Actualitzar Torn
                </button>
            </div>
        </form>
    </div>

</body>
</html>