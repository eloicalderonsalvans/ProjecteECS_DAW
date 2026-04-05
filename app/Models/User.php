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
    public function fixatges(): HasMany
    {
        return $this->hasMany(Fixatge::class, 'user_id');
    }
}
