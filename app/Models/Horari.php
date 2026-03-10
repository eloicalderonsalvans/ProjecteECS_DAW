<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horari extends Model
{
    use HasFactory;
    
    protected $table = 'horari';

    /**
     * Atributs assignables segons la teva captura de phpMyAdmin.
     */
    protected $fillable = [
        'user_id',
        'torn_id',
        'hora_entrada',
        'hora_sortida',
        'dia_setmana',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relació amb l'Usuari.
     * Cada registre d'horari pertany a un usuari (user_id).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relació amb el Torn.
     * Cada registre d'horari té assignat un torn (torn_id).
     */
    public function torn(): BelongsTo
    {
        return $this->belongsTo(Torn::class, 'torn_id');
    }
}
