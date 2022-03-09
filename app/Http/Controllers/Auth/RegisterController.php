<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'USU_PRIMER_NOMBRE' => $data['name'],
            'USU_SEGUNDO_NOMBRE' => $data['name'],
            'USU_PRIMER_APELLIDO' => $data['name'],
            'USU_SEGUNDO_APELLIDO' => $data['name'],
            'USU_TDO_ID' => 2,
            'USU_NUMERO_IDENTIFICACION' => 12345678,
            'USU_CORREO' => $data['email'],
            'USU_USERNAME' => $data['username'],
            'USU_EMAIL_VERIFIED_AT' => date('Y-m-d H:i:s'),
            'USU_FECHA_REGISTRO' => date('Y-m-d H:i:s'),
            'USU_PASSWORD' => Hash::make($data['password']),
            'USU_EMP_ID' => 1,
            'USU_ID_REGISTRO' => 0,            
        ]);
    }
}
