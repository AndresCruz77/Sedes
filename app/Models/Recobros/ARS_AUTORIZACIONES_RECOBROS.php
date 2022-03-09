<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class ARS_AUTORIZACIONES_RECOBROS extends Model
{
    protected $table = 'RECOBROS.ARS_AUTORIZACIONES_RECOBROS';
    
    protected $fillable = 
    [
        'ARS_ID',
        'ARS_MAESTRA_RECOBROS_ID',
        'ARS_NRO_AUTORIZACION',
        'ARS_NUMERO_ENTREGA',
        'ARS_ID_DIRECCIONAMIENTO',
        'ARS_POSTFECHADA',
        'ARS_FECHA_AUTORIZACION',
        'ARS_NOMBRE_IPS_REMITE',
        'ARS_NIT_IPS_REMITE',
        'ARS_CANTIDAD',
        'ARS_CODIGO_TECONOLOGIA',
        'ARS_NUM_DOC_AFILIADO',
        'ARS_TIPO_DOC_AFILIADO',
        'ARS_NUMERO_PRESCRIPCION',
        'ARS_FECHA_POSIBLE_ENTREGA',
        'ARS_ESTADO_ID',
        'ARS_FECHA_CREACION',
        'ARS_FECHA_MODIFICACION',
        'ARS_USER_CREA',
        'ARS_USER_MODIFICA',
    ];

    protected $primaryKey = 'ARS_ID';
    

    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';    
}
