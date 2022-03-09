<?php

namespace App\models\asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class vacunacionTempRegistroAsignacion extends Model
{
    protected $table = 'MiVacuna.traa_tmp_registrar_asignacion';

    protected $fillable = [
        'TRAA_ID',
        'TRAA_TIPO_DOCUMENTO_PACIENTE',
        'TRAA_NUMERO_DOCUMENTO_PACIENTE',
        'TRAA_CODIGO_PRESTADOR',
        'TRAA_CARGUE_ID',
        'TRAA_FILA_CARGUE',
        'TRAA_FECHA_REGISTRO',
        'TRAA_OPERACION_ASIGNACION'
    ];

    protected $primaryKey = 'TRAA_ID';

    public $timestamps = false;

    protected $casts = [
        'TRAA_FECHA_REGISTRO' => 'date:Y-m-d',
    ];     
}
