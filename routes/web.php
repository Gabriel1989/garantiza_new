<?php

use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'InicioController@index')->middleware('auth');



Route::group(['prefix' => 'administrador', 'middleware' => ['auth', 'admin']], function () {

    // Concesionarias
    Route::get('concesionaria/index', 'ConcesionariaController@index')->name('concesionaria.index');
    Route::get('concesionaria/create', 'ConcesionariaController@create')->name('concesionaria.create');
    Route::post('concesionaria', 'ConcesionariaController@store')->name('concesionaria.store');
    Route::get('concesionaria/{id}/edit', 'ConcesionariaController@edit')->name('concesionaria.edit');
    Route::put('concesionaria/{id}', 'ConcesionariaController@update')->name('concesionaria.update');
    Route::delete('concesionaria/{id}', 'ConcesionariaController@destroy')->name('concesionaria.destroy');

    // Sucursales
    Route::get('sucursal/index', 'SucursalController@index')->name('sucursal.index');
    Route::get('sucursal/create', 'SucursalController@create')->name('sucursal.create');
    Route::post('sucursal', 'SucursalController@store')->name('sucursal.store');
    Route::get('sucursal/{id}/edit', 'SucursalController@edit')->name('sucursal.edit');
    Route::put('sucursal/{id}', 'SucursalController@update')->name('sucursal.update');
    Route::delete('sucursal/{id}', 'SucursalController@destroy')->name('sucursal.destroy');

    // Estados
    Route::get('estado/index', 'EstadoController@index')->name('estado.index');
    Route::get('estado/create', 'EstadoController@create')->name('estado.create');
    Route::post('estado', 'EstadoController@store')->name('estado.store');
    Route::get('estado/{id}/edit', 'EstadoController@edit')->name('estado.edit');
    Route::put('estado/{id}', 'EstadoController@update')->name('estado.update');
    Route::delete('estado/{id}', 'EstadoController@destroy')->name('estado.destroy');

    // Tipo de Vehiculos
    Route::get('tipo_vehiculo/index', 'TipoVehiculoController@index')->name('tipo_vehiculo.index');
    Route::get('tipo_vehiculo/create', 'TipoVehiculoController@create')->name('tipo_vehiculo.create');
    Route::post('tipo_vehiculo', 'TipoVehiculoController@store')->name('tipo_vehiculo.store');
    Route::get('tipo_vehiculo/{id}/edit', 'TipoVehiculoController@edit')->name('tipo_vehiculo.edit');
    Route::put('tipo_vehiculo/{id}', 'TipoVehiculoController@update')->name('tipo_vehiculo.update');
    Route::delete('tipo_vehiculo/{id}', 'TipoVehiculoController@destroy')->name('tipo_vehiculo.destroy');

    // Tipo de Tramite
    Route::get('tipo_tramite/index', 'TipotramiteController@index')->name('tipo_tramite.index');
    Route::get('tipo_tramite/create', 'TipotramiteController@create')->name('tipo_tramite.create');
    Route::post('tipo_tramite', 'TipotramiteController@store')->name('tipo_tramite.store');
    Route::get('tipo_tramite/{id}/edit', 'TipotramiteController@edit')->name('tipo_tramite.edit');
    Route::put('tipo_tramite/{id}', 'TipotramiteController@update')->name('tipo_tramite.update');
    Route::delete('tipo_tramite/{id}', 'TipotramiteController@destroy')->name('tipo_tramite.destroy');

    // Tipo de Documentos
    Route::get('tipo_documento/index', 'TipoDocumentoController@index')->name('tipo_documento.index');
    Route::get('tipo_documento/create', 'TipoDocumentoController@create')->name('tipo_documento.create');
    Route::post('tipo_documento', 'TipoDocumentoController@store')->name('tipo_documento.store');
    Route::get('tipo_documento/{id}/edit', 'TipoDocumentoController@edit')->name('tipo_documento.edit');
    Route::put('tipo_documento/{id}', 'TipoDocumentoController@update')->name('tipo_documento.update');
    Route::delete('tipo_documento/{id}', 'TipoDocumentoController@destroy')->name('tipo_documento.destroy');


    //Acreedores

    Route::get('acreedor/index', 'AcreedorController@index')->name('acreedor.index');
    Route::get('acreedor/create', 'AcreedorController@create')->name('acreedor.create');
    Route::post('acreedor', 'AcreedorController@store')->name('acreedor.store');
    Route::get('acreedor/{id}/edit', 'AcreedorController@edit')->name('acreedor.edit');
    Route::put('acreedor/{id}', 'AcreedorController@update')->name('acreedor.update');
    Route::delete('acreedor/{id}', 'AcreedorController@destroy')->name('acreedor.destroy');



    // Usuarios
    Route::get('usuario/index', 'UserController@index')->name('usuario.index');
    Route::get('usuario/create', 'UserController@create')->name('usuario.create');
    Route::post('usuario', 'UserController@store')->name('usuario.store');
    Route::get('usuario/{id}/password', 'UserController@password')->name('usuario.password');
    Route::get('usuario/{id}/edit', 'UserController@edit')->name('usuario.edit');
    Route::put('usuario/{id}', 'UserController@update')->name('usuario.update');
    Route::delete('usuario/{id}', 'UserController@destroy')->name('usuario.destroy');
});



Route::group(['middleware' => ['auth', 'ejecut.conces']], function () {

    // Crear Solicitud (Paso 1)

    Route::get('solicitud/solicitarPPU', 'SolicitudController@solicitaPPU')->name('solicitud.solicitarPPU');
    Route::post('solicitud/consultaPPU','SolicitudController@consultaPPU')->name('solicitud.consultaPPU');

    Route::get('solicitud/create', 'SolicitudController@create')->name('solicitud.create');
    Route::post('solicitud', 'SolicitudController@store')->name('solicitud.store');
    Route::get('solicitud/sinTerminar', 'SolicitudController@sinTerminar')->name('solicitud.sinTerminar');
    Route::get('solicitud/{id}/adquirientes', 'SolicitudController@adquirientes')->name('solicitud.adquirientes');
    Route::post('solicitud/{id}/saveAdquirientes', 'SolicitudController@saveAdquirientes')->name('solicitud.saveAdquirientes');
    Route::get('solicitud/{id}/compraPara', 'SolicitudController@compraPara')->name('solicitud.compraPara');
    Route::post('solicitud/{id}/saveCompraPara', 'SolicitudController@saveCompraPara')->name('solicitud.saveCompraPara');
    Route::get('solicitud/{id}/datosMoto', 'SolicitudController@datosMoto')->name('solicitud.datosMoto');
    Route::get('solicitud/{id}/datosAuto', 'SolicitudController@datosAuto')->name('solicitud.datosAuto');
    Route::get('solicitud/{id}/datosCamion', 'SolicitudController@datosCamion')->name('solicitud.datosCamion');
    Route::put('solicitud/{id}/saveDatosMoto', 'SolicitudController@saveDatosMoto')->name('solicitud.saveDatosMoto');

    Route::delete('solicitud/delete/{id}', 'SolicitudController@destroy')->name('solicitud.destroy');
    Route::get('solicitud/continuar/{id}','SolicitudController@continuarSolicitud')->name('solicitud.continuar');

    Route::get('solicitud/{id}/revision/cedula', 'SolicitudController@RevisionCedula')->name('solicitud.revision.cedula');
    Route::post('solicitud/{id}/updateRevisionFacturaMoto', 'SolicitudController@updateRevisionFacturaMoto')->name('solicitud.updateRevisionFacturaMoto');
    Route::post('solicitud/{id}/updateRevisionFacturaAuto', 'SolicitudController@updateRevisionFacturaAuto')->name('solicitud.updateRevisionFacturaAuto');
    Route::post('solicitud/{id}/updateRevisionFacturaCamion','SolicitudController@updateRevisionFacturaCamion')->name('solicitud.updateRevisionFacturaCamion');
    
    //Crear limitación o prohibición para vehículo

    Route::post('solicitud/{id}/limitacion/form','LimitacionController@ingresarLimitacionForm')->name('solicitud.limitacion.form');
    Route::post('solicitud/{id}/limitacion/new','LimitacionController@ingresaLimitacion')->name('solicitud.limitacion.new');

    Route::get('solicitud/{id}/show', 'SolicitudController@show')->name('solicitud.show');
    Route::get('solicitud/verSolicitudes', 'SolicitudController@verSolicitudes')->name('solicitud.verSolicitudes');
    Route::post('solicitud/{id}/verEstadoSolicitud','SolicitudController@verEstado')->name('solicitud.estadoSolicitud');

    // Documentos
    Route::get('documento/{id}/create', 'DocumentoController@create')->name('documento.create');
    Route::post('documento', 'DocumentoController@store')->name('documento.store');

    Route::post('documento/{id}/cargadocs', 'DocumentoController@CargaDocumentos')->name('documento.cargadocs.rc');
});

Route::group(['middleware' => ['auth', 'ejecut.garantiza']], function () {
    // Solicitudes
    Route::get('solicitud/index', 'SolicitudController@index')->name('solicitud.index');
    Route::get('solicitud/revision', 'SolicitudController@revision')->name('solicitud.revision');

    // Revisión de Solicitudes
    //Route::get('solicitud/{id}/revision/cedula', 'SolicitudController@RevisionCedula')->name('solicitud.revision.cedula');
    Route::put('solicitud/{id}/updateRevisionCedula', 'SolicitudController@updateRevisionCedula')->name('solicitud.updateRevisionCedula');

    Route::get('solicitud/{id}/revision/rol', 'SolicitudController@RevisionRol')->name('solicitud.revision.rol');
    Route::put('solicitud/{id}/updateRevisionRol', 'SolicitudController@updateRevisionRol')->name('solicitud.updateRevisionRol');

    Route::get('solicitud/{id}/revision/paras', 'SolicitudController@RevisionParas')->name('solicitud.revision.paras');
    Route::put('solicitud/{id}/updateRevisionParas', 'SolicitudController@updateRevisionParas')->name('solicitud.updateRevisionParas');

    Route::get('solicitud/{id}/revision/PPU', 'SolicitudController@RevisionPPU')->name('solicitud.revision.PPU');
    Route::put('solicitud/{id}/updateRevisionPPU', 'SolicitudController@updateRevisionPPU')->name('solicitud.updateRevisionPPU');

    Route::get('solicitud/{id}/revision/entradaRC', 'SolicitudController@RevisionEntradaRC')->name('solicitud.revision.entradaRC');
    Route::put('solicitud/{id}/updateRevisionEntradaRC', 'SolicitudController@updateRevisionEntradaRC')->name('solicitud.updateRevisionEntradaRC');

    Route::get('solicitud/{id}/{PPU}/revision/facturaMoto', 'SolicitudController@RevisionFacturaMoto')->name('solicitud.revision.facturaMoto');
    //Route::put('solicitud/{id}/updateRevisionFacturaMoto', 'SolicitudController@updateRevisionFacturaMoto')->name('solicitud.updateRevisionFacturaMoto');

    Route::get('solicitud/{id}/ver', 'SolicitudController@ver')->name('solicitud.ver');
    Route::get('solicitud/{id}/aprobacion', 'SolicitudController@aprobacion')->name('solicitud.aprobacion');
    Route::get('solicitud/{id}/edit', 'SolicitudController@edit')->name('solicitud.edit');
    Route::put('solicitud/{id}', 'SolicitudController@update')->name('solicitud.update');
    Route::delete('solicitud/{id}', 'SolicitudController@destroy')->name('solicitud.destroy');
});


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/solicitud/{id}/generaJson', 'SolicitudController@generaJson')->name('solicitud.generaJson');