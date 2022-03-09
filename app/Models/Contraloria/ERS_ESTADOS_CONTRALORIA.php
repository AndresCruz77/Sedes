<?php

namespace App\Models\contraloria;

use Illuminate\Database\Eloquent\Model;

class ERS_ESTADOS_CONTRALORIA extends Model
{
    protected $table = 'CONTRALORIA.ERS_ESTADOS_CONTRALORIA';

    protected $fillable = 
    [
        'ERS_ID',
        'ERS_NOMBRE',
        'ERS_ESTADO',
        'ERS_FECHA_CREACION',
        'ERS_FECHA_MODIFICACION',
        'ERS_USER_CREA',
        'ERS_USER_MODIFICA',
       
    ];

    protected $primaryKey = 'ERS_ID';

    const CREATED_AT = 'ERS_FECHA_CREACION';
    const UPDATED_AT = 'ERS_FECHA_MODIFICACION';
}
