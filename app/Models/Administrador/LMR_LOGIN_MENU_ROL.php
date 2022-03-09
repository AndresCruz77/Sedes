<?php

namespace App\Models\Administrador;

use Illuminate\Database\Eloquent\Model;

class LMR_LOGIN_MENU_ROL extends Model
{
    protected $table = 'SEGURIDAD.LMR_LOGIN_MENU_ROL';

    protected $fillable = [
        'LMR_ID',	
        'LMR_LMH_ID',	
        'LMR_LOR_ID',	
        'LMR_ESTADO',	
        'LMR_FECHA_CREACION',	
        'LMR_FECHA_ACTUALIZACION',
    ];

    protected $primaryKey = 'LMR_ID';

    const UPDATED_AT = 'LMR_FECHA_CREACION';
    const CREATED_AT = 'LMR_FECHA_ACTUALIZACION';
}
