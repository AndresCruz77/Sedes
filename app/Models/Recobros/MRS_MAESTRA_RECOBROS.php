<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class MRS_MAESTRA_RECOBROS extends Model
{
    protected $table = 'RECOBROS.MRS_MAESTRA_RECOBROS';

    protected $fillable = 
    [
        'MRS_ID',
        'MRS_RADICADO_CMD',
        'MRS_DETALLE_CMD',
        'MRS_RADICADO_OPERADOR',
        'MRS_NRO_FACTURA_UNIFICADO',
        'MRS_NIT_IPS',
        'MRS_PREFIJO_FACTURA',
        'MRS_NRO_FACTURA',
        'MRS_VALOR_SUSCEPTIBLE',
        'MRS_ESTADO_ID',
        'MRS_OBSERVACION_INCONSISTENCIA',
        'MRS_FECHA_CREACION',
        'MRS_FECHA_MODIFICACION',
        'MRS_USER_CREA',
        'MRS_USER_MODIFICA',
    ];

    protected $primaryKey = 'MRS_ID';

    const CREATED_AT = 'MRS_FECHA_CREACION';
    const UPDATED_AT = 'MRS_FECHA_MODIFICACION';

    

    
}
