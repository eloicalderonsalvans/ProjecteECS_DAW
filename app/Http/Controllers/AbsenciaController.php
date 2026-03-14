<?php

namespace App\Http\Controllers;

use App\Models\Absencia;
use App\Models\User;
use Illuminate\Http\Request;

class AbsenciaController extends Controller
{
    public function index()
    {
        $absencies = Absencia::with('user')->orderBy('data_inici', 'desc')->get();
        return view('absencia.index', compact('absencies'));
    }

    public function create()
    {
        $users = User::where('actiu', true)->get();
        $aprovadors = User::where('actiu', true)
            ->where(function($query) {
                $query->whereIn('role', ['admin', 'cap_departament'])
                      ->orWhereHas('departament', function($q) {
                          $q->where('nom', 'LIKE', '%Recursos Humans%');
                      });
            })->get();

        return view('absencia.create', compact('users', 'aprovadors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'motiu'      => 'required|string|max:255',
            'data_inici' => 'required|date',
            'data_fi'    => 'required|date|after_or_equal:data_inici',
            'aprobat_per'=> 'nullable|string',
        ]);

        Absencia::create($request->all());

        return redirect()->route('absencies.index')->with('success', 'Absència registrada correctament.');
    }

    public function edit(string $id)
    {
        $absencia = Absencia::findOrFail($id);
        $users = User::where('actiu', true)->get();
        $aprovadors = User::where('actiu', true)
            ->where(function($query) {
                $query->whereIn('role', ['admin', 'cap_departament'])
                      ->orWhereHas('departament', function($q) {
                          $q->where('nom', 'LIKE', '%Recursos Humans%');
                      });
            })->get();

        return view('absencia.edit', compact('absencia', 'users', 'aprovadors'));
    }

    public function update(Request $request, string $id)
    {
        $absencia = Absencia::findOrFail($id);

        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'motiu'      => 'required|string|max:255',
            'data_inici' => 'required|date',
            'data_fi'    => 'required|date|after_or_equal:data_inici',
            'aprobat_per'=> 'nullable|string',
        ]);

        $absencia->update($request->all());

        return redirect()->route('absencies.index')->with('success', 'Absència actualitzada.');
    }

    public function destroy(string $id)
    {
        Absencia::findOrFail($id)->delete();
        return redirect()->route('absencies.index')->with('success', 'Absència eliminada.');
    }
}