<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class ASIGNACION_RECOBROS_USUARIO extends Model
{
    protected $table = 'RECOBROS.ASIGNACION_RECOBROS_USUARIO';
    
    protected $fillable = 
    [

        'ARU_ID',
        'ARU_MAESTRA_RECOBROS_ID',
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
