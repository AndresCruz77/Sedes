<?php

namespace App\Models\contraloria;

use Illuminate\Database\Eloquent\Model;

class TDR_TIPO_DOCUMENTAL extends Model
{
    protected $table = 'CONTRALORIA.TDR_TIPO_DOCUMENTAL';
    
    protected $fillable = 
    [
        'TDR_ID',
        'TDR_MAESTRA_CONTRALORIA_ID',
        'TDR_TIPO_DOC',
        'TDR_NOMBRE_ARCHIVO',
        'TDR_RUTA_COMPLETA',
        'TDR_REQUERIDO',
        'TDR_ESTADO_ID',
        'TDR_FECHA_CREACION',
        'TDR_FECHA_MODIFICACION',
        'TDR_USER_CREA',
        'TDR_USER_MODIFICA',
    ];

    protected $primarykey = 'TDR_ID';

    const CREATED_AT = 'TDR_FECHA_CREACION';
    const UPDATED_AT = 'TDR_FECHA_MODIFICACION';
}
