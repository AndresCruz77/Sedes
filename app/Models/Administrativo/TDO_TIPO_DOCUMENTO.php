<?php

namespace App\Models\Administrativo;

use Illuminate\Database\Eloquent\Model;

class TDO_TIPO_DOCUMENTO extends Model
{
    protected $table = 'Catalogos.TDO_TIPO_DOCUMENTO';
    
    protected $fillable = [
    
        'TDO_ID',
        'TDO_DESCRIPCION',
        'TDO_HOMOLOGACION1',
        'TDO_HOMOLOGACION2',

    ];

    protected $primaryKey = 'TDO_ID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
