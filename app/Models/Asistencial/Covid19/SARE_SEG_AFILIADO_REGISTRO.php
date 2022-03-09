<?php

namespace App\Models\asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class SARE_SEG_AFILIADO_REGISTRO extends Model
{
    protected $table = 'Mivacuna.SARE_SEG_AFILIADO_REGISTRO';

    protected $primaryKey = 'SARE_ID';

    protected $fillable = [
        'SARE_ID',	
        'SARE_NUMERO_DOCUMENTO',	
        'SARE_ESTADO_SEGUIMIENTO_ID',	
        'SARE_FECHA_ACTUALIZACION',	
        'SARE_FECHA_CREACION',        
    ];

    const CREATED_AT = 'SARE_FECHA_CREACION';
    const UPDATED_AT = 'SARE_FECHA_ACTUALIZACION';
}
