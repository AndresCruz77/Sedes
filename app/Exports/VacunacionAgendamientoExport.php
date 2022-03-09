<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Asistencial\covid19\vacunacionCargueErrores;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VacunacionAgendamientoExport implements FromCollection, WithHeadings
{
    public function __construct($idCargue = null) {
        $this->idCargue = $idCargue;
    }

    public function collection()
    {
        return vacunacionCargueErrores::
        select('TRAG_FILA_CARGUE','TERA_ERROR','TERA_VALOR_CORRECTO','RCP_NOMBRE_ARCHIVO')
        ->where('TRAG_CARGUE_ID',$this->idCargue)
        ->join('MiVacuna.rcp_registrar_cargue_plano as b','tera_tmp_error_registrar_agendamiento.TRAG_CARGUE_ID','=','b.RCP_ID')
        ->get();
    }

    public function headings(): array
    {
        return [
            'FILA_EXCEL',
            'ERROR_CAMPO',	
            'DESCRIPCION_ERROR',
            'NOMBRE_ARCHIVO'
        ];
    }

}