<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Torn extends Model
{
    use HasFactory;

    /**
     * Nom de la taula a la base de dades.
     * @var string
     */
    protected $table = 'torns';

    /**
     * Atributs assignables segons la teva estructura de phpMyAdmin.
     * @var array<string>
     */
    protected $fillable = [
        'nom',
        'descripcio',
        'color',
        'hora_entrada',
        'hora_sortida',
    ];

    /**
     * Relació amb els Horaris.
     * Segons el diagrama, un torn pot estar assignat a diversos horaris.
     */
    public function horaris(): HasMany
    {
        // Suposant que la clau forana a la taula 'horaris' es diu 'torn_id'
        return $this->hasMany(Horari::class, 'torn_id');
    }
}
