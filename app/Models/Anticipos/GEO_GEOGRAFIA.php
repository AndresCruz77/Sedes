<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class GEO_GEOGRAFIA extends Model
{
    protected $table = 'CATALOGOS.GEO_GEOGRAFIA';
    
    protected $fillable = 
    [        
      'GEO_ID',
      'GEO_CODIGO_DANE',
      'GEO_DEPARTAMENTO',
      'GEO_MUNICIPIO',
      'GEO_REGIONAL',
      'GEO_ZONAL',
      'GEO_SECCIONAL',
      'GEO_REGIONAL_ANT',
      'GEO_ID_SUCURSAL_SEVEN',
      'GEO_NOMBRE_SUCURSAL_SEVEN',

    ];

    protected $primaryKey = 'GEO_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
