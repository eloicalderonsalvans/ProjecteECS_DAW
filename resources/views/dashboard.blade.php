<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-500">
                {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold mb-2">
                        Hola, {{ auth()->user()->nom }}! 👋
                    </h3>
                    <p class="text-gray-600 italic">
                        {{ __("Benvingut de nou al teu panell de control.") }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">La teva Informació</h4>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <span class="w-24 text-gray-500 font-medium">Nom:</span>
                            <span class="text-gray-800">{{ auth()->user()->nom }} {{ auth()->user()->cognom }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-24 text-gray-500 font-medium">Email:</span>
                            <span class="text-gray-800">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-24 text-gray-500 font-medium">DNI:</span>
                            <span class="text-gray-800 font-mono">{{ auth()->user()->DNI }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Dades Professionals</h4>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <span class="w-24 text-gray-500 font-medium">Rol:</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded uppercase">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-24 text-gray-500 font-medium">Departament:</span>
                            <span class="text-gray-800">
                                {{ auth()->user()->departament->nom ?? 'Sense assignar' }}
                            </span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-24 text-gray-500 font-medium">Alta:</span>
                            <span class="text-gray-800">{{ auth()->user()->data_alta->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Notícies i Actualitzacions</h4>
                <p class="text-gray-600 italic">Aquí es mostraran notícies, actualitzacions o enllaços útils per als usuaris de la plataforma.</p>
             </div>
        </div>
    </div>
</x-app-layout>