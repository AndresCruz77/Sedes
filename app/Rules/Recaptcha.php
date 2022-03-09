<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.env('RECAPTCHA_SECRET').'&response='.$value;

        $curl = curl_init();
    
        $config = [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => ['application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => [],
        ];

        curl_setopt_array($curl, $config);     

        $response = curl_exec($curl);
        
        curl_close($curl);

        $response = json_decode($response,true);

        //dd($response);

        if($response['success']){
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La verificacion de recaptcha fallo. Intente de nuevo.';
    }
   
}
