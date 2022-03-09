<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;

class DEP_DANE_DEPARTAMENTO extends Model
{
    protected $table = 'CATALOGOS.DEP_DANE_DEPARTAMENTO';
    
    protected $fillable = 
    [
      'DEP_ID',
      'DEP_FECHA_REGISTRO',
      'DEP_CODIGO',
      'DEP_NOMBRE',
    ];

    protected $primaryKey = 'DEP_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
