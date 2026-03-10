<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtenim tots els departaments
        $department = \App\Models\Department::all();

        // Retornem la vista enviant la variable 'department'
        return view('department.index', compact('department'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validació de dades
        $request->validate([
            'nom' => 'required|string|max:255|unique:department,nom',
            'descripcio' => 'nullable|string|max:255',
        ]);

        // 2. Creació del registre
        $dept = new Department();
        $dept->nom = $request->nom;
        $dept->descripcio = $request->descripcio;
        $dept->save();

        // 3. Redirecció amb missatge d'èxit
        return redirect()->route('department.index')->with('success', 'Departament creat correctament!');
    }

   public function edit($id)
    {
        $dept = Department::findOrFail($id);
        return view('department.edit', compact('dept'));
    }

    /**
     * Actualitza el departament a la base de dades.
     */
    public function update(Request $request, $id)
    {
        $dept = Department::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255|unique:department,nom,' . $id,
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
