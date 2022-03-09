<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class LOE_LISTA_OBSERV_ESTADO extends Model
{
    protected $table = 'ANTICIPOS.LOE_LISTA_OBSERV_ESTADO';
    
    protected $fillable = 
    [
        'LOE_ID',
        'LOE_EXA_ID',
        'LOE_DESCRIPCION',          
        'LOE_ESTADO',
        'LOE_FECHA_CREACION',
        'LOE_FECHA_MODIFICACION',
        'LOE_USER_CREA',
        'LOE_USER_MODIFICA',
    ];

    protected $primaryKey = 'LOE_ID';
    
 
    const CREATED_AT = 'LOE_FECHA_CREACION';
    const UPDATED_AT = 'LOE_FECHA_MODIFICACION';     
}
