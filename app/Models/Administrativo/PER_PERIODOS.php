<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;

class PER_PERIODOS extends Model
{
    //public $timestamps = false; quitar create y update
    protected $table = 'Sedes.PER_PERIODOS';
    
    protected $fillable = 
    [
      'PER_ID',
      'PER_CODIGO',
      'PER_FECHA_INICIAL',
      'PER_FECHA_FINAL',
      'PER_DESCRIPCION',
      'PER_CERRADO',
      'PER_USUARIO_CREA',      
      'PER_USUARIO_MODIFICA',      
    ];

    protected $primaryKey = 'PER_ID';
    
 
    const CREATED_AT = 'PER_FECHA_CREA';
    const UPDATED_AT = 'PER_FECHA_MODIFICA';     
}
