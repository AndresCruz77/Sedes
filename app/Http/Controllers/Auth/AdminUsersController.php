<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Models\asistencial\tdo_tipo_documento;
use App\Models\Administrador\LOR_LOGIN_ROL;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Ldap\TraitLdap;

class AdminUsersController extends Controller
{
    use TraitLdap;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tipoDocumento = tdo_tipo_documento::select('TDO_ID','TDO_DESCRIPCION')->orderBy('TDO_DESCRIPCION','ASC')->get();
        $roles = LOR_LOGIN_ROL::orderBy('LOR_NOMBRE')->get();

        return view('Auth.adminUsers', compact('tipoDocumento','roles'));
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
        $validar = User::select('USU_USERNAME','USU_NUMERO_IDENTIFICACION','USU_CORREO')
        ->where('USU_USERNAME',$request->USU_USERNAME)
        ->orWhere('USU_NUMERO_IDENTIFICACION',$request->USU_NUMERO_IDENTIFICACION)
        ->orWhere('USU_CORREO',$request->USU_CORREO)
        ->first();

        if($validar){
            return response()->json(['mensaje' => 'El usuario o número de identificación ingresado ya existe.'], 200);
        }

        $crearUsuario = User::create([
            'USU_ACTIVO' => $request->USU_ACTIVO,
            'USU_CORREO' => $request->USU_CORREO,
            'USU_DIRECCION' => $request->USU_DIRECCION,
            'USU_EMP_ID' => $request->USU_EMP_ID,
            'USU_NUMERO_IDENTIFICACION' => $request->USU_NUMERO_IDENTIFICACION,
            'USU_PRIMER_APELLIDO' => strtoupper($request->USU_PRIMER_APELLIDO),
            'USU_PRIMER_NOMBRE' => strtoupper($request->USU_PRIMER_NOMBRE),
            'USU_SEGUNDO_APELLIDO' => strtoupper($request->USU_SEGUNDO_APELLIDO),
            'USU_SEGUNDO_NOMBRE' => strtoupper($request->USU_SEGUNDO_NOMBRE),
            'USU_TDO_ID' => $request->USU_TDO_ID,
            'USU_TELEFONO_DOS' => $request->USU_TELEFONO_DOS,
            'USU_TELEFONO_UNO' => $request->USU_TELEFONO_UNO,
            'USU_USERNAME' => $request->USU_USERNAME,
            'USU_EMAIL_VERIFIED_AT' => date('Y-m-d H:i:s'),
            'USU_PASSWORD' => Hash::make($request->USU_USERNAME),
            'USU_ID_REGISTRO' => \Auth::user()->USU_ID,
            'USU_REQUIERE_CAMBIAR_CLAVE' => 1,
            'USU_INTENTOS_ERRONEOS' => 0,
            'USU_LOR_ID' => 4
        ]);

        return response()->json([
            'mensaje' => 'Usuario '.$request->USU_USERNAME.' creado correctamente, puede proceder a asignar los demás permisos',
            'informacionUsuario' => $crearUsuario
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($parametro){

        $user = User::where('USU_USERNAME',$parametro)
        ->orWhere('USU_NUMERO_IDENTIFICACION',$parametro)
        ->first();
        
        if(!$user){
            $user = $this->findUserLdap($parametro);
            
            if(isset($user['cn'])){

                $nombres = explode(' ',$user['cn'][0]);
                $PrimerNombre = (isset($nombres[0])) ? $nombres[0] : '';
                $SegundoNombre = (isset($nombres[1])) ? $nombres[1] : '';
                $PrimerApellido = (isset($nombres[2])) ? $nombres[2] : '';
                $SegundoApellido = (isset($nombres[3])) ? $nombres[3] : '';

                $user = [
                    'USU_ID' => 0,
                    'USU_PRIMER_NOMBRE' => $PrimerNombre,
                    'USU_SEGUNDO_NOMBRE' => $SegundoNombre,
                    'USU_PRIMER_APELLIDO' => $PrimerApellido,
                    'USU_SEGUNDO_APELLIDO' => $SegundoApellido,
                    'USU_TDO_ID' => 2,
                    'USU_NUMERO_IDENTIFICACION' => $user['facsimiletelephonenumber'][0],
                    'USU_EMP_ID' => 0,
                    'USU_USERNAME' => $user['samaccountname'][0],
                    'USU_TELEFONO_UNO' => '',
                    'USU_TELEFONO_DOS' => '',
                    'USU_DIRECCION' => '',
                    'USU_CORREO' => $user['userprincipalname'][0],
                    'USU_ACTIVO' => true,                    
                ];               
            }
        }

        return response()->json($user,200);
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
    public function update(Request $request, $username, $iduser)
    {
        $actualizarUsuario = User::where('USU_USERNAME',$username)
        ->where('USU_ID',$iduser)
        ->update($this->getFormularioActualizacion($request->all()));

        if($actualizarUsuario){
            return response()->json(['mensaje' => 'Información actualizada correctamente'], 200);
        }else{
            return response()->json(['mensaje' => 'No se logro guardar la información, por favor intente de nuevo.'], 200);    
        }   
        
    }

    // Este es un metodo intermedio que define que campos actualiza de acuerdo al tipo de formulario.
    private function getFormularioActualizacion($request){
        switch ($request['USU_FORMULARIO']) {
            case 'informacion':
                    return $this->actualizarInformacionUsuario($request);
                break;
            case 'rol':
                    return $this->actualizarRolUsuario($request);
                break;
            default:
                return [];
                break;
        }
    }

    // 
    private function actualizarInformacionUsuario($request){
        return $campos = [
            'USU_TDO_ID' => $request['USU_TDO_ID'],
            'USU_NUMERO_IDENTIFICACION' => $request['USU_NUMERO_IDENTIFICACION'],
            'USU_PRIMER_NOMBRE' => strtoupper($request['USU_PRIMER_NOMBRE']),
            'USU_SEGUNDO_NOMBRE' => strtoupper($request['USU_SEGUNDO_NOMBRE']),
            'USU_PRIMER_APELLIDO' => strtoupper($request['USU_PRIMER_APELLIDO']),
            'USU_SEGUNDO_APELLIDO' => strtoupper($request['USU_SEGUNDO_APELLIDO']),
            'USU_EMP_ID' => $request['USU_EMP_ID'],
            'USU_TELEFONO_UNO' => $request['USU_TELEFONO_UNO'],
            'USU_TELEFONO_DOS' => $request['USU_TELEFONO_DOS'],
            'USU_DIRECCION' => $request['USU_DIRECCION'],
            'USU_CORREO' => $request['USU_CORREO'],
            'USU_ACTIVO' => $request['USU_ACTIVO'],
        ];
    }

    private function actualizarRolUsuario($request){
        return $campos = [
            'USU_LOR_ID' => $request['USU_LOR_ID']
        ];
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
