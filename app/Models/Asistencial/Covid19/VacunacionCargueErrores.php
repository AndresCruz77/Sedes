<?php

namespace App\Models\Asistencial\Covid19;

use Illuminate\Database\Eloquent\Model;

class VacunacionCargueErrores extends Model
{
    protected $table = 'MiVacuna.tera_tmp_error_registrar_agendamiento';

    protected $fillable = [
        'TRAG_ID',	
        'TRAG_FILA_CARGUE',	
        'TRAG_CARGUE_ID',	
        'TERA_ERROR',	
        'TERA_VALOR_CORRECTO',
    ];

    protected $guarded = [];

    public $timestamps = false;
}
