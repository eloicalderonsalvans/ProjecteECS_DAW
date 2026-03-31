<?php

namespace App\Http\Controllers;

use App\Models\Horari;
use App\Models\Torn;
use App\Models\User;
use Illuminate\Http\Request;

class HorariController extends Controller
{
    /**
     * Mostra la llista principal d'horaris (Calendar).
     * Carrega els usuaris per al selector i els torns per a la llegenda.
     */
    public function index()
    {
        // Necessitem els usuaris pel desplegable i els torns per la llegenda del calendari
        $users = User::all();
        $torns = Torn::all();

        return view('horari.index', compact('users', 'torns'));
    }

    /**
     * Obté els esdeveniments d'un usuari en format JSON per a FullCalendar.
     *
     * @param  int  $userId  ID de l'usuari seleccionat.
     */
    public function getEvents($userId)
    {
        // Busquem els horaris de l'usuari seleccionat i carreguem la relació amb el torn per obtenir colors i hores
        $horaris = Horari::where('user_id', $userId)
            ->with('torn')
            ->get();

        // Transformem les dades al format que entén la llibreria FullCalendar
        // Nota: Les hores d'inici i final es defineixen al torn, no a la taula d'horaris.
        $events = $horaris->map(function ($h) {
            $horaEntrada = $h->torn->hora_entrada ?? '08:00:00';
            $horaSortida = $h->torn->hora_sortida ?? '17:00:00';

            return [
                'id' => $h->id,
                'title' => $h->torn->nom ?? 'S/N',
                'start' => $h->data.'T'.$horaEntrada,
                'end' => $h->data.'T'.$horaSortida,
                'backgroundColor' => $h->torn->color ?? '#3788d8',
                'borderColor' => $h->torn->color ?? '#3788d8',
            ];
        });

        return response()->json($events);
    }

    /**
     * Mostra el formulari per assignar nous horaris.
     */
    public function create()
    {
        // Passem els usuaris i torns perquè el formulari pugui mostrar les opcions de selecció
        $users = User::all();
        $torns = Torn::all();

        return view('horari.create', compact('users', 'torns'));
    }

    /**
     * Guarda les assignacions d'horari a la base de dades (assignació massiva per rang de dates).
     */
    public function store(Request $request)
    {
        // 1. Validació de les dades d'entrada
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'torn_id' => 'required|exists:torns,id',
            'data_inici' => 'required|date',
            'data_fi' => 'required|date|after_or_equal:data_inici',
        ]);

        // 2. Busquem el torn per obtenir les seves hores i propietats
        $torn = Torn::find($request->torn_id);

        $inici = \Carbon\Carbon::parse($request->data_inici);
        $fi = \Carbon\Carbon::parse($request->data_fi);
        $ignorarCapsSetmana = $request->boolean('ignorar_caps_setmana');

        // 3. Creació massiva dia a dia en el rang especificat
        while ($inici <= $fi) {
            // Si l'opció "ignorar caps de setmana" està activa, saltem dissabtes (6) i diumenges (0)
            if (! $ignorarCapsSetmana || ! in_array($inici->dayOfWeek, [0, 6])) {
                // Utilitzem updateOrCreate per evitar duplicats per al mateix dia i usuari
                Horari::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'data' => $inici->format('Y-m-d'),
                    ],
                    [
                        'torn_id' => $request->torn_id,
                    ]
                );
            }
            // Avancem al següent dia
            $inici->addDay();
        }

        return redirect()->route('horaris.index')->with('success', 'Horaris assignats correctament!');
    }

    /**
     * Mètodes de recurs estàndard (actualment no implementats).
     * Es mantenen segons l'estructura de rutes resource.
     */
    public function show(string $id)
    { /* No utilitzat */
    }

    public function edit(string $id)
    { /* No utilitzat */
    }

    public function update(Request $request, string $id)
    { /* No utilitzat */
    }

    /**
     * Elimina una assignació d'horari específica (crida AJAX des del calendari).
     */
    public function destroy($id)
    {
        try {
            $horari = Horari::findOrFail($id);
            $horari->delete();

            return response()->json(['success' => true, 'message' => 'Horari eliminat correctament.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar.'], 500);
        }
    }
}
