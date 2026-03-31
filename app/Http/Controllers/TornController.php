<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TornController extends Controller
{
    /**
     * Mostra la llista de tots els torns de treball disponibles.
     */
    public function index()
    {
        // Recuperem tots els registres de la taula 'torns'
        $torns = \App\Models\Torn::all();

        return view('torn.index', compact('torns'));
    }

    /**
     * Mostra el formulari per crear un nou torn.
     */
    public function create()
    {
        return view('torn.create');
    }

    /**
     * Guarda un nou torn a la base de dades.
     */
    public function store(Request $request)
    {
        // Validació de dades: El nom ha de ser únic i les hores han de seguir el format H:i
        $request->validate([
            'nom' => 'required|string|max:255|unique:torns,nom',
            'descripcio' => 'nullable|string|max:255',
            'color' => 'required|string|max:7', // Format hex (ex: #FFFFFF)
            'hora_entrada' => 'required|date_format:H:i',
            'hora_sortida' => 'required|date_format:H:i',
        ]);

        // Creació de l'objecte i assignació de valors
        $torn = new \App\Models\Torn;
        $torn->nom = $request->nom;
        $torn->descripcio = $request->descripcio;
        $torn->color = $request->color;
        $torn->hora_entrada = $request->hora_entrada;
        $torn->hora_sortida = $request->hora_sortida;
        $torn->save();

        return redirect()->route('torns.index')->with('success', 'Torn creat correctament!');
    }

    /**
     * Mètode show (actualment no utilitzat).
     */
    public function show(string $id)
    { /* No utilitzat */
    }

    /**
     * Mostra el formulari per editar un torn existent.
     */
    public function edit(string $id)
    {
        // Busquem el torn o error 404
        $torn = \App\Models\Torn::findOrFail($id);

        return view('torn.edit', compact('torn'));
    }

    /**
     * Actualitza les dades del torn a la base de dades.
     */
    public function update(Request $request, string $id)
    {
        $torn = \App\Models\Torn::findOrFail($id);

        // Validació per l'edició (permetem mantenir el mateix nom per al mateix ID)
        $request->validate([
            'nom' => 'required|string|max:255|unique:torns,nom,'.$id,
            'descripcio' => 'nullable|string|max:255',
            'color' => 'required|string|max:7',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_sortida' => 'required|date_format:H:i',
        ]);

        $torn->nom = $request->nom;
        $torn->descripcio = $request->descripcio;
        $torn->color = $request->color;
        $torn->hora_entrada = $request->hora_entrada;
        $torn->hora_sortida = $request->hora_sortida;
        $torn->save();

        return redirect()->route('torns.index')->with('success', 'Torn actualitzat correctament!');
    }

    /**
     * Elimina un torn de la base de dades.
     */
    public function destroy(string $id)
    {
        $torn = \App\Models\Torn::findOrFail($id);
        $torn->delete();

        return redirect()->route('torns.index')->with('success', 'Torn eliminat correctament!');
    }
}
