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

    //Roles

    Route::get('rol/index', 'RolController@index')->name('rol.index');
    Route::get('rol/create', 'RolController@create')->name('rol.create');
    Route::post('rol', 'RolController@store')->name('rol.store');
    Route::get('rol/{id}/edit', 'RolController@edit')->name('rol.edit');
    Route::put('rol/{id}', 'RolController@update')->name('rol.update');
    Route::delete('rol/{id}', 'RolController@destroy')->name('rol.destroy');


    //Rechazos

    Route::get('rechazo/index', 'RechazoController@index')->name('rechazo.index');
    Route::get('rechazo/create', 'RechazoController@create')->name('rechazo.create');
    Route::post('rechazo', 'RechazoController@store')->name('rechazo.store');
    Route::get('rechazo/{id}/edit', 'RechazoController@edit')->name('rechazo.edit');
    Route::put('rechazo/{id}', 'RechazoController@update')->name('rechazo.update');
    Route::delete('rechazo/{id}', 'RechazoController@destroy')->name('rechazo.destroy');

    // Usuarios
    Route::get('usuario/index', 'UserController@index')->name('usuario.index');
    Route::get('usuario/create', 'UserController@create')->name('usuario.create');
    Route::post('usuario', 'UserController@store')->name('usuario.store');
    Route::get('usuario/{id}/password', 'UserController@password')->name('usuario.password');
    Route::get('usuario/{id}/edit', 'UserController@edit')->name('usuario.edit');
    Route::put('usuario/{id}', 'UserController@update')->name('usuario.update');
    Route::delete('usuario/{id}', 'UserController@destroy')->name('usuario.destroy');

    //Notarias
    Route::get('notaria/index', 'NotariaController@index')->name('notaria.index');
    Route::get('notaria/create', 'NotariaController@create')->name('notaria.create');
    Route::post('notaria', 'NotariaController@store')->name('notaria.store');
    Route::get('notaria/{id}/edit', 'NotariaController@edit')->name('notaria.edit');
    Route::put('notaria/{id}', 'NotariaController@update')->name('notaria.update');
    Route::delete('notaria/{id}', 'NotariaController@destroy')->name('notaria.destroy');

    //Revisión
    Route::get('solicitud/{id}/revision/cedula', 'SolicitudController@RevisionCedula')->name('solicitud.revision.cedula');
    Route::put('solicitud/{id}/updateRevisionCedula', 'SolicitudController@updateRevisionCedula')->name('solicitud.updateRevisionCedula');
    Route::get('solicitud/continuar/{id}/{reingresa?}/{acceso?}','SolicitudController@continuarSolicitud')->name('solicitud.continuar');

    Route::post('solicitud', 'SolicitudController@store')->name('solicitud.store');
    Route::get('solicitud/{id}/adquirientes', 'SolicitudController@adquirientes')->name('solicitud.adquirientes');
    Route::post('solicitud/{id}/saveAdquirientes', 'SolicitudController@saveAdquirientes')->name('solicitud.saveAdquirientes');
    Route::get('solicitud/{id}/compraPara', 'SolicitudController@compraPara')->name('solicitud.compraPara');
    Route::post('solicitud/{id}/saveCompraPara', 'SolicitudController@saveCompraPara')->name('solicitud.saveCompraPara');
    Route::get('solicitud/{id}/datosMoto', 'SolicitudController@datosMoto')->name('solicitud.datosMoto');
    Route::get('solicitud/{id}/datosAuto', 'SolicitudController@datosAuto')->name('solicitud.datosAuto');
    Route::get('solicitud/{id}/datosCamion', 'SolicitudController@datosCamion')->name('solicitud.datosCamion');
    Route::put('solicitud/{id}/saveDatosMoto', 'SolicitudController@saveDatosMoto')->name('solicitud.saveDatosMoto');
    Route::post('solicitud/{id}/updateRevisionFacturaMoto/revision', 'SolicitudController@updateRevisionFacturaMoto')->name('solicitud.updateRevisionFacturaMoto');
    Route::post('solicitud/{id}/updateRevisionFacturaAuto/revision', 'SolicitudController@updateRevisionFacturaAuto')->name('solicitud.updateRevisionFacturaAuto');
    Route::post('solicitud/{id}/updateRevisionFacturaCamion/revision','SolicitudController@updateRevisionFacturaCamion')->name('solicitud.updateRevisionFacturaCamion');

    //Crear limitación o prohibición para vehículo

    Route::post('solicitud/{id}/limitacion/form','LimitacionController@ingresarLimitacionForm')->name('solicitud.limitacion.form');
    Route::post('solicitud/{id}/limitacion/new/revision','LimitacionController@ingresaLimitacion')->name('solicitud.limitacion.new');
    //Route::post('solicitud/{id}/limitacion/verEstadoSolicitud','LimitacionController@verEstado')->name('solicitud.limitacion.estadoSolicitud');
    //Generar comprobante RVM
    //Route::post('solicitud/{id}/descargaComprobanteRVM','SolicitudController@descargaComprobanteRVM')->name('solicitud.descargaComprobanteRVM');

    //Documentos
    Route::post('documento/destroy/revision', 'DocumentoController@destroy')->name('documento.destroy.revision');

    //Registrar Pago
    Route::post('/documento/{id}/cargapago', 'SolicitudController@registrarPago')->name('pago.registrar.revision');

    //Registrar Pago STEV
    Route::post('/documento/transferencia/{id}/cargapago', 'TransferenciaController@registrarPago')->name('pago.transferencia.registrar.revision');
});



Route::group(['middleware' => ['auth', 'ejecut.conces']], function () {

    // Crear Solicitud (Paso 1)

    Route::get('solicitud/solicitarPPU', 'SolicitudController@solicitaPPU')->name('solicitud.solicitarPPU');
    Route::post('solicitud/consultaPPU','SolicitudController@consultaPPU')->name('solicitud.consultaPPU');

    Route::get('solicitud/create', 'SolicitudController@create')->name('solicitud.create');
    Route::post('solicitud/storeConces', 'SolicitudController@store')->name('solicitud.storeConces');
    //Route::post('solicitud', 'SolicitudController@store')->name('solicitud.store');
    Route::get('solicitud/sinTerminar', 'SolicitudController@sinTerminar')->name('solicitud.sinTerminar');
    Route::get('solicitud/{id}/adquirientes', 'SolicitudController@adquirientes')->name('solicitud.adquirientes');
    Route::post('solicitud/{id}/saveAdquirientesConces', 'SolicitudController@saveAdquirientes')->name('solicitud.saveAdquirientes');
    Route::get('solicitud/{id}/compraPara', 'SolicitudController@compraPara')->name('solicitud.compraPara');
    Route::post('solicitud/{id}/saveCompraParaConces', 'SolicitudController@saveCompraPara')->name('solicitud.saveCompraPara');
    Route::get('solicitud/{id}/datosMoto', 'SolicitudController@datosMoto')->name('solicitud.datosMoto');
    Route::get('solicitud/{id}/datosAuto', 'SolicitudController@datosAuto')->name('solicitud.datosAuto');
    Route::get('solicitud/{id}/datosCamion', 'SolicitudController@datosCamion')->name('solicitud.datosCamion');
    Route::put('solicitud/{id}/saveDatosMoto', 'SolicitudController@saveDatosMoto')->name('solicitud.saveDatosMoto');

    Route::delete('solicitud/delete/{id}', 'SolicitudController@destroy')->name('solicitud.destroy');
    Route::get('solicitud/continuarSolicitud/{id}/{reingresa?}/{acceso?}','SolicitudController@continuarSolicitud')->name('solicitud.continuarSolicitud');

    //Route::get('solicitud/{id}/revision/cedula', 'SolicitudController@RevisionCedula')->name('solicitud.revision.cedula');
    Route::post('solicitud/{id}/updateRevisionFacturaMoto', 'SolicitudController@updateRevisionFacturaMoto')->name('solicitud.updateRevisionFacturaMoto');
    Route::post('solicitud/{id}/updateRevisionFacturaAuto', 'SolicitudController@updateRevisionFacturaAuto')->name('solicitud.updateRevisionFacturaAuto');
    Route::post('solicitud/{id}/updateRevisionFacturaCamion','SolicitudController@updateRevisionFacturaCamion')->name('solicitud.updateRevisionFacturaCamion');
    
    //Crear limitación o prohibición para vehículo

    Route::post('solicitud/{id}/limitacion/form','LimitacionController@ingresarLimitacionForm')->name('solicitud.limitacion.form');
    Route::post('solicitud/{id}/limitacion/new','LimitacionController@ingresaLimitacion')->name('solicitud.limitacion.new');
    Route::post('solicitud/{id}/limitacion/verEstadoSolicitud','LimitacionController@verEstado')->name('solicitud.limitacion.estadoSolicitud');
    Route::post('solicitud/{id}/descargaComprobanteLimi','LimitacionController@descargaComprobanteLimi')->name('solicitud.descargaComprobanteLimi');
    Route::post('solicitud/{id}/limitacion/resendFile','LimitacionController@reenviarArchivo')->name('solicitud.limitacion.reenviarArchivo');

    Route::get('solicitud/{id}/show', 'SolicitudController@show')->name('solicitud.show');
    Route::get('solicitud/verSolicitudes', 'SolicitudController@verSolicitudes')->name('solicitud.verSolicitudes');
    Route::post('solicitud/{id}/verEstadoSolicitud','SolicitudController@verEstado')->name('solicitud.estadoSolicitud');

    Route::post('solicitud/{id}/descargaComprobanteRVM','SolicitudController@descargaComprobanteRVM')->name('solicitud.descargaComprobanteRVM');

    // Documentos
    Route::get('documento/{id}/create', 'DocumentoController@create')->name('documento.create');
    Route::post('documento', 'DocumentoController@store')->name('documento.store');
    Route::post('documento/destroy', 'DocumentoController@destroy')->name('documento.destroy');
    Route::get('documento/{id}/get', 'DocumentoController@get')->name('documento.get');

    Route::post('documento/{id}/cargadocs', 'DocumentoController@CargaDocumentos')->name('documento.cargadocs.rc');

    
});

Route::group(['middleware' => ['auth', 'ejecut.garantiza']], function () {
    // Solicitudes SPIEV
    Route::get('solicitud/index', 'SolicitudController@index')->name('solicitud.index');
    Route::get('solicitud/revision', 'SolicitudController@revision')->name('solicitud.revision');

    //Solicitudes STEV
    Route::get('transferencia/revision', 'TransferenciaController@revision')->name('transferencia.revision');
    //Revisión de Solicitudes STEV
    Route::get('transferencia/{id}/revision/cedula', 'TransferenciaController@RevisionCedula')->name('transferencia.revision.cedula');
    Route::put('transferencia/{id}/updateRevisionCedula', 'TransferenciaController@updateRevisionCedula')->name('transferencia.updateRevisionCedula');
    Route::get('transferencia/continuar/{id}/{reingresa?}/{acceso?}','TransferenciaController@continuarSolicitud')->name('transferencia.continuar');


    // Revisión de Solicitudes SPIEV
    Route::get('solicitud/{id}/revision/cedula', 'SolicitudController@RevisionCedula')->name('solicitud.revision.cedula');
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
    Route::get('solicitud/continuar/{id}/{reingresa?}/{acceso?}','SolicitudController@continuarSolicitud')->name('solicitud.continuar');

    Route::get('solicitud/{id}/ver', 'SolicitudController@ver')->name('solicitud.ver');
    Route::get('solicitud/{id}/aprobacion', 'SolicitudController@aprobacion')->name('solicitud.aprobacion');
    Route::get('solicitud/{id}/edit', 'SolicitudController@edit')->name('solicitud.edit');
    Route::put('solicitud/{id}', 'SolicitudController@update')->name('solicitud.update');
    Route::delete('solicitud/{id}', 'SolicitudController@destroy')->name('solicitud.destroy');

    Route::post('solicitud', 'SolicitudController@store')->name('solicitud.store');
    Route::get('solicitud/{id}/adquirientes', 'SolicitudController@adquirientes')->name('solicitud.adquirientes');
    Route::post('solicitud/{id}/saveAdquirientes', 'SolicitudController@saveAdquirientes')->name('solicitud.saveAdquirientes');
    Route::get('solicitud/{id}/compraPara', 'SolicitudController@compraPara')->name('solicitud.compraPara');
    Route::post('solicitud/{id}/saveCompraPara', 'SolicitudController@saveCompraPara')->name('solicitud.saveCompraPara');
    Route::get('solicitud/{id}/datosMoto', 'SolicitudController@datosMoto')->name('solicitud.datosMoto');
    Route::get('solicitud/{id}/datosAuto', 'SolicitudController@datosAuto')->name('solicitud.datosAuto');
    Route::get('solicitud/{id}/datosCamion', 'SolicitudController@datosCamion')->name('solicitud.datosCamion');
    Route::put('solicitud/{id}/saveDatosMoto', 'SolicitudController@saveDatosMoto')->name('solicitud.saveDatosMoto');
    Route::post('solicitud/{id}/updateRevisionFacturaMoto/revision', 'SolicitudController@updateRevisionFacturaMoto')->name('solicitud.updateRevisionFacturaMoto');
    Route::post('solicitud/{id}/updateRevisionFacturaAuto/revision', 'SolicitudController@updateRevisionFacturaAuto')->name('solicitud.updateRevisionFacturaAuto');
    Route::post('solicitud/{id}/updateRevisionFacturaCamion/revision','SolicitudController@updateRevisionFacturaCamion')->name('solicitud.updateRevisionFacturaCamion');

    //Crear limitación o prohibición para vehículo SPIEV

    Route::post('solicitud/{id}/limitacion/form','LimitacionController@ingresarLimitacionForm')->name('solicitud.limitacion.form');
    Route::post('solicitud/{id}/limitacion/new/revision','LimitacionController@ingresaLimitacion')->name('solicitud.limitacion.new');
    //Route::post('solicitud/{id}/limitacion/verEstadoSolicitud','LimitacionController@verEstado')->name('solicitud.limitacion.estadoSolicitud');

    //Generar comprobante RVM
    //Route::post('solicitud/{id}/descargaComprobanteRVM','SolicitudController@descargaComprobanteRVM')->name('solicitud.descargaComprobanteRVM');

    //Documentos
    Route::post('documento/destroy/revision', 'DocumentoController@destroy')->name('documento.destroy.revision');
    //Registrar Pago
    Route::post('/documento/{id}/cargapago', 'SolicitudController@registrarPago')->name('pago.registrar.revision');

    //Registrar Pago STEV
    Route::post('/documento/transferencia/{id}/cargapago', 'TransferenciaController@registrarPago')->name('pago.transferencia.registrar.revision');
});

Route::group(["middleware"=>["auth","ejecut.notaria"]],function(){

    //Transferencias STEV
    Route::get('transferencia/create', 'TransferenciaController@create')->name('transferencia.create');
    Route::get('transferencia/index', 'TransferenciaController@index')->name('transferencia.index');
    Route::post('transferencia/consultaDataVehiculo', 'TransferenciaController@consultaDataVehiculo')->name('transferencia.consultaDataVehiculo');
    Route::post('transferencia', 'TransferenciaController@store')->name('transferencia.store');
    Route::get('transferencia/continuarSolicitud/{id}/{reingresa?}/{acceso?}','TransferenciaController@continuarSolicitud')->name('transferencia.continuarSolicitud');
    Route::post('transferencia/{id}/saveCompradores','TransferenciaController@saveCompradores')->name('transferencia.saveCompradores');
    Route::post('transferencia/{id}/saveVendedor','TransferenciaController@saveVendedor')->name('transferencia.saveVendedor');
    Route::post('transferencia/{id}/saveEstipulante','TransferenciaController@saveEstipulante')->name('transferencia.saveEstipulante');
    Route::post('transferencia/traeNaturalezasporTipoDoc','TransferenciaController@traeNaturalezasporTipoDoc')->name('traeNaturalezasporTipoDoc');
    Route::post('transferencia/{id}/updateDataTransferencia','TransferenciaController@updateDataTransferencia')->name('transferencia.updateDataTransferencia');

    Route::get('transferencia/verSolicitudes', 'TransferenciaController@verSolicitudes')->name('transferencia.verSolicitudes');
    Route::get('transferencia/sinTerminar', 'TransferenciaController@sinTerminar')->name('transferencia.sinTerminar');
    Route::post('transferencia/{id}/descargaComprobanteTransferencia','TransferenciaController@descargaComprobanteTransferencia')->name('transferencia.descargaComprobanteTransferencia');
    Route::post('transferencia/{id}/verEstadoSolicitud','TransferenciaController@verEstado')->name('transferencia.estadoSolicitud');

    //Documentos Transferencias STEV
    Route::post('documentoTransferencia/destroy', 'DocumentoController@destroyDocTransf')->name('transferencia.documento.destroy');
    Route::get('documentoTransferencia/{id}/get', 'DocumentoController@getDocsTransferencia')->name('transferencia.documento.get');
    Route::post('documentoTransferencia/{id}/cargadocs', 'DocumentoController@CargaDocumentosTransf')->name('transferencia.documento.cargadocs.rc');

    //Limitación STEV

    Route::post('transferencia/{id}/limitacion/new','LimitacionController@ingresaLimitacionTransferencia')->name('transferencia.limitacion.new');
    Route::post('transferencia/{id}/limitacion/verEstadoSolicitud','LimitacionController@verEstadoLimiTransf')->name('transferencia.limitacion.estadoSolicitud');
    Route::post('transferencia/{id}/descargaComprobanteLimi','LimitacionController@descargaComprobanteLimiTransf')->name('transferencia.descargaComprobanteLimi');
    Route::post('transferencia/{id}/limitacion/resendFile','LimitacionController@reenviarArchivoLimiTransf')->name('transferencia.limitacion.reenviarArchivo');

});


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/solicitud/{id}/generaJson', 'SolicitudController@generaJson')->name('solicitud.generaJson');