<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nombre total de dies de vacances disponibles per any natural.
     */
    const DIES_VACANCES_ANUALS = 30;

    /**
     * Sobreescribim el mètode per indicar quina columna conté la contrasenya.
     * Per defecte Laravel busca 'password'.
     */
    public function getAuthPassword()
    {
        return $this->contrassenya;
    }

    /**
     * Atributs assignables (Mass Assignment).
     */
    protected $fillable = [
        'nom',
        'cognom',
        'DNI',
        'department_id',
        'role',
        'email',
        'contrassenya',
        'data_alta',
        'actiu',
        'foto_perfil',
    ];

    /**
     * Retorna la URL de la foto de perfil o un avatar generat amb les inicials.
     */
    public function getAvatarUrl(): ?string
    {
        if ($this->foto_perfil) {
            return asset('storage/' . $this->foto_perfil);
        }
        return null;
    }

    /**
     * Retorna les inicials de l'usuari (per a l'avatar per defecte).
     */
    public function getInicials(): string
    {
        return strtoupper(mb_substr($this->nom, 0, 1) . mb_substr($this->cognom, 0, 1));
    }

    /**
     * Atributs ocults en la serialització (JSON, etc).
     */
    protected $hidden = [
        'contrassenya',
        'remember_token',
    ];

    /**
     * Càstings de tipus per a atributs específics.
     */
    protected function casts(): array
    {
        return [
            'data_alta' => 'date',
            'actiu' => 'boolean',
            'contrassenya' => 'hashed', // Laravel farà el hash automàticament en guardar
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Comprova si l'usuari té el rol d'administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cache memòria instància
     */
    protected $vacancesConsumitsCache = [];

    /**
     * Calcula els dies de vacances consumits durant un any natural.
     * Compta els dies naturals de totes les absències amb motiu 'Vacances'
     * que estiguin aprovades o pendents (les pendents es reserven).
     */
    public function diesVacancesConsumits(int $any = null): int
    {
        $any = $any ?? now()->year;

        // Memòria cau instància per evitar consultes repetides (ex. Dashboard)
        if (isset($this->vacancesConsumitsCache[$any])) {
            return $this->vacancesConsumitsCache[$any];
        }

        // Si tenim les absències carregades utilitzem la col·lecció (Eager Loading)
        if ($this->relationLoaded('absencies')) {
            $absenciesFiltered = $this->absencies->filter(function ($absencia) use ($any) {
                if ($absencia->motiu !== 'Vacances')
                    return false;
                if (!in_array($absencia->estat, ['aprovada', 'pendent']))
                    return false;
                $iniciYear = \Carbon\Carbon::parse($absencia->data_inici)->year;
                $fiYear = \Carbon\Carbon::parse($absencia->data_fi)->year;
                return $iniciYear == $any || $fiYear == $any;
            });
        } else {
            $absenciesFiltered = $this->absencies()
                ->where('motiu', 'Vacances')
                ->whereIn('estat', ['aprovada', 'pendent'])
                ->where(function ($query) use ($any) {
                    $query->whereYear('data_inici', $any)
                        ->orWhereYear('data_fi', $any);
                })
                ->get();
        }

        $totalDies = 0;

        foreach ($absenciesFiltered as $absencia) {
            // Limitem l'interval dins de l'any natural
            $inici = \Carbon\Carbon::parse($absencia->data_inici);
            $fi = \Carbon\Carbon::parse($absencia->data_fi);

            $iniciAny = \Carbon\Carbon::create($any, 1, 1);
            $fiAny = \Carbon\Carbon::create($any, 12, 31);

            // Si l'absència comença abans de l'any, limitem al 1 de gener
            if ($inici->lt($iniciAny)) {
                $inici = $iniciAny;
            }
            // Si l'absència acaba després de l'any, limitem al 31 de desembre
            if ($fi->gt($fiAny)) {
                $fi = $fiAny;
            }

            // +1 perquè ambdós dies són inclusius
            $totalDies += (int) ($inici->diffInDays($fi) + 1);
        }

        $this->vacancesConsumitsCache[$any] = $totalDies;

        return $totalDies;
    }

    /**
     * Calcula el total de dies de vacances que corresponen a l'usuari segons la seva data d'alta.
     * PER QUÈ: Un usuari que entra el juny no té dret a 30 dies, se li ha de calcular la part proporcional.
     */
    public function totalDiesVacances(int $any = null): int
    {
        $any = $any ?? now()->year;
        $dataAlta = $this->data_alta;

        // Si no té data d'alta, o va ser donat d'alta abans de l'any actual,
        // té dret a la totalitat dels dies de vacances configurats (per defecte 30).
        if (!$dataAlta || $dataAlta->year < $any) {
            return self::DIES_VACANCES_ANUALS;
        }

        // Si ha entrat aquest mateix any, fem una regla de tres.
        if ($dataAlta->year == $any) {
            $fiAny = \Carbon\Carbon::create($any, 12, 31);

            // Calculem quants dies ha format part de l'empresa aquest any
            $diesEnActiu = $dataAlta->diffInDays($fiAny) + 1;

            // Determinem si és un any de traspàs per ser matemàticament exactes amb els 365 o 366 dies
            $diesAny = $fiAny->isLeapYear() ? 366 : 365;

            // Retorna els dies proporcionals. Exemple: 180 dies treballats / 365 * 30 dies = ~15 dies.
            return (int) round(($diesEnActiu / $diesAny) * self::DIES_VACANCES_ANUALS);
        }

        return 0; // Si entra en el futur, no té dies.
    }

    /**
     * Retorna els dies de vacances restants per a un any natural.
     * PER QUÈ: És la funció clau per la barra de progrés del Dashboard.
     * Simplement resta els consumits als totals i assegura que no doni xifres negatives amb la funció max()
     */
    public function diesVacancesRestants(int $any = null): int
    {
        return max(0, $this->totalDiesVacances($any) - $this->diesVacancesConsumits($any));
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relació amb el Departament.
     * Un usuari pertany a un departament.
     */
    public function departament(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Relació amb Absències.
     * Un usuari pot tenir moltes absències registrades.
     */
    public function absencies(): HasMany
    {
        return $this->hasMany(Absencia::class, 'user_id');
    }

    /**
     * Relació amb Horaris.
     * Un usuari té múltiples assignacions d'horari al calendari.
     */
    public function horaris(): HasMany
    {
        return $this->hasMany(Horari::class, 'user_id');
    }

    /**
     * Relació amb Fixatges (Clock-in/out).
     * PER QUÈ: Ho utilitzem per saber totes les vegades que el treballador ha tocat l'anell.
     */
    public function fixatges(): HasMany
    {
        return $this->hasMany(Fixatge::class, 'user_id');
    }

    /**
     * Comprova si l'usuari està actiu en aquest moment segons el seu torn d'avui.
     * PER QUÈ: S'utilitzaria per saber qui està teòricament treballant "ara mateix" 
     * a partir del que marca el seu horari planificat.
     */
    public function isCurrentlyActive(): bool
    {
        $now = \Carbon\Carbon::now();
        $date = $now->toDateString(); // YYYY-MM-DD
        $time = $now->toTimeString(); // HH:MM:SS

        // Pas 1: Evitar sobrecàrrega de Base de dades (Optimització).
        // Si el controlador ja li ha donat els horaris anteriorment, evitem llançar una consulta SQL innecessària.
        if ($this->relationLoaded('horaris')) {
            $horarisToday = $this->horaris->filter(function ($horari) use ($date) {
                // Assegurem el format String per comparar-ho independentment de si ve del DB o memòria.
                $hDate = $horari->data instanceof \Carbon\Carbon ? $horari->data->toDateString() : (string) $horari->data;
                return $hDate === $date;
            });
        } else {
            // Si no estan carregats, llavors si o si anem a buscar els horaris d'avui creuant la taula Torns.
            $horarisToday = $this->horaris()->where('data', $date)->with('torn')->get();
        }

        // Pas 2: Processament lògic. Iterem per tots els torns del dia de la persona
        foreach ($horarisToday as $horari) {
            if ($horari->torn) {
                $entrada = $horari->torn->hora_entrada;
                $sortida = $horari->torn->hora_sortida;

                // Diferenciem si és un torn Diürn (09 a 17) o Nocturn (22 a 06).
                // Cas normal: L'entrada és més petita numèricament que la sortida.
                if ($entrada <= $sortida) {
                    if ($time >= $entrada && $time <= $sortida) {
                        return true; // L'hora actual encaixa dins del torn
                    }
                } else {
                    // Cas torn nocturn (l'entrada és les 22, i sortida les 06. Entrada > Sortida).
                    if ($time >= $entrada || $time <= $sortida) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
