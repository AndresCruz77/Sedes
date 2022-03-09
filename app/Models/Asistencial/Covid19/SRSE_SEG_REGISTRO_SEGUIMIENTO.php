<?php

namespace App\Models\asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class SRSE_SEG_REGISTRO_SEGUIMIENTO extends Model
{
    protected $table = 'MiVacuna.SRSE_SEG_REGISTRO_SEGUIMIENTO';

    protected $primaryKey = 'SRSE_ID';

    protected $fillable = [
        'SRSE_ID',	
        'SRSE_AFILIADO_REGISTRO_ID',	
        'SRSE_TIPO_CONTACTO_ID',	
        'SRSE_TIPO_GESTION_ID',	
        'SRSE_ESTADO_SEGUIMIENTO_ID',	
        'SRSE_OBSERVACION',	
        'SRSE_FECHA_CREACION',	
        'SRSE_FECHA_ACTUALIZACION',	
        'SRSE_USUARIO_ID',        
    ];

    const CREATED_AT = 'SRSE_FECHA_CREACION';
    const UPDATED_AT = 'SRSE_FECHA_ACTUALIZACION';    
}

