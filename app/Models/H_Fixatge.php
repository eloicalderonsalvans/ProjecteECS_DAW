<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class H_Fixatge extends Model
{
    use HasFactory;

    protected $table = 'h_fitxatges';

    /**
     * Atributs assignables segons la captura de phpMyAdmin.
     */
    protected $fillable = [
        'user_id',
        'fitxatge_id',
        'data',
    ];

    /**
     * Càstings de tipus.
     */
    protected function casts(): array
    {
        return [
            'data' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relació amb l'usuari que va fer l'acció.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relació amb el registre original de la taula fixatges.
     */
    public function fixatge(): BelongsTo
    {
        return $this->belongsTo(Fixatge::class, 'fitxatge_id');
    }
}
