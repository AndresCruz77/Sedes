<?php

namespace App\Models\Administrador;

use Illuminate\Database\Eloquent\Model;

class LMP_LOGIN_MENU_PADRE extends Model
{
    protected $table = 'SEGURIDAD.LMP_LOGIN_MENU_PADRE';

    protected $fillable = [
        'LMP_ID',	
        'LMP_NOMBRE',	
        'LMP_ESTADO',	
        'LMP_FECHA_CREACION',	
        'LMP_FECHA_ACTUALIZACION',	
        'LMP_ICONO',	
        'LMP_DESCRIPCION',
    ];

    protected $primaryKey = 'LMP_ID';

    const UPDATED_AT = 'LMP_FECHA_ACTUALIZACION';
    const CREATED_AT = 'LMP_FECHA_CREACION';
}
