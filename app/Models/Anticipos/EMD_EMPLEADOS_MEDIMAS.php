<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class EMD_EMPLEADOS_MEDIMAS extends Model
{
    protected $table = 'COVIT.EMD_EMPLEADOS_MEDIMAS';
    
    protected $fillable = 
    [
        'EMD_ID',
        'EMD_TIPO_DOC',
        'EMD_NUMERO_DOCUMENTO',
        'EMD_NOMBRES',
        'EMD_APELLIDOS',
        'EMD_CARGO',
        'EMD_GERENCIA',
        'EMD_REGIONAL',
        'EMD_SEDE',
        'EMD_ZONA',
        'EMD_ESTADO_CONTRATO',
        'EMD_VICE_GERENCIA',
        'EMD_MUNICIPIO_SEDE',
    ];

    protected $primaryKey = 'EMD_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
