<?php

namespace App\Http\Controllers\Asistencial\afiliados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\asistencial\tdo_tipo_documento;
use App\Models\asistencial\covid19\iha_ips_habilitacion;

use App\Http\Controllers\Asistencial\Covid19\TraitApiVacunacion;
use App\Rules\Recaptcha;

class VacunacionCovidController extends Controller
{

    use TraitApiVacunacion;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposDocumento = tdo_tipo_documento::all();

        return view('asistencial.afiliados.VacunacionCovid',compact('tiposDocumento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Recaptcha $recaptcha)
    {
        $consultas = [];
        $this->validate($request, [
            'TipoIDPaciente' => 'required',
            'NoIDPaciente' => 'required',
            'g-recaptcha-response' => ['required', $recaptcha],
        ]);

        $token = $this->getToken();
        $consulta = $this->getConsultarAgendamiento($request->TipoIDPaciente,$request->NoIDPaciente,$token);

        if($consulta){
            $consulta = json_decode($consulta,true);

            foreach ($consulta as $key => $value) {
                $consultas[] = [
                    'nombrePrestador' => iha_ips_habilitacion::select('IHA_NOMBRE_PRESTADOR')->where('IHA_CODIGO_HABILITACION',$value['CodPrestador'])->first(),
                    'CausaNoAgendamiento' => $value['CausaNoAgendamiento'], 
                    'CodPrestador' => $value['CodPrestador'], 
                    'EstadoAgendamiento' => $value['EstadoAgendamiento'], 
                    'FechaAgendamiento' => $value['FechaAgendamiento'], 
                    'FechaAnulacion' => $value['FechaAnulacion'], 
                    'FechaRegistro' => $value['FechaRegistro'], 
                    'HoraAgendamiento' => $value['HoraAgendamiento'], 
                    'IDAgendamiento' => $value['IDAgendamiento'], 
                    'NoIDPaciente' => $value['NoIDPaciente'], 
                    'NumeroDosis' => $value['NumeroDosis'], 
                    'PrimerApellido' => $value['PrimerApellido'], 
                    'PrimerNombre' => $value['PrimerNombre'], 
                    'SegundoApellido' => $value['SegundoApellido'], 
                    'SegundoNombre' => $value['SegundoNombre'], 
                    'TipoIDPaciente' => $value['TipoIDPaciente'], 
                ];
            }
        }

        return $consultas;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
