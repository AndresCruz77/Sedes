<?php

namespace App\Models\anticipos;

use Illuminate\Database\Eloquent\Model;

class TXA_TIPO_X_ADJUNTO extends Model
{
    protected $table = 'ANTICIPOS.TXA_TIPO_X_ADJUNTO';
    
    protected $fillable = 
    [
        'TXA_ID',
        'TXA_DESCRIPCION',
        'TXA_ESTADO'   
    ];

    protected $primarykey = 'TXA_ID';

    /* const CREATED_AT = 'TCU_FECHA_CREACION';
    const UPDATED_AT = 'TCU_FECHA_MODIFICACION'; */
}
