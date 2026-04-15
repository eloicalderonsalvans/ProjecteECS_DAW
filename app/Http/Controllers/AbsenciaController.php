<?php

namespace App\Http\Controllers;

use App\Models\Absencia;
use App\Models\User;
use Carbon\Carbon;
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

        // Dies de vacances per a l'usuari actual
        $diesVacancesRestants = $user->diesVacancesRestants();
        $diesVacancesConsumits = $user->diesVacancesConsumits();
        $diesVacancesTotal = User::DIES_VACANCES_ANUALS;

        if ($user->isAdmin()) {
            // Admin veu totes les absències vigents (data_fi >= avui)
            $absencies = Absencia::with('user')
                ->where('data_fi', '>=', now()->toDateString())
                ->orderBy('data_inici', 'desc')->get();
        } else {
            // Usuari normal només veu les seves pròpies vigents
            $absencies = Absencia::where('user_id', $user->id)
                ->where('data_fi', '>=', now()->toDateString())
                ->orderBy('data_inici', 'desc')
                ->get();
        }

        return view('absencia.index', compact('absencies', 'diesVacancesRestants', 'diesVacancesConsumits', 'diesVacancesTotal'));
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

        // Dies de vacances restants per a l'usuari actual
        $diesRestants = $user->diesVacancesRestants();
        $diesConsumits = $user->diesVacancesConsumits();

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

        return view('absencia.create', compact('users', 'aprovadors', 'diesRestants', 'diesConsumits'));
    }

    /**
     * Guarda la sol·licitud d'absència a la base de dades.
     * Usuari normal: s'assigna automàticament com a sol·licitant, estat = pendent.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'motiu' => 'required|string|in:Vacances,Baixa mèdica,Assumptes propis,Formació,Altres',
            'data_inici' => 'required|date',
            'data_fi' => 'required|date|after_or_equal:data_inici',
        ]);

        $targetUserId = $user->isAdmin() && $request->has('user_id') ? $request->user_id : $user->id;
        $targetUser = User::findOrFail($targetUserId);

        // Comprovem que l'usuari tingui com a mínim un torn assignat entre aquestes dates
        $hasShifts = \App\Models\Horari::where('user_id', $targetUserId)
            ->whereBetween('data', [$request->data_inici, $request->data_fi])
            ->exists();

        if (! $hasShifts) {
            return redirect()->back()
                ->withErrors(['data_inici' => "No es pot demanar una absència si no es té cap torn assignat en aquestes dates."])
                ->withInput();
        }

        // Validació de dies de vacances disponibles
        if ($request->motiu === 'Vacances') {
            $dataInici = Carbon::parse($request->data_inici);
            $dataFi = Carbon::parse($request->data_fi);
            $diesSollicitats = $dataInici->diffInDays($dataFi) + 1;

            $anyInici = $dataInici->year;
            $diesRestants = $targetUser->diesVacancesRestants($anyInici);

            if ($diesSollicitats > $diesRestants) {
                return redirect()->back()
                    ->withErrors(['motiu' => "No tens prou dies de vacances. Dies sol·licitats: {$diesSollicitats}. Dies disponibles: {$diesRestants}."])
                    ->withInput();
            }
        }

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

        // Si és de vacances, validem que l'usuari encara tingui dies disponibles
        if ($absencia->motiu === 'Vacances') {
            $empleat = User::findOrFail($absencia->user_id);
            $dataInici = Carbon::parse($absencia->data_inici);
            $dataFi = Carbon::parse($absencia->data_fi);
            $diesAbsencia = $dataInici->diffInDays($dataFi) + 1;

            // Calculem restants sense comptar aquesta absència (ja és pendent, ja es compta)
            // Simplement verifiquem que els consumits no superin el màxim
            $diesRestants = $empleat->diesVacancesRestants($dataInici->year);

            // Si l'absència ja es comptava com a pendent, els dies restants ja la tenen en compte.
            // Per tant, si diesRestants >= 0, vol dir que hi cap.
            if ($diesRestants < 0) {
                return redirect()->route('absencies.index')
                    ->with('error', "No es pot aprovar: l'empleat no té prou dies de vacances disponibles.");
            }
        }

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
