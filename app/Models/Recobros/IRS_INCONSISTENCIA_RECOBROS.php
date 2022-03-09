<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class IRS_INCONSISTENCIA_RECOBROS extends Model
{
    protected $table = 'RECOBROS.IRS_INCONSISTENCIA_RECOBROS';
    
    protected $fillable = 
    [
        'IRS_ID',
        'IRS_MAESTRA_RECOBROS_ID',
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
