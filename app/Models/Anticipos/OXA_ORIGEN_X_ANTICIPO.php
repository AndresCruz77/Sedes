<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class OXA_ORIGEN_X_ANTICIPO extends Model
{
    protected $table = 'ANTICIPOS.OXA_ORIGEN_X_ANTICIPO';
    
    protected $fillable = 
    [
        'OXA_ID',
        'OXA_DESCRIPCION',
        'OXA_ESTADO',        
    ];

    protected $primaryKey = 'OXA_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
