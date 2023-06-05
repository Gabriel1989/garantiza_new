<?php

namespace App\Http\Controllers;

use App\Helpers\RegistroCivil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Limitacion;
use App\Models\LimitacionRC;
use App\Models\SolicitudRC;
use App\Models\Adquiriente;
use App\Models\Acreedor;
use App\Models\Tipo_Documento;
use App\Models\Solicitud;
use App\Models\Documento;
use App\Models\ErrorEnvioDoc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
class LimitacionController extends Controller{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function ingresarLimitacionForm(){


        return view('solicitud.limitacion');
    }

    public function reenviarArchivo(Request $request, $id){
        if($request->hasFile('Doc_Lim2')){
            //Obtenemos archivo adjunto
            $file = $request->file('Doc_Lim2');
            //Guardamos archivo y obtenemos el path
            $path = Storage::disk('public')->putFileAs('', $file, $file->getClientOriginalName());
            //Obtenemos los datos de la limitación por medio del error en documento
            $error_doc_limi = ErrorEnvioDoc::where('solicitud_id',$id)->first();
            //Actualizamos documento en la bd
            $doc = Documento::where('solicitud_id',$id)->where('tipo_documento_id',$error_doc_limi->tipo_documento_id)->first();
            if($doc != null){
                $doc->name = 'public/'.$path;
                $doc->type = 'pdf';
                $doc->description = trim(Tipo_Documento::select('name')->where('id',$error_doc_limi->tipo_documento_id)->first()->name);
                $doc->solicitud_id = $id;
                $doc->tipo_documento_id = $error_doc_limi->tipo_documento_id;
                $doc->added_at = Carbon::now()->toDateTimeString();
                $doc->save();
            }
            else{
                $doc = new Documento();
                $doc->name = 'public/'.$path;
                $doc->type = 'pdf';
                $doc->description = trim(Tipo_Documento::select('name')->where('id',$error_doc_limi->tipo_documento_id)->first()->name);
                $doc->solicitud_id = $id;
                $doc->tipo_documento_id = $error_doc_limi->tipo_documento_id;
                $doc->added_at = Carbon::now()->toDateTimeString();
                $doc->save();
            }

            sleep(2);
            $base64_doc_limitacion = '';
            $doc_limitacion = Documento::where('solicitud_id', $id)->where('tipo_documento_id',$error_doc_limi->tipo_documento_id)->first();
            if($doc_limitacion != null){
                $base64_doc_limitacion = $this->getFileAsBase64($doc_limitacion->name);
            }
            //Si no obtiene base64, manda mensaje de error
            if($base64_doc_limitacion == ''){
                return json_encode(['status'=>'ERROR','msj'=>'Error al crear prohibición, no se adjuntó ni guardó el documento fundante de la prohibición']);
            }

            $validaDocLimi = true;
            if($error_doc_limi != null){

                $parametros = [
                    'consumidor' => 'ACOBRO',
                    'servicio' => 'INGRESO DOCUMENTOS RVM',
                    'file' => ($base64_doc_limitacion == '')? base64_encode(file_get_contents($request->file('Doc_Lim2')->getRealPath())) : $base64_doc_limitacion,
                    'patente' => $error_doc_limi->patente,
                    'nro' => $error_doc_limi->numSol,
                    'tipo_sol' => 'A',
                    'tipo_doc' => "PDF",
                    'clasificacion' => 1,
                    'fecha_ing' => date('d-m-Y'),
                    'nombre' => ($base64_doc_limitacion == '')? $request->file('Doc_Lim2')->getClientOriginalName() : str_replace('public/','',$doc_limitacion->name)
                ];
                $data = RegistroCivil::subirDocumentos(json_encode($parametros));
                $salida = json_decode($data, true);
                //dd($salida);
                if (isset($salida['OUTPUT'])) {
                    if ($salida['OUTPUT'] != "OK") {
                        $validaDocLimi = false;
                        
                        return json_encode(['status'=>'ERROR','msj'=>'Error al subir documento de limitación. Favor enviar el archivo de limitación nuevamente']);
                    }
                }
                else{
                    $validaDocLimi = false;
                    
                    return json_encode(['status'=>'ERROR','msj'=>'Error al subir documento de limitación. Favor enviar el archivo de limitación nuevamente.']);
                }

                $error_doc_limi->delete();
                return json_encode(['status'=>'OK','msj'=>'Archivo de limitación enviado exitosamente']);
            }
        }
        

    }

    public function ingresaLimitacion(Request $request, $id){

        $adquiriente = Adquiriente::where('solicitud_id',$id)->first();
        $solicitud_rc = SolicitudRC::getSolicitud($id);
        $get_limitacion_rc_rechazada = LimitacionRC::where('solicitud_id',$id)->first();
        $get_limitacion = Limitacion::where('solicitud_id',$id)->first();
        $solicitud = Solicitud::where('id',$id)->first();
        if($get_limitacion == null){
            $limitacion = new Limitacion();
            $limitacion->solicitud_id = $id;
            $limitacion->acreedor_id = Acreedor::select('id')->where('rut',$request->get('runAcreedor'))->first()->id;
            $limitacion->folio = trim($request->get('folio'));
            $limitacion->autorizante = trim($request->get('autorizante'));
            $limitacion->tipo_documento_id = Tipo_Documento::select('id')->where('name',trim($request->get('tipoDoc')))->first()->id;
            $limitacion->nro_vin = is_null($request->get('nro_vin')) ? '' : trim($request->get('nro_vin'));
            $limitacion->nro_motor = trim($request->get('nro_motor'));
            $limitacion->nro_serie = is_null($request->get('nro_serie')) ? '' : trim($request->get('nro_serie'));
            $limitacion->nro_chasis = trim($request->get('nro_chasis'));
            $limitacion->save();
        }
        else{
            $get_limitacion->acreedor_id = Acreedor::select('id')->where('rut',$request->get('runAcreedor'))->first()->id;
            $get_limitacion->folio = trim($request->get('folio'));
            $get_limitacion->autorizante = trim($request->get('autorizante'));
            $get_limitacion->tipo_documento_id = Tipo_Documento::select('id')->where('name',trim($request->get('tipoDoc')))->first()->id;
            $get_limitacion->nro_vin = is_null($request->get('nro_vin')) ? '' : trim($request->get('nro_vin'));
            $get_limitacion->nro_motor = trim($request->get('nro_motor'));
            $get_limitacion->nro_serie = is_null($request->get('nro_serie')) ? '' : trim($request->get('nro_serie'));
            $get_limitacion->nro_chasis = trim($request->get('nro_chasis'));
            $get_limitacion->save();
        }

        //Si no hay archivo adjunto en request, verifica si ya hay un archivo del mismo tipo guardado en bd y servidor
        if(!$request->hasFile('Doc_Lim')){
            $base64_doc_limitacion = '';
            $doc_limitacion = Documento::where('solicitud_id', $id)->whereIn('tipo_documento_id',[6,7])->first();
            if($doc_limitacion != null){
                //Si hay archivo guardado en la bd, obtenemos el binario desde el servidor
                $base64_doc_limitacion = $this->getFileAsBase64($doc_limitacion->name);
            }
            //Si no obtiene base64, manda mensaje de error
            if($base64_doc_limitacion == ''){
                return json_encode(['status'=>'ERROR','msj'=>'Error al crear prohibición, no se adjuntó ni guardó el documento fundante de la prohibición']);
            }
        }

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3){
            $datosReingreso = null;
            if($request->get('fechaResExenta') !== '' || $request->get('fechaSolRech') !== '' || $request->get('nroResExenta') !== ''){
                if($get_limitacion_rc_rechazada != null){
                    $datosReingreso = array(
                        'fechaResExenta' => $request->get('fechaResExenta'),
                        'fechaSolRech' => $request->get('fechaSolRech'),
                        'nroResExenta' => $request->get('nroResExenta'),
                        'nroSolicitud' => $get_limitacion_rc_rechazada->numeroSol,
                        'ppu' => isset($solicitud_rc[0]->ppu)? str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]) : ''
                    );
                }
                else{
                    $datosReingreso = array(
                        'fechaResExenta' => '',
                        'fechaSolRech' => '',
                        'nroResExenta' => '',
                        'nroSolicitud' => '',
                        'ppu' => ''
                    );
                }
            }
            else{
                $datosReingreso = array(
                    'fechaResExenta' => '',
                    'fechaSolRech' => '',
                    'nroResExenta' => '',
                    'nroSolicitud' => '',
                    'ppu' => ''
                );
            }

            $parametro = [
                'propietario' => [
                    'calidad' => $adquiriente->tipo,
                    'email' => is_null($adquiriente->email) ? 'info@acobro.cl' : $adquiriente->email,
                    'nombresRazon' => $adquiriente->nombre,
                    'runRut' => str_replace('.', '', str_replace('-', '', substr($adquiriente->rut, 0, -1))),
                    'aMaterno' => $adquiriente->aMaterno,
                    'aPaterno' => $adquiriente->aPaterno,
                    'domicilio' => [
                        'calle' => $adquiriente->calle,
                        'comuna' => $adquiriente->comuna,
                        'telefono' => is_null($adquiriente->telefono) ? '123456789' : $adquiriente->telefono,
                        'nroDomicilio' => $adquiriente->numero,
                        'ltrDomicilio' => '',
                        'rDomicilio' => $adquiriente->rDomicilio,
                        'cPostal' => '',
                    ],
                ],
                'comunidad' => [
                    'cantidad' => '0',
                    'esComunidad' => 'NO'
                ],
                'operador' => array(
                    'region' => '13',
                    'runUsuario' => '10796553',
                    'rEmpresa' => '77880510'
                ),
                'solicitante' => array(
                    'calidad' => 'N',
                    'email' => 'rodbay07@gmail.com',
                    'nombresRazon' => 'ROMAN ALEXIS',
                    'runRut' => '10796553',
                    'aMaterno' => 'RAVEST',
                    'aPaterno' => 'PINTO',
                    'domicilio' => array(
                        'calle' => 'LAS TINAJAS',
                        'ltrDomicilio' => '',
                        'nroDomicilio' => '1886',
                        'rDomicilio' => '',
                        'telefono' => '979761113',
                        'comuna' => '106',
                        'cPostal' => '',
                    )
                ),
                'observaciones' => '',
                'vehiculo' => array(
                    'patente' => isset($solicitud_rc[0]->ppu)? str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]) : '',
                    'nroChasis' => trim($request->get('nro_chasis')),
                    'nroMotor' => trim($request->get('nro_motor')),
                    'nroSerie' => is_null($request->get('nro_serie')) ? '' : trim($request->get('nro_serie')),
                    'nroVin' => is_null($request->get('nro_vin')) ? '' : trim($request->get('nro_vin')),
                ),
                'acreedor'=> array(
                    'runRut' => $request->get('runAcreedor'),
                    'nombreRazon' => $request->get('nombreRazon')
                ),
                'documento' => [
                    'fecha' => date('Ymd'),
                    'lugar' => $solicitud->sucursal->comuna, //Nro Comuna Sucursal -> Maipu
                    'numero' => $request->get('folio'),
                    'tipoDoc' => trim($request->get('tipoDoc')),
                    'autorizante' => trim($request->get('autorizante'))
                ],

            ];
            $parametro['reIngreso'] = $datosReingreso;
            $data = RegistroCivil::LimPrimera(json_encode($parametro));
            $salida = json_decode($data, true);

            //dd($salida);

            if(isset($salida['codigoresp'])){
                //dd((int)$salida['codigoresp']);
                $cod_salida_resp = $salida['codigoresp'];
                if(trim($cod_salida_resp)=="OK"){
                    $nro_limitacion_rc = $salida['solicitud']['numeroSol'];
                    $ppu_rc = $salida['solicitud']['ppu'];
                    $fecha = $salida['solicitud']['fecha'];
                    $hora = $salida['solicitud']['hora'];
                    $oficina = $salida['solicitud']['oficina'];
                    $tipo_sol = $salida['solicitud']['tipoSol'];

                    $get_limitacion_rc = LimitacionRC::where('solicitud_id',$id)->first();
                    if($get_limitacion_rc == null){
                        $limitacion_rc = new LimitacionRC();
                        $limitacion_rc->fecha = $fecha;
                        $limitacion_rc->hora = $hora;
                        $limitacion_rc->numSol = $nro_limitacion_rc;
                        $limitacion_rc->oficina = $oficina;
                        $limitacion_rc->ppu = $ppu_rc;
                        $limitacion_rc->tipoSol = $tipo_sol;
                        $limitacion_rc->solicitud_id = $id;
                        $limitacion_rc->save();
                    }
                    else{
                        $get_limitacion_rc->fecha = $fecha;
                        $get_limitacion_rc->hora = $hora;
                        $get_limitacion_rc->numSol = $nro_limitacion_rc;
                        $get_limitacion_rc->oficina = $oficina;
                        $get_limitacion_rc->ppu = $ppu_rc;
                        $get_limitacion_rc->tipoSol = $tipo_sol;
                        $get_limitacion_rc->save();
                    }
                    sleep(4);
                    $limitacion_rc_2 = LimitacionRC::getSolicitud($id);


                    $validaDocLimi = true;
                    $parametros = [
                        'consumidor' => 'ACOBRO',
                        'servicio' => 'INGRESO DOCUMENTOS RVM',
                        'file' => ($base64_doc_limitacion == '')? base64_encode(file_get_contents($request->file('Doc_Lim')->getRealPath())) : $base64_doc_limitacion,
                        'patente' => str_replace(".","",explode("-",$limitacion_rc_2[0]->ppu)[0]),
                        'nro' => $limitacion_rc_2[0]->numSol,
                        'tipo_sol' => 'A',
                        'tipo_doc' => "PDF",
                        'clasificacion' => 1,
                        'fecha_ing' => date('d-m-Y'),
                        'nombre' => ($base64_doc_limitacion == '')? $request->file('Doc_Lim')->getClientOriginalName() : str_replace('public/','',$doc_limitacion->name)
                    ];
                    $data = RegistroCivil::subirDocumentos(json_encode($parametros));
                    $salida = json_decode($data, true);
                    //dd($salida);
                    if (isset($salida['OUTPUT'])) {
                        if ($salida['OUTPUT'] != "OK") {
                            $validaDocLimi = false;
                            $error_docs = new ErrorEnvioDoc();
                            $error_docs->tipo_documento_id = Tipo_Documento::select('id')->where('name',trim($request->get('tipoDoc')))->first()->id;
                            $error_docs->solicitud_id = $id;
                            $error_docs->numSol = $limitacion_rc_2[0]->numSol;
                            $error_docs->patente = str_replace(".","",explode("-",$limitacion_rc_2[0]->ppu)[0]);
                            $error_docs->save();
                            return json_encode(['status'=>'ERROR','msj'=>'Error al subir documento de limitación. Favor enviar el archivo de limitación nuevamente']);
                        }
                    }
                    else{
                        $validaDocLimi = false;
                        $error_docs = new ErrorEnvioDoc();
                        $error_docs->tipo_documento_id = Tipo_Documento::select('id')->where('name',trim($request->get('tipoDoc')))->first()->id;
                        $error_docs->solicitud_id = $id;
                        $error_docs->numSol = $limitacion_rc_2[0]->numSol;
                        $error_docs->patente = str_replace(".","",explode("-",$limitacion_rc_2[0]->ppu)[0]);
                        $error_docs->save();
                        return json_encode(['status'=>'ERROR','msj'=>'Error al subir documento de limitación. Favor enviar el archivo de limitación nuevamente.']);
                    }

                    return json_encode(['status'=>'OK','msj'=>'Solicitud de limitación registrada exitosamente']);
                    
                }
                else{
                    return json_encode(['status'=>'ERROR','msj'=>$salida['glosa']]);
                }
            }
        }
        else{

            if($request->hasFile('Doc_Lim')){
                $file = $request->file('Doc_Lim');
                $path = Storage::disk('public')->putFileAs('', $file, $file->getClientOriginalName());
                $doc = new Documento();
                $doc->name = 'public/'.$path;
                $doc->type = 'pdf';
                $doc->description = trim($request->get('tipoDoc'));
                $doc->solicitud_id = $id;
                $doc->tipo_documento_id = Tipo_Documento::select('id')->where('name',trim($request->get('tipoDoc')))->first()->id;
                $doc->added_at = Carbon::now()->toDateTimeString();
                $doc->save();
            }else{
                if($base64_doc_limitacion == ''){
                    $errors = new MessageBag();
                    $errors->add('Documentos', 'Debe adjuntar documento fundante de limitación');
                    return json_encode(['status'=>'ERROR','esRevision'=>false,'msj'=>'Error al subir documento fundante de limitación']);
                }
            }



            return json_encode(['status'=>'OK','msj'=>'Solicitud de limitación registrada exitosamente. Espere mientras un ejecutivo de Garantiza revise su solicitud']);
        }

    }

    public function verEstado(Request $request, $id){
        $solicitud_rc = LimitacionRC::where('solicitud_id',$id)->first();

        echo '<h2>Datos Registro Limitación</h2>';

        $parametro = [
            'consumidor' => 'ACOBRO',
            'servicio' => 'CONSULTA LIMITACION',
            'ppu' => str_replace(".","",explode("-",$solicitud_rc->ppu)[0]),
            'nroSolicitud' => $request->get('id_solicitud_rc'),
            'anho' => substr($solicitud_rc->fecha,0,4)
        ];

        //dd($parametro);


        $data = RegistroCivil::consultaLimitacion($parametro);

        $salida = json_decode($data, true);
        $codigoresp = null;

        foreach($salida as $index => $detalle){
            if($index != "documento"){
                if($index == "codigoresp"){
                    $codigoresp = $detalle;
                }
                if($codigoresp != null){
                    echo "<label>".$index.': </label> '.$detalle.'<br>';
                }
            }
        }
        echo '<button type="button" data-garantizaSol="'.$id.'" data-numsol="'.$solicitud_rc->numSol.'" class="btn btn-success btn-sm btnDescargaComprobanteLimi"><i class="fa fa-download"></i>  Descarga Comprobante</button>';
        die;

    }

    public function descargaComprobanteLimi(Request $request, $id){
        $solicitud_rc = LimitacionRC::where('solicitud_id',$id)->first();
        $parametro = [
            'consumidor' => 'ACOBRO',
            'servicio' => 'CONSULTA LIMITACION',
            'ppu' => str_replace(".","",explode("-",$solicitud_rc->ppu)[0]),
            'nroSolicitud' => $request->get('id_solicitud_rc'),
            'anho' => substr($solicitud_rc->fecha,0,4)
        ];

        //dd($parametro);


        $data = RegistroCivil::consultaLimitacion($parametro);

        $salida = json_decode($data, true);
        $docdata = '';

        foreach($salida as $index => $detalle){
            if($index == "documento"){
                $docdata = $detalle;
                break;
            }
        }
        if($docdata != ''){
            // Decodificar la cadena en base64 a bytes
            $data = base64_decode($docdata);

            // Definir el nombre del archivo de salida
            $filename = 'comprobante_limitacion_garantiza.pdf';

            // Enviar encabezados al navegador para forzar la descarga
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($data));

            // Enviar el contenido del archivo PDF al navegador
            echo $data;
            exit;
        }
        else{
            // Enviar un mensaje de error en formato JSON
            header('Content-Type: application/json');
            echo json_encode(['error' => 'El comprobante de la limitación no se encuentra disponible aún']);
            exit;
        }
    }

    public function getFileAsBase64($filePath)
    {
        // Obtener el contenido del archivo utilizando el almacenamiento de Laravel
        $fileContents = Storage::get($filePath);
        // Convertir el contenido del archivo a base64
        $base64 = base64_encode($fileContents);
        return $base64;
    }



}




