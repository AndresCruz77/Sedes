<?php

namespace App\Models\contraloria;

use Illuminate\Database\Eloquent\Model;

class FRS_FACTURA_CONTRALORIA extends Model
{
    protected $table = 'CONTRALORIA.FRS_FACTURA_CONTRALORIA';

    protected $fillable = 
    [
        'FRS_ID',
        'FRS_MAESTRA_CONTRALORIA_ID',
        'FRS_NRO_FACTURA',
        'FRS_FECHA_FACTURA',
        'FRS_NIT_IPS',
        'FRS_NOMBRE_IPS',
        'FRS_ESTADO_ID',
        'FRS_FECHA_CREACION',
        'FRS_FECHA_MODIFICACION',
        'FRS_USER_CREA',
        'FRS_USER_MODIFICA',
    ];

    protected $primaryKey = 'FRS_ID';

    const CREATED_AT = 'FRS_FECHA_CREACION';
    const UPDATED_AT = 'FRS_FECHA_MODIFICACION';
}
