<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class AMH_ANTICIPO_M_HISTORICO extends Model
{
    protected $table = 'ANTICIPOS.AMH_ANTICIPO_M_HISTORICO';
    
    protected $fillable = 
    [   

        'AMH_ID',
        'AMH_CONSECUTIVO_ANTICIPO',
        'AMH_ANTICIPO_ESTADO_CODIGO',
        'AMH_OBSERV_ESTADO_ANTICIPO_CODIGO',
        'AMH_RESP_SOLIC_CODIGO',
        'AMH_IPS',
        'AMH_REGIONAL_CODIGO',
        'AMH_SECCIONAL_CODIGO',
        'AMH_MUNICIPIO_CODIGO',
        'AMH_REGIMEN_ANTICIPO_CODIGO',
        'AMH_ORIGEN_ANTICIPO_CODIGO',
        'AMH_TIPO_ORDEN_JUDIC_CODIGO',
        'AMH_MOTIVO_ANTICIPO_CODIGO',
        'AMH_VALOR_CRUCE',
        'AMH_VALOR_BRUTO_PAG',
        'AMH_VALOR_TOTAL_COTIZ',
        'AMH_SALDO_LEGALIZAR_PREST',
        'AMH_NUMERO_MIPRES',
        'AMH_DIAGNOSTICO_PRINCI_CODIGO',
        'AMH_DIAGNOSTICO_SECUN_CODIGO',
        'AMH_NUMERO_AUTORIZA',
        'AMH_OBSERV_GENER',
        'AMH_TIPO_IDENT_AFI',
        'AMH_NUM_IDENT_AFI',
        'AMH_PRIM_NOMB_AFI',
        'AMH_SEG_NOMB_AFI',
        'AMH_PRIM_APELL_AFI',
        'AMH_SEG_APELL_AFI',
        'AMH_ESTADO',
        'AMH_FECHA_CREACION',
        'AMH_USER_CREA',
        'AMH_ACCION',
          
    ];

    protected $primaryKey = 'AMH_ID';
    
 
    //const CREATED_AT = 'AHC_FECHA_CREACION';
    //const UPDATED_AT = 'AHC_FECHA_MODIFICACION';     
}
