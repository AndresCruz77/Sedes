<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;


use App\Models\Administrativo\TDO_TIPO_DOCUMENTO;

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
