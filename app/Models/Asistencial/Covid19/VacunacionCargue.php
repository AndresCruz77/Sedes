<?php

namespace App\Models\Asistencial\Covid19;

use Illuminate\Database\Eloquent\Model;

class VacunacionCargue extends Model
{
    protected $table = 'MiVacuna.trag_tmp_registrar_agendamiento';

    protected $fillable = [
        'TRAG_ID',
        'TRAG_TIPO_DOCUMENTO_PACIENTE',
        'TRAG_NUMERO_DOCUMENTO_PACIENTE',
        'TRAG_PRIMER_NOMBRE',
        'TRAG_SEGUNDO_NOMBRE',
        'TRAG_PRIMER_APELLIDO',
        'TRAG_SEGUNDO_APELLIDO',
        'TRAG_CODIGO_PRESTADOR',
        'TRAG_FECHA_AGENDAMIENTO',
        'TRAG_HORA_AGENDAMIENTO',
        'TRAG_NUMERO_DOSIS',
        'TRAG_CAUSA_NO_AGENDAMIENTO',
        'TRAG_OPERACION_AGENDAMIENTO',   
        'TRAG_CARGUE_ID',
        'TRAG_FILA_CARGUE',
        'TRAG_FECHA_REGISTRO'
    ];

    protected $guarded = [];

    public $timestamps = false;

}

