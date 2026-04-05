<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absencia extends Model
{
    use HasFactory;

    protected $table = 'absencia';

    /**
     * Atributs assignables per a la gestió d'absències.
     */
    protected $fillable = [
        'user_id',
        'motiu',
        'data_inici',
        'data_fi',
        'aprobat_per',
        'estat',
    ];

    /**
     * Càstings de tipus de dades per facilitar el treball amb dates.
     */
    protected function casts(): array
    {
        return [
            'data_inici' => 'date',
            'data_fi' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relació amb l'Usuari.
     * Cada absència pertany a un usuari a través de la clau 'user_id'.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
