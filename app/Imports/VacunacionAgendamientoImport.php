<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Asistencial\covid19\vacunacioncargue;
use Maatwebsite\Excel\Concerns\{ToModel, WithBatchInserts, WithHeadingRow, RemembersRowNumber};

class VacunacionAgendamientoImport implements ToModel ,WithBatchInserts, WithHeadingRow{

    use RemembersRowNumber;
    use Importable;
    

    function __construct($idCargue){
        $this->idCargue = $idCargue;
    }

    public function model(array $row)
    {				

        $currentRowNumber = $this->getRowNumber();

        // if($currentRowNumber == 262){
            //dd($row);
        // }

        return new vacunacioncargue([
            'TRAG_TIPO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row['tipo_documento_paciente'] ?? null)),
            'TRAG_NUMERO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row['numero_documento_paciente']?? null)),
            'TRAG_PRIMER_NOMBRE' => rtrim($row['primer_nombre']?? null),
            'TRAG_SEGUNDO_NOMBRE' => rtrim($row['segundo_nombre']?? null),
            'TRAG_PRIMER_APELLIDO' => rtrim($row['primer_apellido']?? null),
            'TRAG_SEGUNDO_APELLIDO' => rtrim($row['segundo_apellido']?? null),
            'TRAG_CODIGO_PRESTADOR' => intval($row['codigo_prestador']?? null),
            'TRAG_FECHA_AGENDAMIENTO' => (isset($row['fecha_agendamiento']) ? (is_string($row['fecha_agendamiento'])) ? $row['fecha_agendamiento'] : Date::excelToDateTimeObject($row['fecha_agendamiento']) : "null" ),
            'TRAG_HORA_AGENDAMIENTO' => (isset($row['hora_agendamiento']) ? (is_string($row['hora_agendamiento'])) ? $row['hora_agendamiento'] : Date::excelToDateTimeObject($row['hora_agendamiento']) : "null"),
            'TRAG_NUMERO_DOSIS' => intval($row['numero_dosis'] ?? null),
            'TRAG_CAUSA_NO_AGENDAMIENTO' => intval($row['causa_no_agendamiento'] ?? null),
            'TRAG_OPERACION_AGENDAMIENTO' => intval($row['operacion_agendamiento'] ?? null),
            'TRAG_CARGUE_ID' => $this->idCargue,
            'TRAG_FILA_CARGUE' => $currentRowNumber,
            'TRAG_FECHA_REGISTRO' => date('Y-m-d H:i:s')
        ]);

    }

    public function  batchSize(): int{
        return 100;
    }

}
