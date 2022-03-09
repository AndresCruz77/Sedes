<?php

namespace App\Http\Controllers\Anticipos;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Anticipos\nuevoController;

use App\Models\Anticipos\AHC_ANTICIPO_H_CABECERA;
use App\Models\Anticipos\AXD_ANTICIPO_X_DETALLE;
use App\Models\Anticipos\AXA_ADJUNTO_X_ANTICIPO; 
use App\Models\Anticipos\AMH_ANTICIPO_M_HISTORICO; 
use App\Models\Anticipos\EMD_EMPLEADOS_MEDIMAS;
use App\Models\Anticipos\GEO_GEOGRAFIA;
use App\Models\Anticipos\REDIPS;
use App\Models\Anticipos\DIA_DIAGNOSTICO_CIE10;
use App\Models\Anticipos\TXA_TIPO_X_ADJUNTO;

use DB;
use Auth;

class visor_medicoController extends Controller
{      
    public function index(){            
        $objeto = new nuevoController();
        //lista de Estados
        $listaEstados = $objeto->listaEstados();

        //lista observacion estado
        $listaObsEstado = $objeto->listaObsEstado();

        //lista Origen
        $listaOrigen = $objeto->listaOrigen();

        //Regionales
        $listRegional = $objeto->listRegional();

        //lista seccional
        $listaSeccional = $objeto->listaSeccional();

        //lista regimen
        $listaRegimen = $objeto->listaRegimen();

        //lista origen 
        $listaOrigen = $objeto->listaOrigen();

        //$lista tipo orden judicial
        $listaTipoOrdenj = $objeto->listaTipoOrdenj();

        //lista motivos
        $listaMotivo = $objeto->listaMotivo();

        //lista de documentos
        $listaDocs = $objeto->listaDocs();
        return view('anticipos.visor_medico', compact('listaEstados', 'listaOrigen', 'listRegional', 'listaObsEstado', 'listaSeccional', 'listaRegimen', 'listaOrigen', 'listaTipoOrdenj', 'listaMotivo', 'listaDocs'));
    }

    public function visorAnticipos(Request $request){        
        $resplist = array();                       
        $anticiposMed = array();        
        if($request->fechInicial != "" || $request->fechFinal != "" || $request->prestador !="null" || $request->afiliados !="null" || $request->regSolicita != "" || $request->anticipoMedi != "" || $request->autoriMedi != "" || $request->origAnticipo != "" || $request->estaAnticipo != ""){
            $afilia = json_decode($request->afiliados);
            $prestador = json_decode($request->prestador);
            $anticiposMed = DB::table('ANTICIPOS.AHC_ANTICIPO_H_CABECERA AS A');
            $anticiposMed->distinct();
            $anticiposMed->select('A.AHC_ID','A.AHC_FECHA_CREACION',
                            'B.EXA_DESCRIPCION','C.RNL_NOMBRE', 'A.AHC_TIPO_IDENT_AFI','A.AHC_NUM_IDENT_AFI', 
                            DB::raw(" CONCAT( A.AHC_PRIM_NOMB_AFI,' ',A.AHC_SEG_NOMB_AFI,' ',A.AHC_PRIM_APELL_AFI,' ',A.AHC_SEG_APELL_AFI ) AS AHC_NOMBRE_COMPLETO "),
                            'A.AHC_VALOR_CRUCE');
            $anticiposMed->join('ANTICIPOS.EXA_ESTADO_X_ANTICIPOS AS B','B.EXA_ID','=','A.AHC_ANTICIPO_ESTADO_CODIGO');
            $anticiposMed->join('CATALOGOS.RNL_REGIONAL AS C','C.RNL_ID','=','A.AHC_REGIONAL_CODIGO');                    
            if($request->fechInicial != "" && $request->fechFinal != ""){//fechas
                $anticiposMed->where('A.AHC_FECHA_CREACION','>=', $request->fechInicial);
                $anticiposMed->where('A.AHC_FECHA_CREACION','<=',$request->fechFinal);
            }   
            if(isset($prestador->ipsID)){
                if($prestador->ipsID != ""){//prestador
                    $anticiposMed->where('A.AHC_IPS', $prestador->ipsID);
                } 
            }    
            if(isset($afilia->NumDocAfiliado)){
                if($afilia->NumDocAfiliado != ""){//afiliado
                    $anticiposMed->where('A.AHC_TIPO_IDENT_AFI', $afilia->TipDocAfiliado);
                    $anticiposMed->where('A.AHC_NUM_IDENT_AFI', $afilia->NumDocAfiliado);
                } 
            }    
            if($request->regSolicita != ""){//regional
                $anticiposMed->where('A.AHC_REGIONAL_CODIGO', $request->regSolicita);            
            } 
            if($request->anticipoMedi != ""){//anticipo #
                $anticiposMed->where('A.AHC_ID', $request->anticipoMedi);            
            } 
            if($request->autoriMedi != ""){//autorizacion #
                $anticiposMed->where('A.AHC_NUMERO_AUTORIZA', $request->autoriMedi);            
            } 
            if($request->origAnticipo != ""){//origen
                $anticiposMed->where('A.AHC_ORIGEN_ANTICIPO_CODIGO', $request->origAnticipo);            
            } 
            if($request->estaAnticipo != ""){//estado
                $anticiposMed->where('A.AHC_ANTICIPO_ESTADO_CODIGO', $request->estaAnticipo);            
            }         
            $resplist = $anticiposMed->get(); 
        }    
        return response()->json($resplist);    
    }

    public function consultarHistorico($parametro){        
        $conhist = array();
        $conhist = AMH_ANTICIPO_M_HISTORICO::select('AMH_ID', 'EXA_DESCRIPCION', 'LOE_DESCRIPCION', 'RNL_NOMBRE',
                    DB::raw(" CONCAT(EMD_NOMBRES,' ',EMD_APELLIDOS,' - ',EMD_CARGO) AS RESPONSABLE "),
                    DB::raw(" UPPER(CONCAT( ipsNitIPS,' - ',ipsNombre )) AS PRESTADOR "),
                    DB::raw(" UPPER(F.GEO_SECCIONAL) AS GEO_SECCIONAL "),
                    DB::raw(" UPPER(J.GEO_MUNICIPIO) AS GEO_MUNICIPIO "),   
                    'REG_NOMBRE', 'OXA_DESCRIPCION',                 
                    'TOJ_DESCRIPCION', 'MXA_DESCRIPCION', 'AMH_VALOR_CRUCE', 'AMH_VALOR_BRUTO_PAG',	
                    'AMH_VALOR_TOTAL_COTIZ', 'AMH_SALDO_LEGALIZAR_PREST', 'AMH_NUMERO_MIPRES',
                    DB::raw(" UPPER(O.DIA_DESCRIPCION) AS DIAG_PRINC "),                    
                    DB::raw(" UPPER(P.DIA_DESCRIPCION) AS DIAG_SECON "),                    
		            'AMH_NUMERO_AUTORIZA', 
                    DB::raw(" CONCAT(AMH_TIPO_IDENT_AFI,' - ',AMH_NUM_IDENT_AFI,' - ',AMH_PRIM_NOMB_AFI,' ',AMH_SEG_NOMB_AFI,' ',AMH_PRIM_APELL_AFI,' ',AMH_SEG_APELL_AFI) AS AFILIADO "),
                    'AMH_OBSERV_GENER', 'AMH_FECHA_CREACION', 'AMH_USER_CREA', 'AMH_ACCION'
                )
                ->join("ANTICIPOS.EXA_ESTADO_X_ANTICIPOS ","EXA_ID","=","AMH_ANTICIPO_ESTADO_CODIGO")
                ->join("CATALOGOS.RNL_REGIONAL","RNL_ID","=","AMH_REGIONAL_CODIGO")
                ->join("ANTICIPOS.LOE_LISTA_OBSERV_ESTADO","LOE_ID","=","AMH_OBSERV_ESTADO_ANTICIPO_CODIGO")
                ->join("COVIT.EMD_EMPLEADOS_MEDIMAS","EMD_ID","=","AMH_RESP_SOLIC_CODIGO")
                ->join("RED.REDIPS","ipsID","=","AMH_IPS")
                ->join("CATALOGOS.GEO_GEOGRAFIA AS F","F.GEO_ID","=","AMH_SECCIONAL_CODIGO")
                ->join("CATALOGOS.GEO_GEOGRAFIA AS J","J.GEO_ID","=","AMH_MUNICIPIO_CODIGO")
                ->join("CATALOGOS.REG_REGIMEN","REG_ID","=","AMH_REGIMEN_ANTICIPO_CODIGO")
                ->join("ANTICIPOS.OXA_ORIGEN_X_ANTICIPO","OXA_ID","=","AMH_ORIGEN_ANTICIPO_CODIGO")
                ->join("ANTICIPOS.TOJ_TIPO_ORD_JUDICIAL","TOJ_ID","=","AMH_TIPO_ORDEN_JUDIC_CODIGO")
                ->join("ANTICIPOS.MXA_MOTIVO_X_ANTICIPO","MXA_ID","=","AMH_MOTIVO_ANTICIPO_CODIGO")
                ->join("CATALOGOS.DIA_DIAGNOSTICO_CIE10 AS O","O.DIA_ID","=","AMH_DIAGNOSTICO_PRINCI_CODIGO")
                ->join("CATALOGOS.DIA_DIAGNOSTICO_CIE10 As P","P.DIA_ID","=","AMH_DIAGNOSTICO_SECUN_CODIGO")
                ->where('AMH_CONSECUTIVO_ANTICIPO','=', strval($parametro))
                ->orderBy('AMH_ID','DESC')
                ->get();
        return response()->json($conhist,200);    
    }
    
    public function dataAnticipo($parametro){
        $anticipoMedico = [];
        $anticipoDoc = [];
        $autorizaciones = [];
        $objeto = new nuevoController();
       
        $anticipoCab = AHC_ANTICIPO_H_CABECERA::where('AHC_ID',$parametro)->get();
        $anticipoDet = AXD_ANTICIPO_X_DETALLE::where('AXD_AHC_ID',$parametro)->get();
        $consultaDocsAnticipo = AXA_ADJUNTO_X_ANTICIPO::select('AXA_ID', 'AXA_AHC_ID', 'AXA_ID_TXA_ID', 
                            'AXA_NOMBRE', 'AXA_MIME_TIPO', 'AXA_CONFORME', 'AXA_DESCRIPCION', 'AXA_ESTADO', 
                            'AXA_FECHA_CREACION',  'AXA_USER_CREA', 'AXA_USER_CREA')
                        ->where('AXA_AHC_ID',$parametro)
                        ->where('AXA_ESTADO',1)
                        ->get(); 
        $consultaDocsAnticipo = json_decode($consultaDocsAnticipo, true);           
        for ($i=0; $i < count($consultaDocsAnticipo); $i++) {            
           $consultaDocsAnticipo[$i]['EDITAR_ADJUNTO'] = false;                      
        };
       
        foreach ($anticipoDet as $value1) {
            $autorizaciones[] = [
                'TIPODOCUMENTO' => $value1->AXD_TIPO_DOCUMENTO,
                'NUMDOCUMENTO' => $value1->AXD_NUMERO_DOCUMENTO,
                'PRIMERNOMBRE' => $value1->AXD_PRIMER_NOMBRE,
                'SEGUNDONOMBRE' => $value1->AXD_SEGUNDO_NOMBRE,
                'PRIMERAPELLIDO' => $value1->AXD_PRIMER_APELLIDO,
                'SEGUNDOAPELLIDO' => $value1->AXD_SEGUNDO_APELLIDO,
                'CIE10' => $value1->AXD_CODIGO_CIE10,
                'COBERTURA' => $value1->AXD_COBERTURA,
                'CODIGO_CUMS_CUPS' => $value1->AXD_CODIGO_CUPS_CUMS,
                'PRODUCTO' => $value1->AXD_PRODUCTO,
                'CANTIDAD' =>  $value1->AXD_CANTIDAD_AUTORIZADA,
                'NUMAUTORIZACION' =>  $value1->AXD_NUMERO_AUTORIZACION,
                'VLRAUTORIZACION' =>  $value1->AXD_VALOR_SERVICIO,
                'CODIGOTECNOLOGIA' =>  $value1->AXD_COD_HEON,
                'NOMBRE_COMPLETO' =>  $value1->AXD_PRIMER_NOMBRE.' '.$value1->AXD_SEGUNDO_NOMBRE.' '.$value1->AXD_PRIMER_APELLIDO.' '.$value1->AXD_SEGUNDO_APELLIDO,                
            ];
        }
        
        //consulta responsable        
        $responsable = EMD_EMPLEADOS_MEDIMAS::select('EMD_ID',
                        DB::raw("CONCAT(EMD_NOMBRES,' ',EMD_APELLIDOS,' - ',EMD_CARGO ) as EMD_NOMBRES"))
                        ->where('EMD_ID',$anticipoCab[0]->AHC_RESP_SOLIC_CODIGO)
                        ->get();        
                                      
      /*   $responsabilito['EMD_ID'] = $responsable[0]->EMD_ID;
        $responsabilito['EMD_NOMBRES'] = $responsable[0]->EMD_NOMBRES;    */

        //consulta municipio
        $municipio = GEO_GEOGRAFIA::select('GEO_ID','GEO_MUNICIPIO', 'GEO_REGIONAL')
                        ->where('GEO_ID',$anticipoCab[0]->AHC_MUNICIPIO_CODIGO)
                        ->get();

        //consulta prestador
        $prestador =  REDIPS::select('ipsID', 'ipsRegimen',
                        DB::raw(" CONCAT( ipsNitIPS,' - ',ipsNombre ) AS PRESTADOR  "))
                        ->where('ipsID',$anticipoCab[0]->AHC_IPS)                        
                        ->get();        
                        
        //consulta Afiliado       
        switch ($anticipoCab[0]->AHC_REGIMEN_ANTICIPO_CODIGO) {
            case '1':
                $afiliado = DB::table(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].dbo.AfiCliente A'))
                ->distinct()
                ->select( DB::raw("UPPER(CONCAT( tdoHomologacion2,' - ',cliIden,' - ',cliPrimerNombre,' ',cliSegundoNombre,' ',CliPrimerApellido,' ',CliSegundoApellido )) AS NombreCompleto"),
                        'Razon',
                        'Estado',
                        'tdoHomologacion2 AS TipDocAfiliado',
                        'cliIden AS NumDocAfiliado',
                        'cliPrimerNombre AS PrimerNombre',
                        'cliSegundoNombre AS SegundoNombre',
                        'CliPrimerApellido AS PrimerApellido',
                        'CliSegundoApellido AS SegundoApellido')
                ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].[dbo].[commTipDocumento] B'),'B.tdoIDTipoDocumento','=','A.cliTipIdenTT')
                ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].dbo.AfiAfiliadoEPS  C'),'C.afiIdCliente','=','A.cliIdCliente')
                ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].[dbo].[vwEstadosRazonAfiliados] D'),function($join){
                        $join->on('D.Razon','=','C.afiEstRazonTT');
                        $join->on('D.Estado','=','C.afiEstAfiliacionTT');
                })                      
                ->where('cliIden',$anticipoCab[0]->AHC_NUM_IDENT_AFI)
                ->get();
            break;
            case '2':
                $afiliado =  DB::table(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].dbo.AfiCliente A'))
                    ->distinct()
                    ->select( DB::raw("UPPER(CONCAT( tdoHomologacion2,' - ',cliIden,' - ',cliPrimerNombre,' ',cliSegundoNombre,' ',CliPrimerApellido,' ',CliSegundoApellido )) AS NombreCompleto"),
                            'Razon',
                            'Estado',
                            'tdoHomologacion2 AS TipDocAfiliado',
                            'cliIden AS NumDocAfiliado',
                            'cliPrimerNombre AS PrimerNombre',
                            'cliSegundoNombre AS SegundoNombre',
                            'CliPrimerApellido AS PrimerApellido',
                            'CliSegundoApellido AS SegundoApellido')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].[dbo].[commTipDocumento] B'),'B.tdoIDTipoDocumento','=','A.cliTipIdenTT')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].dbo.AfiAfiliadoEPS  C'),'C.afiIdCliente','=','A.cliIdCliente')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].[dbo].[vwEstadosRazonAfiliados] D'),function($join){
                            $join->on('D.Razon','=','C.afiEstRazonTT');
                            $join->on('D.Estado','=','C.afiEstAfiliacionTT');
                    })                      
                    ->where('cliIden',$anticipoCab[0]->AHC_NUM_IDENT_AFI)
                    ->get();           
            break;
            case '3':
                $resplistb = DB::table(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].dbo.AfiCliente A'))
                    ->distinct()
                    ->select( DB::raw("UPPER(CONCAT( tdoHomologacion2,' - ',cliIden,' - ',cliPrimerNombre,' ',cliSegundoNombre,' ',CliPrimerApellido,' ',CliSegundoApellido )) AS NombreCompleto"),
                            'Razon',
                            'Estado',
                            'tdoHomologacion2 AS TipDocAfiliado',
                            'cliIden AS NumDocAfiliado',
                            'cliPrimerNombre AS PrimerNombre',
                            'cliSegundoNombre AS SegundoNombre',
                            'CliPrimerApellido AS PrimerApellido',
                            'CliSegundoApellido AS SegundoApellido')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].[dbo].[commTipDocumento] B'),'B.tdoIDTipoDocumento','=','A.cliTipIdenTT')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].dbo.AfiAfiliadoEPS  C'),'C.afiIdCliente','=','A.cliIdCliente')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCECON].[dbo].[vwEstadosRazonAfiliados] D'),function($join){
                            $join->on('D.Razon','=','C.afiEstRazonTT');
                            $join->on('D.Estado','=','C.afiEstAfiliacionTT');
                    })                      
                    ->where('cliIden',$anticipoCab[0]->AHC_NUM_IDENT_AFI);        
                $afiliado =  DB::table(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].dbo.AfiCliente A'))
                    ->distinct()
                    ->select( DB::raw("UPPER(CONCAT( tdoHomologacion2,' - ',cliIden,' - ',cliPrimerNombre,' ',cliSegundoNombre,' ',CliPrimerApellido,' ',CliSegundoApellido )) AS NombreCompleto"),
                            'Razon',
                            'Estado',
                            'tdoHomologacion2 AS TipDocAfiliado',
                            'cliIden AS NumDocAfiliado',
                            'cliPrimerNombre AS PrimerNombre',
                            'cliSegundoNombre AS SegundoNombre',
                            'CliPrimerApellido AS PrimerApellido',
                            'CliSegundoApellido AS SegundoApellido')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].[dbo].[commTipDocumento] B'),'B.tdoIDTipoDocumento','=','A.cliTipIdenTT')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].dbo.AfiAfiliadoEPS  C'),'C.afiIdCliente','=','A.cliIdCliente')
                    ->join(DB::raw('[10.99.1.37\REP_MEDIMAS].[HEONASSURANCESUB].[dbo].[vwEstadosRazonAfiliados] D'),function($join){
                            $join->on('D.Razon','=','C.afiEstRazonTT');
                            $join->on('D.Estado','=','C.afiEstAfiliacionTT');
                    })                      
                    ->where('cliIden',$anticipoCab[0]->AHC_NUM_IDENT_AFI)
                    ->union($resplistb)                      
                    ->get();    
                break;                   
            break;            
        }
        //consulta diganostico
        $diagnostico1 = self::consultaDiagnosticos($anticipoCab[0]->AHC_DIAGNOSTICO_PRINCI_CODIGO);
        $diagnostico2 = self::consultaDiagnosticos($anticipoCab[0]->AHC_DIAGNOSTICO_SECUN_CODIGO);

        ///consulta de lista de docuemntos vs cargados anticipo
        $docVslista = TXA_TIPO_X_ADJUNTO::select( 'TXA_ID', 'TXA_DESCRIPCION'
                                ,'TXA_REQUIRED',  'AXA_ID'
                        )
                        ->join('ANTICIPOS.AXA_ADJUNTO_X_ANTICIPO','AXA_ID_TXA_ID','=','TXA_ID')     
                        ->where('AXA_ESTADO',1)
                        ->orWhere('TXA_ESTADO',1)
                        ->get();

        $anticipoMedico = [               
            'ANTICIPO_CABECERA' => $anticipoCab,
            'ANTICIPO_DETALLE' => $autorizaciones,
            'ANTICIPO_ADJUNTOS' => $consultaDocsAnticipo,
            'ANTICIPO_RESPONSABLE' => $responsable[0],
            'ANTICIPO_MUNICIPIO' => $municipio[0],
            'ANTICIPO_PRESTADOR' => $prestador[0],
            'ANTICIPO_AFILIADO' => $afiliado[0],
            'ANTICIPO_DIAGPRINC' => $diagnostico1[0],
            'ANTICIPO_DIAGSECND' => $diagnostico2[0],
            'ANTICIPO_LISTADOCVSADJUNTOS' => $docVslista,
     
        ]; 
        return response()->json($anticipoMedico,200);   
    }

    public function consultaDiagnosticos($id = null){
        $resplist = array();
        $resplist = DIA_DIAGNOSTICO_CIE10::select('DIA_ID',
                    DB::raw(" CONCAT( DIA_CODIGO_CIE10,' - ',DIA_DESCRIPCION ) AS DIAGNOSTICO  "))
                    ->where('DIA_ID',$id)                    
                    ->get();
        return $resplist; 
    }

    public function actualizaAnticipo(Request $request){
        //dd($request);
        $actcabecera = array();
        $respuesta = [];        
        $responsable = json_decode($request->responsable);                
        
        $auto = json_decode($request->autorizaciones);
        $municipio = json_decode($request->municipio);
        $prestador = json_decode($request->prestador);        
        $diag_first = json_decode($request->diag_first);
        $diag_second = json_decode($request->diag_second); 
        $afilia = json_decode($request->afiliados);
        $documentos = json_decode($request->adjuntos);        
        $files = $request->file();
        //dd($responsable);
        //dd($documentos);
        //dd($files);

        //actualizamos cabezera    
        $actcabecera = AHC_ANTICIPO_H_CABECERA::where('AHC_ID',$request->id_anticipo)->first();
        if($actcabecera){            
            $actcabecera->AHC_ANTICIPO_ESTADO_CODIGO = $request->estaAnticipo;
                $actcabecera->AHC_OBSERV_ESTADO_ANTICIPO_CODIGO = $request->obsEstado;
                $actcabecera->AHC_RESP_SOLIC_CODIGO = $responsable->EMD_ID;
                $actcabecera->AHC_IPS = $prestador->ipsID;
                $actcabecera->AHC_REGIONAL_CODIGO = $request->regSolicita;
                $actcabecera->AHC_SECCIONAL_CODIGO = $request->seccional;
                $actcabecera->AHC_MUNICIPIO_CODIGO = $municipio->GEO_ID;
                $actcabecera->AHC_REGIMEN_ANTICIPO_CODIGO = $request->regimen;
                $actcabecera->AHC_ORIGEN_ANTICIPO_CODIGO = $request->origAnticipo;
                $actcabecera->AHC_TIPO_ORDEN_JUDIC_CODIGO = $request->tipOrdJud;
                $actcabecera->AHC_MOTIVO_ANTICIPO_CODIGO = $request->motAnticipo;
                $actcabecera->AHC_VALOR_CRUCE = $request->valorCruce;
                $actcabecera->AHC_VALOR_BRUTO_PAG = $request->valorBruto;
                $actcabecera->AHC_VALOR_TOTAL_COTIZ = $request->valorTotalCoti;
                $actcabecera->AHC_SALDO_LEGALIZAR_PREST = $request->saldoLegalizar;
                $actcabecera->AHC_NUMERO_MIPRES = $request->numMipres;
                $actcabecera->AHC_DIAGNOSTICO_PRINCI_CODIGO = $diag_first->DIA_ID;
                $actcabecera->AHC_DIAGNOSTICO_SECUN_CODIGO = $diag_second->DIA_ID;
                $actcabecera->AHC_NUMERO_AUTORIZA = $request->numAutorizacion;
                $actcabecera->AHC_OBSERV_GENER = $request->ObserGen;
                $actcabecera->AHC_TIPO_IDENT_AFI = $afilia->TipDocAfiliado ;
                $actcabecera->AHC_NUM_IDENT_AFI = $afilia->NumDocAfiliado ;
                $actcabecera->AHC_PRIM_NOMB_AFI = $afilia->PrimerNombre ;
                $actcabecera->AHC_SEG_NOMB_AFI = $afilia->SegundoNombre ;
                $actcabecera->AHC_PRIM_APELL_AFI = $afilia->PrimerApellido ;
                $actcabecera->AHC_SEG_APELL_AFI = $afilia->SegundoApellido ;
                $actcabecera->AHC_USER_MODIFICA = Auth::user()->USU_USERNAME; 
            $actcabecera->save();     
        } 
        
        ///actualizamos detalle
        AXD_ANTICIPO_X_DETALLE::where('AXD_AHC_ID',$request->id_anticipo)->delete();
        foreach ($auto as $key => $value) {
            AXD_ANTICIPO_X_DETALLE::create([      
                'AXD_AHC_ID' =>  $request->id_anticipo,
                'AXD_TIPO_DOCUMENTO' => $value->TIPODOCUMENTO,
                'AXD_NUMERO_DOCUMENTO' => $value->NUMDOCUMENTO,
                'AXD_PRIMER_NOMBRE' => $value->PRIMERNOMBRE,
                'AXD_SEGUNDO_NOMBRE' => $value->SEGUNDONOMBRE,
                'AXD_PRIMER_APELLIDO' => $value->PRIMERAPELLIDO,
                'AXD_SEGUNDO_APELLIDO' => $value->SEGUNDOAPELLIDO,
                'AXD_CODIGO_CIE10' => $value->CIE10,
                'AXD_COBERTURA' => $value->COBERTURA,
                'AXD_CODIGO_CUPS_CUMS' => $value->CODIGO_CUMS_CUPS,
                'AXD_PRODUCTO' => $value->PRODUCTO,
                'AXD_CANTIDAD_AUTORIZADA' => $value->CANTIDAD,
                'AXD_NUMERO_AUTORIZACION' => $request->numAutorizacion,
                'AXD_VALOR_SERVICIO' => $value->VLRAUTORIZACION,
                'AXD_COD_HEON' =>  $value->CODIGOTECNOLOGIA,
                'AXD_ESTADO' => '1',                                
                'AXD_USER_CREA' => Auth::user()->USU_USERNAME,
            ]);    
        }   
        // actualizamos adjuntos    
        foreach ($documentos as $key => $docs) {              
            $conform = "conforme_".$docs->TXA_ID; 
            $observ = "observ_".$docs->TXA_ID;            
            
            $consultarAdjuntoAnticpio = AXA_ADJUNTO_X_ANTICIPO::where('AXA_AHC_ID',$request->id_anticipo)
                            ->where('AXA_ID_TXA_ID',$docs->TXA_ID)                                    
                            ->first();                                                  
            if($consultarAdjuntoAnticpio){                
                //actualiza
                if(array_key_exists($docs->TXA_ID,$files)){
                    $consultarAdjuntoAnticpio->AXA_NOMBRE = $files[$docs->TXA_ID]->getClientOriginalName();
                    $consultarAdjuntoAnticpio->AXA_MIME_TIPO = $files[$docs->TXA_ID]->getClientMimeType();
                    $consultarAdjuntoAnticpio->AXA_ADJUNTO = base64_encode(file_get_contents($files[$docs->TXA_ID]));
                }
                $consultarAdjuntoAnticpio->AXA_CONFORME = $request->$conform;
                $consultarAdjuntoAnticpio->AXA_DESCRIPCION = $request->$observ;
                $consultarAdjuntoAnticpio->AXA_USER_MODIFICA = Auth::user()->USU_USERNAME;
                $consultarAdjuntoAnticpio->save(); 
            }else{                                            
                //inserta
                  AXA_ADJUNTO_X_ANTICIPO::create([      
                    'AXA_AHC_ID' =>  $request->id_anticipo,
                    'AXA_ID_TXA_ID' => $docs->TXA_ID,
                    'AXA_NOMBRE' => $files[$docs->TXA_ID]->getClientOriginalName(),                    
                    'AXA_MIME_TIPO' => $files[$docs->TXA_ID]->getClientMimeType(),
                    'AXA_ADJUNTO' => base64_encode(file_get_contents($files[$docs->TXA_ID])), 
                    'AXA_CONFORME' => $request->$conform,
                    'AXA_DESCRIPCION' => $request->$observ,
                    'AXA_ESTADO' => '1',                
                    'AXA_USER_CREA' => Auth::user()->USU_USERNAME, 
                ]);         
            }                            

        } 
        return response()->json($request->id_anticipo,200);          
    }

    public function getDocuments($parametro){                
        $AdjuntoAnticpio = AXA_ADJUNTO_X_ANTICIPO::select('AXA_ID',                                                        
                            'AXA_NOMBRE',                            
                            'AXA_FECHA_CREACION',                          
                        )
                    ->where('AXA_AHC_ID',$parametro)
                    ->where('AXA_ESTADO',1)                        
                    ->get();         
        return $AdjuntoAnticpio;
    }

    public function showDoc($parametro){
      
         $base64 = AXA_ADJUNTO_X_ANTICIPO::select('AXA_ID',                                                        
                    'AXA_NOMBRE',                            
                    'AXA_MIME_TIPO',                          
                    'AXA_ADJUNTO',                          
                )
                ->where('AXA_ID',$parametro)
                ->where('AXA_ESTADO',1)  
                ->first();
        if($base64){
            $response = \Response::make(base64_decode($base64->AXA_ADJUNTO,true), 200);
            $response->header('Content-Type', $base64->AXA_MIME_TIPO);
            $response->header('Content-disposition','inline; filename="'.$base64->AXA_NOMBRE.'"');
            return $response;   
        } 
        return abort(404);    
    }


 
































}
