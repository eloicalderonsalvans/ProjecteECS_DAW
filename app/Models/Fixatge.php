<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fixatge extends Model
{
    use HasFactory;

    protected $table = 'fixatges';

    /**
     * Atributs assignables per al registre de jornada laboral.
     */
    protected $fillable = [
        'user_id',
        'data',
        'check',       // Indica si és entrada (true) o sortida (false)
        'ubicacio_x',
        'ubicacio_y',
        'dispositiu',
        'comentaris',
    ];

    /**
     * Càstings de tipus de dades.
     */
    protected function casts(): array
    {
        return [
            'data' => 'datetime',
            'check' => 'boolean',  // El tinyint(1) el tractem com a booleà
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relació amb l'Usuari que ha fet el fitxatge.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
