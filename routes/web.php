<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::middleware(['auth','accesosusuario'])->group(function (){

    

    // Modulo administrador de usuarios
    Route::resource('/administrar/usuarios', 'Auth\AdminUsersController');
    Route::put('/administrar/usuarios/{username}/{iduser}', 'Auth\AdminUsersController@update');

    // Modulo administrativo de roles
    Route::resource('/administrar/roles', 'Auth\AdminRolesController');    

    ///admistracion sedes
    Route::resource('/administrativo/sedes','administrativo\sedesController');
    Route::get('/administrativo/consultarMunicipios/{parametro}','administrativo\sedesController@consultarMunicipios');
    Route::post('/administrativo/guardaSede','administrativo\sedesController@guardaSede');
    Route::post('/administrativo/listaSedes','administrativo\sedesController@consultaSedes');
    Route::get('/administrativo/detalleSede/{parametro}','administrativo\sedesController@dataSede');

    ///admistracion terceros
    Route::resource('/administrativo/terceros','administrativo\tercerosController');
    Route::post('/administrativo/guardaTercero','administrativo\tercerosController@saveTercero');
    Route::get('/administrativo/detalleTercero/{parametro}','administrativo\tercerosController@dataTercero');
    Route::post('/administrativo/listaTerceros','administrativo\tercerosController@consultaTerceros');
    
    //admistracion Periodos
    Route::resource('/administrativo/periodos','administrativo\periodosController');
    Route::post('/administrativo/listaPeriodos','administrativo\periodosController@consultaPeriodos');
    Route::get('/administrativo/accionEstado/{id}/{cierre}','administrativo\periodosController@actualizaEstado');


    //Modulo Obligaciones
    Route::resource('/obligaciones/nueva_obligacion','Obligaciones\obligacionesController');
    Route::get('/obligaciones/searchSedes/{parametro}','Obligaciones\obligacionesController@consultarSedes');
    Route::get('/obligaciones/searchObligaciones/{sede}/{periodo}/{categoria}','Obligaciones\obligacionesController@consultarObligaciones');
    Route::get('/obligaciones/searchTercero/{parametro}','Obligaciones\obligacionesController@consultarTerceros');
    Route::post('/obligaciones/guardaObligaciones','Obligaciones\obligacionesController@saveObligaciones');

    //Modulo Visor Obligaciones
    Route::resource('/obligaciones/visor_obligaciones','Obligaciones\visorobligacionesController');


    //Modulo anticipos 
    Route::resource('/anticipos/nuevo','Anticipos\nuevoController');
    Route::get('/anticipos/consultarResponsable/{parametro}','Anticipos\nuevoController@consultarResponsable');
    
    Route::get('/anticipos/consultarPrestadores/{parametro}','Anticipos\nuevoController@consultarPrestadores');
    Route::get('/anticipos/consultarDiagnosticos/{parametro}','Anticipos\nuevoController@consultarDiagnosticos');
    Route::get('/anticipos/consultarAfiliado/{parametro}','Anticipos\nuevoController@consultarAfiliado');
    Route::post('/anticipos/consultarAutorizacion','Anticipos\nuevoController@consultarAutorizacion');
    Route::post('/anticipos/guardaAnticipo','Anticipos\nuevoController@guardaAnticipos');

    //Modulo visor anticipos
    Route::resource('/anticipos/visor_medico','Anticipos\visor_medicoController');
    Route::post('/anticipos/consultaVisorAnticipo','Anticipos\visor_medicoController@visorAnticipos');
    Route::get('/anticipos/ConsultarHistorial/{parametro}','Anticipos\visor_medicoController@consultarHistorico');
    Route::get('/anticipos/dataAnticipo/{parametro}','Anticipos\visor_medicoController@dataAnticipo');
    Route::get('/anticipos/getDocuments/{parametro}','Anticipos\visor_medicoController@getDocuments');
    Route::get('/anticipos/verSoporte/{parametro}','Anticipos\visor_medicoController@showDoc');
    Route::post('/anticipos/actualizaAnticipo','Anticipos\visor_medicoController@actualizaAnticipo');
    
    

    //Modulo visor Recobros
    Route::resource('/recobros/visor','Recobros\visorController');
    Route::post('/recobros/visor-autorizacion','Recobros\visorController@busqAutorizacion');
    Route::post('/recobros/visor-guardaRegRecobro','Recobros\visorController@guardaRecobro');
    Route::post('/recobros/visor-consultaTecnologias','Recobros\visorController@consultaTecnologias');
    Route::get('/recobros/visor-verDocumentos/{idRuta}','Recobros\visorController@verDocs');

    //Modulo visor Contraloria
    Route::resource('/contraloria/visor','Contraloria\visorController');
    Route::post('/contraloria/visor-autorizacion','Contraloria\visorController@busqAutorizacion');
    Route::post('/contraloria/visor-afiliado','Contraloria\visorController@busqAfiliado');
    Route::post('/contraloria/visor-guardaRegRecobro','Contraloria\visorController@guardaRecobro');
    Route::post('/contraloria/visor-consultaTecnologias','Contraloria\visorController@consultaTecnologias');
    Route::get('/contraloria/visor-verDocumentos/{idRuta}','Contraloria\visorController@verDocs');

});

