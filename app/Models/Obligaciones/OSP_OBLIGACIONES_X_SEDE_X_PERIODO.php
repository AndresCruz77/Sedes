<?php

namespace App\Models\Obligaciones;

use Illuminate\Database\Eloquent\Model;

class OSP_OBLIGACIONES_X_SEDE_X_PERIODO extends Model
{
    public $timestamps = false; //quitar create y update
    protected $table = 'Sedes.OSP_OBLIGACIONES_X_SEDE_X_PERIODO';
    
    protected $fillable = 
    [
      'OSP_ID',
      'OSP_OBL_ID',
      'OSP_COS_ID',
      'OSP_PER_ID',
      'OSP_VALOR_INT',
      'OSP_VALOR_FLOAT',
      'OSP_VALOR_TEXTO',
      'OSP_FECHA_PAGO',
      'OSP_REFERENCIA',
      'OSP_USER_CREA',      
      'OSP_USER_MODIFICA',      
    ];

    protected $primaryKey = 'OSP_ID';
    
 
   // const CREATED_AT = 'OSP_FECHA_CREA';
   // const UPDATED_AT = 'OSP_FECHA_MODIFICA';     
}
