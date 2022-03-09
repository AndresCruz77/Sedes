<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class AXD_ANTICIPO_X_DETALLE extends Model
{
    protected $table = 'ANTICIPOS.AXD_ANTICIPO_X_DETALLE';
    
    protected $fillable = 
    [   
        'AXD_ID',
        'AXD_AHC_ID',
        'AXD_TIPO_DOCUMENTO',
        'AXD_NUMERO_DOCUMENTO',
        'AXD_PRIMER_NOMBRE',
        'AXD_SEGUNDO_NOMBRE',
        'AXD_PRIMER_APELLIDO',
        'AXD_SEGUNDO_APELLIDO',
        'AXD_CODIGO_CIE10',
        'AXD_COBERTURA',
        'AXD_CODIGO_CUPS_CUMS',
        'AXD_PRODUCTO',
        'AXD_CANTIDAD_AUTORIZADA',
        'AXD_NUMERO_AUTORIZACION',
        'AXD_VALOR_SERVICIO',
        'AXD_ESTADO',
        'AXD_FECHA_CREACION',
        'AXD_FECHA_MODIFICACION',
        'AXD_USER_CREA',
        'AXD_USER_MODIFICA',              
    ];

    protected $primaryKey = 'AXD_ID';
    
 
    const CREATED_AT = 'AXD_FECHA_CREACION';
    const UPDATED_AT = 'AXD_FECHA_MODIFICACION';     
}
