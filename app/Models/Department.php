<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';

    /**
     * Atributs assignables basats en la teva estructura de phpMyAdmin.
     */
    protected $fillable = [
        'nom',
        'descripcio',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relació amb els Usuaris.
     * Un departament té molts usuaris (hasMany).
     */
    public function users(): HasMany
    {
        // Utilitzem 'department_id' que és la FK que apareix a la teva taula users
        return $this->hasMany(User::class, 'department_id');
    }
}
