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
    ];

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
     * Calcula els dies de vacances consumits durant un any natural.
     * Compta els dies naturals de totes les absències amb motiu 'Vacances'
     * que estiguin aprovades o pendents (les pendents es reserven).
     */
    public function diesVacancesConsumits(int $any = null): int
    {
        $any = $any ?? now()->year;

        $absencies = $this->absencies()
            ->where('motiu', 'Vacances')
            ->whereIn('estat', ['aprovada', 'pendent'])
            ->where(function ($query) use ($any) {
                $query->whereYear('data_inici', $any)
                      ->orWhereYear('data_fi', $any);
            })
            ->get();

        $totalDies = 0;

        foreach ($absencies as $absencia) {
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
            $totalDies += $inici->diffInDays($fi) + 1;
        }

        return $totalDies;
    }

    /**
     * Retorna els dies de vacances restants per a un any natural.
     */
    public function diesVacancesRestants(int $any = null): int
    {
        return max(0, self::DIES_VACANCES_ANUALS - $this->diesVacancesConsumits($any));
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
     * Un usuari té molts registres de fitxatge.
     */
    /**
     * Relació amb Fixatges (Clock-in/out).
     * Un usuari té molts registres de fitxatge.
     */
    public function fixatges(): HasMany
    {
        return $this->hasMany(Fixatge::class, 'user_id');
    }

    /**
     * Comprova si l'usuari està actiu en aquest moment segons el seu torn d'avui.
     * Retorna true si l'hora actual està dins del rang d'entrada i sortida del seu torn.
     */
    public function isCurrentlyActive(): bool
    {
        $now = \Carbon\Carbon::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        // Obtenim els horaris d'avui. Si ja estan carregats per Eager Loading, els filtrem a la col·lecció
        if ($this->relationLoaded('horaris')) {
            $horarisToday = $this->horaris->filter(function ($horari) use ($date) {
                // Comprovem si el camp data és un string o instància Carbon
                $hDate = $horari->data instanceof \Carbon\Carbon ? $horari->data->toDateString() : (string) $horari->data;
                return $hDate === $date;
            });
        } else {
            $horarisToday = $this->horaris()->where('data', $date)->with('torn')->get();
        }

        foreach ($horarisToday as $horari) {
            if ($horari->torn) {
                $entrada = $horari->torn->hora_entrada;
                $sortida = $horari->torn->hora_sortida;

                // Cas normal (ej. 08:00 - 15:00)
                if ($entrada <= $sortida) {
                    if ($time >= $entrada && $time <= $sortida) {
                        return true;
                    }
                } else {
                    // Cas torn nocturn, creua la mitjanit (ej. 22:00 - 06:00)
                    if ($time >= $entrada || $time <= $sortida) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
