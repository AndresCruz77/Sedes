<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;

class COS_CODIGO_SEDES extends Model
{
    //public $timestamps = false; quitar create y update
    protected $table = 'Sedes.COS_CODIGO_SEDES';
    
    protected $fillable = 
    [
      'COS_ID',
      'COS_CODIGO',
      'COS_DIRECCION_SEDE',
      'COS_NOMBRE_SEDE',
      'COS_METROS_CUADRADO',
      'COS_TIS_ID',
      'COS_RNL_ID',
      'COS_ESS_ID',
      'COS_MUN_ID',
      'COS_DEP_ID',
      'COS_SEC_ID',
      'COS_TIP_ID',
      'COS_REG_ID',
      'COS_OBSERVACION',
      'COS_USUARIO_CREA',
      'COS_USUARIO_MODIFICA',
    ];

    protected $primaryKey = 'COS_ID';
    
 
    const CREATED_AT = 'COS_FECHA_CREA';
    const UPDATED_AT = 'COS_FECHA_MODIFICA';     
}
