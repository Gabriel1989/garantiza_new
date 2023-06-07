<?php

namespace App\Http\Controllers;

use App\Helpers\RegistroCivil;
use Exception;
use App\Models\Transferencia;
use App\Models\TransferenciaRC;
use App\Models\InfoVehiculoTransferencia;
use App\Models\Comuna;
use App\Models\EnvioDocumentoRC;
use App\Models\Reingreso;
use App\Models\Region;
use App\Models\Comprador;
use App\Models\Vendedor;
use App\Models\Estipulante;
use App\Models\NoEstipulante;
use App\Models\Propietario;
use App\Models\NaturalezaDoc;
use App\Models\NaturalezaActo;
use App\Models\Tipo_Documento;
use App\Models\TransferenciaData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use stdClass;

class TransferenciaController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $solicita_data = false;
        $salida = null;
        $id_transferencia = 0;
        $id_comprador = 0;
        $id_vendedor = 0;
        $solicitud_data = null;
        $id_transferencia_rc = 0;
        $id_estipulante = 0;
        
        return view('transferencia.index',compact('solicita_data','salida','id_estipulante','id_transferencia_rc','id_comprador','id_vendedor','id_transferencia','solicitud_data'));
    }

    public function consultaDataVehiculo(Request $request){
        
        $parametro = [
            'patente' => $request->ppu_request
        ];

        $data = RegistroCivil::consultaDatosVehiculo($parametro);

        $salida = json_decode($data, true);
        $id_transferencia = 0;
        $solicitud_data = null;

        if (isset($salida['codigoresp']) && $salida['codigoresp'] == '1') {
            $html = view ('transferencia.dataVehiculo',compact('salida','id_transferencia','solicitud_data'))->render();
            return json_encode(["status"=> "OK", "html" =>$html]);
            
        } else {
            return json_encode(["status"=> "ERROR", "msj"=>$salida['glosa']]);
        }
        
    }

    public function store(Request $request){
        $solicitud_id = $request->get('transferencia_id');
        if($solicitud_id != 0){
            $solicitud = Transferencia::find($solicitud_id);
            $solicitud->notaria_id = $request->get('notaria_id');
            if(Auth::user()->rol_id == 7){
                $solicitud->estado_id = 1;
                $solicitud->user_id = Auth::user()->id;
            }

            $vehiculo = InfoVehiculoTransferencia::where('transferencia_id',$solicitud_id)->first();
            $vehiculo->ppu = $request->ppu_transf;
            $vehiculo->tipo_vehiculo = $request->tipo_transf;
            $vehiculo->anio = $request->anio_transf;
            $vehiculo->marca = $request->marca_transf;
            $vehiculo->modelo = $request->modelo_transf;
            $vehiculo->chasis = $request->chasis_transf;
            $vehiculo->motor = $request->motor_transf;
            $vehiculo->serie = (trim($request->serie_transf) == "(NO INFORMADO)")? '' :  trim($request->serie_transf);
            $vehiculo->vin = (trim($request->vin_transf) == "(NO INFORMADO)")? '' : trim($request->vin_transf);
            $vehiculo->color = $request->color_transf;
            $vehiculo->save();

            $solicitud->vehiculo_id = $vehiculo->id;
            $solicitud->save();

            $propietario = Propietario::where('transferencia_id',$solicitud_id)->where('vehiculo_id',$vehiculo->id)->first();
            if($propietario != null){
                $propietario->rut = $request->rut_transf;
                $propietario->nombre = $request->nombres_transf;
                $propietario->razon_social = $request->razon_social_transf;
                $propietario->aPaterno = $request->apaterno_transf;
                $propietario->aMaterno = $request->amaterno_transf;
                $propietario->save();
            }
            else{
                $propietario = new Propietario();
                $propietario->rut = $request->rut_transf;
                $propietario->nombre = $request->nombres_transf;
                $propietario->razon_social = $request->razon_social_transf;
                $propietario->aPaterno = $request->apaterno_transf;
                $propietario->aMaterno = $request->amaterno_transf;
                $propietario->transferencia_id = $solicitud_id;
                $propietario->vehiculo_id = $vehiculo->id;
                $propietario->save();
            }
        }
        else{
            $solicitud = new Transferencia();
            $solicitud->notaria_id = $request->get('notaria_id');
            if(Auth::user()->rol_id == 7){
                $solicitud->estado_id = 1;
                $solicitud->user_id = Auth::user()->id;
            }

            $vehiculo = new InfoVehiculoTransferencia();
            $vehiculo->ppu = $request->ppu_transf;
            $vehiculo->tipo_vehiculo = $request->tipo_transf;
            $vehiculo->anio = $request->anio_transf;
            $vehiculo->marca = $request->marca_transf;
            $vehiculo->modelo = $request->modelo_transf;
            $vehiculo->chasis = $request->chasis_transf;
            $vehiculo->motor = $request->motor_transf;
            $vehiculo->serie = (trim($request->serie_transf) == "(NO INFORMADO)")? '' :  trim($request->serie_transf);
            $vehiculo->vin = (trim($request->vin_transf) == "(NO INFORMADO)")? '' : trim($request->vin_transf);
            $vehiculo->color = $request->color_transf;
            $vehiculo->save();
            //Asociando vehículo creado a transferencia y guardando transferencia
            $solicitud->vehiculo_id = $vehiculo->id;
            $solicitud->save();
            //Asociando transferencia a vehículo y guarda vehículo nuevamente
            $vehiculo->transferencia_id = $solicitud->id;
            $vehiculo->save();

            $propietario = new Propietario();
            $propietario->rut = $request->rut_transf;
            $propietario->nombre = $request->nombres_transf;
            $propietario->razon_social = $request->razon_social_transf;
            $propietario->aPaterno = $request->apaterno_transf;
            $propietario->aMaterno = $request->amaterno_transf;
            $propietario->transferencia_id = $solicitud->id;
            $propietario->vehiculo_id = $vehiculo->id;
            $propietario->save();
            
        }
        session(['solicitud_id_transf' => $solicitud->id]);
        $id = $solicitud->id;
        $comunas = Comuna::allOrder();
        $html = view('transferencia.comprador', compact('id', 'comunas'))->render();
        if($solicitud_id != 0){
            return json_encode(array('html'=>$html,'solicitud_id'=>0, 'solicitud_id2'=>$solicitud_id));
        }else{
            return json_encode(array('html'=>$html,'solicitud_id'=>$id));
        }
    }

    public function continuarSolicitud($id,$reingresa = false,$acceso = "ingreso"){
        $id_transferencia = $id;
        $comunas = Comuna::allOrder();
        $solicitud_data = Transferencia::find($id);

        $reingreso = Reingreso::where('transferencia_id',$id_transferencia)->whereIn('estado_id',[0,2,3])->first();
        $documento_rc = EnvioDocumentoRC::where('solicitud_id',$id_transferencia)->first();

        $region = Region::all();
        $solicita_data = true;

        if($reingresa){
            if($reingreso == null){
                $transferencia_rc = TransferenciaRC::where('solicitud_id',$id)->first();
                if($transferencia_rc != null){
                    $new_reingreso = new Reingreso();
                    $new_reingreso->ppu = explode('-',str_replace('.','',$transferencia_rc->ppu))[0];
                    $new_reingreso->nroSolicitud = $transferencia_rc->numeroSol;
                    $new_reingreso->transferencia_id = $id;
                    $new_reingreso->estado_id = 0; //Pendiente de reingreso
                    $new_reingreso->save();
                }
            }
        }

        $id_comprador = 0;
        $id_vendedor = 0;
        $id_estipulante = 0;
        $id_transferencia_rc = 0;
        $compradores = Comprador::where('transferencia_id',$id_transferencia)->first();
        $propietario_data = null;
        if($solicitud_data != null){
            $propietario_data = Propietario::where('transferencia_id',$id_transferencia)->where('vehiculo_id',$solicitud_data->vehiculo->id)->first();
        }
        

        if($compradores != null){
            //Menu vendedor
            $id_comprador = $compradores->id;
            $compradores = Comprador::getSolicitud($id_transferencia);
            $transferencia_rc = TransferenciaRC::getSolicitud($id_transferencia);
            
            $id_transferencia_rc = @isset($transferencia_rc[0]->numeroSol)? $transferencia_rc[0]->numeroSol : 0;
            

            $vendedor = Vendedor::where('transferencia_id',$id_transferencia)->first();
            if ($vendedor != null) {
                $id_vendedor = $vendedor->id;
            }


            $estipulante = Estipulante::where('transferencia_id', $id_transferencia)->first();
            if ($estipulante != null) {
                $id_estipulante = $estipulante->id;
            }
            else{
                $no_estipulante = NoEstipulante::where('transferencia_id', $id_transferencia)->first();
                if($no_estipulante != null){
                    $id_estipulante = $no_estipulante->id;
                }
                else{
                    $id_estipulante = 0;
                }
            }

        }
        //Menu comprador: solicitud recién creada
        return view('transferencia.index', compact('acceso','estipulante','compradores','vendedor','propietario_data','documento_rc','reingreso','region','solicita_data','solicitud_data','comunas','id_transferencia','id','id_comprador','id_estipulante','id_vendedor','id_transferencia_rc'));
    }

    public function saveCompradores(Request $request, $id){
        // Si tipo de persona es comunidad, se deben validar los otros compradores
        $errors = new MessageBag();
        if(is_null($request->input('rut'))) $errors->add('Garantiza', 'Debe Ingresar el Rut del Comprador.');
        if(is_null($request->input('nombre'))) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Comprador.');
        if(is_null($request->input('calle'))) $errors->add('Garantiza', 'Debe Ingresar la dirección del Comprador.');
        if(is_null($request->input('numero'))) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Comprador.');
        if($request->input('comuna')=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Comprador.');
        if(is_null($request->input('email'))) $errors->add('Garantiza', 'Debe Ingresar el email del Comprador.');
        if(is_null($request->input('telefono'))) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Comprador.');
        if($request->input('tipoPersona')=='O'){
            // Revisa los datos del segundo comprador
            if(is_null($request->input('rut2'))) $errors->add('Garantiza', 'Debe Ingresar el Rut del Segundo Comprador.');
            if(is_null($request->input('nombre2'))) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Segundo Comprador.');
            if(is_null($request->input('calle2'))) $errors->add('Garantiza', 'Debe Ingresar la dirección del Segundo Comprador.');
            if(is_null($request->input('numero2'))) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Segundo Comprador.');
            if($request->input('comuna2')=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Segundo Comprador.');
            if(is_null($request->input('email2'))) $errors->add('Garantiza', 'Debe Ingresar el email del Segundo Comprador.');
            if(is_null($request->input('telefono2'))) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Segundo Comprador.');
            // Revisa si viene un tercer comprador
            if(!is_null($request->input('rut3'))){
                if(is_null($request->input('nombre3'))) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Tercer Comprador.');
                if(is_null($request->input('calle3'))) $errors->add('Garantiza', 'Debe Ingresar la dirección del Tercer Comprador.');
                if(is_null($request->input('numero3'))) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Tercer Comprador.');
                if($request->input('comuna3')=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Tercer Comprador.');
                if(is_null($request->input('email3'))) $errors->add('Garantiza', 'Debe Ingresar el email del Tercer Comprador.');
                if(is_null($request->input('telefono3'))) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Tercer Comprador.');
            }
        }

        if($errors->count()>0) return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);

        DB::beginTransaction();

        if($request->input('comprador_1') != 0){
            //Edita comprador principal
            $comprador_1 = $request->input('comprador_1');
            $comprador = Comprador::find($comprador_1);
            $comprador->rut = $request->get('rut');
            $comprador->nombre = $request->get('nombre');
            $comprador->aPaterno = $request->get('aPaterno');
            $comprador->aMaterno = $request->get('aMaterno');
            $comprador->calle = $request->get('calle');
            $comprador->numero = $request->get('numero');
            $comprador->rDomicilio = $request->get('rDireccion');
            $comprador->comuna = $request->get('comuna');
            $comprador->telefono = $request->get('telefono');
            $comprador->email = $request->get('email');
            $comprador->tipo = $request->get('tipoPersona')=='O' ? 'N' : $request->get('tipoPersona');
            $comprador->save();
            DB::commit();

            $solicitud = Transferencia::find($id);
            if($comprador->tipo == "J"){
                $solicitud->empresa = 1;
                $solicitud->save();
            }

            return response()->json(['status' => "OK"], 200);
        }

        // Graba comprador principal
        $comprador = new Comprador();
        $comprador->transferencia_id = $id;
        $comprador->rut = $request->get('rut');
        $comprador->nombre = $request->get('nombre');
        $comprador->aPaterno = $request->get('aPaterno');
        $comprador->aMaterno = $request->get('aMaterno');
        $comprador->calle = $request->get('calle');
        $comprador->numero = $request->get('numero');
        $comprador->rDomicilio = $request->get('rDireccion');
        $comprador->comuna = $request->get('comuna');
        $comprador->telefono = $request->get('telefono');
        $comprador->email = $request->get('email');
        $comprador->tipo = $request->get('tipoPersona')=='O' ? 'N' : $request->get('tipoPersona');

        if(!$comprador->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al grabar Comprador.');
            return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
        }

        // Graba segundo comprador
        if($request->tipoPersona=='O'){
            $comprador = new Comprador();
            $comprador->transferencia_id = $id;
            $comprador->rut = $request->get('rut2');
            $comprador->nombre = $request->get('nombre2');
            $comprador->aPaterno = $request->get('aPaterno2');
            $comprador->aMaterno = $request->get('aMaterno2');
            $comprador->calle = $request->get('calle2');
            $comprador->numero = $request->get('numero2');
            $comprador->rDomicilio = $request->get('rDomicilio2');
            $comprador->comuna = $request->get('comuna2');
            $comprador->telefono = $request->get('telefono2');
            $comprador->email = $request->get('email2');
            $comprador->tipo = 'N';

            if(!$comprador->save()){
                DB::rollBack();
                $errors->add('Garantiza', 'Problemas al grabar segundo Comprador.');
                return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
            }

            // Graba tercer comprador
            if(!is_null($request->rut3)){
                $comprador = new Comprador();
                $comprador->solicitud_id = $id;
                $comprador->rut = $request->get('rut3');
                $comprador->nombre = $request->get('nombre3');
                $comprador->aPaterno = $request->get('aPaterno3');
                $comprador->aMaterno = $request->get('aMaterno3');
                $comprador->calle = $request->get('calle3');
                $comprador->numero = $request->get('numero3');
                $comprador->rDomicilio = $request->get('rDomicilio3');
                $comprador->comuna = $request->get('comuna3');
                $comprador->telefono = $request->get('telefono3');
                $comprador->email = $request->get('email3');
                $comprador->tipo = 'N';

                if(!$comprador->save()){
                    DB::rollBack();
                    $errors->add('Garantiza', 'Problemas al grabar tercer Comprador.');
                    return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
                }
            }
        }

        $solicitud = Transferencia::find($id);
        //Solo ejecutivo de concesionaria gatilla los cambios de estado
        if(Auth::user()->rol_id == 7){
            $solicitud->estado_id = 2;
        }
        //Si es persona jurídica, la solicitud es de una empresa como adquiriente
        if($request->get('tipoPersona')=='J'){
            $solicitud->empresa = 1;
        }
        if(!$solicitud->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al actualizar estado de Solicitud.');
            return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
        }

        DB::commit();
        $compradores = Comprador::getSolicitud($id);
        $comunas = Comuna::allOrder();
        $vendedor = null;
        $html = view('solicitud.vendedor', compact('id', 'comunas', 'compradores','vendedor'))->render();

        return response()->json(['status' => "OK","html" => $html, "id_comprador" => $comprador->id]);

    }

    public function saveVendedor(Request $request, $id){
        $errors = new MessageBag();
        if(is_null($request->input('rut'))) $errors->add('Garantiza', 'Debe Ingresar el Rut del Vendedor.');
        if(is_null($request->input('nombre'))) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Vendedor.');
        if(is_null($request->input('calle'))) $errors->add('Garantiza', 'Debe Ingresar la dirección del Vendedor.');
        if(is_null($request->input('numero'))) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Vendedor.');
        if($request->input('comuna')=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Vendedor.');
        if(is_null($request->input('email'))) $errors->add('Garantiza', 'Debe Ingresar el email del Vendedor.');
        if(is_null($request->input('telefono'))) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Vendedor.');

        if($errors->count()>0) return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
        DB::beginTransaction();

        if($request->input('vendedor_1') != 0){
            //Edita vendedor principal
            $vendedor_1 = $request->input('vendedor_1');
            $vendedor = Vendedor::find($vendedor_1);
            $vendedor->rut = $request->get('rut');
            $vendedor->nombre = $request->get('nombre');
            $vendedor->aPaterno = $request->get('aPaterno');
            $vendedor->aMaterno = $request->get('aMaterno');
            $vendedor->calle = $request->get('calle');
            $vendedor->numero = $request->get('numero');
            $vendedor->rDomicilio = $request->get('rDireccion');
            $vendedor->comuna = $request->get('comuna');
            $vendedor->telefono = $request->get('telefono');
            $vendedor->email = $request->get('email');
            $vendedor->tipo = $request->get('tipoPersona')=='O' ? 'N' : $request->get('tipoPersona');
            $vendedor->save();
            DB::commit();

            return response()->json(['status' => "OK"], 200);
        }

        // Graba vendedor principal
        $vendedor = new Vendedor();
        $vendedor->transferencia_id = $id;
        $vendedor->rut = $request->get('rut');
        $vendedor->nombre = $request->get('nombre');
        $vendedor->aPaterno = $request->get('aPaterno');
        $vendedor->aMaterno = $request->get('aMaterno');
        $vendedor->calle = $request->get('calle');
        $vendedor->numero = $request->get('numero');
        $vendedor->rDomicilio = $request->get('rDireccion');
        $vendedor->comuna = $request->get('comuna');
        $vendedor->telefono = $request->get('telefono');
        $vendedor->email = $request->get('email');
        $vendedor->tipo = $request->get('tipoPersona')=='O' ? 'N' : $request->get('tipoPersona');

        if(!$vendedor->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al grabar Vendedor.');
            return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
        }

        $solicitud = Transferencia::find($id);
        //Solo ejecutivo de concesionaria gatilla los cambios de estado
        if(Auth::user()->rol_id == 7){
            $solicitud->estado_id = 3;
        }

        if(!$solicitud->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al actualizar estado de Solicitud.');
            return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
        }

        DB::commit();
        $vendedor = Vendedor::getSolicitud($id);
        $comunas = Comuna::allOrder();
        $estipulante = null;
        $html = view('transferencia.estipulante', compact('id', 'comunas', 'vendedor','estipulante'))->render();

        return response()->json(['status' => "OK","html" => $html, "id_vendedor" => $vendedor->id]);
    }

    public function saveEstipulante(Request $request, $id){
        $guardaestipulante = $request->input('guardar');
        Log::info('guarda estipulante: '.$guardaestipulante);
        if ($guardaestipulante == "SI") {
            Log::info('guardamos estipulante');
            $errors = new MessageBag();
            if (!is_null($request->rut)) {
                if (is_null($request->nombre))
                    $errors->add('Garantiza', 'Debe Ingresar el Nombre del Estipulante');
                if (is_null($request->calle))
                    $errors->add('Garantiza', 'Debe Ingresar la dirección del Estipulante');
                if (is_null($request->numero))
                    $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Estipulante');
                if ($request->comuna == "0")
                    $errors->add('Garantiza', 'Debe Ingresar la comuna del Estipulante');
                if (is_null($request->email))
                    $errors->add('Garantiza', 'Debe Ingresar el email del Estipulante');
                if (is_null($request->telefono))
                    $errors->add('Garantiza', 'Debe Ingresar el teléfono del Estipulante');
            }
            if ($errors->count() > 0){
                return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
            }

            $no_estipulante = NoEstipulante::where('transferencia_id',$id)->first();
            if($no_estipulante != null){
                $no_estipulante->delete();
            }

            DB::beginTransaction();

            if ($request->input('id_estipulante') != 0) {
                //Edita estipulante principal
                $id_estipulante = $request->input('id_estipulante');
                $estipulante = Estipulante::find($id_estipulante);
                $estipulante->rut = $request->get('rut');
                $estipulante->nombre = $request->get('nombre');
                $estipulante->aPaterno = $request->get('aPaterno');
                $estipulante->aMaterno = $request->get('aMaterno');
                $estipulante->calle = $request->get('calle');
                $estipulante->numero = $request->get('numero');
                $estipulante->rDomicilio = $request->get('rDireccion');
                $estipulante->comuna = $request->get('comuna');
                $estipulante->telefono = $request->get('telefono');
                $estipulante->email = $request->get('email');
                $estipulante->tipo = $request->get('tipoPersona');
                $estipulante->esProhibicion = $request->get('esProhibicion');
                $estipulante->save();
                DB::commit();

                return response()->json(['status' => "OK","id_estipulante" => $estipulante->id], 200);
            }

            // Graba estipulante principal
            if (!is_null($request->rut)) {
                $estipulante = new Estipulante();
                $estipulante->transferencia_id = $id;
                $estipulante->rut = $request->get('rut');
                $estipulante->nombre = $request->get('nombre');
                $estipulante->aPaterno = $request->get('aPaterno');
                $estipulante->aMaterno = $request->get('aMaterno');
                $estipulante->calle = $request->get('calle');
                $estipulante->numero = $request->get('numero');
                $estipulante->rDomicilio = $request->get('rDireccion');
                $estipulante->comuna = $request->get('comuna');
                $estipulante->telefono = $request->get('telefono');
                $estipulante->email = $request->get('email');
                $estipulante->esProhibicion = $request->get('esProhibicion');
                $estipulante->tipo = $request->get('tipoPersona');

                if (!$estipulante->save()) {
                    DB::rollBack();
                    $errors->add('Garantiza', 'Problemas al grabar Estipulante.');
                    return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
                }
            }
        }
        else{
            Log::info('NO guardamos estipulante');
            $para_get = Estipulante::where('transferencia_id',$id)->first();
            if ($para_get != null) {
                $para_get->delete();
            }
            $no_estipulante = new NoEstipulante();
            $no_estipulante->transferencia_id = $id;
            $no_estipulante->save();
        }

        $solicitud = Transferencia::find($id);
        if(Auth::user()->rol_id == 7){
            $solicitud->estado_id = 4;
            if(!$solicitud->save()){
                DB::rollBack();
                $errors->add('Garantiza', 'Problemas al actualizar estado de Solicitud.');
                return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
            }
        }

        DB::commit();

        $html = view('transferencia.dataResumen', compact('id'))->render();
        if ($guardaestipulante == "SI") {
            return response()->json(['status' => "OK","html" => $html, "id_estipulante" => $estipulante->id]);
        }else{
            return response()->json(['status' => "OK","html" => $html, "id_estipulante" => $no_estipulante->id]);
        }
    }

    public function traeNaturalezasporTipoDoc(Request $request){
        $tipo_doc = Tipo_Documento::select('id')->where('name',trim($request->tipoDocTransf))->first()->id;
        $naturalezas = NaturalezaDoc::where('tipo_documento_id',$tipo_doc)->get();
        foreach($naturalezas as $item){
            echo '<option value="'.$item->naturalezas->nombre.'">'.$item->naturalezas->nombre.'</option>';    
        }
    }

    public function updateDataTransferencia(Request $request, $id){
        $comprador = Comprador::where('transferencia_id',$id)->first();
        $vendedor = Vendedor::where('transferencia_id',$id)->first();
        $estipulante = Estipulante::where('transferencia_id',$id)->first();
        $transferencia = Transferencia::where('id',$id)->first();
        $reingreso = Reingreso::where('transferencia_id',$id)->whereIn('estado_id',[0,2,3])->first();
        $get_transferencia_rc = TransferenciaRC::where('transferencia_id',$id)->first();

        $transferencia_data = TransferenciaData::where('transferencia_id',$id)->first();

        if($transferencia_data == null){
            $transferencia_data = new TransferenciaData();
            $transferencia_data->transferencia_id = $id;
            $transferencia_data->dv_ppu = trim($request->digitoVerificadorPPU);
            $transferencia_data->tipo_documento_id = Tipo_Documento::select('id')->where('name',trim($request->tipoDocTransf))->first()->id;
            $transferencia_data->naturaleza_id = NaturalezaActo::select('id')->where('nombre',trim($request->naturalezaTransf))->first()->id;
            $transferencia_data->num_doc = $request->numDocTransf;
            $transferencia_data->fecha_emision = $request->fechaEmisionTransf;
            $transferencia_data->lugar_id = $request->lugarTransf;
            $transferencia_data->total_venta = $request->totalTransf;
            $transferencia_data->moneda = $request->monedaTransf;
            $transferencia_data->kilometraje = $request->kilometraje;
            $transferencia_data->rut_emisor = str_replace('.', '', str_replace('-', '', substr($request->rutEmisorTransf, 0, -1)));
            $transferencia_data->codigo_cid = $request->codigoCID;
            $transferencia_data->monto_impuesto = $request->montoPagadoImpuesto;
            $transferencia_data->codigoNotaria = Auth::user()->notaria->codigo_notaria_rc;
            $transferencia_data->save();
        }
        else{
            $transferencia_data->dv_ppu = trim($request->digitoVerificadorPPU);
            $transferencia_data->tipo_documento_id = Tipo_Documento::select('id')->where('name',trim($request->tipoDocTransf))->first()->id;
            $transferencia_data->naturaleza_id = NaturalezaActo::select('id')->where('nombre',trim($request->naturalezaTransf))->first()->id;
            $transferencia_data->num_doc = $request->numDocTransf;
            $transferencia_data->fecha_emision = $request->fechaEmisionTransf;
            $transferencia_data->lugar_id = $request->lugarTransf;
            $transferencia_data->total_venta = $request->totalTransf;
            $transferencia_data->moneda = $request->monedaTransf;
            $transferencia_data->kilometraje = $request->kilometraje;
            $transferencia_data->rut_emisor = str_replace('.', '', str_replace('-', '', substr($request->rutEmisorTransf, 0, -1)));
            $transferencia_data->codigo_cid = $request->codigoCID;
            $transferencia_data->monto_impuesto = $request->montoPagadoImpuesto;
            $transferencia_data->codigoNotaria = Auth::user()->notaria->codigo_notaria_rc;
            $transferencia_data->save();
        }

        $datosEstipulante = null;
        if($estipulante != null){
            $datosEstipulante = array(
                'persona' => [
                    'calidad' => $estipulante->tipo,
                    'runRut' => str_replace('.', '', str_replace('-', '', substr($estipulante->rut, 0, -1))),
                    'nombresRazon' => $estipulante->nombre,
                    'aPaterno' => $estipulante->aPaterno,
                    'aMaterno' => $estipulante->aMaterno,
                    'Email' => is_null($estipulante->email) ? 'info@acobro.cl' : $estipulante->email
                ],
                'direccion' => [
                    'calle' => $estipulante->calle,
                    'comuna' => $estipulante->comuna,
                    'ltrDomicilio' => '',
                    'nroDomicilio' => $estipulante->numero,
                    'telefono' => is_null($estipulante->telefono) ? '123456789' : $estipulante->telefono,
                    'cPostal' => '',
                    'rDomicilio' => $estipulante->rDomicilio
                ],
                'Prohibicion' => ($estipulante->esProhibicion)? 'SI' : 'NO'
            );
        }
        else{
            $datosEstipulante = array(
                'persona' => [
                    'calidad' => '',
                    'runRut' => '',
                    'nombresRazon' => '',
                    'aPaterno' => '',
                    'aMaterno' => '',
                    'Email' => ''
                ],
                'direccion' => [
                    'calle' => '',
                    'comuna' => '',
                    'ltrDomicilio' => '',
                    'nroDomicilio' => '',
                    'telefono' => '',
                    'cPostal' => '',
                    'rDomicilio' => ''
                ],
                'Prohibicion' => ''
            );
        }

        $datosReingreso = null;
        if($reingreso != null && $get_transferencia_rc != null){
            $datosReingreso = array(
                'FechaResExenta' => $request->get('fechaResExenta'),
                'FechaSolRech' => $request->get('fechaSolRech'),
                'NroResExenta' => $request->get('nroResExenta'),
                'NroSolicitud' => $reingreso->nroSolicitud,
                'PPU' => $reingreso->ppu
            );
            Log::info('adjunta datos de reingreso');
        }
        else{
            $datosReingreso = array(
                'FechaResExenta' => '',
                'FechaSolRech' => '',
                'NroResExenta' => '',
                'NroSolicitud' => '',
                'PPU' => ''
            );
            Log::info('no adjunta datos de reingreso');
        }

        $parametros = [
            'vehiculo' => [
                'PPU' => $transferencia->vehiculo->ppu,
                'DV'=> $request->digitoVerificadorPPU,
                'TipoVehiculo' => $transferencia->vehiculo->tipo_vehiculo,
                'Marca' =>  $transferencia->vehiculo->marca,
                'Modelo' => $transferencia->vehiculo->modelo,
                'AnoFabric' => $transferencia->vehiculo->anio,
                'Color' => $transferencia->vehiculo->color,
                'nroMotor' => $transferencia->vehiculo->motor,
                'nroChasis' => $transferencia->vehiculo->chasis,
                'nroSerie' => $transferencia->vehiculo->serie,
                'nroVin' => $transferencia->vehiculo->vin,
            ],
            'Vendedor' => [
                'comunidad' => [
                    'esComunidad' => 'NO',
                    'cantidad' => 0
                ],
                'persona' => [
                    'calidad' => $vendedor->tipo,
                    'runRut' => str_replace('.', '', str_replace('-', '', substr($vendedor->rut, 0, -1))),
                    'nombresRazon' => $vendedor->nombre,
                    'aPaterno' => $vendedor->aPaterno,
                    'aMaterno' => $vendedor->aMaterno,
                    'Email' => $vendedor->email
                ]
            ],
            'Comprador' => [
                'comunidad' => [
                    'esComunidad' => 'NO',
                    'cantidad' => 0
                ],
                'compran' => [
                    'persona' => [
                        'calidad' => $comprador->tipo,
                        'runRut' => str_replace('.', '', str_replace('-', '', substr($comprador->rut, 0, -1))),
                        'nombresRazon' => $comprador->nombre,
                        'aPaterno' => $comprador->aPaterno,
                        'aMaterno' => $comprador->aMaterno,
                        'Email' => $comprador->email
                    ],
                    'direccion' => [
                        'calle' => $comprador->calle,
                        'comuna' => $comprador->comuna,
                        'ltrDomicilio' => '',
                        'nroDomicilio' => $comprador->numero,
                        'telefono' => $comprador->telefono,
                        'cPostal' => '',
                        'rDomicilio' => $comprador->rDomicilio
                    ],
                ],
            ],
            'documento' => [
                'TipoDocumento' => $transferencia_data->tipoDocumento->name,
                'Naturaleza' => $transferencia_data->naturaleza->nombre,
                'Numero' => $transferencia_data->num_doc,
                'Fecha' => $transferencia_data->fecha_emision,
                'Lugar' => $transferencia_data->lugar_id,
                'Total' => $transferencia_data->total_venta,
                'Moneda' => $transferencia_data->moneda,
                'Kilometraje' => $transferencia_data->kilometraje,
                'RutEmisor' => $transferencia_data->rut_emisor,
                'codigoNotaria' => $transferencia_data->codigoNotaria
            ],
            'ImpuestoTransferencia' => [
                'CodigoCID' => $transferencia_data->codigo_cid,
                'MontoPagado' => $transferencia_data->monto_impuesto
            ],
            'operador' => array(
                'region' => '13',
                'runUsuario' => '10796553',
                'rutEmpresa' => '77880510'
            ),
            'Solicitante' => array(
                'persona'=> [
                    'calidad' => 'N',
                    'runRut' => '10796553',
                    'nombresRazon' => 'ROMAN ALEXIS',
                    'aPaterno' => 'PINTO',
                    'aMaterno' => 'RAVEST',
                    'Email' => 'rodbay07@gmail.com',
                ],
                'direccion' => [
                    'calle' => 'LAS TINAJAS',
                    'comuna' => '106',
                    'ltrDomicilio' => '',
                    'nroDomicilio' => '1886',
                    'telefono' => '979761113',
                    'cPostal' => '',
                    'rDomicilio' => ''
                ],
            )
        ];

        $parametros['Estipulante'] = $datosEstipulante;
        $parametros['ReIngreso'] = $datosReingreso;

        //dd($parametros);

        $data = RegistroCivil::creaStev(json_encode($parametros));
        $salida = json_decode($data, true);

        dd($salida);

        //if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3){
            
            
            if(isset($salida['codigoresp'])){
                //dd((int)$salida['codigoresp']);
                $cod_salida_resp = (int)$salida['codigoresp'];
                if($cod_salida_resp==1 || $cod_salida_resp==0){
                    $nro_solicitud_transf_rc = $salida['solicitud']['numeroSol'];
                    $ppu_rc = $salida['solicitud']['ppu'];
                    $fecha = $salida['solicitud']['fecha'];
                    $hora = $salida['solicitud']['hora'];
                    $oficina = $salida['solicitud']['oficina'];
                    $tipo_sol = $salida['solicitud']['tipoSol'];
                    @$observaciones = $salida['Observaciones'];

                    if($reingreso == null && $get_transferencia_rc == null){

                        $transferencia_rc = new TransferenciaRC();
                        $transferencia_rc->fecha = $fecha;
                        $transferencia_rc->hora = $hora;
                        $transferencia_rc->numeroSol = $nro_solicitud_transf_rc;
                        $transferencia_rc->oficina = $oficina;
                        $transferencia_rc->ppu = $ppu_rc;
                        $transferencia_rc->tipoSol = $tipo_sol;
                        $transferencia_rc->solicitud_id = $id;
                        $transferencia_rc->save();
                    }
                    else{
                        $transferencia_rc = TransferenciaRC::where('transferencia_id',$id)->first();
                        $transferencia_rc->fecha = $fecha;
                        $transferencia_rc->hora = $hora;
                        $transferencia_rc->numeroSol = $nro_solicitud_transf_rc;
                        $transferencia_rc->oficina = $oficina;
                        $transferencia_rc->ppu = $ppu_rc;
                        $transferencia_rc->tipoSol = $tipo_sol;
                        $transferencia_rc->solicitud_id = $id;
                        $transferencia_rc->save();

                    }

                    if(@sizeof($observaciones)==0){
                        $solicitud2 = Transferencia::find($id);
                        $solicitud2->estado_id = 6;
                        $solicitud2->save();

                        if($reingreso != null && $get_transferencia_rc != null){
                            $reingreso->estado_id = 1; //Reingreso aceptado
                            $reingreso->nroSolicitud = $nro_solicitud_transf_rc;
                            $reingreso->save();
                        }
                    }
                    else{
                        $solicitud2 = Transferencia::find($id);
                        $solicitud2->estado_id = 12;
                        $solicitud2->save();

                        if($reingreso != null && $get_transferencia_rc != null){
                            $reingreso->observaciones = json_encode($observaciones);
                            $reingreso->nroSolicitud = $nro_solicitud_transf_rc;
                            $reingreso->estado_id = 2; //Reingreso rechazado
                            $reingreso->save();
                        }
                        else{
                            $new_reingreso = new Reingreso();
                            $new_reingreso->ppu = explode('-',str_replace('.','',$ppu_rc))[0];
                            $new_reingreso->nroSolicitud = $nro_solicitud_transf_rc;
                            $new_reingreso->transferencia_id = $id;
                            $new_reingreso->estado_id = 0; //Pendiente de reingreso
                            $new_reingreso->observaciones = json_encode($observaciones);
                            $new_reingreso->save();
                        }
                    }

                    sleep(4);
                    $transferencia_rc = TransferenciaRC::getSolicitud($id);
                    return view('transferencia.menuDocs', compact('id', 'nro_solicitud_transf_rc', 'ppu_rc','transferencia_rc'));

                }
                else{
                    return view('general.ErrorRC', ['glosa' => $salida['glosa']]);
                }
            }
            else{
                return view('general.ErrorRC', ['glosa' => $salida['glosa']]);
            }
        /*}
        else{
            $solicitud2 = Transferencia::find($id);
            $solicitud2->estado_id = 5;
            $solicitud2->save();

            $transferencia_rc = null;
            $nro_solicitud_transf_rc = null;
            $ppu_rc = null;

            return view('transferencia.menuDocs', compact('id', 'nro_solicitud_transf_rc', 'ppu_rc','transferencia_rc'));
        }*/
    }
}