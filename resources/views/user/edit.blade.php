@extends('layouts.app')

@section('content')
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

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Foto de perfil -->
            <div class="mb-8 flex flex-col items-center">
                <div class="relative group mb-4">
                    @if($user->getAvatarUrl())
                        <img src="{{ $user->getAvatarUrl() }}" alt="Foto de {{ $user->nom }}" class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 shadow-lg">
                    @else
                        <div class="w-28 h-28 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-extrabold shadow-lg border-4 border-blue-100">
                            {{ $user->getInicials() }}
                        </div>
                    @endif
                    <label for="foto_perfil" class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                </div>
                <p class="text-xs text-gray-400 font-medium">Passa el ratolí per canviar la foto</p>
                @if($user->getAvatarUrl())
                    <label class="mt-2 flex items-center gap-2 text-xs text-red-500 cursor-pointer hover:text-red-700 transition-colors">
                        <input type="checkbox" name="eliminar_foto" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                        Eliminar foto actual
                    </label>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->actiu ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $user->actiu ? '● Actiu (ha fitxat entrada)' : '○ Inactiu (no ha fitxat)' }}
                    </span>
                    <span class="ml-2 text-xs text-gray-400 italic">L'estat es gestiona automàticament en fitxar.</span>
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

@section('scripts_body')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Reemplaçem l'avatar actual per la previsualització
            const container = input.closest('.relative');
            const existingImg = container.querySelector('img');
            const existingDiv = container.querySelector('div:not(.absolute)');
            
            if (existingImg) {
                existingImg.src = e.target.result;
            } else if (existingDiv) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Previsualització';
                img.className = 'w-28 h-28 rounded-full object-cover border-4 border-blue-100 shadow-lg';
                existingDiv.replaceWith(img);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
@endsection
