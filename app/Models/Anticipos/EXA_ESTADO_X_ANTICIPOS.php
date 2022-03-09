<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class EXA_ESTADO_X_ANTICIPOS extends Model
{
    protected $table = 'ANTICIPOS.EXA_ESTADO_X_ANTICIPOS';
    
    protected $fillable = 
    [
        'EXA_ID',
        'EXA_DESCRIPCION',
        'EXA_ESTADO',        
    ];

    protected $primaryKey = 'EXA_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
