<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class REG_REGIMEN extends Model
{
    protected $table = 'Catalogos.REG_REGIMEN';
    
    protected $fillable = 
    [
        'REG_ID',
        'REG_CODIGO',        
        'REG_NOMBRE',        
    ];

    protected $primaryKey = 'REG_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
