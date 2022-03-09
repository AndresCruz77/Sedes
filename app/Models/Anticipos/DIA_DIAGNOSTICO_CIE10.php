<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class DIA_DIAGNOSTICO_CIE10 extends Model
{
    protected $table = 'CATALOGOS.DIA_DIAGNOSTICO_CIE10';
    
    protected $fillable = 
    [        
      'DIA_ID',
      'DIA_CDG_CODIGO_CAPITULO',
      'DIA_DC3_CODIGO_CIE10_3C',
      'DIA_CODIGO_CIE10',
      'DIA_DESCRIPCION',

    ];

    protected $primaryKey = 'DIA_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
