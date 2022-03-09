<?php

namespace App\Models\asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class SESE_SEG_ESTADO_SEGUIMIENTO extends Model
{
    protected $table = 'MiVacuna.SESE_SEG_ESTADO_SEGUIMIENTO';

    protected $primaryKey = 'SESE_ID';

    protected $fillable = [
        'SESE_ID',
        'SESE_NOMBRE'        
    ];
    
}
