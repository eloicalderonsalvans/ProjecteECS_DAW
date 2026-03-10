<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TornController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $torns = \App\Models\Torn::all();
        return view('torn.index', compact('torns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('torn.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:torns,nom',
            'descripcio' => 'nullable|string|max:255',
            'color'      => 'required|string|max:7', // Per al codi HEX (#000000)
        ]);

        $torn = new \App\Models\Torn();
        $torn->nom = $request->nom;
        $torn->descripcio = $request->descripcio;
        $torn->color = $request->color;
        $torn->save();

        return redirect()->route('torns.index')->with('success', 'Torn creat correctament!');
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
        $torn = \App\Models\Torn::findOrFail($id);
        return view('torn.edit', compact('torn'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $torn = \App\Models\Torn::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255|unique:torns,nom,' . $id,
            'descripcio' => 'nullable|string|max:255',
            'color'      => 'required|string|max:7',
        ]);

        $torn->nom = $request->nom;
        $torn->descripcio = $request->descripcio;
        $torn->color = $request->color;
        $torn->save();

        return redirect()->route('torns.index')->with('success', 'Torn actualitzat correctament!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $torn = \App\Models\Torn::findOrFail($id);
        $torn->delete();

        return redirect()->route('torns.index')->with('success', 'Torn eliminat correctament!');
    }
}
