<?php

namespace App\Http\Controllers\Administrativo\MedicinaLaboral;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;

use App\Models\Administrativo\MedicinaLaboral\UPG_USUARIO_PROCESO_GESTION;
use App\Models\Administrativo\MedicinaLaboral\PRG_PROCESO_GESTION;
use App\Models\Administrativo\MedicinaLaboral\USP_USUARIO_PROCESO;

class ResponsablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = [];

        $bquedaUsuarios = User::select('USU_ID',
        'USU_PRIMER_NOMBRE',
        'USU_SEGUNDO_NOMBRE',
        'USU_PRIMER_APELLIDO',
        'USU_SEGUNDO_APELLIDO',
        'USU_USERNAME',
        'USP_USU_ID',
        'USP_ID',
        'USP_ESTADO'
        )
        ->distinct()
        ->leftJoin('MedicinaLaboral.USP_USUARIO_PROCESO as b',function($join){
            $join->on('USU_ID','=','USP_USU_ID');
            $join->on('USP_ESTADO','=',DB::raw(1));
        })
        ->where('USU_LOR_ID',1)
        ->orderBy('USU_PRIMER_NOMBRE','ASC')
        ->get();

        foreach ($bquedaUsuarios as $key => $usuario) {
            $usuarios[] = [
                'USU_ID' => $usuario->USU_ID,
                'USU_PRIMER_NOMBRE' => $usuario->USU_PRIMER_NOMBRE,
                'USU_SEGUNDO_NOMBRE' => $usuario->USU_SEGUNDO_NOMBRE,
                'USU_PRIMER_APELLIDO' => $usuario->USU_PRIMER_APELLIDO,
                'USU_SEGUNDO_APELLIDO' => $usuario->USU_SEGUNDO_APELLIDO,
                'USU_USERNAME' => $usuario->USU_USERNAME,
                'USP_USU_ID' => $usuario->USP_USU_ID,
                'USP_ID' => $usuario->USP_ID,
                'USP_ESTADO' => $usuario->USP_ESTADO,
                'procesosGestion' => $this->getProcesoGestionUsuario(intval($usuario->USP_ID))
            ];
        }

        return view('Administrativo.MedicinaLaboral.ResponsablesHome',compact('usuarios'));
    }

    private function getProcesoGestionUsuario($idProceso=0){
        
       return PRG_PROCESO_GESTION::select('PRG_ID','PRG_NOMBRE','UPG_ID','UPG_USP_ID','UPG_PRG_ID','UPG_ESTADO')
        ->leftJoin('MedicinaLaboral.UPG_USUARIO_PROCESO_GESTION',function($join) use($idProceso) {
            $join->on('PRG_ID','=','UPG_PRG_ID');
            $join->on('UPG_USP_ID','=',DB::raw($idProceso));
        })
        ->orderBy('PRG_NOMBRE','ASC')
        ->get();
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    private function getProcesosUsuario($request){
        return USP_USUARIO_PROCESO::where('USP_USU_ID',$request->USU_ID)->first();
    }

    public function procesosUsuario(Request $request){

        $consulta = $this->getProcesosUsuario($request);

        if($consulta){
            
            $consulta->USP_ESTADO = ($consulta->USP_ESTADO == 1 ) ? 0 : 1;
            $consulta->USP_FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
            $consulta->save();

            return $consulta;

        }else{

            $crear = USP_USUARIO_PROCESO::create([
                'USP_USU_ID' => $request->USU_ID,
                'USP_ESTADO' => 1,
                'USP_FECHA_CREACION' => date('Y-m-d H:i:s'),
            ]);

            return $crear;
        }

    }

    public function procesoGestionUsuario(Request $request){

        $consulta = UPG_USUARIO_PROCESO_GESTION::where('UPG_USP_ID',$request->USP_ID)->where('UPG_PRG_ID',$request->PRG_ID)->first();

        if($consulta){
            $consulta->UPG_ESTADO = ($consulta->UPG_ESTADO) ? 0 : 1;
            $consulta->save();
            return $consulta;
        }else{
            return UPG_USUARIO_PROCESO_GESTION::create([
                'UPG_USP_ID' => $request->USP_ID,	
                'UPG_PRG_ID' => $request->PRG_ID,	
                'UPG_ESTADO' => 1,
            ]);
        }
        
    }
}
