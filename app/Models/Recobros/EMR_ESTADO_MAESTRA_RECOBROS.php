<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class EMR_ESTADO_MAESTRA_RECOBROS extends Model
{
    protected $table = 'RECOBROS.EMR_ESTADO_MAESTRA_RECOBROS';

    protected $fillable = 
    [   
        'EMR_ID',
        'EMR_MAESTRA_RECOBROS_ID',
        'EMR_ESTADO_ID',
        'EMR_FECHA_CREACION',
        'EMR_FECHA_MODIFICACION',
        'EMR_FECHA_VENCIMIENTO',
        'EMR_ESTADO_ID_ANTERIOR',
        'EMR_USER_CREAR',
        'EMR_USER_MODIFICA',       
       
    ];

    protected $primaryKey = 'EMR_ID';

    const CREATED_AT = 'EMR_FECHA_CREACION';
    const UPDATED_AT = 'EMR_FECHA_MODIFICACION';
}
