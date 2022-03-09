<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\Asistencial\covid19\vacunacioncargue;
use App\Models\Asistencial\covid19\VacunacionCargueErrores;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToCollection, WithBatchInserts, WithChunkReading};

class VacunacionAgendamientoImport implements ToCollection ,WithBatchInserts, WithChunkReading
{

    function __construct($idCargue){
        $this->idCargue = $idCargue;
    }

    public function collection(Collection $rows)
    {
        $validateHeading = $this->validateHeading($rows[0]);

        if($validateHeading){
            
            foreach($validateHeading as $value){

                VacunacionCargueErrores::create([
                    'TRAG_ID' => 0,
                    'TRAG_FILA_CARGUE' => 1,
                    'TRAG_CARGUE_ID' => $this->idCargue,
                    'TERA_ERROR' => 'EL NOMBRE DE LA COLUMNA '.$value.' NO ES VALIDO',
                    'TERA_VALOR_CORRECTO' => 'DESCARGUE LA ESTRUCTURA'                
                ]);

            }

        }else{

            $currentRowNumber = 1;

            foreach($rows as $row){

                if($currentRowNumber > 1){

                    vacunacioncargue::create([
                        'TRAG_TIPO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row[0])),
                        'TRAG_NUMERO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row[1])),
                        'TRAG_PRIMER_NOMBRE' => rtrim($row[2]),
                        'TRAG_SEGUNDO_NOMBRE' => rtrim($row[3]),
                        'TRAG_PRIMER_APELLIDO' => rtrim($row[4]),
                        'TRAG_SEGUNDO_APELLIDO' => rtrim($row[5]),
                        'TRAG_CODIGO_PRESTADOR' => intval($row[6]),
                        'TRAG_FECHA_AGENDAMIENTO' => ($row[7]) ? Date::excelToDateTimeObject($row[7]) : '',
                        'TRAG_HORA_AGENDAMIENTO' => ($row[8] || $row[8] > 0) ? Date::excelToDateTimeObject($row[8]) : '',
                        'TRAG_NUMERO_DOSIS' => intval($row[9]),
                        'TRAG_CAUSA_NO_AGENDAMIENTO' => intval($row[10]),
                        'TRAG_OPERACION_AGENDAMIENTO' => intval($row[11]),
                        'TRAG_CARGUE_ID' => $this->idCargue,
                        'TRAG_FILA_CARGUE' => $currentRowNumber,
                        'TRAG_FECHA_REGISTRO' => date('Y-m-d H:i:s')
                    ]);

                }

                $currentRowNumber++;
            }

        }

    }

    public function  batchSize(): int{
        return 10000;
    }

    public function chunkSize(): int{
        return 10000;
    }    

    private function validateHeading($row){
        
        $headingValues = [];

        foreach($row as $value){
            $headingValues[] = strtolower($value);
        }

        $validate = array_diff($headingValues,$this->getHeading());

        if($validate){
            return $validate;
        }else{
            return false;
        }
    }

    private function getHeading(){
        return $heading = [
            'tipo_documento_paciente',
            'numero_documento_paciente',
            'primer_nombre',
            'segundo_nombre',
            'primer_apellido',
            'segundo_apellido',
            'codigo_prestador',
            'fecha_agendamiento',
            'hora_agendamiento',
            'numero_dosis',
            'causa_no_agendamiento',
            'operacion_agendamiento',            
        ];
    }

}
