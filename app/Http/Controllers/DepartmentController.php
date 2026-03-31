<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Mostra la llista de tots els departaments de l'empresa.
     */
    public function index()
    {
        // Obtenim tots els departaments des del Model
        $department = \App\Models\Department::all();

        // Retornem la vista amb la col·lecció de departaments
        return view('department.index', compact('department'));
    }

    /**
     * Mostra el formulari per registrar un nou departament.
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Guarda el nou departament a la base de dades.
     */
    public function store(Request $request)
    {
        // 1. Validació de dades: El nom del departament ha de ser únic
        $request->validate([
            'nom' => 'required|string|max:255|unique:department,nom',
            'descripcio' => 'nullable|string|max:255',
        ]);

        // 2. Creació del registre utilitzant Eloquent
        $dept = new Department;
        $dept->nom = $request->nom;
        $dept->descripcio = $request->descripcio;
        $dept->save();

        // 3. Redirecció amb missatge de confirmació
        return redirect()->route('department.index')->with('success', 'Departament creat correctament!');
    }

    /**
     * Mostra el formulari per editar un departament existent.
     */
    public function edit($id)
    {
        // Cerquem el departament per ID
        $dept = Department::findOrFail($id);

        return view('department.edit', compact('dept'));
    }

    /**
     * Actualitza les dades del departament a la base de dades.
     */
    public function update(Request $request, $id)
    {
        $dept = Department::findOrFail($id);

        // Validació: Permetem mantenir el mateix nom si és el mateix registre
        $request->validate([
            'nom' => 'required|string|max:255|unique:department,nom,'.$id,
            'descripcio' => 'nullable|string|max:255',
        ]);

        $dept->nom = $request->nom;
        $dept->descripcio = $request->descripcio;
        $dept->save();

        return redirect()->route('departments.index')->with('success', 'Departament actualitzat correctament!');
    }

    /**
     * Elimina un departament.
     */
    public function destroy($id)
    {
        $dept = Department::findOrFail($id);
        $dept->delete();

        return redirect()->route('departments.index')->with('success', 'Departament eliminat correctament!');
    }
}
