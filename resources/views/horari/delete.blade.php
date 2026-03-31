@extends('layouts.app')

{{-- Vista per a l'eliminació massiva de torns per rang de dates --}}
@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Capçalera -->
    <div class="mb-8">
        <a href="{{ route('horaris.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors mb-4 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Tornar al calendari
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight text-red-600">Eliminar Torns</h1>
        <p class="text-slate-500 font-medium mt-1">Selecciona el període i l'empleat per eliminar les seves assignacions.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/60 overflow-hidden">
        {{-- El formulari utilitza el mètode DELETE (emulat via POST) per seguretat --}}
        <form action="{{ route('horaris.destroy-batch') }}" method="POST" class="p-8 space-y-8" onsubmit="return confirm('Estàs segur que vols eliminar tots els torns en aquest rang? Aquesta acció no es pot desfer.');">
            @csrf
            @method('DELETE')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Selecció d'Usuari -->
                <div class="space-y-2 md:col-span-2">
                    <label for="user_id" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Empleat
                    </label>
                    <select name="user_id" id="user_id" required class="block w-full pl-4 pr-10 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700 transition-all cursor-pointer">
                        <option value="">-- Selecciona un empleat --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->cognom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Rang de dates -->
                <div class="space-y-2">
                    <label for="data_inici" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Data d'Inici
                    </label>
                    <input type="date" name="data_inici" id="data_inici" required class="block w-full px-4 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700">
                </div>

                <div class="space-y-2">
                    <label for="data_fi" class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Data de Finalització
                    </label>
                    <input type="date" name="data_fi" id="data_fi" required class="block w-full px-4 py-3 text-base border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-xl shadow-sm bg-slate-50 font-semibold text-slate-700">
                </div>
            </div>

            <!-- Botó Eliminar -->
            <div class="pt-6">
                <button type="submit" class="w-full flex justify-center items-center px-6 py-4 bg-red-600 hover:bg-red-700 text-white font-extrabold rounded-2xl shadow-xl shadow-red-500/20 transition-all hover:-translate-y-1 active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Eliminar Torns en aquest Rang
                </button>
            </div>
        </form>
    </div>

    <!-- Informació extra -->
    <div class="mt-8 p-6 bg-red-50/50 rounded-2xl border border-red-100 flex items-start gap-4">
        <div class="text-red-500 mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <h4 class="text-red-900 font-bold mb-1">Atenció: Acció irreversible</h4>
            <p class="text-red-700/80 text-sm font-medium leading-relaxed">
                Tots els torns assignats a l'empleat seleccionat dins del rang de dates indicat seran eliminats permanentment de la base de dades.
            </p>
        </div>
    </div>
</div>
@endsection
