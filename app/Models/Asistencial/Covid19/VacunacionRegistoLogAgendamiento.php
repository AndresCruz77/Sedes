<?php

namespace App\Models\Asistencial\covid19;

use Illuminate\Database\Eloquent\Model;

class VacunacionRegistoLogAgendamiento extends Model
{
    protected $table = 'MiVacuna.rla_registrar_log_agendamiento';

    protected $fillable = [    
        'RLA_RAG_ID',	
        'RLA_RCP_ID',
        'RLA_REQUEST',	
        'RLA_ID_USER',	
        'RLA_CREATED_AT',
    ];

    public $timestamps = false;

}
