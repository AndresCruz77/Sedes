<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class AXA_ADJUNTO_X_ANTICIPO extends Model
{
    protected $table = 'ANTICIPOS.AXA_ADJUNTO_X_ANTICIPO';
    
    protected $fillable = 
    [   
        'AHC_ID',
        'AXA_AHC_ID',
        'AXA_ID_TXA_ID',
        'AXA_NOMBRE',
        'AXA_RUTA_FILE',
        'AXA_MIME_TIPO',
        'AXA_ADJUNTO',
        'AXA_CONFORME',
        'AXA_DESCRIPCION',
        'AXA_ESTADO',
        'AXA_FECHA_CREACION',
        'AXA_USER_CREA',        
    ];

    protected $primaryKey = 'AXA_ID';
    
 
    const CREATED_AT = 'AXA_FECHA_CREACION';
    const UPDATED_AT = 'AXA_FECHA_MODIFICACION';     
}
