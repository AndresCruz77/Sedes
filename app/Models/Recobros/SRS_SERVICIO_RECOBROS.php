<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class SRS_SERVICIO_RECOBROS extends Model
{
    protected $table = 'RECOBROS.SRS_SERVICIO_RECOBROS';

    protected $fillable = 
    [
        'SRS_ID',
        'SRS_MAESTRA_RECOBROS_ID',
        'SRS_CODIGO_TECNOLOGIA',
        'SRS_NOMBRE_TECNOLOGIA',
        'SRS_FECHA_PRESTACION',
        'SRS_NRO_AUTORIZACION',
        'SRS_CANTIDAD',
        'SRS_VALOR_UNITARIO',
        'SRS_VALOR_TOTAL',
        'SRS_CUOTA_MODERADORA_COPAGO',
        'SRS_TIPO_ID_USUARIO',
        'SRS_NRO_ID_USUARIO',
        'SRS_ESTADO_ID',
        'SRS_FECHA_CREACION',
        'SRS_FECHA_MODIFICACION',
        'SRS_USER_CREA',
        'SRS_USER_MODIFICA',
    ];

    protected $primaryKey = 'SRS_ID';

    const CREATED_AT = 'SRS_FECHA_CREACION';
    const UPDATED_AT = 'SRS_FECHA_MODIFICACION';
}