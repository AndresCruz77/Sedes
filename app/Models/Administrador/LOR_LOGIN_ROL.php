<?php

namespace App\Models\Administrador;

use Illuminate\Database\Eloquent\Model;

class LOR_LOGIN_ROL extends Model
{
    protected $table = 'SEGURIDAD.LOR_LOGIN_ROL';

    protected $fillable = 
    [
        'LOR_ID',	
        'LOR_NOMBRE',	
        'LOR_ESTADO',	
        'LOR_FECHA_CREACION',	
        'LOR_FECHA_ACTUALIZACION',
    ];

    protected $primaryKey = 'LOR_ID';
}
