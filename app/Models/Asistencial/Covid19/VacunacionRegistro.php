<?php

namespace App\Models\Asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class VacunacionRegistro extends Model
{
    protected $table = 'MiVacuna.rag_registrar_agendamiento';

    protected $fillable = [
        'RAG_ID',
        'RAG_TIPO_DOCUMENTO_PACIENTE',
        'RAG_NUMERO_DOCUMENTO_PACIENTE',
        'RAG_PRIMER_NOMBRE',
        'RAG_SEGUNDO_NOMBRE',
        'RAG_PRIMER_APELLIDO',
        'RAG_SEGUNDO_APELLIDO',
        'RAG_CODIGO_PRESTADOR',
        'RAG_FECHA_AGENDAMIENTO',
        'RAG_HORA_AGENDAMIENTO',
        'RAG_NUMERO_DOSIS',
        'RAG_CAUSA_NO_AGENDAMIENTO',
        'RAG_OPERACION_AGENDAMIENTO',
        'RAG_CARGUE_ID',
        'RAG_FILA_CARGUE',
        'RAG_RADICADO_ID',
        'RAG_ANULADO_ID',
        'RAG_TRA_ID',   
        'RAG_RADICADO_MENSAJE',
        'RAG_ANULADO_MENSAJE', 
        'RAG_FECHA_RADICADO',
        'RAG_FECHA_ANULADO',
        'RAG_FECHA_REGISTRO'
    ];

    public $timestamps = false;

    protected $primaryKey = 'RAG_ID';

    protected $casts = [
        'RAG_HORA_AGENDAMIENTO' => 'time(0)'
    ];
}
