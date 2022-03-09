<?php

namespace App\Imports;

use App\Models\asistencial\covid19\vacunacionTempRegistroAsignacion;
use App\Models\Asistencial\covid19\VacunacionCargueErrores;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{toCollection, WithBatchInserts};

class VacunacionAsignacionImport implements toCollection, WithBatchInserts
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
                    vacunacionTempRegistroAsignacion::create([
                        'TRAA_TIPO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row[0])),
                        'TRAA_NUMERO_DOCUMENTO_PACIENTE' => ltrim(rtrim($row[1])),
                        'TRAA_CODIGO_PRESTADOR' => intval($row[2]),
                        'TRAA_CARGUE_ID' => $this->idCargue,
                        'TRAA_FILA_CARGUE' => $currentRowNumber,
                        'TRAA_FECHA_REGISTRO' => date('Y-m-d H:i:s') ,
                        'TRAA_OPERACION_ASIGNACION' => intval($row[3])       
                    ]);
                }

                $currentRowNumber++;
            }
                    
        }


    }

    public function  batchSize(): int{
        return 2000;
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
            'codigo_prestador',
            'operacion_asignacion'
        ];
    }
}
