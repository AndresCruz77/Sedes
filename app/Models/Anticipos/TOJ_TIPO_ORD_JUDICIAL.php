<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class TOJ_TIPO_ORD_JUDICIAL extends Model
{
    protected $table = 'ANTICIPOS.TOJ_TIPO_ORD_JUDICIAL';
    
    protected $fillable = 
    [
        'TOJ_ID',
        'TOJ_DESCRIPCION',
        'TOJ_ESTADO',        
    ];

    protected $primaryKey = 'TOJ_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
