<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;

class RNL_REGIONAL extends Model
{
    protected $table = 'CATALOGOS.RNL_REGIONAL';
    
    protected $fillable = 
    [
      'RNL_ID',
      'RNL_CODIGO',
      'RNL_NOMBRE',
      'RNL_ESTADO',
    ];

    protected $primaryKey = 'RNL_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
