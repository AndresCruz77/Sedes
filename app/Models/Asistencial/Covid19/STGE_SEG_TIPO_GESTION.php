<?php

namespace App\Models\asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class STGE_SEG_TIPO_GESTION extends Model
{
    protected $table = 'MiVacuna.STGE_SEG_TIPO_GESTION';

    protected $primaryKey = 'STGE_ID';

    protected $fillable = [
        'STGE_ID',
        'STGE_NOMBRE'
    ];
}
