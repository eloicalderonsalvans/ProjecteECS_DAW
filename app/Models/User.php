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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function getAuthPassword()
    {
        return $this->contrassenya;
    }

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
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'contrassenya',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data_alta' => 'date',
            'actiu' => 'boolean',
            'contrassenya' => 'hashed',
        ];
    }

    public function departament(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Relació amb Absències.
     */
    public function absencies(): HasMany
    {
        return $this->hasMany(Absencia::class, 'user_id');
    }

    /**
     * Relació amb Horaris (recorda que al diagrama deies "id_usuari").
     */
    public function horaris(): HasMany
    {
        return $this->hasMany(Horari::class, 'user_id');
    }

    /**
     * Relació amb Fixatges.
     */
    public function fixatges(): HasMany
    {
        return $this->hasMany(Fixatge::class, 'user_id');
    }
}
