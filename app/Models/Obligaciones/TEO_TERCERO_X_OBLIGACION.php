<?php

namespace App\Models\Obligaciones;

use Illuminate\Database\Eloquent\Model;

class TEO_TERCERO_X_OBLIGACION extends Model
{
    //public $timestamps = false; quitar create y update
    protected $table = 'Sedes.TEO_TERCERO_X_OBLIGACION';
    
    protected $fillable = 
    [
      'TEO_ID',
      'TEO_OSP_ID',
      'TEO_TER_ID',
      'TEO_USU_CREA',
      'TEO_USU_MODIFICA',     
    ];

    protected $primaryKey = 'TEO_ID';
    
 
    const CREATED_AT = 'TEO_FECHA_CREACION';
    const UPDATED_AT = 'TEO_FECHA_MODIFICACION';     
}
