<?php

namespace App\Http\Controllers;

use App\Models\Absencia;
use App\Models\User;
use Illuminate\Http\Request;

class AbsenciaController extends Controller
{
    /**
     * Mostra la llista de totes les absències registrades.
     * Ordenades per data d'inici (les més recents primer).
     */
    public function index()
    {
        // Carreguem les absències amb l'usuari associat per mostrar el seu nom
        $absencies = Absencia::with('user')->orderBy('data_inici', 'desc')->get();

        return view('absencia.index', compact('absencies'));
    }

    /**
     * Mostra el formulari per demanar una nova absència.
     */
    public function create()
    {
        // Necessitem els usuaris actius per al selector
        $users = User::where('actiu', true)->get();

        // Filtrem els aprovadors: Admins, Caps de Departament o personal de RRHH
        $aprovadors = User::where('actiu', true)
            ->where(function ($query) {
                $query->whereIn('role', ['admin', 'cap_departament'])
                    ->orWhereHas('departament', function ($q) {
                        $q->where('nom', 'LIKE', '%Recursos Humans%');
                    });
            })->get();

        return view('absencia.create', compact('users', 'aprovadors'));
    }

    /**
     * Guarda la sol·licitud d'absència a la base de dades.
     */
    public function store(Request $request)
    {
        // Validació: La data de fi ha de ser posterior o igual a la d'inici
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'motiu' => 'required|string|max:255',
            'data_inici' => 'required|date',
            'data_fi' => 'required|date|after_or_equal:data_inici',
            'aprobat_per' => 'nullable|string',
        ]);

        // Creació massiva utilitzant l'array de dades validades
        Absencia::create($request->all());

        return redirect()->route('absencies.index')->with('success', 'Absència registrada correctament.');
    }

    /**
     * Mostra el formulari per editar una absència ja existent.
     */
    public function edit(string $id)
    {
        $absencia = Absencia::findOrFail($id);
        $users = User::where('actiu', true)->get();
        // Tornem a carregar la llista de possibles aprovadors
        $aprovadors = User::where('actiu', true)
            ->where(function ($query) {
                $query->whereIn('role', ['admin', 'cap_departament'])
                    ->orWhereHas('departament', function ($q) {
                        $q->where('nom', 'LIKE', '%Recursos Humans%');
                    });
            })->get();

        return view('absencia.edit', compact('absencia', 'users', 'aprovadors'));
    }

    /**
     * Actualitza les dades de l'absència.
     */
    public function update(Request $request, string $id)
    {
        $absencia = Absencia::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'motiu' => 'required|string|max:255',
            'data_inici' => 'required|date',
            'data_fi' => 'required|date|after_or_equal:data_inici',
            'aprobat_per' => 'nullable|string',
        ]);

        $absencia->update($request->all());

        return redirect()->route('absencies.index')->with('success', 'Absència actualitzada.');
    }

    /**
     * Elimina una absència.
     */
    public function destroy(string $id)
    {
        Absencia::findOrFail($id)->delete();

        return redirect()->route('absencies.index')->with('success', 'Absència eliminada.');
    }
}
