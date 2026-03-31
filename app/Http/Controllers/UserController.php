<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Mostra la llista de tots els usuaris.
     * Carrega la relació amb el departament per mostrar el nom en lloc de l'ID.
     */
    public function index()
    {
        // Obtenim tots els usuaris amb el seu departament carregat (Eager Loading)
        $users = \App\Models\User::with('departament')->get();

        // Retornem la vista enviant la llista d'usuaris
        return view('user.index', compact('users'));
    }

    /**
     * Mostra el formulari per crear un nou usuari.
     */
    public function create()
    {
        // Necessitem els departaments per al selector del formulari
        $department = \App\Models\Department::all();

        return view('user.create', compact('department'));
    }

    /**
     * Guarda un nou usuari a la base de dades.
     */
    public function store(Request $request)
    {
        // 1. Validació estricta de les dades d'entrada
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'cognom' => 'required|string|max:255',
            'DNI' => 'required|string|max:255|unique:users,DNI', // El DNI ha de ser únic
            'email' => 'required|email|max:255|unique:users,email', // L'email ha de ser únic
            'contrassenya' => 'required|min:6', // Mínim 6 caràcters per seguretat
            'data_alta' => 'required|date',
            'department_id' => 'nullable|exists:department,id',
            'role' => 'required|string|max:255',
            'actiu' => 'sometimes|boolean',
        ]);

        try {
            // 2. Creació de la instància i assignació de valors
            $user = new \App\Models\User;
            $user->nom = $request->nom;
            $user->cognom = $request->cognom;
            $user->DNI = $request->DNI;
            $user->email = $request->email;
            $user->contrassenya = $request->contrassenya; // El hashing es gestiona al Model (Casts)
            $user->data_alta = $request->data_alta;
            $user->department_id = $request->department_id;
            $user->role = $request->role;
            $user->actiu = $request->has('actiu') ? 1 : 0; // Si no ve el checkbox, s'assigna 0

            $user->save();

            // 3. Redirecció si tot ha anat bé
            return redirect()->route('users.index')->with('success', 'Usuari creat amb èxit!');

        } catch (\Exception $e) {
            // Gestió d'errors de base de dades
            return back()->withInput()->withErrors(['db_error' => 'Error al guardar: '.$e->getMessage()]);
        }
    }

    /**
     * Mètode show (actualment no utilitzat).
     */
    public function show(string $id)
    { /* Per implementar si cal fitxa de detall */
    }

    /**
     * Mostra el formulari per editar un usuari existent.
     */
    public function edit(string $id)
    {
        // Cerquem l'usuari o llencem error 404 si no existeix
        $user = \App\Models\User::findOrFail($id);
        // Carreguem els departaments per al selector
        $department = \App\Models\Department::all();

        return view('user.edit', compact('user', 'department'));
    }

    /**
     * Actualitza les dades de l'usuari a la base de dades.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        // Validació (ignorem el DNI i l'email de l'usuari actual per permetre "guardar sense canvis")
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'cognom' => 'required|string|max:255',
            'DNI' => 'required|string|max:255|unique:users,DNI,'.$id,
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'contrassenya' => 'nullable|min:6', // Contrasenya opcional en edició
            'data_alta' => 'required|date',
            'department_id' => 'nullable|exists:department,id',
            'role' => 'required|string|max:255',
        ]);

        // Assignació de nous valors
        $user->nom = $request->nom;
        $user->cognom = $request->cognom;
        $user->DNI = $request->DNI;
        $user->email = $request->email;
        $user->data_alta = $request->data_alta;
        $user->department_id = $request->department_id;
        $user->role = $request->role;
        $user->actiu = $request->has('actiu') ? 1 : 0;

        // Només actualitzem la contrasenya si l'usuari n'ha escrit una de nova
        if ($request->filled('contrassenya')) {
            $user->contrassenya = $request->contrassenya;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuari actualitzat correctament.');
    }

    /**
     * Elimina un usuari de la base de dades.
     */
    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuari eliminat amb èxit.');
    }
}
