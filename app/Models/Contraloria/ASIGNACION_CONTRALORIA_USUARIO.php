<?php

namespace App\Models\contraloria;

use Illuminate\Database\Eloquent\Model;

class ASIGNACION_CONTRALORIA_USUARIO extends Model
{
    protected $table = 'CONTRALORIA.ASIGNACION_CONTRALORIA_USUARIO';
    
    protected $fillable = 
    [

        'ARU_ID',
        'ARU_MAESTRA_CONTRALORIA_ID',
        'ARU_USUARIO_ASIGNADO',
        'ARU_ESTADO_ID',
        'ARU_FECHA_CREACION',
        'ARU_FECHA_MODIFICACION',
        'ARU_USER_CREA',
        'ARU_USER_MODIFICA',
    ];

    protected $primaryKey = 'ARU_ID';
    
    const CREATED_AT = 'ARU_FECHA_CREACION';
    const UPDATED_AT = 'ARU_FECHA_MODIFICACION';    
}
