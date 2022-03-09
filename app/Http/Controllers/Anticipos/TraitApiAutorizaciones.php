<?php

namespace App\Http\Controllers\Anticipos;
use Illuminate\Support\Facades\Http;

trait TraitApiAutorizaciones{

    private $method = '';
    private $apiUrl = '';
    private $body = array();
    private $headers = array();
    private $token = '0';


    
    
    public function getToken(){        
        $response = Http::asForm()->post(env('API_TOKEN_AUTO_URL'), [
            'username' => env('API_TOKEN_AUTO_USU'),            
            'password' => env('API_TOKEN_AUTO_PAS'),
            'grant_type' => 'password',
            'type_auth' => 'domain',
            'module' => 'MenuSeguridad'
        ]);
        if(isset($response['access_token'])){
            return $response['access_token'];
        }else{
            return "Error TOKEN";
        }        
    }

    public function consultaAutorizaciones($token = null, $id = null ){
        $error = array('An error has occurred.','Autorización no direccionada al dispensador de consulta');
        $response = "";
        $autoriza = array();
        $resultado = array();                
        if($token != '0'){            
            $curl = curl_init();
            $field = '{\r\n    \"fecha\":\"\",\r\n    \"NumAut\":'.$id.'\r\n}';
            curl_setopt_array($curl, array(
              CURLOPT_URL => env('API_AUTORIZA_URL'),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS =>'{"fecha":"","NumAut":"'.$id.'"}',                   
              CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: bearer ".$token.""            
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);    
            $response = json_decode($response,true);                    
            if(isset($response["data"][0])){
                if (array_key_exists('MENSAJE', $response["data"][0])) {                    
                    foreach ($error as $value) {
                        if($value == $response["data"][0]["MENSAJE"]){                    
                            $resultado["valida"] = 0;
                            $resultado["mensaje"] = "No Se Encontro Autorizacion, Por Favor Verificar Numero.";
                            $resultado["data"] = "";
                        }
                    }
                }                
            }
            if(isset($response["Message"])){
                if (array_key_exists('Message', $response)) {        
                    foreach ($error as $value) {
                        if($value == $response["Message"]){
                            $resultado["valida"] = 0;
                            $resultado["mensaje"] = "Problemas Para Consumir Servicio de Consulta de Autorizaciones.";
                            $resultado["data"] = "";
                        }
                    }
                }
            }

            if(isset($response["data"][0]["NUMAUTORIZACION"])){
                if (array_key_exists('NUMAUTORIZACION', $response["data"][0])) {                                        
                    /* echo "<pre>";
                    print_r($response);
                    die(); */
                    $i = 0;
                    foreach ( $response["data"] as $value ){                        
                        $autoriza[$i]['AUTIDDETALLEAUTORIZACION'] = $value['AUTIDDETALLEAUTORIZACION'];
                        $autoriza[$i]['NOMBRE_COMPLETO'] = $value['PRIMERNOMBRE']." ".$value['SEGUNDONOMBRE']." ".$value['PRIMERAPELLIDO']." ".$value['SEGUNDOAPELLIDO'];
                        $autoriza[$i]['PRIMERNOMBRE'] = $value['PRIMERNOMBRE'];
                        $autoriza[$i]['SEGUNDONOMBRE'] = $value['SEGUNDONOMBRE'];
                        $autoriza[$i]['PRIMERAPELLIDO'] = $value['PRIMERAPELLIDO'];
                        $autoriza[$i]['SEGUNDOAPELLIDO'] = $value['SEGUNDOAPELLIDO'];
                        $autoriza[$i]['CIE10'] = $value['CIE10'];
                        $autoriza[$i]['DIAGNOSTICO_CIE10'] = $value['Diagnóstico CIE10']; 
                        $autoriza[$i]['CODIGOTECNOLOGIA'] = $value['CODIGOTECNOLOGIA'];
                        $autoriza[$i]['CODIGO_CUMS_CUPS'] = $value['Código CUMS/CUPS'];
                        $autoriza[$i]['PRODUCTO'] = $value['PRODUCTO'];
                        $autoriza[$i]['CANTIDAD'] = $value['CANTIDAD'];
                        $autoriza[$i]['NUMAUTORIZACION'] = $value['NUMAUTORIZACION'];
                        $autoriza[$i]['COBERTURA'] = $value['COBERTURA'];
                        $autoriza[$i]['VLRAUTORIZACION'] = $value['VLRAUTORIZACION'];
                        $autoriza[$i]['TIPODOCUMENTO'] = $value['TIPODOCUMENTO'];
                        $autoriza[$i]['NUMDOCUMENTO'] = $value['NUMDOCUMENTO'];
                        $i++;                        
                    }                    
                    $resultado["valida"] = 1;
                    $resultado["mensaje"] = "Consulta Ejecutada Con Exito";
                    $resultado["data"] = $autoriza;
                }
            }
        }else{
            $resultado["valida"] = 0;
            $resultado["mensaje"] = "Problemas Para Consumir Servicio de token.";
            $resultado["data"] = "";
        }    
        return $resultado; 
    }

   
   


















































































    private function validateToken(){
        if( !session('tokenMipres') ){
            $this->setSessionToken($this->getToken());
        }

        $this->setToken(session('tokenMipres'));
    }

}
