<?php

namespace App\Http\Controllers;

use App\Models\Absencia;
use App\Models\User;
use Illuminate\Http\Request;

class AbsenciaController extends Controller
{
    /**
     * Mostra la llista d'absències.
     * Admin: veu totes les absències.
     * Usuari normal: veu només les pròpies.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // Admin veu totes les absències amb l'usuari associat
            $absencies = Absencia::with('user')->orderBy('data_inici', 'desc')->get();
        } else {
            // Usuari normal només veu les seves pròpies
            $absencies = Absencia::where('user_id', $user->id)
                ->orderBy('data_inici', 'desc')
                ->get();
        }

        return view('absencia.index', compact('absencies'));
    }

    /**
     * Mostra el formulari per demanar una nova absència.
     * Admin: pot seleccionar qualsevol usuari i aprovar directament.
     * Usuari normal: formulari simplificat (només motiu i dates).
     */
    public function create()
    {
        $user = auth()->user();
        $users = null;
        $aprovadors = null;

        if ($user->isAdmin()) {
            $users = User::where('actiu', true)->get();
            $aprovadors = User::where('actiu', true)
                ->where(function ($query) {
                    $query->whereIn('role', ['admin', 'cap_departament'])
                        ->orWhereHas('departament', function ($q) {
                            $q->where('nom', 'LIKE', '%Recursos Humans%');
                        });
                })->get();
        }

        return view('absencia.create', compact('users', 'aprovadors'));
    }

    /**
     * Guarda la sol·licitud d'absència a la base de dades.
     * Usuari normal: s'assigna automàticament com a sol·licitant, estat = pendent.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'motiu' => 'required|string|max:255',
            'data_inici' => 'required|date',
            'data_fi' => 'required|date|after_or_equal:data_inici',
        ]);

        if ($user->isAdmin()) {
            // Admin pot assignar a qualsevol usuari
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'aprobat_per' => 'nullable|string',
                'estat' => 'nullable|string',
            ]);

            Absencia::create([
                'user_id' => $request->user_id,
                'motiu' => $request->motiu,
                'data_inici' => $request->data_inici,
                'data_fi' => $request->data_fi,
                'aprobat_per' => $request->aprobat_per,
                'estat' => $request->aprobat_per ? 'aprovada' : 'pendent',
            ]);
        } else {
            // Usuari normal: forcem el seu propi ID i estat pendent
            Absencia::create([
                'user_id' => $user->id,
                'motiu' => $request->motiu,
                'data_inici' => $request->data_inici,
                'data_fi' => $request->data_fi,
                'estat' => 'pendent',
            ]);
        }

        return redirect()->route('absencies.index')->with('success', 'Absència registrada correctament.');
    }

    /**
     * Mostra el formulari per editar una absència (només admin).
     */
    public function edit(string $id)
    {
        $absencia = Absencia::findOrFail($id);
        $users = User::where('actiu', true)->get();
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
     * Actualitza les dades de l'absència (només admin).
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
            'estat' => 'nullable|string',
        ]);

        $absencia->update($request->all());

        return redirect()->route('absencies.index')->with('success', 'Absència actualitzada.');
    }

    /**
     * Aprova una absència (només admin).
     */
    public function aprovar(string $id)
    {
        $absencia = Absencia::findOrFail($id);
        $admin = auth()->user();

        $absencia->update([
            'estat' => 'aprovada',
            'aprobat_per' => $admin->nom . ' ' . $admin->cognom,
        ]);

        return redirect()->route('absencies.index')->with('success', 'Absència aprovada correctament.');
    }

    /**
     * Rebutja una absència (només admin).
     */
    public function rebutjar(string $id)
    {
        $absencia = Absencia::findOrFail($id);
        $admin = auth()->user();

        $absencia->update([
            'estat' => 'rebutjada',
            'aprobat_per' => $admin->nom . ' ' . $admin->cognom,
        ]);

        return redirect()->route('absencies.index')->with('success', 'Absència rebutjada.');
    }

    /**
     * Elimina/cancel·la una absència.
     * Admin: pot eliminar qualsevol absència.
     * Usuari normal: només pot cancel·lar les pròpies que estiguin pendents.
     */
    public function destroy(string $id)
    {
        $absencia = Absencia::findOrFail($id);
        $user = auth()->user();

        if (! $user->isAdmin()) {
            // Verificar que és la seva pròpia absència i que està pendent
            if ($absencia->user_id !== $user->id || $absencia->estat !== 'pendent') {
                return redirect()->route('absencies.index')
                    ->with('error', 'No pots cancel·lar aquesta absència.');
            }
        }

        $absencia->delete();

        return redirect()->route('absencies.index')->with('success', 'Absència eliminada.');
    }
}
