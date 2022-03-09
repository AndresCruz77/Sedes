<?php

namespace App\Models\contraloria;

use Illuminate\Database\Eloquent\Model;

class IRS_INCONSISTENCIA_CONTRALORIA extends Model
{
    protected $table = 'CONTRALORIA.IRS_INCONSISTENCIA_CONTRALORIA';
    
    protected $fillable = 
    [
        'IRS_ID',
        'IRS_MAESTRA_CONTRALORIA_ID',
        'IRS_TIPO_DOCUMENTAL_ID',        
        'IRS_ESTADO_ID',
        'IRS_FECHA_CREACION',
        'IRS_FECHA_MODIFICACION',
        'IRS_USER_CREA',
        'IRS_USER_MODIFICA',
    ];

    protected $primaryKey = 'IRS_ID';

    const CREATED_AT = 'IRS_FECHA_CREACION';
    const UPDATED_AT = 'IRS_FECHA_MODIFICACION';
}
