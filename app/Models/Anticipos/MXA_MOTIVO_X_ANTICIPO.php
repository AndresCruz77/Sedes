<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class MXA_MOTIVO_X_ANTICIPO extends Model
{
    protected $table = 'ANTICIPOS.MXA_MOTIVO_X_ANTICIPO';
    
    protected $fillable = 
    [
        'MXA_ID',
        'MXA_DESCRIPCION',
        'MXA_ESTADO',        
    ];

    protected $primaryKey = 'MXA_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
