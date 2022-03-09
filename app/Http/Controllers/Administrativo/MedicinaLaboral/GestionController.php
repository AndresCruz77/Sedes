<?php

namespace App\Http\Controllers\Administrativo\MedicinaLaboral;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Models\Administrativo\MedicinaLaboral\UPG_USUARIO_PROCESO_GESTION;

class GestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $procesosUsuario = UPG_USUARIO_PROCESO_GESTION::
        join('MedicinaLaboral.USP_USUARIO_PROCESO as b','UPG_USP_ID','=','USP_ID')
        ->join('MedicinaLaboral.PRG_PROCESO_GESTION as c','UPG_PRG_ID','=','PRG_ID')
        ->where('USP_USU_ID',Auth::user()->USU_ID)
        ->where('USP_ESTADO',1)
        ->where('UPG_ESTADO',1)
        ->get();

        return view('Administrativo.MedicinaLaboral.GestionHome',compact('procesosUsuario'));
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
        return view('Administrativo.MedicinaLaboral.Home');
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
