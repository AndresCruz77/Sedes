<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Administrador\LOR_LOGIN_ROL;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'SEGURIDAD.USU_LOGIN';

    protected $primaryKey = 'USU_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name', 'email', 'password','username','type'
        'USU_ID',
        'USU_PRIMER_NOMBRE',
        'USU_SEGUNDO_NOMBRE',
        'USU_PRIMER_APELLIDO',
        'USU_SEGUNDO_APELLIDO',
        'USU_TDO_ID',
        'USU_NUMERO_IDENTIFICACION',
        'USU_EMP_ID',
        'USU_USERNAME',
        'USU_TELEFONO_UNO',
        'USU_TELEFONO_DOS',
        'USU_DIRECCION',
        'USU_CORREO',
        'USU_FECHA_ULTIMO_ACCESO',
        'USU_REQUIERE_CAMBIAR_CLAVE',
        'USU_INTENTOS_ERRONEOS',
        'USU_RUI_ID',
        'USU_ACTIVO',
        'USU_FECHA_INACTIVACION',
        'USU_ID_REGISTRO',
        'USU_FECHA_REGISTRO',
        'USU_ID_MODIFICO',
        'USU_FECHA_ULTIMA_MODIFICACION',
        'USU_FECHA_REACTIVACION',
        'USU_PASSWORD',
        'USU_EMAIL_VERIFIED_AT',
        'USU_REMEMBER_TOKEN',
        'USU_LOR_ID',
        'USU_AREA',
        'USU_CARGO',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $maps = [
        'USU_USERNAME' => 'username',
        'USU_CORREO' => 'email',
        'USU_PASSWORD' => 'password',
        'USU_EMAIL_VERIFIED_AT' => 'email_verified_at',
        'USU_REMEMBER_TOKEN' => 'remember_token',        
    ];

    protected $append = [
        'username',
        'email',
        'password',
        'email_verified_at',
        'remember_token',
    ];
    
    protected $hidden = [
        'USU_PASSWORD','USU_REMEMBER_TOKEN'
    ];

    public function rolUsuario(){
        return $this->hasOne(LOR_LOGIN_ROL::class, 'LOR_ID', 'USU_LOR_ID');
    }


    public function getUsernameAttribute()
    {
      return $this->attributes['username'];
    }   
    
    
    public function getPasswordAttribute()
    {
      return $this->attributes['USU_PASSWORD'];
    }     

    const CREATED_AT = 'USU_FECHA_REGISTRO';
    const UPDATED_AT = 'USU_FECHA_ULTIMA_MODIFICACION';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'USU_EMAIL_VERIFIED_AT' => 'datetime',
        'USU_USERNAME' => 'varchar'
    ];
}
