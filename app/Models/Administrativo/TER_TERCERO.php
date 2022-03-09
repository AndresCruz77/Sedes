<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;

class TER_TERCERO extends Model
{
    protected $table = 'Sedes.TER_TERCERO';
    
    protected $fillable = 
    [
      'TER_ID',
      'TER_TDO_ID',
      'TER_NUMERO_DOCUMENTO',
      'TER_NOMBRE',
      'TER_ACTIVO',
      'TER_USUARIO_CREA',      
      'TER_USUARIO_MODIFICA',      
    ];

    protected $primaryKey = 'TER_ID';
    
 
    const CREATED_AT = 'TER_FECHA_CREACION';
    const UPDATED_AT = 'TER_FECHA_MODIFICA';     
}
