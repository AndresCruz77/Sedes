<?php

namespace App\Models\contraloria;

use Illuminate\Database\Eloquent\Model;

class TCU_TECNOLOGIA_CUMS extends Model
{
    protected $table = 'CONTRALORIA.TCU_TECNOLOGIA_CUMS';
    
    protected $fillable = 
    [
        'TCU_ID',
        'TCU_TIPO',
        'TCU_CUMS_MINISTERIO',
        'TCU_EXPEDIENTE',
        'TCU_PRODUCTO',
        'TCU_TITULAR',
        'TCU_REG_SANITARIO',
        'TCU_FECHA_EXPEDICION',
        'TCU_FECHA_VENCIMIENTO',
        'TCU_ESTADO_MINISTERIO',
        'TCU_DESCRIPCION_COMERCIAL',
        'TCU_ESTADO',
        'TCU_FECHA_CREACION',
        'TCU_USUARIO_CREA',
        'TCU_FECHA_MODIFICACION',
        'TCU_USUARIO_MODIFICA',       
    ];

    protected $primarykey = 'TCU_ID';

    const CREATED_AT = 'TCU_FECHA_CREACION';
    const UPDATED_AT = 'TCU_FECHA_MODIFICACION';
}
