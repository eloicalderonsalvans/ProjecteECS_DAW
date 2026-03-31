@extends('layouts.app')

@section('styles')
    <!-- Llibreria FullCalendar per gestionar el calendari visualment -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <style>
        /* Estils personalitzats per al calendari (Colors, marges i tipografia) */
        .fc {
            --fc-border-color: #e2e8f0;
            font-family: inherit;
        }

        /* ... resta d'estils CSS per al calendari ... */
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #e2e8f0; }
        .fc .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 800; color: #0f172a; text-transform: capitalize; }
        .fc .fc-button-primary { background-color: #2563eb; border: none; font-weight: 600; padding: 0.5rem 1rem; border-radius: 0.5rem; }
        .fc .fc-button-primary:hover { background-color: #1e40af; }
        .fc .fc-daygrid-day-frame { min-height: 120px; }
        
        /* Estil de les etiquetes de torn (Events) */
        .fc-event {
            cursor: pointer;
            border: none !important;
            padding: 5px 10px !important;
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 4px 6px !important;
            font-size: 0.8rem;
            font-weight: 700;
            color: white !important;
        }

        .fc-daygrid-event-dot { display: none !important; }
        .fc-daygrid-day-top { padding: 8px; font-weight: 600; color: #64748b; }
        .fc-day-today { background-color: #eff6ff !important; }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">

        <!-- Capçalera i Botó d'Assignació -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/60 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Calendari de Treball</h1>
                    <p class="text-slate-500 font-medium mt-1">Consulta i gestiona els horaris dels empleats.</p>
                </div>

                <a href="{{ route('horaris.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Assignar Torns
                </a>
            </div>

            <!-- Selector d'Usuari per carregar les seves dades al calendari -->
            <div class="flex flex-col items-center justify-center max-w-2xl mx-auto py-4">
                <div class="flex flex-col items-center gap-4 w-full">
                    <div class="flex items-center gap-2 px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100 shadow-sm animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-widest">Selecciona Usuari</span>
                    </div>

                    <div class="relative w-full">
                        <select id="user-selector" class="block w-full pl-6 pr-12 py-4 text-lg border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 rounded-2xl shadow-sm bg-white font-bold text-slate-800 transition-all appearance-none cursor-pointer hover:border-indigo-300">
                            <option value="">Selecciona un empleat...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->cognom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Spinner de càrrega (S'activa durant l'AJAX) -->
                <div id="loading-spinner" class="mt-4 hidden transform transition-all">
                    <div class="flex items-center gap-3 px-6 py-3 bg-slate-900 text-white rounded-2xl shadow-2xl scale-95 animate-in fade-in zoom-in duration-300">
                        <svg class="animate-spin h-5 w-5 text-indigo-400" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span class="text-sm font-bold tracking-tight">Recuperant dades del calendari...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenidor del Calendari -->
        <div class="bg-white p-8 rounded-2xl shadow-xl shadow-slate-200/20 border border-slate-200/60 transition-opacity duration-300" id="calendar-container">
            <div id="calendar" class="min-h-[700px]"></div>
        </div>

        <!-- Llegenda de Torns (Dades dinàmiques des de la BD) -->
        <div class="mt-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Llegenda de disponibilitat</h3>
            <div class="flex flex-wrap gap-6">
                @foreach($torns as $torn)
                    <div class="flex items-center gap-3 group translate-all hover:translate-x-1 duration-200">
                        <span class="w-4 h-4 rounded-full shadow-sm ring-2 ring-offset-2 ring-transparent group-hover:ring-slate-100" style="background-color: {{ $torn->color }};"></span>
                        <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900 transition-colors">{{ $torn->nom }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts_body')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const userSelector = document.getElementById('user-selector');
            const loading = document.getElementById('loading-spinner');
            const calendarContainer = document.getElementById('calendar-container');

            // 1. Configuració inicial de FullCalendar
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ca', // Idioma Català
                firstDay: 1,  // Dilluns com a primer dia
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                buttonText: { today: 'Avui', month: 'Mes', week: 'Setmana' },
                events: [],
                eventDisplay: 'block',
                eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },

                // --- ACCIÓ AL CLICAR UN EVENT: ELIMINAR ---
                eventClick: function (info) {
                    const eventId = info.event.id;
                    const eventTitle = info.event.title;

                    if (confirm(`Vols eliminar el torn de "${eventTitle}"?`)) {
                        fetch(`/horaris/${eventId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                info.event.remove(); // Elimina l'event del calendari visualment
                            } else {
                                alert('No s\'ha pogut eliminar: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error de connexió.');
                        });
                    }
                }
            });

            calendar.render();

            // 2. Escoltador de canvis al selector d'usuari
            userSelector.addEventListener('change', function () {
                const userId = this.value;
                calendar.removeAllEventSources(); // Netegem els torns de l'usuari anterior

                if (!userId) {
                    calendarContainer.style.opacity = '0.5';
                    return;
                }

                calendarContainer.style.opacity = '1';
                loading.classList.remove('hidden');

                // Carreguem els esdeveniments via API (rutes de Laravel)
                calendar.addEventSource({
                    url: '/api/horaris/user/' + userId,
                    success: () => loading.classList.add('hidden'),
                    failure: () => {
                        loading.classList.add('hidden');
                        alert('Error al carregar les dades.');
                    }
                });
            });

            // Llança el canvi automàticament si ja hi ha un usuari seleccionat
            if (userSelector.value) {
                userSelector.dispatchEvent(new Event('change'));
            } else {
                calendarContainer.style.opacity = '0.5';
            }
        });
    </script>
@endsection
ction