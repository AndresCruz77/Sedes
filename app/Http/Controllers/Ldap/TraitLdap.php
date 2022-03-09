<?php

namespace App\Http\Controllers\Ldap;
use LdapRecord\Connection;
use App\User;
use Illuminate\Support\Facades\Hash;
use DB;

trait TraitLdap {

    private function connection(){

        $conn = new Connection([
            // Mandatory Configuration Options
            'hosts'            => [env('LDAP_HOST')],
            'base_dn'          => env('LDAP_BASE_DN'),
            'username'         => env('LDAP_USERNAME'),
            'password'         => env('LDAP_PASSWORD'),
        
            // Optional Configuration Options
            'port'             => env('LDAP_PORT'),
            'use_ssl'          => false,
            'use_tls'          => false,
            'version'          => 3,
            'timeout'          => 5,
            'follow_referrals' => false,
        
            // Custom LDAP Options
            'options' => [
                // See: http://php.net/ldap_set_option
                LDAP_OPT_X_TLS_REQUIRE_CERT => LDAP_OPT_X_TLS_HARD
            ]
        ]);

        return $conn;

    } 
    
    private function connect(){
        try {

            return $con = $this->connection()->connect();            

        } catch (\LdapRecord\Auth\BindException $e) {

            $error = $e->getDetailedError();

            $message = $error->getErrorCode().' '.$error->getErrorMessage().$error->getDiagnosticMessage();

            return \Session::flash('message',$message);
        }
    }

    public function findUserLdap($username=null){
    
        if( $conn = $this->connect() ){

            $user = $this->connect()->query()
            ->where('samaccountname', '=', $username)
            ->orWhere('facsimiletelephonenumber', '=', $username)
            ->first();
            
            if($user){
                return $user;
            }else{
                return null;
            }

        }
    }

    public function loginUserLdap($request){
        ///validacion empleados
        $empleado = DB::table(DB::raw('[Covit].[EMD_EMPLEADOS_MEDIMAS]'))
        ->select('EMD_ID')
        ->where('EMD_TIPO_DOC',$request->tip_doc)
        ->where('EMD_NUMERO_DOCUMENTO',$request->num_identifica)
        ->where('EMD_ESTADO_CONTRATO','A')->count();
        $empleado = json_decode($empleado, true);
        if($empleado == true){
        
            $user = $this->findUserLdap($request->usu_username);
            
        
            if ($user && $this->connect()->auth()->attempt($user['distinguishedname'][0], $request->usu_password)) {
                return $this->verifyLogin($user);
            } else {
                return null;
            }         
        }else{
            return null;
        }    
    }

    private function verifyLogin($user){        
        $findUser = User::where('usu_username',$user['samaccountname'][0])->first();

        if(!$findUser){

            $nombres = explode(' ',$user['cn'][0]);
            $PrimerNombre = (isset($nombres[0])) ? $nombres[0] : '';
            $SegundoNombre = (isset($nombres[1])) ? $nombres[1] : '';
            $PrimerApellido = (isset($nombres[2])) ? $nombres[2] : '';
            $SegundoApellido = (isset($nombres[3])) ? $nombres[3] : '';

            $findUser = User::create([
                //'USU_PRIMER_NOMBRE' => strtoupper($PrimerNombre),
                'USU_SEGUNDO_NOMBRE' => strtoupper($SegundoNombre),
                'USU_PRIMER_APELLIDO' => strtoupper($PrimerApellido),
                'USU_SEGUNDO_APELLIDO' => strtoupper($SegundoApellido),
                'USU_TDO_ID' => 2,
                'USU_NUMERO_IDENTIFICACION' => $user['facsimiletelephonenumber'][0],
                'USU_CORREO' => $user['userprincipalname'][0],
                'USU_USERNAME' => $user['samaccountname'][0],
                'USU_EMAIL_VERIFIED_AT' => date('Y-m-d H:i:s'),
                'USU_FECHA_REGISTRO' => date('Y-m-d H:i:s'),
                'USU_PASSWORD' => Hash::make('$ldap*'.$user['userprincipalname'][0]),
                'USU_EMP_ID' => 1,
                'USU_ID_REGISTRO' => 0                
            ]);
            
        }

        return $findUser;

    }

}