<?php

namespace App\Models\asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class iha_ips_habilitacion extends Model
{
    protected $table = 'MiVacuna.IHA_IPS_HABILITACION_ATENCION';

    protected $fillable = [
        'IHA_ID',
        'IHA_REGIMEN',
        'IHA_CODIGO_DANE',
        'IHA_REGION',
        'IHA_REGIONAL',
        'IHA_DEPARTAMENTO',
        'IHA_MUNICIPIO',
        'IHA_NIT',
        'IHA_CODIGO_HABILITACION',
        'IHA_NOMBRE_PRESTADOR',
    ];
}
