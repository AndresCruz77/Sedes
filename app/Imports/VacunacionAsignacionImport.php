<?php

namespace App\Imports;

use App\Models\asistencial\covid19\vacunacionTempRegistroAsignacion;
use App\Models\Asistencial\covid19\VacunacionCargueErrores;
use Maatwebsite\Excel\Concerns\{ToModel, WithBatchInserts, WithHeadingRow, RemembersRowNumber};

class VacunacionAsignacionImport implements ToModel ,WithBatchInserts, WithHeadingRow
{

    use RemembersRowNumber;

    function __construct($idCargue){
        $this->idCargue = $idCargue;
    }

    public function model(array $row)
    {
        $currentRowNumber = $this->getRowNumber();

            return new vacunacionTempRegistroAsignacion([
                'TRAA_TIPO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row['tipo_documento_paciente'] ?? null)),
                'TRAA_NUMERO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row['numero_documento_paciente'] ?? null)),
                'TRAA_CODIGO_PRESTADOR' => intval($row['codigo_prestador'] ?? null),
                'TRAA_CARGUE_ID' => $this->idCargue,
                'TRAA_FILA_CARGUE' => $currentRowNumber,
                'TRAA_FECHA_REGISTRO' => date('Y-m-d H:i:s') ,
                'TRAA_OPERACION_ASIGNACION' => intval($row['operacion_asignacion'] ?? null)       
            ]);
                    
    }

    public function  batchSize(): int{
        return 250;
    }  

}
