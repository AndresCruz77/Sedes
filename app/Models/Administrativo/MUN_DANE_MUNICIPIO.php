<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;

class MUN_DANE_MUNICIPIO extends Model
{
    protected $table = 'CATALOGOS.MUN_DANE_MUNICIPIO';
    
    protected $fillable = 
    [
      'MUN_ID',
      'MUN_FECHA_REGISTRO',
      'MUN_CODIGO',
      'MUN_DEP_CODIGO',
      'MUN_NOMBRE',
      'MUN_NOMBRE_SIN_CARACTERES_ESPECIALES',

    ];

    protected $primaryKey = 'MUN_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
