<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Mostra la llista de tots els usuaris.
     * Carrega la relació amb el departament per mostrar el nom en lloc de l'ID.
     */
    public function index()
    {
        // Obtenim tots els usuaris amb el seu departament carregat (Eager Loading) i revisem els horaris d'avui per evitar l'N+1
        $today = \Carbon\Carbon::now()->toDateString();
        $users = \App\Models\User::with(['departament', 'horaris' => function ($query) use ($today) {
            $query->where('data', $today)->with('torn');
        }])->get();

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
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // 'actiu' ja no es gestiona manualment, es controla automàticament en fitxar
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
            $user->actiu = 0; // L'usuari comença inactiu; passarà a actiu automàticament en fitxar entrada

            // 3. Gestió de la foto de perfil
            if ($request->hasFile('foto_perfil')) {
                $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
                $user->foto_perfil = $path;
            }

            $user->save();

            // 4. Redirecció si tot ha anat bé
            return redirect()->route('users.index')->with('success', 'Usuari creat amb èxit!');

        } catch (\Exception $e) {
            // Gestió d'errors de base de dades
            return back()->withInput()->withErrors(['db_error' => 'Error al guardar: '.$e->getMessage()]);
        }
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
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Assignació de nous valors
        $user->nom = $request->nom;
        $user->cognom = $request->cognom;
        $user->DNI = $request->DNI;
        $user->email = $request->email;
        $user->data_alta = $request->data_alta;
        $user->department_id = $request->department_id;
        $user->role = $request->role;
        // $user->actiu no es modifica aquí; es gestiona automàticament en fitxar entrada/sortida

        // Només actualitzem la contrasenya si l'usuari n'ha escrit una de nova
        if ($request->filled('contrassenya')) {
            $user->contrassenya = $request->contrassenya;
        }

        // Gestió de la foto de perfil
        if ($request->hasFile('foto_perfil')) {
            // Eliminem la foto antiga si existeix
            if ($user->foto_perfil) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
            $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $user->foto_perfil = $path;
        }

        // Opció per eliminar la foto
        if ($request->boolean('eliminar_foto') && $user->foto_perfil) {
            Storage::disk('public')->delete($user->foto_perfil);
            $user->foto_perfil = null;
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

        // Eliminem la foto de perfil si n'hi ha
        if ($user->foto_perfil) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuari eliminat amb èxit.');
    }
}
