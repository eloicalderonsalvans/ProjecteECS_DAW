<?php

namespace App\Http\Controllers;

use App\Models\Fixatge;
use App\Models\H_Fixatge;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixatgeController extends Controller
{
    /**
     * Mostra la pantalla de fitxatge i l'historial de l'usuari autenticat.
     */
    public function index()
    {
        $user = auth()->user();
        $usuaris = collect();
        $usuariSeleccionat = null;

        $ultimFixatge = $user->fixatges()
            ->latest('data')
            ->first();

        $properTipus = $ultimFixatge?->check ? 'sortida' : 'entrada';

        $historialQuery = Fixatge::query()
            ->with('user')
            ->orderByDesc('data');

        if ($user->isAdmin()) {
            $usuaris = User::where('actiu', true)
                ->orderBy('nom')
                ->orderBy('cognom')
                ->get();

            $usuariSeleccionat = request()->integer('user_id') ?: null;

            if ($usuariSeleccionat) {
                $historialQuery->where('user_id', $usuariSeleccionat);
            }
        } else {
            $historialQuery->where('user_id', $user->id);
        }

        $historial = $historialQuery
            ->paginate(15)
            ->withQueryString();

        return view('fixatge.index', compact('ultimFixatge', 'properTipus', 'historial', 'usuaris', 'usuariSeleccionat'));
    }

    /**
     * Formulari per fitxar (Per implementar).
     */
    public function create()
    {
        //
    }

    /**
     * Guarda un nou fitxatge i la seva entrada d'historial.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'ubicacio_x' => 'required|integer',
            'ubicacio_y' => 'required|integer',
        ], [
            'ubicacio_x.required' => 'Cal autoritzar la geolocalització abans de fitxar.',
            'ubicacio_y.required' => 'Cal autoritzar la geolocalització abans de fitxar.',
        ]);

        $user = $request->user();
        $ultimFixatge = $user->fixatges()->latest('data')->first();
        $tipus = $ultimFixatge?->check ? 'sortida' : 'entrada';

        $momentFixatge = now();

        DB::transaction(function () use ($user, $tipus, $momentFixatge, $request) {
            $fixatge = Fixatge::create([
                'user_id' => $user->id,
                'data' => $momentFixatge,
                'check' => $tipus === 'entrada',
                'ubicacio_x' => $request->integer('ubicacio_x'),
                'ubicacio_y' => $request->integer('ubicacio_y'),
                'dispositiu' => (string) $request->userAgent(),
            ]);

            H_Fixatge::create([
                'user_id' => $user->id,
                'fitxatge_id' => $fixatge->id,
                'data' => $momentFixatge,
            ]);
        });

        $missatge = $tipus === 'entrada'
            ? 'Entrada registrada correctament.'
            : 'Sortida registrada correctament.';

        return redirect()
            ->route('fitxar.index')
            ->with('success', $missatge);
    }

    /**
     * Detall d'un fitxatge (Per implementar).
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Editar un fitxatge (Per implementar).
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Actualitzar un fitxatge (Per implementar).
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Eliminar un fitxatge (Per implementar).
     */
    public function destroy(string $id)
    {
        //
    }
}
