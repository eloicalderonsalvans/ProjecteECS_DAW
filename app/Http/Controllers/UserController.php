<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtenim tots els usuaris amb el seu departament carregat
        $users = \App\Models\User::with('departament')->get();

        // Retornem la vista enviant la variable 'users'
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $department = \App\Models\Department::all(); // O Department::all() si tens l'import
        return view('user.create', compact('department'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // Validació estricta segons la teva estructura (image_5d5c9c.png)
        $validated = $request->validate([
        'nom'           => 'required|string|max:255',
        'cognom'        => 'required|string|max:255',
        'DNI'           => 'required|string|max:255|unique:users,DNI',
        'email'         => 'required|email|max:255|unique:users,email',
        'contrassenya'  => 'required|min:6',
        'data_alta'     => 'required|date',
        'department_id' => 'nullable|exists:department,id',
        'role'          => 'required|string|max:255',
        'actiu'         => 'sometimes|boolean',
        ]);

        try {
            $user = new \App\Models\User();
            $user->nom = $request->nom;
            $user->cognom = $request->cognom;
            $user->DNI = $request->DNI;
            $user->email = $request->email;
            $user->contrassenya = $request->contrassenya; 
            $user->data_alta = $request->data_alta;
            $user->department_id = $request->department_id;
            $user->role = $request->role;
            $user->actiu = $request->has('actiu') ? 1 : 0;
            
            $user->save();

            return redirect()->route('users.index')->with('success', 'Usuari creat amb èxit!');
            
        } catch (\Exception $e) {
            // Si hi ha un error de base de dades, el veuràs aquí
            return back()->withInput()->withErrors(['db_error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     */
    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $department = \App\Models\Department::all(); // Cargamos departamentos para el select
        
        return view('user.edit', compact('user', 'department'));
    }

    /**
     * Actualiza el usuario en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        // Validamos (el email y DNI ignoran al usuario actual para no dar error de "duplicado")
        $validated = $request->validate([
            'nom'           => 'required|string|max:255',
            'cognom'        => 'required|string|max:255',
            'DNI'           => 'required|string|max:255|unique:users,DNI,' . $id,
            'email'         => 'required|email|max:255|unique:users,email,' . $id,
            'contrassenya'  => 'nullable|min:6', // Opcional al editar
            'data_alta'     => 'required|date',
            'department_id' => 'nullable|exists:department,id',
            'role'          => 'required|string|max:255',
        ]);

        $user->nom = $request->nom;
        $user->cognom = $request->cognom;
        $user->DNI = $request->DNI;
        $user->email = $request->email;
        $user->data_alta = $request->data_alta;
        $user->department_id = $request->department_id;
        $user->role = $request->role;
        $user->actiu = $request->has('actiu') ? 1 : 0;

        // Solo actualizamos la contraseña si el usuario ha escrito algo
        if ($request->filled('contrassenya')) {
            $user->contrassenya = $request->contrassenya; 
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
    * Remove the specified resource from storage.
   */
    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuari eliminat amb èxit.');
    }
}
