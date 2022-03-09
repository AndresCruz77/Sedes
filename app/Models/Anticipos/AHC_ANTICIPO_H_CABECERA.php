<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class AHC_ANTICIPO_H_CABECERA extends Model
{
    protected $table = 'ANTICIPOS.AHC_ANTICIPO_H_CABECERA';
    
    protected $fillable = 
    [   
        'AHC_ID',      
        'AHC_ANTICIPO_ESTADO_CODIGO',
        'AHC_OBSERV_ESTADO_ANTICIPO_CODIGO',
        'AHC_RESP_SOLIC_CODIGO',
        'AHC_IPS',
        'AHC_REGIONAL_CODIGO',
        'AHC_SECCIONAL_CODIGO',
        'AHC_MUNICIPIO_CODIGO',
        'AHC_REGIMEN_ANTICIPO_CODIGO',
        'AHC_ORIGEN_ANTICIPO_CODIGO',
        'AHC_TIPO_ORDEN_JUDIC_CODIGO',
        'AHC_MOTIVO_ANTICIPO_CODIGO',
        'AHC_VALOR_CRUCE',
        'AHC_VALOR_BRUTO_PAG',
        'AHC_VALOR_TOTAL_COTIZ',
        'AHC_SALDO_LEGALIZAR_PREST',
        'AHC_NUMERO_MIPRES',
        'AHC_DIAGNOSTICO_PRINCI_CODIGO',
        'AHC_DIAGNOSTICO_SECUN_CODIGO',
        'AHC_NUMERO_AUTORIZA',
        'AHC_OBSERV_GENER',
        'AHC_TIPO_IDENT_AFI',
        'AHC_NUM_IDENT_AFI',
        'AHC_PRIM_NOMB_AFI',
        'AHC_SEG_NOMB_AFI',
        'AHC_PRIM_APELL_AFI',
        'AHC_SEG_APELL_AFI',
        'AHC_ESTADO',
        'AHC_FECHA_CREACION',
        'AHC_FECHA_MODIFICACION',
        'AHC_USER_CREA',
        'AHC_USER_MODIFICA',        
    ];

    protected $primaryKey = 'AHC_ID';
    
 
    const CREATED_AT = 'AHC_FECHA_CREACION';
    const UPDATED_AT = 'AHC_FECHA_MODIFICACION';     
}
