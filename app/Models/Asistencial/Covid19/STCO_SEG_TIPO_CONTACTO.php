<?php

namespace App\Models\asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class STCO_SEG_TIPO_CONTACTO extends Model
{
    protected $table = 'MiVacuna.STCO_SEG_TIPO_CONTACTO';

    protected $primaryKey = 'STCO_ID';

    protected $fillable = [
        'STCO_ID',
        'STCO_NOMBRE'        
    ];
}
