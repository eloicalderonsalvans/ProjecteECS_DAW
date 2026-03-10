<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fixatge extends Model
{
    use HasFactory;
   
    protected $table = 'fixatges';

    /**
     * Atributs assignables segons la teva estructura de phpMyAdmin.
     *
     */
    protected $fillable = [
        'user_id',
        'data',
        'check',
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
            'data' => 'datetime', //
            'check' => 'boolean',  // El tinyint(1) el tractem com a booleà
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relació amb l'Usuari.
     * Cada fixatge pertany a un usuari a través de la clau silver 'user_id'.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
