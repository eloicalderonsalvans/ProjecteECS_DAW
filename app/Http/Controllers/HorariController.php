<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horari;
use App\Models\User;
use App\Models\Torn;

class HorariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Necessitem els usuaris pel desplegable i els torns per la llegenda
        $users = User::all();
        $torns = Torn::all();

        return view('horari.index', compact('users', 'torns'));
    }

    public function getEvents($userId)
    {
        // Busquem els horaris de l'usuari seleccionat i carreguem la relació amb el torn
        $horaris = Horari::where('user_id', $userId)
            ->with('torn')
            ->get();

        // Transformem les dades al format que entén FullCalendar
        $events = $horaris->map(function ($h) {
            return [
                'id'    => $h->id,
                'title' => $h->torn->nom ?? 'S/N',
                'start' => $h->data . 'T' . $h->hora_entrada,
                'end'   => $h->data . 'T' . $h->hora_sortida,
                'backgroundColor' => $h->torn->color ?? '#3788d8',
                'borderColor'     => $h->torn->color ?? '#3788d8',
            ];
        });

        return response()->json($events);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       // Passem els usuaris i torns perquè el formulari pugui omplir els <select> i els <input radio>
       $users = User::all();
       $torns = Torn::all();

       return view('horari.create', compact('users', 'torns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validació de dades
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'torn_id'    => 'required|exists:torns,id',
            'data_inici' => 'required|date',
            'data_fi'    => 'required|date|after_or_equal:data_inici',
        ]);

        // 2. Busquem el torn per saber el seu NOM (Matí, Tarda, Nit...)
        $torn = Torn::find($request->torn_id);
        $nomTorn = strtolower($torn->nom); // Ho passem a minúscules per evitar errors

        /**
         * SIMULACIÓ D'HORARIS D'HOSPITAL
         * Aquí definim les hores segons el nom del torn
         */
        [$hora_entrada, $hora_sortida] = match (true) {
            str_contains($nomTorn, 'matí')  => ['08:00:00', '15:00:00'],
            str_contains($nomTorn, 'tarda') => ['15:00:00', '22:00:00'],
            str_contains($nomTorn, 'nit')   => ['22:00:00', '08:00:00'],
            default                         => ['08:00:00', '17:00:00'], // Horari d'oficina per defecte
        };

        $inici = \Carbon\Carbon::parse($request->data_inici);
        $fi = \Carbon\Carbon::parse($request->data_fi);

        // 3. Creació massiva (dia a dia)
        while ($inici <= $fi) {
            Horari::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'data'    => $inici->format('Y-m-d'),
                ],
                [
                    'torn_id'      => $request->torn_id,
                    'hora_entrada' => $hora_entrada,
                    'hora_sortida' => $hora_sortida,
                ]
            );
            $inici->addDay();
        }

        return redirect()->route('horaris.index')->with('success', 'Horaris hospitalaris assignats!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
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
