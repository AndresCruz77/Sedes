<?php

namespace App\Http\Middleware;
use App\Models\Administrador\LMR_LOGIN_MENU_ROL;

use Closure;

class AccesosUsuarioMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // if($request->method() == "GET"){

        //     $path = $request->header('referer');
        //     $pathParametros = $this->extraerParametrosPath($path);

        //     //$pathParametros[0] = menuId
        //     //$pathParametros[1] = nameMenu

        //     $menuId = ($request->menuId) ? $request->menuId : (isset($pathParametros[0])) ? $pathParametros[0] : null;
        //     $menuName = ($request->nameMenu) ? $request->nameMenu : (isset($pathParametros[1])) ? $pathParametros[1] : null;
        //     $userId = $request->user()->USU_ID;
        //     $userRol = $request->user()->USU_LOR_ID;
    
        //     $verificarRuta = LMR_LOGIN_MENU_ROL::select('LMH_ID')  
        //     ->where('LMR_LMH_ID',$menuId)
        //     ->join('SEGURIDAD.LMH_LOGIN_MENU_HIJO','LMR_LMH_ID','=','LMH_ID')
        //     ->where('LMH_NOMBRE',$menuName)
        //     ->where('LMR_LOR_ID',$userRol)
        //     ->where('LMR_LOR_ID',$userRol)
        //     ->first();
    
        //     if(!$verificarRuta){
        //         return abort(401);
        //     }

        // }

        return $next($request);
    }

    private function extraerParametrosPath($path){
        //El primer explode separa el path donde encuentre el simbolo ?
        $p = explode("?",$path);
        $parametros = [];

        //Se valida si la separación tuvo resultados, en caso de ser asi continua.
        if(isset($p[1])){

            //Ahora se debe separar por & ya que nos dara un Array con la cantidad de parametros que se pasen
            $p = explode("&",$p[1]);

            foreach($p as $p2){
                list($nombreParametro,$valueParametro) = explode("=",$p2);
                $parametros[] = [
                    $nombreParametro => $valueParametro
                ];
            }
        }

        return $parametros;
    }
}
