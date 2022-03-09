<?php

namespace App\Http\Controllers\Asistencial\Covid19;

trait TraitApiVacunacion{

    private $method = '';
    private $apiUrl = '';
    private $body = array();
    private $headers = array();
    private $token = '0';

    private function execConnection(){

        $curl = curl_init();
        

        $config = [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_POSTFIELDS => json_encode($this->body),
        ];

        curl_setopt_array($curl, $config);     

        $response = curl_exec($curl);
        
        curl_close($curl);
        
        // if($this->apiUrl == 'https://wsmivacuna.sispro.gov.co/WSAgendamiento/api/login/autenticar'){
        //     dd($config);
        // }

        return $response;
    }
    
    public function getToken(){

        $body = [
            "nit" => env('API_SISPRO_NIT'),
            "tipoEntidad" => intval(env('API_SISPRO_TIPOENTIDAD')),
            "token" => env('API_SISPRO_TOKEN')
        ];

        $this->setApiUrl(env('API_SISPRO_URL').'/WSAgendamiento/api/login/autenticar');
        $this->setMethod('POST');
        $this->setBody($body);
        $this->setHeaders(['Content-Type: application/json','Accept: application/json']);
        return $this->execConnection();

    }

    public function putAsignacion($datosAsignacion=array(),$token=null){
        $nit = env('API_SISPRO_NIT');
        $body = $datosAsignacion;
        $token = ($token==null) ? $this->validateToken() : $this->setToken($token);

        $this->setApiUrl(env('API_SISPRO_URL').'/WSAgendamiento/api/RegistrarAsignacion/'.$nit.'');
        $this->setMethod('PUT');
        $this->setBody($body);
        $this->setHeaders([
            'Content-Type: application/json', 
            'Authorization : '.$this->token.' ',
            'Cookie: cookiesession1='.session('_token')
        ]);

        return $this->execConnection();        
    }  
    
    public function putAnularAsignacion($datosAsignacion=array(),$token=null){
        $nit = env('API_SISPRO_NIT');
        $body = $datosAsignacion;
        $token = ($token==null) ? $this->validateToken() : $this->setToken($token);

        $this->setApiUrl(env('API_SISPRO_URL').'/WSAgendamiento/api/AnularAsignacion/'.$nit.'');
        $this->setMethod('PUT');
        $this->setBody($body);
        $this->setHeaders([
            'Content-Type: application/json', 
            'Authorization : '.$this->token.' ',
            'Cookie: cookiesession1='.session('_token')
        ]);
        return $this->execConnection();        
    }    

    public function putAgendamiento($datosAgendamiento=array(),$token=null){
        $nit = env('API_SISPRO_NIT');
        $body = $datosAgendamiento;
        $token = ($token==null) ? $this->validateToken() : $this->setToken($token);

        $this->setApiUrl(env('API_SISPRO_URL').'/WSAgendamiento/api/RegistrarAgendamiento/'.$nit.'');
        $this->setMethod('PUT');
        $this->setBody($body);
        $this->setHeaders([
            'Content-Type: application/json', 
            'Authorization : '.$this->token.' ',
            'Cookie: cookiesession1='.session('_token')
        ]);

        return $this->execConnection();        
    }

    public function putAnularAgendamiento($datosAgendamiento=array(),$token=null){
        $nit = env('API_SISPRO_NIT');
        $body = $datosAgendamiento;
        $token = ($token==null) ? $this->validateToken() : $this->setToken($token);

        $this->setApiUrl(env('API_SISPRO_URL').'/WSAgendamiento/api/AnularAgendamiento/'.$nit.'');
        $this->setMethod('PUT');
        $this->setBody($body);
        $this->setHeaders([
            'Content-Type: application/json', 
            'Authorization : '.$this->token.' ',
            'Cookie: cookiesession1='.session('_token')
        ]);
        
        return $this->execConnection();        
    }    

    public function getConsultarAgendamiento($tipoIdentificacion='',$numeroIdentificacion=0,$token=null){
        $nit = env('API_SISPRO_NIT');
        $token = ($token==null) ? $this->validateToken() : $this->setToken($token);

        $this->setApiUrl(env('API_SISPRO_URL').'/WSAgendamiento/api/ConsultarAgendamientoXIdentificacion/'.$nit.'/tipoIdentificacion/numeroIdentificacion?tipoIdentificacion='.$tipoIdentificacion.'&numeroIdentificacion='.$numeroIdentificacion.'');
        $this->setMethod('GET');
        $this->setHeaders(['Authorization : '.$this->token.' ']);
        return $this->execConnection();        
    }

    private function setApiUrl($url){
        $this->apiUrl = $url;
    }

    private function setMethod($method){
        $this->method = $method;
    }

    private function setBody($body=array()){
        $this->body = $body;
    }

    private function setHeaders($headers=array()){
        $this->headers = $headers;
    }

    private function setToken($token=''){
        $this->token = json_decode($token);
    }    

    private function setSessionToken($token=''){
        session(['tokenMipres' => $token]);
    }

    private function validateToken(){
        if( !session('tokenMipres') ){
            $this->setSessionToken($this->getToken());
        }

        $this->setToken(session('tokenMipres'));
    }

}
