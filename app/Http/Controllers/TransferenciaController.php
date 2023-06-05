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
        $solicitud_data = null;
        return view('transferencia.index',compact('solicita_data','salida','id_transferencia','solicitud_data'));
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
            $solicitud->save();

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
        }
        else{
            $solicitud = new Transferencia();
            $solicitud->notaria_id = $request->get('notaria_id');
            if(Auth::user()->rol_id == 7){
                $solicitud->estado_id = 1;
                $solicitud->user_id = Auth::user()->id;
            }
            $solicitud->save();

            $vehiculo = new InfoVehiculoTransferencia();
            $vehiculo->transferencia_id = $solicitud->id;
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

            $vehiculo->save();
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

        if($compradores != null){
            //Menu vendedor
            $id_comprador = $compradores->id;
            $compradores = Comprador::getSolicitud($id_transferencia);
            $transferencia_rc = TransferenciaRC::getSolicitud($id_transferencia);
            
            $id_transferencia_rc = @isset($transferencia_rc[0]->numeroSol)? $transferencia_rc[0]->numeroSol : 0;
            

            $vendedores = Vendedor::where('transferencia_id',$id_transferencia)->first();


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
        return view('transferencia.index', compact('acceso','documento_rc','reingreso','region','solicitud_data','comunas','id_transferencia','id','id_comprador','id_estipulante','id_transferencia_rc'));
    }

}