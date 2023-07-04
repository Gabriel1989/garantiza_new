<?php

namespace App\Http\Controllers;

use App\Helpers\RegistroCivil;
use App\Helpers\Funciones;
use App\Helpers\PdftoXML;
use App\Http\Requests\PPURequest;
use App\Http\Requests\SolicitudRequest;
use App\Models\Adquiriente;
use App\Models\CompraPara;
use App\Models\Comuna;
use App\Models\Documento;
use App\Models\InfoVehiculo;
use App\Models\Para;
use App\Models\Solicitud;
use App\Models\Sucursal;
use App\Models\Tipo_Tramite;
use App\Models\Tipo_Vehiculo;
use App\Models\TipoTramite_Solicitud;
use App\Models\Region;
use App\Models\Factura;
use App\Models\SolicitudRC;
use App\Models\NoPara;
use App\Models\Tipo_Carroceria;
use App\Models\TipoPotencia;
use App\Models\Reingreso;
use App\Models\EnvioDocumentoRC;
use App\Models\Rechazo;
use App\Models\TipoPlacaPatente;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use stdClass;

class SolicitudController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create()
    {
        $sucursales = Sucursal::where('concesionaria_id',Auth::user()->concesionaria_id)->get();
        $tipo_vehiculos = Tipo_Vehiculo::all();
        $acceso = 'ingreso';
        return view('solicitud.create', compact('acceso','sucursales', 'tipo_vehiculos'));
    }

    public function solicitaPPU(){

        $region = Region::all();
        $solicita_ppu = false;
        $sucursales = Sucursal::where('concesionaria_id',Auth::user()->concesionaria_id)->get();
        $tipo_vehiculos = Tipo_Vehiculo::all();
        $reingreso = null;
        $documento_rc = null;
        $solicitud_data = null;
        $ppu = array();
        $comunas = Comuna::allOrder();
        $id_solicitud = 0;
        $id_adquiriente = 0;
        $id_comprapara = 0;
        $id_solicitud_rc = 0;
        $header = new stdClass;
        session()->forget('solicitud_id');
        $tipo_carroceria = Tipo_Carroceria::all();
        $tipo_potencia = TipoPotencia::all();
        $acceso = 'ingreso';
        $solicitud_rc = null;

        return view('solicitud.create', compact('acceso','documento_rc','reingreso','tipo_potencia','tipo_carroceria','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','header','id_adquiriente','id_comprapara','solicitud_rc','id_solicitud_rc'));
    }

    public function continuarSolicitud($id,$reingresa = false,$acceso = "ingreso"){
        if($acceso == "revision" && (Auth::user()->rol_id == 4 || Auth::user()->rol_id == 5 || Auth::user()->rol_id == 6)){
            abort(403);
        }
        $id_solicitud = $id;
        $comunas = Comuna::allOrder();

        $header = new stdClass;
        $factura = Factura::where('id_solicitud',$id_solicitud)->first();
        $solicitud_data = Solicitud::find($id);
        $reingreso = Reingreso::where('solicitud_id',$id_solicitud)->whereIn('estado_id',[0,2,3])->first();
        $documento_rc = EnvioDocumentoRC::where('solicitud_id',$id_solicitud)->first();
        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 5 || Auth::user()->rol_id == 6){
            $sucursales = Sucursal::where('concesionaria_id',Auth::user()->concesionaria_id)->get();
        }
        else{
            $sucursales = Sucursal::all();
        }
        $tipo_vehiculos = Tipo_Vehiculo::all();
        $tipo_potencia = TipoPotencia::all();
        $ppu = array();
        $region = Region::all();
        $tipo_carroceria = Tipo_Carroceria::all();
        $solicita_ppu = true;

        if($reingresa){
            if($reingreso == null){
                $solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();
                if($solicitud_rc != null){
                    $new_reingreso = new Reingreso();
                    $new_reingreso->ppu = explode('-',str_replace('.','',$solicitud_rc->ppu))[0];
                    $new_reingreso->nroSolicitud = $solicitud_rc->numeroSol;
                    $new_reingreso->solicitud_id = $id;
                    $new_reingreso->estado_id = 0; //Pendiente de reingreso
                    //$new_reingreso->observaciones = json_encode($observaciones);
                    $new_reingreso->save();
                }
            }
        }

        if($factura != null){
            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)strtoupper($factura->comuna);
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;

            $header->AnnioFabricacion = (string)$factura->agno_fabricacion;
            $header->Color = (string)$factura->color;
            $header->TipoCombustible = (string)$factura->tipo_combustible;
            $header->Marca = (string)$factura->marca;
            $header->Modelo = (string)$factura->modelo;
            $header->NroChasis = (string)$factura->nro_chasis;
            $header->NroMotor = (string)$factura->motor;
            $header->NroVin  = (string)$factura->nro_vin;
            $header->NroSerie  = (string)$factura->nro_serie;
            $header->PesoBrutoVehi = (string)$factura->peso_bruto_vehicular;
            $header->TipoVehiculo = (string)$factura->tipo_vehiculo;
            $header->Asientos = (string) $factura->asientos;
            $header->Puertas = (string) $factura->puertas;
            $header->CitModelo = (string) $factura->codigo_cit;
            $header->TipoPBV = (string) $factura->tipo_pbv;
            $header->TipoCarga = (string) $factura->tipo_carga;

            $header->FchEmis = (string)$factura->fecha_emision;
            $header->RUTEmisor = (string)$factura->rut_emisor;
            $header->RznSoc = (string)$factura->razon_social_emisor;
            $header->MntTotal = (string)$factura->monto_total_factura;

            $header->MntNeto = '';
            $header->MntExe = '';
            $header->TasaIVA = '';
            $header->IVA = '';
            
        }

        $id_adquiriente = 0;
        $id_comprapara = 0;
        $id_tipo_vehiculo = 0;
        $id_solicitud_rc = 0;
        $detalle = array();
        $adquirientes = Adquiriente::where('solicitud_id',$id_solicitud)->first();
        if($adquirientes != null){
            //Menu compra para
            $id_adquiriente = $adquirientes->id;
            $adquirentes = Adquiriente::getSolicitud($id_solicitud);
            $tipo_vehiculo = Solicitud::getTipoVehiculo($id_solicitud);
            $solicitud_rc = SolicitudRC::getSolicitud($id_solicitud);
            //dd($solicitud_rc);
            $id_solicitud_rc = @isset($solicitud_rc[0]->numeroSol)? $solicitud_rc[0]->numeroSol : 0;
            //dd($id_solicitud_rc);
            $comprapara = CompraPara::where('solicitud_id', $id_solicitud)->first();
            if ($comprapara != null) {
                $id_comprapara = $comprapara->id;
            }
            else{
                $no_para = NoPara::where('solicitud_id', $id_solicitud)->first();
                if($no_para != null){
                    $id_comprapara = $no_para->id;
                }
                else{
                    $id_comprapara = 0;
                }
            }
            Log::info('tipo vehiculo: '.$tipo_vehiculo);
            if ($tipo_vehiculo != null) {
                switch ($tipo_vehiculo[0]->tipo) {
                    case 1:
                        $id_tipo_vehiculo = 1;
                        break;

                    case 2:
                        $id_tipo_vehiculo = 2;
                        break;
                    case 3:
                        $id_tipo_vehiculo = 3;
                        break;
                }
            }
            return view('solicitud.create', compact('acceso','documento_rc','reingreso','tipo_potencia','tipo_carroceria','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','id','header','id_adquiriente','adquirentes',
            'id_tipo_vehiculo','id_comprapara','detalle','comprapara','solicitud_rc','id_solicitud_rc'));
        }
        else{
            $solicitud_rc = null;
        }
        //Menu adquiriente: solicitud recién creada
        return view('solicitud.create', compact('acceso','documento_rc','reingreso','tipo_potencia','tipo_carroceria','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','id','header','id_adquiriente','id_tipo_vehiculo','id_comprapara','solicitud_rc','id_solicitud_rc'));
    }


    public function consultaPPU(PPURequest $request){

        $comunas = Comuna::allOrder();

        $parametro = [
            'Region' => $request->get('region'),
            'TipoPlaca' => $request->get('placa_patente_id'),
            'Servicio' => 'TERMINACION PATENTES DISPONIBLES',
            'Institucion' => $request->get('nombre_institucion')
        ];

        $tipo_placa = $request->get('placa_patente_id');
        $id_tipo_placa = TipoPlacaPatente::where('codigo',$tipo_placa)->first()->id;
        $region_selected = $request->get('region');

        $data = RegistroCivil::PPUDisponible($parametro);
        $ppu = json_decode($data, true);

        $id_solicitud = 0;
        $id_adquiriente = 0;
        $id_comprapara = 0;
        $id_solicitud_rc = 0;
        $solicitud_data = null;
        $reingreso = null;
        $documento_rc = null;
        //dd(session('solicitud_id'));
        $id_solicitud = (session('solicitud_id') != null) ? session('solicitud_id') : 0;
        //dd($id_solicitud);
        $solicita_ppu = true;
        $region = Region::all();
        $tipo_carroceria = Tipo_Carroceria::all();
        $tipo_potencia = TipoPotencia::all();
        $acceso = 'ingreso';

        if(isset($ppu['codigoresp'])){

            if($ppu['codigoresp']=='OK'){
                $ppu = $ppu['PPU'];
                $sucursales = Sucursal::where('concesionaria_id',Auth::user()->concesionaria_id)->get();
                switch($tipo_placa){
                    case "A":
                        $tipo_vehiculos = Tipo_Vehiculo::whereIn('id',[1,2,3,4,5,6,7,14,15])->get();
                        break;

                    case "M":
                        $tipo_vehiculos = Tipo_Vehiculo::where('tipo',2)->get();
                        break;


                    case "R":
                        $tipo_vehiculos = Tipo_Vehiculo::whereIn('id',[16,17])->get();
                        break;

                    default:

                        $tipo_vehiculos = Tipo_Vehiculo::all();
                        break;
                }
                
                
                if($id_solicitud == 0){
                    $header = new stdClass;
                    return view('solicitud.createsolicitudnew', compact('region_selected','id_tipo_placa','acceso','documento_rc','reingreso','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','header','id_adquiriente','id_comprapara','id_solicitud_rc'));
                }
                else{
                    $header = new stdClass;
                    $solicitud_data = Solicitud::find($id_solicitud);
                    $factura = Factura::where('id_solicitud',$id_solicitud)->first();
                    $reingreso = Reingreso::where('solicitud_id',$id_solicitud)->whereIn('estado_id',[0,2,3])->first();
                    $documento_rc = EnvioDocumentoRC::where('solicitud_id',$id_solicitud)->first();
                    $id = $id_solicitud;
                    if($factura != null){
                        $header->RUTRecep = (string)$factura->rut_receptor;
                        $header->RznSocRecep = (string)$factura->razon_social_recep;
                        $header->GiroRecep = (string)$factura->giro;
                        $header->Contacto = (string)$factura->contacto;
                        $header->DirRecep = (string)$factura->direccion;
                        $header->CmnaRecep = (string)strtoupper($factura->comuna);
                        $header->CiudadRecep = (string)$factura->ciudad;
                        $header->DirPostal = (string)$factura->direccion;

                        $header->AnnioFabricacion = (string)$factura->agno_fabricacion;
                        $header->Color = (string)$factura->color;
                        $header->TipoCombustible = (string)$factura->tipo_combustible;
                        $header->Marca = (string)$factura->marca;
                        $header->Modelo = (string)$factura->modelo;
                        $header->NroChasis = (string)$factura->nro_chasis;
                        $header->NroMotor = (string)$factura->motor;
                        $header->NroVin  = (string)$factura->nro_vin;
                        $header->PesoBrutoVehi = (string)$factura->peso_bruto_vehicular;
                        $header->TipoVehiculo = (string)$factura->tipo_vehiculo;
                        $header->Asientos = (string) $factura->asientos;
                        $header->Puertas = (string) $factura->puertas;
                        $header->CitModelo = (string) $factura->codigo_cit;
                        $header->TipoPBV = (string) $factura->tipo_pbv;
                        $header->TipoCarga = (string) $factura->tipo_carga;
                    }

                    $adquirientes = Adquiriente::where('solicitud_id',$id_solicitud)->first();
                    if($adquirientes != null){
                        $id_adquiriente = $adquirientes->id;
                        $adquirentes = Adquiriente::getSolicitud($id_solicitud);
                        //Menu compra para
                        $tipo_vehiculo = Solicitud::getTipoVehiculo($id_solicitud);
                        $solicitud_rc = SolicitudRC::getSolicitud($id_solicitud);
                        $id_solicitud_rc = @isset($solicitud_rc->numeroSol)? $solicitud_rc->numeroSol : 0;
                        $comprapara = CompraPara::where('solicitud_id', $id_solicitud)->first();
                        $detalle = array();
                        if ($comprapara != null) {
                            $id_comprapara = $comprapara->id;
                        }
                        else{
                            $no_para = NoPara::where('solicitud_id', $id_solicitud)->first();
                            if($no_para != null){
                                $id_comprapara = $no_para->id;
                            }
                            else{
                                $id_comprapara = 0;
                            }
                        }
                        if ($tipo_vehiculo != null) {
                            switch ($tipo_vehiculo[0]->tipo) {
                                case 1:
                                    $id_tipo_vehiculo = 1;
                                    break;

                                case 2:
                                    $id_tipo_vehiculo = 2;
                                    break;
                                case 3:
                                    $id_tipo_vehiculo = 3;
                                    break;
                            }
                        }
                        return view('solicitud.create', compact('acceso','documento_rc','reingreso','tipo_potencia','tipo_carroceria','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','id','header','id_adquiriente','adquirentes',
                        'id_tipo_vehiculo','id_comprapara','detalle','comprapara','solicitud_rc','id_solicitud_rc'));
                        //return view('solicitud.create', compact('sucursales', 'tipo_vehiculos','ppu','comunas','id_solicitud','id','header','id_adquiriente','adquirentes'));
                    }
                    else{
                        $solicitud_rc = null;
                        $id_solicitud_rc = 0;
                        //Menu adquiriente: solicitud recién creada
                        return view('solicitud.create', compact('acceso','documento_rc','reingreso','tipo_potencia','tipo_carroceria','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','id','header','id_adquiriente','id_tipo_vehiculo','solicitud_rc','id_solicitud_rc'));
                    }
                    
                }
            }else{
                return view('general.ErrorRC', ['glosa' => $ppu['glosa']]); 
            }
        }
        else{
            return view('general.ErrorRC', ['glosa' => 'No hay una respuesta válida desde RC']); 
        }
        
    }

    public function store(SolicitudRequest $request)
    {
        $solicitud_id = $request->get('solicitud_id');
        if($solicitud_id != 0){
            $solicitud = Solicitud::find($solicitud_id);
            $solicitud->sucursal_id = $request->get('sucursal_id');
            if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 5 || Auth::user()->rol_id == 6){
                $solicitud->estado_id = 1;
                $solicitud->user_id = Auth::user()->id;
            }
            $solicitud->tipoVehiculos_id = $request->get('tipoVehiculos_id');
            $solicitud->termino_1 = $request->get('ppu_terminacion');
            $solicitud->id_tipo_placa_patente = $request->get('id_tipo_placa_patente');
            $solicitud->region_id = $request->get('region_selected');
            $solicitud->save();

            //return 0;
        }
        else{
            $solicitud = new Solicitud();
            $solicitud->sucursal_id = $request->get('sucursal_id');
            if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 5 || Auth::user()->rol_id == 6){
                $solicitud->estado_id = 1;
                $solicitud->user_id = Auth::user()->id;
            }
            $solicitud->tipoVehiculos_id = $request->get('tipoVehiculos_id');
            $solicitud->termino_1 = $request->get('ppu_terminacion');
            $solicitud->id_tipo_placa_patente = $request->get('id_tipo_placa_patente');
            $solicitud->region_id = $request->get('region_selected');
            $solicitud->save();
        }
        
        

        if($request->hasFile('Factura_XML')){
            //Si se adjunta documento, se revisa primero si hay un documento anterior
            $trae_doc = Documento::where('solicitud_id',$solicitud->id)->where('name','public/'.$request->file('Factura_XML')->getClientOriginalName())->first();
            if($trae_doc == null){
                //Si no hay doc anterior, se guarda el archivo en servidor y bd
                $file = $request->file('Factura_XML');
                $path = Storage::disk('public')->putFileAs('', $file, $file->getClientOriginalName());
                $extension = $request->file('Factura_XML')->extension();
                $doc = new Documento();
                $doc->name = 'public/'.$path;
                $doc->type = 'pdf';
                $doc->description = 'Factura en PDF';
                $doc->solicitud_id = $solicitud->id;
                $doc->tipo_documento_id = 2;
                $doc->added_at = Carbon::now()->toDateTimeString();
                $doc->save();
            }
            else{
                $trae_doc->type = 'pdf';
                $trae_doc->description = 'Factura en PDF';
                $trae_doc->solicitud_id = $solicitud->id;
                $trae_doc->tipo_documento_id = 2;
                $trae_doc->added_at = Carbon::now()->toDateTimeString();
                $trae_doc->save();
            }

            
            $data_factura = $this->escanearDatosFactura($request);

            $revisa_factura = Factura::where('id_solicitud',$solicitud->id)->first();
            if($revisa_factura == null){
                $fac = new Factura();
                $fac->id_solicitud = $solicitud->id;
                $fac->nro_chasis = trim($data_factura['chasis']);
                $fac->nro_vin = trim($data_factura['nro_vin']);
                $fac->motor = trim($data_factura['motor']);
                $fac->marca = trim($data_factura['marca']);
                $fac->modelo = trim($data_factura['modelo']);
                $fac->peso_bruto_vehicular = trim($data_factura['peso_bruto_vehicular']);
                $fac->tipo_vehiculo = trim($data_factura['tipo_vehiculo']);
                $fac->tipo_combustible = trim($data_factura['combustible']);
                $fac->agno_fabricacion = trim($data_factura['anno']);
                $fac->color = trim($data_factura['color']);
                $fac->tipo_carga = trim($data_factura['tipo_carga']);
                $fac->tipo_pbv = trim($data_factura['tipo_pbv']);
                $fac->num_factura = trim($data_factura['num_factura']);
                $fac->giro = trim($data_factura['giro']);
                $fac->direccion = trim($data_factura['direccion']);
                $fac->comuna = trim($data_factura['comuna']);
                $fac->ciudad = trim($data_factura['ciudad']);
                $fac->contacto = trim($data_factura['contacto']);
                $fac->rut_receptor = trim($data_factura['rut_recep']);
                $fac->razon_social_recep = trim($data_factura['razon_social']);
                $fac->razon_social_emisor = trim($request->get('razon_soc_emisor'));
                $fac->rut_emisor = trim($request->get('rut_emisor'));
                $fac->fecha_emision = trim($request->get('fecha_emision_fac'));
                $fac->monto_total_factura = trim($request->get('monto_factura'));
                $fac->puertas = trim($data_factura['puertas']);
                $fac->asientos = trim($data_factura['asientos']);
                $fac->codigo_cit = trim($data_factura['codigo_cit']);
                $fac->codigo_cid = trim($data_factura['codigo_cid']);
                $fac->save();
            }
            else{
                $revisa_factura->nro_chasis = trim($data_factura['chasis']);
                $revisa_factura->nro_vin = trim($data_factura['nro_vin']);
                $revisa_factura->motor = trim($data_factura['motor']);
                $revisa_factura->marca = trim($data_factura['marca']);
                $revisa_factura->modelo = trim($data_factura['modelo']);
                $revisa_factura->peso_bruto_vehicular = trim($data_factura['peso_bruto_vehicular']);
                $revisa_factura->tipo_vehiculo = trim($data_factura['tipo_vehiculo']);
                $revisa_factura->tipo_combustible = trim($data_factura['combustible']);
                $revisa_factura->agno_fabricacion = trim($data_factura['anno']);
                $revisa_factura->color = trim($data_factura['color']);
                $revisa_factura->tipo_carga = trim($data_factura['tipo_carga']);
                $revisa_factura->tipo_pbv = trim($data_factura['tipo_pbv']);
                $revisa_factura->num_factura = trim($data_factura['num_factura']);
                $revisa_factura->giro = trim($data_factura['giro']);
                $revisa_factura->direccion = trim($data_factura['direccion']);
                $revisa_factura->comuna = trim($data_factura['comuna']);
                $revisa_factura->ciudad = trim($data_factura['ciudad']);
                $revisa_factura->contacto = trim($data_factura['contacto']);
                $revisa_factura->rut_receptor = trim($data_factura['rut_recep']);
                $revisa_factura->razon_social_recep = trim($data_factura['razon_social']);
                $revisa_factura->razon_social_emisor = trim($request->get('razon_soc_emisor'));
                $revisa_factura->rut_emisor = trim($request->get('rut_emisor'));
                $revisa_factura->fecha_emision = trim($request->get('fecha_emision_fac'));
                $revisa_factura->monto_total_factura = trim($request->get('monto_factura'));
                $revisa_factura->puertas = trim($data_factura['puertas']);
                $revisa_factura->asientos = trim($data_factura['asientos']);
                $revisa_factura->codigo_cit = trim($data_factura['codigo_cit']);
                $revisa_factura->codigo_cid = trim($data_factura['codigo_cid']);
                $revisa_factura->save();
            }
            //echo $datos;
            
            /*
            echo 'CHASIS: '.trim($chasis);
            echo "<br>";
            echo 'NRO VIN: '.trim($nro_vin);
            echo "<br>";
            echo 'MOTOR: '.trim($motor);
            echo "<br>";
            echo 'MARCA: '.trim($marca);
            echo "<br>";
            echo 'MODELO: '.trim($modelo);
            echo "<br>";
            echo 'PBV: '.trim($peso_bruto_vehicular);
            echo "<br>";
            echo 'TIPO VEHICULO: '.$tipo_vehiculo2;
            echo "<br>";
            echo 'COMBUSTIBLE: '.$combustible;
            echo "<br>";
            echo 'AÑO: '.$anno;
            echo "<br>";
            echo 'COLOR: '.$color;
            echo "<br>";
            echo 'TIPO CARGA: '.$tipo_carga2;
            echo "<br>";
            echo 'TIPO PBV: '.$tipo_pbv;
            echo "<br>";
            echo 'NUM FACTURA: '.$num_factura;
            echo "<br>";
            echo 'GIRO: '.$giro;
            echo "<br>";
            echo 'DIRECCION: '.$direccion;
            echo "<br>";
            echo 'COMUNA: '.$comuna;
            echo "<br>";
            echo 'CIUDAD: '.$ciudad;
            echo "<br>";
            echo 'CONTACTO: '.$contacto;
            echo "<br>";
            echo 'RUT RECEPTOR: '.$rut_recep;
            echo "<br>";
            echo 'RAZON SOCIAL: '.$razon_social;
            echo "<br>";
            echo 'CIT: '.$codigo_cit;
            echo "<br>";
            echo 'CID: '.$codigo_cid;
            echo "<br>";
            echo 'PUERTAS: '.$puertas;
            echo "<br>";
            echo 'ASIENTOS: '.$asientos2;
            
            die;*/

            
        }else{
            $trae_doc = Documento::where('solicitud_id',$solicitud->id)->where('tipo_documento_id',2)->first();
            if($trae_doc == null){            
                $errors = new MessageBag();
                $errors->add('Documentos', 'Debe adjuntar Factura PDF.');
                return redirect()->route('solicitud.create')->withErrors($errors);
                //Posible flujo para insertar datos manuales de la factura
            }
            else{
                $trae_doc->type = 'pdf';
                $trae_doc->description = 'Factura en PDF';
                $trae_doc->solicitud_id = $solicitud->id;
                $trae_doc->tipo_documento_id = 2;
                $trae_doc->added_at = Carbon::now()->toDateTimeString();
                $trae_doc->save();

                $contents = $trae_doc->name;

                $data_factura = $this->escanearDatosFactura($contents);

                $revisa_factura = Factura::where('id_solicitud',$solicitud->id)->first();
                if($revisa_factura == null){
                    $fac = new Factura();
                    $fac->id_solicitud = $solicitud->id;
                    $fac->nro_chasis = trim($data_factura['chasis']);
                    $fac->nro_vin = trim($data_factura['nro_vin']);
                    $fac->motor = trim($data_factura['motor']);
                    $fac->marca = trim($data_factura['marca']);
                    $fac->modelo = trim($data_factura['modelo']);
                    $fac->peso_bruto_vehicular = trim($data_factura['peso_bruto_vehicular']);
                    $fac->tipo_vehiculo = trim($data_factura['tipo_vehiculo']);
                    $fac->tipo_combustible = trim($data_factura['combustible']);
                    $fac->agno_fabricacion = trim($data_factura['anno']);
                    $fac->color = trim($data_factura['color']);
                    $fac->tipo_carga = trim($data_factura['tipo_carga']);
                    $fac->tipo_pbv = trim($data_factura['tipo_pbv']);
                    $fac->num_factura = trim($data_factura['num_factura']);
                    $fac->giro = trim($data_factura['giro']);
                    $fac->direccion = trim($data_factura['direccion']);
                    $fac->comuna = trim($data_factura['comuna']);
                    $fac->ciudad = trim($data_factura['ciudad']);
                    $fac->contacto = trim($data_factura['contacto']);
                    $fac->rut_receptor = trim($data_factura['rut_recep']);
                    $fac->razon_social_recep = trim($data_factura['razon_social']);
                    $fac->razon_social_emisor = trim($request->get('razon_soc_emisor'));
                    $fac->rut_emisor = trim($request->get('rut_emisor'));
                    $fac->fecha_emision = trim($request->get('fecha_emision_fac'));
                    $fac->monto_total_factura = trim($request->get('monto_factura'));
                    $fac->puertas = trim($data_factura['puertas']);
                    $fac->asientos = trim($data_factura['asientos']);
                    $fac->codigo_cit = trim($data_factura['codigo_cit']);
                    $fac->codigo_cid = trim($data_factura['codigo_cid']);
                    $fac->save();
                }
                else{
                    $revisa_factura->nro_chasis = trim($data_factura['chasis']);
                    $revisa_factura->nro_vin = trim($data_factura['nro_vin']);
                    $revisa_factura->motor = trim($data_factura['motor']);
                    $revisa_factura->marca = trim($data_factura['marca']);
                    $revisa_factura->modelo = trim($data_factura['modelo']);
                    $revisa_factura->peso_bruto_vehicular = trim($data_factura['peso_bruto_vehicular']);
                    $revisa_factura->tipo_vehiculo = trim($data_factura['tipo_vehiculo']);
                    $revisa_factura->tipo_combustible = trim($data_factura['combustible']);
                    $revisa_factura->agno_fabricacion = trim($data_factura['anno']);
                    $revisa_factura->color = trim($data_factura['color']);
                    $revisa_factura->tipo_carga = trim($data_factura['tipo_carga']);
                    $revisa_factura->tipo_pbv = trim($data_factura['tipo_pbv']);
                    $revisa_factura->num_factura = trim($data_factura['num_factura']);
                    $revisa_factura->giro = trim($data_factura['giro']);
                    $revisa_factura->direccion = trim($data_factura['direccion']);
                    $revisa_factura->comuna = trim($data_factura['comuna']);
                    $revisa_factura->ciudad = trim($data_factura['ciudad']);
                    $revisa_factura->contacto = trim($data_factura['contacto']);
                    $revisa_factura->rut_receptor = trim($data_factura['rut_recep']);
                    $revisa_factura->razon_social_recep = trim($data_factura['razon_social']);
                    $revisa_factura->razon_social_emisor = trim($request->get('razon_soc_emisor'));
                    $revisa_factura->rut_emisor = trim($request->get('rut_emisor'));
                    $revisa_factura->fecha_emision = trim($request->get('fecha_emision_fac'));
                    $revisa_factura->monto_total_factura = trim($request->get('monto_factura'));
                    $revisa_factura->puertas = trim($data_factura['puertas']);
                    $revisa_factura->asientos = trim($data_factura['asientos']);
                    $revisa_factura->codigo_cit = trim($data_factura['codigo_cit']);
                    $revisa_factura->codigo_cid = trim($data_factura['codigo_cid']);
                    $revisa_factura->save();
                }
            }
        }

        //session('solicitud_id',$solicitud->id);
        session(['solicitud_id' => $solicitud->id]);

        $header = new stdClass;

        $factura = Factura::where('id_solicitud',$solicitud->id)->first();

        $id = $solicitud->id;

        if($factura != null){
            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)strtoupper($factura->comuna);
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;
        }

        $comunas = Comuna::allOrder();
        $html = view('solicitud.adquirientes', compact('id', 'comunas', 'header'))->render();
        if($solicitud_id != 0){
            return json_encode(array('html'=>$html,'solicitud_id'=>0, 'solicitud_id2'=>$solicitud_id));
        }else{
            return json_encode(array('html'=>$html,'solicitud_id'=>$id));
        }
    }

    private function escanearDatosFactura($request){
        ob_start();
        $pdftoxml = new PdftoXML();
        $pdftoxml->init($request);
        $datos = ob_get_contents();
        ob_clean();

        $datos = str_replace("Ñ",'NN',$datos);
        //echo $datos; 
        //echo "<br>";
        $chasis = '';
        $nro_vin = '';
        $motor = '';
        $marca = '';
        $modelo = '';
        $peso_bruto_vehicular = '';
        $tipo_pbv = '';
        $tipo_vehiculo2 = '';
        $combustible = '';
        $anno = '';
        $color = '';
        $tipo_carga2 = '';
        $num_factura = '';
        $giro = '';
        $direccion = '';
        $comuna = '';
        $ciudad = '';
        $contacto = '';
        $rut_recep = '';
        $razon_social = '';
        $codigo_cit = '';
        $codigo_cid = '';
        $puertas = 0;
        $asientos2 = 0;



        //EXTRAYENDO DATOS CHASIS
        if(stripos($datos,"chasis") !== false){
            $chasis = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"chasis"),strlen($datos))));
            $chasis = str_ireplace("chasis:",'',PdftoXML::substring($chasis,0,strpos($chasis,'<br>')));
        }
        else if (stripos($datos,"chassis") !== false){
            $chasis = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"chassis"),strlen($datos)))) ;
            $chasis = str_ireplace("chassis: ",'',PdftoXML::substring($chasis,0,strpos($chasis,'<br>')));
            if(stripos($datos," ") !== false){
                $chasis = explode(" ",$chasis)[0];
            }
        }
        //$chasis = str_ireplace("chasis:",'',PdftoXML::substring($chasis,0,strpos($chasis,'<br>')));

        //EXTRAYENDO DATOS N° VIN
        if(stripos($datos,"vin:") !== false){
            $nro_vin = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"vin:"),strlen($datos)))) ;
            $nro_vin = str_ireplace("vin:",'',PdftoXML::substring($nro_vin,0,strpos($nro_vin,'<br>')));
        }
        else{
            $nro_vin = '';
        }
        //MOTOR
        $motor = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"motor"),strlen($datos)))) ;
        $motor = str_ireplace(["motor:","motor: ","motor:  "],'',PdftoXML::substring($motor,0,strpos($motor,'<br>')));
        if(stripos($motor," ") !== false){
            $motor = trim($motor);
            $motor = explode(" ",$motor)[0];
        }
        //MARCA
        $marca = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"marca"),strlen($datos)))) ;
        $marca = str_ireplace(["marca:","marca: ","marca:  "],'',PdftoXML::substring($marca,0,strpos($marca,'<br>')));
        if(stripos($marca," ") !== false){
            $marca = trim($marca);
            $marca = explode(" ",$marca)[0];
        }
        //MODELO
        $modelo = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"modelo"),strlen($datos)))) ;
        $modelo = str_ireplace(["modelo:","modelo: ","modelo:  "],'',PdftoXML::substring($modelo,0,strpos($modelo,'<br>')));
        if(stripos($modelo," ") !== false){
            $modelo = trim($modelo);
            $modelo = explode("COLOR:",$modelo)[0];
        }

        //PESO BRUTO VEHICULAR Y TIPO PESO BRUTO VEHICULAR
        $peso_bruto_vehicular = str_ireplace(["&#160;"],'',trim(substr($datos,stripos($datos,"peso bruto vehicular"),strlen($datos))));
        $tipo_pbv = stripos($peso_bruto_vehicular,"kg") !== false ? "K": "T";
        if($tipo_pbv == "K"){
            $peso_bruto_vehicular = str_ireplace("kg",'',$peso_bruto_vehicular);
        }
        else{
            $peso_bruto_vehicular = str_ireplace("ton",'',$peso_bruto_vehicular);
        }
        $peso_bruto_vehicular = str_ireplace("peso bruto vehicular:",'',PdftoXML::substring($peso_bruto_vehicular,0,strpos($peso_bruto_vehicular,'<br>')));

        if(stripos($peso_bruto_vehicular," ") !== false){
            $peso_bruto_vehicular = trim($peso_bruto_vehicular);
            $peso_bruto_vehicular = explode(" ",$peso_bruto_vehicular)[0];
        }

        if(stripos($peso_bruto_vehicular,".") !== false){
            $tipo_pbv = "T";
        }

        if(trim($tipo_pbv) == "K"){
            $peso_bruto_vehicular = round($peso_bruto_vehicular);
        }

        //TIPO VEHICULO
        $tipo_vehiculo = str_ireplace(["&#160;"],'',trim(substr($datos,stripos($datos,"tipo"),strlen($datos)))) ;
        $tipo_vehiculo = str_ireplace(["tipo:","tipo de vehiculo:","tipo: ","tipo de vehiculo: "],'',PdftoXML::substring($tipo_vehiculo,0,strpos($tipo_vehiculo,'<br>')));
        if(stripos($tipo_vehiculo," ") !== false){
            $tipo_vehiculo = trim($tipo_vehiculo);
            $tipo_vehiculo = explode(" ",$tipo_vehiculo)[0];
        }
        $tipo_vehiculo2 = (trim($tipo_vehiculo) == "MOTOCICLETA")? "MOTO": $tipo_vehiculo;

        //COMBUSTIBLE
        if(stripos($datos,"tipo combustible") !== false){
            $combustible = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"tipo combustible"),strlen($datos)))) ;
        }
        else if(stripos($datos,"tipo de combustible") !== false){
            $combustible = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"tipo de combustible"),strlen($datos)))) ;
        }
        $combustible = str_ireplace(["tipo combustible:","tipo de combustible: "],'',PdftoXML::substring($combustible,0,strpos($combustible,'<br>')));
        if(stripos($combustible," ") !== false){
            $combustible = trim($combustible);
            $combustible = explode(" ",$combustible)[0];
        }
        switch(trim($combustible)){
            case "BENCINA":
                $combustible = "GASOLINA";
            break;

            case "ELECTRICO": case "ELÉCTRICO":
                $combustible = "ELECTRICO";
                break;
        }

        //AÑO COMERCIAL
        $anno = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"anno comercial"),strlen($datos)))) ;
        $anno = str_ireplace(["anno comercial:","anno comercial: "],'',PdftoXML::substring($anno,0,strpos($anno,'<br>')));
        if(stripos($anno," ") !== false){
            $anno = trim($anno);
            $anno = explode(" ",$anno)[0];
        }

        //COLOR
        $color = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"color"),strlen($datos))));
        $color = str_ireplace(["color:","color: "],'',PdftoXML::substring($color,0,strpos($color,'<br>')));
        if(stripos($color," ") !== false){
            $color = trim($color);
            $color = explode(" ",$color)[0];
        }

        //TIPO CARGA
        if(stripos($datos,"capacidad carga") !== false){
            $tipo_carga2 = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"capacidad carga"),strlen($datos))));
            $tipo_carga2 = str_ireplace("capacidad carga:",'',PdftoXML::substring($tipo_carga2,0,strpos($tipo_carga2,'<br>')));
            $tipo_carga2 = stripos($tipo_carga2,"KG") !== false? "K" : "T";
        }
        else{
            $tipo_carga2 = "K";
        }

        //$color = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"color"),strlen($datos))));
        //$color = str_ireplace("color:",'',PdftoXML::substring($color,0,strpos($color,'<br>')));

        //N° FACTURA
        $num_factura = substr($datos,mb_stripos($datos,"factura n",0,'UTF-8'),strlen($datos));
        $num_factura = explode(" ",str_ireplace(">factura ",'',PdftoXML::substring($num_factura,0,strpos($num_factura,'<br>'))))[1];

        //GIRO CLIENTE
        if(stripos($datos,"personas naturales") == false){
            $giro = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"giro"),strlen($datos))));
            $giro = str_ireplace("giro:",'',PdftoXML::substring($giro,0,strpos($giro,'<br>')));
        }
        else{
            $giro = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"personas naturales"),strlen($datos))));
            $giro = str_ireplace("giro:",'',PdftoXML::substring($giro,0,strpos($giro,'<br>')));
        }

        //DIRECCION CLIENTE
        if(mb_stripos($datos,"dirección:",0,'UTF-8') !== false){
            $direccion = str_replace("&#160;",'',trim(substr($datos,mb_stripos($datos,"dirección:",0,'UTF-8'),strlen($datos))));
            $direccion = str_ireplace(["dirección:","br>"],'',PdftoXML::substring($direccion,0,strpos($direccion,'<br>')));
        }
        else{
            $direccion = explode("<br>",$datos)[5];
        }

        //COMUNA CLIENTE
        if(stripos($datos,"comuna") !== false){
            $comuna = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"comuna"),strlen($datos))));
            $comuna =  explode(" ",str_ireplace(["comuna:","br>"],'',PdftoXML::substring($comuna,0,strpos($comuna,'<br>'))))[0];
        }
        else{
            $comuna = explode(" ",explode("<br>",$datos)[6])[0];
        }

        //CIUDAD CLIENTE
        if(stripos($datos,"provincia") !== false){
            $ciudad = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"provincia"),strlen($datos))));
            $ciudad = str_ireplace(["provincia :","br>"],'',PdftoXML::substring($ciudad,0,strpos($ciudad,'<br>')));
        }
        else{
            $ciudad = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"ciudad"),strlen($datos))));
            $ciudad = str_ireplace(["ciudad :","br>","ciudad","ciudad "],'',PdftoXML::substring($ciudad,0,strpos($ciudad,'<br>')));
        }

        //N° CONTACTO CLIENTE
        if(stripos($datos,"teléfono")){
            $contacto = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"teléfono"),strlen($datos))));
            $contacto = explode(" ",str_ireplace(["teléfono:"],'',PdftoXML::substring($contacto,0,strpos($contacto,'<br>'))))[0];
            $contacto = substr($contacto,3,strlen($contacto));
        }
        else{
            $contacto = 0;
        }

        //RUT CLIENTE
        if(stripos($datos,"rut receptor")!== false){
            $rut_recep = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"rut receptor"),strlen($datos))));
            $rut_recep = str_ireplace(["rut receptor :",'.'],'',PdftoXML::substring($rut_recep,0,strpos($rut_recep,'<br>')));
        }
        else {
            //dd(explode("<br>",$datos));
            $rut_recep = explode(" ",explode("<br>",$datos)[14])[0];
            $rut_recep = str_ireplace(".",'',$rut_recep);
        }
        

        if(stripos($rut_recep,'-') !== false){
            $rut_recep = substr($rut_recep,0,stripos($rut_recep,'-'));
        }

        //RAZON SOCIAL CLIENTE
        if(stripos($datos,"Señor(es)") !== false){
            $razon_social = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"Señor(es)"),strlen($datos))));
            $razon_social = str_ireplace(["Señor(es):"],'',PdftoXML::substring($razon_social,0,strpos($razon_social,'<br>')));
        }
        else{
            $razon_social = explode("<br>",$datos)[4];
        }
        
        //CODIGO CIT
        if(stripos($datos,"cit:") !== false){
            $codigo_cit = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"cit:"),strlen($datos))));
            $codigo_cit = str_ireplace(["cit: "],'',PdftoXML::substring($codigo_cit,0,strpos($codigo_cit,'<br>')));
            if(stripos($codigo_cit," ") !== false){
                $codigo_cit = trim($codigo_cit);
                $codigo_cit = explode(" ",$codigo_cit)[0];
            }
        }
        else{
            $codigo_cit = '';
        }

        //CODIGO CID

        if(stripos($datos,"cid:") !== false){
            $codigo_cid = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"cid:"),strlen($datos))));
            $codigo_cid = str_ireplace(["cid: "],'',PdftoXML::substring($codigo_cid,0,strpos($codigo_cid,'<br>')));
        }
        else{
            $codigo_cid = '';
        }

        //PUERTAS

        if(stripos($datos,"puertas:") !== false){
            $puertas = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"puertas:"),strlen($datos))));
            $puertas = str_ireplace(["puertas: "],'',PdftoXML::substring($puertas,0,strpos($puertas,'<br>')));
            if(stripos($puertas," ") !== false){
                $puertas = trim($puertas);
                $puertas = explode(" ",$puertas)[0];
            }
        }
        else{
            $puertas = 0;
        }

        //ASIENTOS

        if(stripos($datos,"asientos:") !== false){
            $asientos2 = str_replace("&#160;",'',trim(substr($datos,stripos($datos,"asientos:"),strlen($datos))));
            $asientos2 = str_ireplace(["asientos: "],'',PdftoXML::substring($asientos2,0,strpos($asientos2,'<br>')));
            if(stripos($asientos2," ") !== false){
                $asientos2 = trim($asientos2);
                $asientos2 = explode(" ",$asientos2)[0];
            }
        }
        else{
            $asientos2 = 0;
        }

        return array("chasis"=> $chasis,
                    "nro_vin" => $nro_vin,
                    "motor" => $motor,
                    "marca" => $marca,
                    "modelo" => $modelo,
                    "peso_bruto_vehicular" => $peso_bruto_vehicular,
                    "tipo_pbv" => $tipo_pbv,
                    "tipo_vehiculo" => $tipo_vehiculo2,
                    "combustible" => $combustible,
                    "anno" => $anno,
                    "color" => $color,
                    "tipo_carga" => $tipo_carga2,
                    "num_factura" => $num_factura,
                    "giro" => $giro,
                    "direccion" => $direccion,
                    "comuna" => $comuna,
                    "ciudad" => $ciudad,
                    "contacto" => $contacto,
                    "rut_recep" => $rut_recep,
                    "razon_social" => $razon_social,
                    "codigo_cit" => $codigo_cit,
                    "codigo_cid" => $codigo_cid,
                    "puertas" => $puertas,
                    "asientos" => $asientos2);
    }

    public function adquirientes($id){
        $header = new stdClass;

        $factura = Factura::where('id_solicitud',$id)->first();

        if($factura != null){
            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)strtoupper($factura->comuna);
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;
        }
        /*
        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;
        
        if (Storage::exists($file)) {
            $contents = Storage::get($file);
            $xmlResponse = simplexml_load_string($contents);
            
            $receptor = $this->getNode($xmlResponse, 'Receptor');

            $header->RUTRecep = (string)$receptor->RUTRecep;
            $header->RznSocRecep = (string)$receptor->RznSocRecep;
            $header->GiroRecep = (string)$receptor->GiroRecep;
            $header->Contacto = (string)$receptor->Contacto;
            $header->DirRecep = (string)$receptor->DirRecep;
            $header->CmnaRecep = (string)strtoupper($receptor->CmnaRecep);
            $header->CiudadRecep = (string)$receptor->CiudadRecep;
            $header->DirPostal = (string)$receptor->DirPostal;
        }
        else{
            
        }*/
        $comunas = Comuna::allOrder();
        return view('solicitud.adquirientes', compact('id', 'comunas', 'header'));
    }

    /*
     * Se debe validar el largo de los datos
     */
    public function saveAdquirientes(Request $request, $id){
        $errors = new MessageBag();
        if(is_null($request->input('rut'))) $errors->add('Garantiza', 'Debe Ingresar el Rut del Adquiriente.');
        if(is_null($request->input('nombre'))) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Adquiriente.');
        if(is_null($request->input('calle'))) $errors->add('Garantiza', 'Debe Ingresar la dirección del Adquiriente.');
        if(is_null($request->input('numero'))) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Adquiriente.');
        if($request->input('comuna')=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Adquiriente.');
        if(is_null($request->input('email'))) $errors->add('Garantiza', 'Debe Ingresar el email del Adquiriente.');
        if(is_null($request->input('telefono'))) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Adquiriente.');
        if($request->input('tipoPersona')=='O'){
            if(is_null($request->input('cantidad_integrantes'))) $errors->add('Garantiza', 'Debe Ingresar cantidad de integrantes de comunidad.');
        }

        if($errors->count()>0) return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);

        DB::beginTransaction();

        if($request->input('adquiriente_1') != 0){
            //Edita adquiriente principal
            $adquirente_1 = $request->input('adquiriente_1');
            $adquiriente = Adquiriente::find($adquirente_1);
            $adquiriente->rut = $request->get('rut');
            $adquiriente->nombre = $request->get('nombre');
            $adquiriente->aPaterno = $request->get('aPaterno');
            $adquiriente->aMaterno = $request->get('aMaterno');
            $adquiriente->calle = $request->get('calle');
            $adquiriente->numero = $request->get('numero');
            $adquiriente->rDomicilio = $request->get('rDireccion');
            $adquiriente->comuna = $request->get('comuna');
            $adquiriente->telefono = $request->get('telefono');
            $adquiriente->email = $request->get('email');
            $adquiriente->tipo = $request->get('tipoPersona');
            if($request->tipoPersona=='O'){
                $adquiriente->cantidadIntegrantes = $request->get('cantidad_integrantes');
            }
            $adquiriente->save();
            DB::commit();

            $solicitud = Solicitud::find($id);
            if($adquiriente->tipo == "J"){
                $solicitud->empresa = 1;
                $solicitud->save();
            }

            return true;
        }

        // Graba adquiriente principal
        $adquiriente = new Adquiriente();
        $adquiriente->solicitud_id = $id;
        $adquiriente->rut = $request->get('rut');
        $adquiriente->nombre = $request->get('nombre');
        $adquiriente->aPaterno = $request->get('aPaterno');
        $adquiriente->aMaterno = $request->get('aMaterno');
        $adquiriente->calle = $request->get('calle');
        $adquiriente->numero = $request->get('numero');
        $adquiriente->rDomicilio = $request->get('rDireccion');
        $adquiriente->comuna = $request->get('comuna');
        $adquiriente->telefono = $request->get('telefono');
        $adquiriente->email = $request->get('email');
        $adquiriente->tipo = $request->get('tipoPersona');
        if($request->tipoPersona=='O'){
            $adquiriente->cantidadIntegrantes = $request->get('cantidad_integrantes');
        }

        if(!$adquiriente->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al grabar Adquiriente.');
            return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
        }

        $solicitud = Solicitud::find($id);
        //Solo ejecutivo de concesionaria gatilla los cambios de estado
        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 5 || Auth::user()->rol_id == 6){
            $solicitud->estado_id = 2;
        }
        //Si es persona jurídica, la solicitud es de una empresa como adquiriente
        if($request->get('tipoPersona')=='J'){
            $solicitud->empresa = 1;
        }
        if(!$solicitud->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al actualizar estado de Solicitud.');
            return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
        }

        DB::commit();
        //return redirect()->route('solicitud.compraPara', ['id' => $id]);
        $adquirentes = Adquiriente::getSolicitud($id);
        $comunas = Comuna::allOrder();
        $comprapara = null;
        //return view('solicitud.compraPara', compact('id', 'comunas', 'adquirentes','comprapara'));
        $html = view('solicitud.compraPara', compact('id', 'comunas', 'adquirentes','comprapara'))->render();

        return response()->json(["html" => $html, "id_adquiriente" => $adquiriente->id]);
    }

    public function compraPara($id){
        $comunas = Comuna::allOrder();
        $adquirentes = Adquiriente::getSolicitud($id);
        return view('solicitud.compraPara', compact('id', 'comunas', 'adquirentes'));
    }

    public function saveCompraPara(Request $request, $id){
        $guardacomprapara = $request->input('guardar');
        Log::info('guarda compra para: '.$guardacomprapara);
        if ($guardacomprapara == "SI") {
            Log::info('guardamos compra para');
            $errors = new MessageBag();
            if (!is_null($request->rut)) {
                if (is_null($request->nombre))
                    $errors->add('Garantiza', 'Debe Ingresar el Nombre del Compra/Para.');
                if (is_null($request->calle))
                    $errors->add('Garantiza', 'Debe Ingresar la dirección del Compra/Para.');
                if (is_null($request->numero))
                    $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Compra/Para.');
                if ($request->comuna == "0")
                    $errors->add('Garantiza', 'Debe Ingresar la comuna del Compra/Para.');
                if (is_null($request->email))
                    $errors->add('Garantiza', 'Debe Ingresar el email del Compra/Para.');
                if (is_null($request->telefono))
                    $errors->add('Garantiza', 'Debe Ingresar el teléfono del Compra/Para.');
            }
            else{
                $errors->add('Garantiza','El RUT es requerido para agregar o editar Compra/Para.');
            }
            if ($errors->count() > 0){
                //return redirect()->route('solicitud.compraPara', ['id' => $id])->withErrors($errors);
                return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
            }

            $no_para = NoPara::where('solicitud_id',$id)->first();
            if($no_para != null){
                $no_para->delete();
            }

            DB::beginTransaction();

            if ($request->input('id_comprapara') != 0) {
                //Edita compra para principal
                $id_comprapara = $request->input('id_comprapara');
                $para = CompraPara::find($id_comprapara);
                $para->rut = $request->get('rut');
                $para->nombre = $request->get('nombre');
                $para->aPaterno = $request->get('aPaterno');
                $para->aMaterno = $request->get('aMaterno');
                $para->calle = $request->get('calle');
                $para->numero = $request->get('numero');
                $para->rDomicilio = $request->get('rDireccion');
                $para->comuna = $request->get('comuna');
                $para->telefono = $request->get('telefono');
                $para->email = $request->get('email');
                $para->tipo = $request->get('tipoPersona');
                $para->save();
                DB::commit();

                //return true;
                return response()->json(['status' => "OK","id_para" => $para->id], 200);
            }

            // Graba adquiriente principal
            if (!is_null($request->rut)) {
                $para = new CompraPara();
                $para->solicitud_id = $id;
                $para->rut = $request->get('rut');
                $para->nombre = $request->get('nombre');
                $para->aPaterno = $request->get('aPaterno');
                $para->aMaterno = $request->get('aMaterno');
                $para->calle = $request->get('calle');
                $para->numero = $request->get('numero');
                $para->rDomicilio = $request->get('rDireccion');
                $para->comuna = $request->get('comuna');
                $para->telefono = $request->get('telefono');
                $para->email = $request->get('email');
                $para->tipo = $request->get('tipoPersona');

                if (!$para->save()) {
                    DB::rollBack();
                    $errors->add('Garantiza', 'Problemas al grabar CompraPara.');
                    return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
                    //return redirect()->route('solicitud.compraPara', ['id' => $id])->withErrors($errors);
                }
            }
        }
        else{
            Log::info('NO guardamos compra para');
            //Si existe compra para guardado anteriormente, éste es borrado
            $para_get = CompraPara::where('solicitud_id',$id)->first();
            if ($para_get != null) {
                $para_get->delete();
            }
            $no_para = NoPara::where('solicitud_id',$id)->first();
            //Si no hay nopara, se crea uno, sino, se actualiza
            if($no_para == null){
                $no_para = new NoPara();
                $no_para->solicitud_id = $id;
                $no_para->save();
            }
            else{
                $no_para->solicitud_id = $id;
                $no_para->save();
            }
        }
        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 5 || Auth::user()->rol_id == 6){
            $solicitud = Solicitud::find($id);
            $solicitud->estado_id = 3;
            if(!$solicitud->save()){
                DB::rollBack();
                $errors->add('Garantiza', 'Problemas al actualizar estado de Solicitud.');
                return response()->json(['status' => "ERROR",'errors' => $errors->getMessages()], 400);
                //return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
            }
        }
        DB::commit();

        $solicitud = Solicitud::getTipoVehiculo($id);
        $ruta = '';
        
        switch($solicitud[0]->tipo){
            case 1:
                $ruta = 'solicitud.datosAuto';
                $header = new stdClass;
                $detail = new stdClass;
                $detalle = array();
                $factura = Factura::where('id_solicitud',$id)->first();
                if($factura != null){
                    $header->Folio = (string)$factura->num_factura;
                    $header->FchEmis = '';
                    $header->RUTEmisor = '';
                    $header->RznSoc = '';
                    $header->GiroEmis = '';
                    $header->Sucursal = '';
                    $header->DirOrigen = '';
                    $header->CmnaOrigen = '';
                    $header->CiudadOrigen = '';

                    $header->RUTRecep = (string)$factura->rut_receptor;
                    $header->RznSocRecep = (string)$factura->razon_social_recep;
                    $header->GiroRecep = (string)$factura->giro;
                    $header->Contacto = (string)$factura->contacto;
                    $header->DirRecep = (string)$factura->direccion;
                    $header->CmnaRecep = (string)$factura->comuna;
                    $header->CiudadRecep = (string)$factura->ciudad;
                    $header->DirPostal = (string)$factura->direccion;

                    $header->AnnioFabricacion = (string)$factura->agno_fabricacion;
                    $header->Color = (string)$factura->color;
                    $header->TipoCombustible = (string)$factura->tipo_combustible;
                    $header->Marca = (string)$factura->marca;
                    $header->Modelo = (string)$factura->modelo;
                    $header->NroChasis = (string)$factura->nro_chasis;
                    $header->NroMotor = (string)$factura->motor;
                    $header->NroVin  = (string)$factura->nro_vin;
                    $header->NroSerie  = (string)$factura->nro_serie;
                    $header->PesoBrutoVehi = (string)$factura->peso_bruto_vehicular;
                    $header->TipoVehiculo = (string)$factura->tipo_vehiculo;
                    $header->Asientos = (string) $factura->asientos;
                    $header->Puertas = (string) $factura->puertas;
                    $header->CitModelo = (string) $factura->codigo_cit;
                    $header->TipoPBV = (string) $factura->tipo_pbv;
                    $header->TipoCarga = (string) $factura->tipo_carga;

                    $header->MntNeto = '';
                    $header->MntExe = '';
                    $header->TasaIVA = '';
                    $header->IVA = '';
                    $header->MntTotal = '';
                }
                $comunas = Comuna::allOrder();
                $html = view('solicitud.revision.facturaAuto', compact('id', 'comunas', 'header', 'detalle'))->render();
                if ($guardacomprapara == "SI") {
                    return response()->json(['status' => "OK","id_para" => $para->id, "html" => $html], 200);
                }
                else{
                    return response()->json(['status' => "OK","html" => $html, "id_para" => $no_para->id]);
                }
                break;
            case 2:
                $ruta = 'solicitud.datosMoto';
                $header = new stdClass;
                $detail = new stdClass;
                $detalle = array();
                $factura = Factura::where('id_solicitud',$id)->first();
                if($factura != null){
                    $header->Folio = (string)$factura->num_factura;
                    $header->FchEmis = '';
                    $header->RUTEmisor = '';
                    $header->RznSoc = '';
                    $header->GiroEmis = '';
                    $header->Sucursal = '';
                    $header->DirOrigen = '';
                    $header->CmnaOrigen = '';
                    $header->CiudadOrigen = '';

                    $header->RUTRecep = (string)$factura->rut_receptor;
                    $header->RznSocRecep = (string)$factura->razon_social_recep;
                    $header->GiroRecep = (string)$factura->giro;
                    $header->Contacto = (string)$factura->contacto;
                    $header->DirRecep = (string)$factura->direccion;
                    $header->CmnaRecep = (string)$factura->comuna;
                    $header->CiudadRecep = (string)$factura->ciudad;
                    $header->DirPostal = (string)$factura->direccion;

                    $header->AnnioFabricacion = (string)$factura->agno_fabricacion;
                    $header->Color = (string)$factura->color;
                    $header->TipoCombustible = (string)$factura->tipo_combustible;
                    $header->Marca = (string)$factura->marca;
                    $header->Modelo = (string)$factura->modelo;
                    $header->NroChasis = (string)$factura->nro_chasis;
                    $header->NroMotor = (string)$factura->motor;
                    $header->NroVin  = (string)$factura->nro_vin;
                    $header->NroSerie  = (string)$factura->nro_serie;
                    $header->PesoBrutoVehi = (string)$factura->peso_bruto_vehicular;
                    $header->TipoVehiculo = (string)$factura->tipo_vehiculo;
                    $header->Asientos = (string) $factura->asientos;
                    $header->Puertas = (string) $factura->puertas;
                    $header->CitModelo = (string) $factura->codigo_cit;
                    $header->TipoPBV = (string) $factura->tipo_pbv;
                    $header->TipoCarga = (string) $factura->tipo_carga;

                    $header->MntNeto = '';
                    $header->MntExe = '';
                    $header->TasaIVA = '';
                    $header->IVA = '';
                    $header->MntTotal = '';
                }
                $comunas = Comuna::allOrder();
                $html = view('solicitud.revision.facturaMoto', compact('id', 'comunas', 'header', 'detalle'))->render();
                if ($guardacomprapara == "SI") {
                    return response()->json(['status' => "OK","id_para" => $para->id, "html" => $html], 200);
                }
                else{
                    return response()->json(['status' => "OK","html" => $html, "id_para" => $no_para->id]);
                }
                break;
            case 3:
                $ruta = 'solicitud.datosCamion';
                $header = new stdClass;
                $detail = new stdClass;
                $detalle = array();
                $factura = Factura::where('id_solicitud',$id)->first();
                
                if($factura != null){
                    $header->Folio = (string)$factura->num_factura;
                    $header->FchEmis = '';
                    $header->RUTEmisor = '';
                    $header->RznSoc = '';
                    $header->GiroEmis = '';
                    $header->Sucursal = '';
                    $header->DirOrigen = '';
                    $header->CmnaOrigen = '';
                    $header->CiudadOrigen = '';

                    $header->RUTRecep = (string)$factura->rut_receptor;
                    $header->RznSocRecep = (string)$factura->razon_social_recep;
                    $header->GiroRecep = (string)$factura->giro;
                    $header->Contacto = (string)$factura->contacto;
                    $header->DirRecep = (string)$factura->direccion;
                    $header->CmnaRecep = (string)$factura->comuna;
                    $header->CiudadRecep = (string)$factura->ciudad;
                    $header->DirPostal = (string)$factura->direccion;
                    $header->AnnioFabricacion = (string)$factura->agno_fabricacion;
                    $header->Color = (string)$factura->color;
                    $header->TipoCombustible = (string)$factura->tipo_combustible;
                    $header->Marca = (string)$factura->marca;
                    $header->Modelo = (string)$factura->modelo;
                    $header->NroChasis = (string)$factura->nro_chasis;
                    $header->NroMotor = (string)$factura->motor;
                    $header->NroVin  = (string)$factura->nro_vin;
                    $header->NroSerie  = (string)$factura->nro_serie;
                    $header->PesoBrutoVehi = (string)$factura->peso_bruto_vehicular;
                    $header->TipoVehiculo = (string)$factura->tipo_vehiculo;
                    $header->Asientos = (string) $factura->asientos;
                    $header->Puertas = (string) $factura->puertas;
                    $header->CitModelo = (string) $factura->codigo_cit;
                    $header->TipoPBV = (string) $factura->tipo_pbv;
                    $header->TipoCarga = (string) $factura->tipo_carga;

                    $header->MntNeto = '';
                    $header->MntExe = '';
                    $header->TasaIVA = '';
                    $header->IVA = '';
                    $header->MntTotal = '';
                }
                $comunas = Comuna::allOrder();
                $tipo_carroceria = Tipo_Carroceria::all();
                $tipo_potencia = TipoPotencia::all();
                $html = view('solicitud.revision.facturaCamion', compact('id','tipo_potencia','tipo_carroceria', 'comunas', 'header', 'detalle'))->render();
                if ($guardacomprapara == "SI") {
                    return response()->json(['status' => "OK","id_para" => $para->id, "html" => $html], 200);
                }
                else{
                    return response()->json(['status' => "OK","html" => $html, "id_para" => $no_para->id]);
                }
                break;
        }

        //return redirect()->route($ruta, ['id' => $id]);
    }

    public function getNode($obj, $node) {
        if($obj->getName() == $node) { 
            return $obj;
        }
        foreach ($obj->children() as $child) {
            $findme = $this->getNode($child, $node);
            if($findme) return $findme;
        }
    }

    public function sinTerminar()
    {
        $header = new stdClass;

        $solicitudes = Solicitud::sinTerminar(auth()->user()->id);
        
        $SolicitudItem = array();
        foreach($solicitudes as $item){
            try{
                $item->cliente = Factura::select('razon_social_recep')->where('id_solicitud',$item->id)->first();
                
            }
            catch(Exception $e){
                $item->cliente = '';
            }
        }
        
        //return dd($solicitudes);
        return view('solicitud.sinTerminar', compact('solicitudes'));
    }

    public function datosMoto($id){
        $header = new stdClass;
        $detail = new stdClass;

        $detalle = array();

        $factura = Factura::where('id_solicitud',$id)->first();

        if($factura != null){

            $header->Folio = (string)$factura->num_factura;
            //$header->FchEmis = (string)$encabezado->IdDoc->FchEmis;
            //$header->RUTEmisor = (string)$encabezado->Emisor->RUTEmisor;
            //$header->RznSoc = (string)$encabezado->Emisor->RznSoc;
            //$header->GiroEmis = (string)$encabezado->Emisor->GiroEmis;
            //$header->Sucursal = (string)$encabezado->Emisor->Sucursal;
            //$header->DirOrigen = (string)$encabezado->Emisor->DirOrigen;
            //$header->CmnaOrigen = (string)$encabezado->Emisor->CmnaOrigen;
            //$header->CiudadOrigen = (string)$encabezado->Emisor->CiudadOrigen;


            $header->FchEmis = '';
            $header->RUTEmisor = '';
            $header->RznSoc = '';
            $header->GiroEmis = '';
            $header->Sucursal = '';
            $header->DirOrigen = '';
            $header->CmnaOrigen = '';
            $header->CiudadOrigen = '';



            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)$factura->comuna;
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;

            $header->AnnioFabricacion = (string)$factura->agno_fabricacion;
            $header->Color = (string)$factura->color;
            $header->TipoCombustible = (string)$factura->tipo_combustible;
            $header->Marca = (string)$factura->marca;
            $header->Modelo = (string)$factura->modelo;
            $header->NroChasis = (string)$factura->nro_chasis;
            $header->NroMotor = (string)$factura->motor;
            $header->NroVin  = (string)$factura->nro_vin;
            $header->PesoBrutoVehi = (string)$factura->peso_bruto_vehicular;
            $header->TipoVehiculo = (string)$factura->tipo_vehiculo;
            //$header->MntNeto = (string)$encabezado->Totales->MntNeto;
            //$header->MntExe = (string)$encabezado->Totales->MntExe;
            //$header->TasaIVA = (string)$encabezado->Totales->TasaIVA;
            //$header->IVA = (string)$encabezado->Totales->IVA;
            //$header->MntTotal = (string)$encabezado->Totales->MntTotal;

            $header->MntNeto = '';
            $header->MntExe = '';
            $header->TasaIVA = '';
            $header->IVA = '';
            $header->MntTotal = '';


        }
        /*

        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;

        if (Storage::exists($file)) {
            $contents = Storage::get($file);
            $xmlResponse = simplexml_load_string($contents);

            $encabezado = $this->getNode($xmlResponse, 'Encabezado');
            if($encabezado){
                $header->Folio = (string)$encabezado->IdDoc->Folio;
                $header->FchEmis = (string)$encabezado->IdDoc->FchEmis;
                $header->RUTEmisor = (string)$encabezado->Emisor->RUTEmisor;
                $header->RznSoc = (string)$encabezado->Emisor->RznSoc;
                $header->GiroEmis = (string)$encabezado->Emisor->GiroEmis;
                $header->Sucursal = (string)$encabezado->Emisor->Sucursal;
                $header->DirOrigen = (string)$encabezado->Emisor->DirOrigen;
                $header->CmnaOrigen = (string)$encabezado->Emisor->CmnaOrigen;
                $header->CiudadOrigen = (string)$encabezado->Emisor->CiudadOrigen;
                $header->RUTRecep = (string)$encabezado->Receptor->RUTRecep;
                $header->RznSocRecep = (string)$encabezado->Receptor->RznSocRecep;
                $header->GiroRecep = (string)$encabezado->Receptor->GiroRecep;
                $header->Contacto = (string)$encabezado->Receptor->Contacto;
                $header->DirRecep = (string)$encabezado->Receptor->DirRecep;
                $header->CmnaRecep = (string)$encabezado->Receptor->CmnaRecep;
                $header->CiudadRecep = (string)$encabezado->Receptor->CiudadRecep;
                $header->DirPostal = (string)$encabezado->Receptor->DirPostal;
                $header->MntNeto = (string)$encabezado->Totales->MntNeto;
                $header->MntExe = (string)$encabezado->Totales->MntExe;
                $header->TasaIVA = (string)$encabezado->Totales->TasaIVA;
                $header->IVA = (string)$encabezado->Totales->IVA;
                $header->MntTotal = (string)$encabezado->Totales->MntTotal;
            }            
            
            $detail = $this->getNode($xmlResponse, 'Detalle');
            $detalle = explode(chr(10), (string)$detail->DscItem);
            

        }*/


        $comunas = Comuna::allOrder();
        
        return view('solicitud.revision.facturaMoto', compact('id', 'comunas', 'header', 'detalle'));
    }

    public function datosAuto($id){
        $header = new stdClass;
        $detail = new stdClass;

        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;

        $header = new stdClass;
        $detail = new stdClass;

        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;

        if (Storage::exists($file)) {
            $contents = Storage::get($file);
            $xmlResponse = simplexml_load_string($contents);

            $encabezado = $this->getNode($xmlResponse, 'Encabezado');
            if($encabezado){
                $header->Folio = (string)$encabezado->IdDoc->Folio;
                $header->FchEmis = (string)$encabezado->IdDoc->FchEmis;
                $header->RUTEmisor = (string)$encabezado->Emisor->RUTEmisor;
                $header->RznSoc = (string)$encabezado->Emisor->RznSoc;
                $header->GiroEmis = (string)$encabezado->Emisor->GiroEmis;
                $header->Sucursal = (string)$encabezado->Emisor->Sucursal;
                $header->DirOrigen = (string)$encabezado->Emisor->DirOrigen;
                $header->CmnaOrigen = (string)$encabezado->Emisor->CmnaOrigen;
                $header->CiudadOrigen = (string)$encabezado->Emisor->CiudadOrigen;
                $header->RUTRecep = (string)$encabezado->Receptor->RUTRecep;
                $header->RznSocRecep = (string)$encabezado->Receptor->RznSocRecep;
                $header->GiroRecep = (string)$encabezado->Receptor->GiroRecep;
                $header->Contacto = (string)$encabezado->Receptor->Contacto;
                $header->DirRecep = (string)$encabezado->Receptor->DirRecep;
                $header->CmnaRecep = (string)$encabezado->Receptor->CmnaRecep;
                $header->CiudadRecep = (string)$encabezado->Receptor->CiudadRecep;
                $header->DirPostal = (string)$encabezado->Receptor->DirPostal;
                $header->MntNeto = (string)$encabezado->Totales->MntNeto;
                $header->MntExe = (string)$encabezado->Totales->MntExe;
                $header->TasaIVA = (string)$encabezado->Totales->TasaIVA;
                $header->IVA = (string)$encabezado->Totales->IVA;
                $header->MntTotal = (string)$encabezado->Totales->MntTotal;
            }            
            
            $detail = $this->getNode($xmlResponse, 'Detalle');
            $detalle = explode(chr(10), (string)$detail->DscItem);
            $comunas = Comuna::allOrder();

        }
        
        return view('solicitud.revision.facturaAuto', compact('id', 'comunas', 'header', 'detalle'));

    }

    public function datosCamion($id){
        return 'datos Camión';
        return view('solicitud.compraPara', compact('id', 'comunas'));
    }

    public function show($id)
    {
        $solicitud = Solicitud::DatosSolicitud($id);
        $tramites = Solicitud::Tramites($id);
        $para = Para::find($id);
        $documentos = Solicitud::DocumentosSolicitud($id);
        return view('solicitud.show', compact('solicitud', 'tramites', 'para', 'documentos'));

    }

    public function RevisionCedula($id)
    {
        $header = new stdClass;
        //Obtengo listado de rechazos
        $rechazos = Rechazo::all();
        //Obtengo los documentos de la solicitud
        $documentos = Solicitud::DocumentosSolicitud($id);
        
        //Obtengo la cédula subida del cliente en pdf
        $cedula_cliente = $documentos->where('tipo_documento_id', '3')->where('solicitud_id',$id)->first();
        $cedula_compra_para = $documentos->where('tipo_documento_id', '5')->where('solicitud_id',$id)->first();
        $factura_cliente = $documentos->where('tipo_documento_id', '2')->where('solicitud_id',$id)->first();
        $rol_empresa = $documentos->where('tipo_documento_id', '4')->where('solicitud_id',$id)->first();
        $doc_limitacion = $documentos->whereIn('tipo_documento_id', [6,7])->where('solicitud_id',$id)->first();
        $doc_primera_inscri = $documentos->where('tipo_documento_id', '8')->where('solicitud_id',$id)->first();

        $id_solicitud = $id;
        $comunas = Comuna::allOrder();
        $reingresa = false;
        $acceso = "revision";
        $header = new stdClass;
        $factura = Factura::where('id_solicitud',$id_solicitud)->first();
        $solicitud_data = Solicitud::find($id);
        $reingreso = Reingreso::where('solicitud_id',$id_solicitud)->whereIn('estado_id',[0,2,3])->first();
        $documento_rc = EnvioDocumentoRC::where('solicitud_id',$id_solicitud)->first();
        if(Auth::user()->rol_id == 4 || Auth::user()->rol_id == 5 || Auth::user()->rol_id == 6){
            $sucursales = Sucursal::where('concesionaria_id',Auth::user()->concesionaria_id)->get();
        }
        else{
            $sucursales = Sucursal::all();
        }
        $tipo_vehiculos = Tipo_Vehiculo::all();
        $tipo_potencia = TipoPotencia::all();
        $ppu = array();
        $region = Region::all();
        $tipo_carroceria = Tipo_Carroceria::all();
        $solicita_ppu = true;

        if($reingresa){
            if($reingreso == null){
                $solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();
                if($solicitud_rc != null){
                    $new_reingreso = new Reingreso();
                    $new_reingreso->ppu = explode('-',str_replace('.','',$solicitud_rc->ppu))[0];
                    $new_reingreso->nroSolicitud = $solicitud_rc->numeroSol;
                    $new_reingreso->solicitud_id = $id;
                    $new_reingreso->estado_id = 0; //Pendiente de reingreso
                    //$new_reingreso->observaciones = json_encode($observaciones);
                    $new_reingreso->save();
                }
            }
        }

        if($factura != null){
            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)strtoupper($factura->comuna);
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;

            $header->AnnioFabricacion = (string)$factura->agno_fabricacion;
            $header->Color = (string)$factura->color;
            $header->TipoCombustible = (string)$factura->tipo_combustible;
            $header->Marca = (string)$factura->marca;
            $header->Modelo = (string)$factura->modelo;
            $header->NroChasis = (string)$factura->nro_chasis;
            $header->NroMotor = (string)$factura->motor;
            $header->NroVin  = (string)$factura->nro_vin;
            $header->NroSerie  = (string)$factura->nro_serie;
            $header->PesoBrutoVehi = (string)$factura->peso_bruto_vehicular;
            $header->TipoVehiculo = (string)$factura->tipo_vehiculo;
            $header->Asientos = (string) $factura->asientos;
            $header->Puertas = (string) $factura->puertas;
            $header->CitModelo = (string) $factura->codigo_cit;
            $header->TipoPBV = (string) $factura->tipo_pbv;
            $header->TipoCarga = (string) $factura->tipo_carga;

            $header->FchEmis = (string)$factura->fecha_emision;
            $header->RUTEmisor = (string)$factura->rut_emisor;
            $header->RznSoc = (string)$factura->razon_social_emisor;
            $header->MntTotal = (string)$factura->monto_total_factura;

            $header->MntNeto = '';
            $header->MntExe = '';
            $header->TasaIVA = '';
            $header->IVA = '';
            
        }

        $id_adquiriente = 0;
        $id_comprapara = 0;
        $id_tipo_vehiculo = 0;
        $id_solicitud_rc = 0;
        $detalle = array();
        $adquirientes = Adquiriente::where('solicitud_id',$id_solicitud)->first();
        if($adquirientes != null){
            //Menu compra para
            $id_adquiriente = $adquirientes->id;
            $adquirentes = Adquiriente::getSolicitud($id_solicitud);
            $tipo_vehiculo = Solicitud::getTipoVehiculo($id_solicitud);
            $solicitud_rc = SolicitudRC::getSolicitud($id_solicitud);
            //dd($solicitud_rc);
            $id_solicitud_rc = @isset($solicitud_rc[0]->numeroSol)? $solicitud_rc[0]->numeroSol : 0;
            //dd($id_solicitud_rc);
            $comprapara = CompraPara::where('solicitud_id', $id_solicitud)->first();
            if ($comprapara != null) {
                $id_comprapara = $comprapara->id;
            }
            else{
                $no_para = NoPara::where('solicitud_id', $id_solicitud)->first();
                if($no_para != null){
                    $id_comprapara = $no_para->id;
                }
                else{
                    $id_comprapara = 0;
                }
            }
            Log::info('tipo vehiculo: '.$tipo_vehiculo);
            if ($tipo_vehiculo != null) {
                switch ($tipo_vehiculo[0]->tipo) {
                    case 1:
                        $id_tipo_vehiculo = 1;
                        break;

                    case 2:
                        $id_tipo_vehiculo = 2;
                        break;
                    case 3:
                        $id_tipo_vehiculo = 3;
                        break;
                }
            }
            return view('solicitud.revision.cedula', compact('rechazos','header','doc_primera_inscri','doc_limitacion','rol_empresa', 'cedula_cliente','factura_cliente','cedula_compra_para','acceso','documento_rc','reingreso','tipo_potencia','tipo_carroceria','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','id','header','id_adquiriente','adquirentes',
            'id_tipo_vehiculo','id_comprapara','detalle','comprapara','solicitud_rc','id_solicitud_rc'));
        }
        else{
            $solicitud_rc = null;
        }
        //Menu adquiriente: solicitud recién creada
        return view('solicitud.revision.cedula', compact('rechazos','header','doc_primera_inscri','doc_limitacion','rol_empresa', 'cedula_cliente','factura_cliente','cedula_compra_para','acceso','documento_rc','reingreso','tipo_potencia','tipo_carroceria','solicita_ppu','region','sucursales','solicitud_data', 'tipo_vehiculos','ppu','comunas','id_solicitud','id','header','id_adquiriente','id_tipo_vehiculo','id_comprapara','solicitud_rc','id_solicitud_rc'));
       
        //return view('solicitud.revision.cedula', compact('rechazos','header','doc_limitacion','rol_empresa', 'cedula_cliente','factura_cliente','cedula_compra_para', 'id'));
    }

    public function updateRevisionCedula(Request $request, $id)
    {
        $motivo = $request->get('motivo_rechazo');

        if(!$request->has('aprobado')&&$motivo=="0"){
            $errors = new MessageBag();
            $errors->add('Garantiza', 'Debe seleccionar un motivo de rechazo.');
            return redirect()->route('solicitud.revision.cedula', ['id' => $id]);
        }

        if(!$request->has('aprobado')){
            $solicitud = Solicitud::findOrFail($id);
            $solicitud->estado_id = 9;
            $solicitud->rechazo_id = $motivo;
            $solicitud->save();
            return redirect()->route('solicitud.revision');
        }

        if($request->has('aprobado')){
            $solicitud = Solicitud::findOrFail($id);
            $solicitud->estado_id = 8;
            $solicitud->save();
            return redirect()->route('solicitud.revision');
        }

        //return redirect()->route(Funciones::FlujoRevision(1, $id), ['id' => $id]);
    }

    public function RevisionPPU($id){
        $solicitud_PPU = Solicitud::PPU_Solicitud($id);

        $parametro = [
            'Region' => $solicitud_PPU[0]->region,
            'TipoPlaca' => 'M'
        ];

        $data = RegistroCivil::PPUDisponible($parametro);
        $ppu = json_decode($data, true);

        if($ppu['codigoresp']=='OK'){
            $ppu = $ppu['PPU'];
            return view('solicitud.revision.PPU', compact('solicitud_PPU', 'ppu', 'id'));
        }else{
            return view('general.ErrorRC', ['glosa' => $ppu['glosa']]); 
        }
    }

    public function updateRevisionPPU(Request $request, $id){
        if (!$request->has('ppu_terminacion')||$request->get('ppu_terminacion')==''){
            $errors = new MessageBag();
            $errors->add('Garantiza', 'Debe seleccionar una terminación de PPU.');
            return redirect()->route('solicitud.revision.PPU', ['id' => $id])->withErrors($errors);
        }
        return redirect()->route('solicitud.revision.facturaMoto', ['id' => $id, 'PPU' => $request->get('ppu_terminacion')]);
    }

    public function RevisionFacturaMoto($id, $PPU)
    {
        $header = new stdClass;
        $detail = new stdClass;

        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;
        
        if (Storage::exists($file)) {
            $contents = Storage::get($file);
            $xmlResponse = simplexml_load_string($contents);
            //return dd($xmlResponse);
            if($xmlResponse->Documento->Encabezado){
                $header->Folio = (string)$xmlResponse->Documento->Encabezado->IdDoc->Folio;
                $header->FchEmis = (string)$xmlResponse->Documento->Encabezado->IdDoc->FchEmis;
                $header->RUTEmisor = (string)$xmlResponse->Documento->Encabezado->Emisor->RUTEmisor;
                $header->RznSoc = (string)$xmlResponse->Documento->Encabezado->Emisor->RznSoc;
                $header->GiroEmis = (string)$xmlResponse->Documento->Encabezado->Emisor->GiroEmis;
                $header->Sucursal = (string)$xmlResponse->Documento->Encabezado->Emisor->Sucursal;
                $header->DirOrigen = (string)$xmlResponse->Documento->Encabezado->Emisor->DirOrigen;
                $header->CmnaOrigen = (string)$xmlResponse->Documento->Encabezado->Emisor->CmnaOrigen;
                $header->CiudadOrigen = (string)$xmlResponse->Documento->Encabezado->Emisor->CiudadOrigen;
                $header->RUTRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->RUTRecep;
                $header->RznSocRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->RznSocRecep;
                $header->GiroRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->GiroRecep;
                $header->Contacto = (string)$xmlResponse->Documento->Encabezado->Receptor->Contacto;
                $header->DirRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->DirRecep;
                $header->CmnaRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->CmnaRecep;
                $header->CiudadRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->CiudadRecep;
                $header->DirPostal = (string)$xmlResponse->Documento->Encabezado->Receptor->DirPostal;
                $header->MntNeto = (string)$xmlResponse->Documento->Encabezado->Totales->MntNeto;
                $header->MntExe = (string)$xmlResponse->Documento->Encabezado->Totales->MntExe;
                $header->TasaIVA = (string)$xmlResponse->Documento->Encabezado->Totales->TasaIVA;
                $header->IVA = (string)$xmlResponse->Documento->Encabezado->Totales->IVA;
                $header->MntTotal = (string)$xmlResponse->Documento->Encabezado->Totales->MntTotal;
            }

            if($xmlResponse->Documento->Detalle){
                $detail->DscItem = (string)$xmlResponse->Documento->Detalle->DscItem;
                $detail->DscItem = str_replace("\n", "", $detail->DscItem);
                $detalle = explode('^^', $detail->DscItem);
            }
        }

        $comunas = Comuna::all();
        return view('solicitud.revision.facturaMoto', compact('header', 'detalle', 'id', 'comunas', 'PPU'));
    }

    public function generaJson($id){

        /************************************************* 
            Rut Garantiza :  77880510-3
            Rut Demo      :  76401911-3
        **************************************************/
                
        $para = CompraPara::getSolicitud($id);
        $compraParaDTO = new stdClass;
        $adquirientes = Adquiriente::getSolicitud($id);
        $listaAdquierente = array();
        //$listaAdquierente = new stdClass;
        $comunidadDTO = new stdClass;
        $documentoDTO = new stdClass;
        $impuestoVerdeDTO = new stdClass;
        $vehiculo = InfoVehiculo::getSolicitud($id);
        $moto = new stdClass;
        $solicitud = Solicitud::find($id);
        $operadorDTO = new stdClass;
        $solicitanteDTO = new stdClass;

        $observaciones = is_null($solicitud->observaciones) ? '' : $solicitud->observaciones;

        // CompraPara
        if($para->count()==0){
            $compraParaDTO->calidad = $adquirientes[0]->tipo;
            $compraParaDTO->calle = $adquirientes[0]->calle;
            $compraParaDTO->comuna = $adquirientes[0]->comuna;
            $compraParaDTO->email = $adquirientes[0]->email;
            $compraParaDTO->ltrDomicilio = '';
            $compraParaDTO->nombresRazon = $adquirientes[0]->nombre;
            $compraParaDTO->nroDomicilio = $adquirientes[0]->numero;
            $compraParaDTO->runRut = str_replace('.', '', str_replace('-', '', substr($adquirientes[0]->rut, 0, -1)));
            $compraParaDTO->telefono = $adquirientes[0]->telefono;
            $compraParaDTO->aMaterno = $adquirientes[0]->aMaterno;
            $compraParaDTO->aPaterno = $adquirientes[0]->aPaterno;  
            $compraParaDTO->cPostal = '';
            $compraParaDTO->rDomicilio = is_null($adquirientes[0]->rDomicilio) ? '' : $adquirientes[0]->rDomicilio;
        }else{
            $compraParaDTO->calidad = $para->tipo;
            $compraParaDTO->calle = $para->calle;
            $compraParaDTO->comuna = $para->comuna;
            $compraParaDTO->email = $para->email;
            $compraParaDTO->ltrDomicilio = '';
            $compraParaDTO->nombresRazon = $para->nombre;
            $compraParaDTO->nroDomicilio = $para->numero;
            $compraParaDTO->runRut = str_replace('.', '', str_replace('-', '', substr($para->rut, 0, -1)));
            $compraParaDTO->telefono = $para->telefono;
            $compraParaDTO->aMaterno = $para->aMaterno;
            $compraParaDTO->aPaterno = $para->aPaterno;  
            $compraParaDTO->cPostal = '';
            $compraParaDTO->rDomicilio = $para->rDomicilio;
        }

        // Comunidad
        if($adquirientes->count()==1){
            $comunidadDTO->cantidad = '0';
            $comunidadDTO->esComunidad = 'NO';
        }else{
            $comunidadDTO->cantidad = $adquirientes->count();
            $comunidadDTO->esComunidad = 'SI';
        }

        // documentoDTO
        $documentoDTO->emisor = 'PRUEBA';
        $documentoDTO->fecha = '20210223';
        $documentoDTO->lugar = '94';
        $documentoDTO->mbTotal = 150000;
        $documentoDTO->numero = 123;
        $documentoDTO->tipo = 'FACTURA ELECTRONICA';
        $documentoDTO->rEmisor = '91139000';
        $documentoDTO->tMoneda = '$';

        // Impuesto Verde
        $impuestoVerdeDTO->cid = '';
        $impuestoVerdeDTO->cit = '';
        $impuestoVerdeDTO->mImpuesto = '';
        $impuestoVerdeDTO->tFactura = '';

        foreach($adquirientes as $nodo){
            $persona = new stdClass;
            $persona->calidad = $nodo->tipo;
            $persona->calle = $nodo->calle;
            $persona->comuna = $nodo->comuna;
            $persona->email = $nodo->email;
            $persona->ltrDomicilio = '';
            $persona->nombresRazon = $nodo->nombre;
            $persona->nroDomicilio = $nodo->numero;
            $persona->runRut = str_replace('.', '', str_replace('-', '', substr($nodo->rut, 0, -1)));
            $persona->telefono = $nodo->telefono;
            $persona->aMaterno = $nodo->aMaterno;
            $persona->aPaterno = $nodo->aPaterno;  
            $persona->cPostal = '';
            $persona->rDomicilio = is_null($nodo->rDomicilio) ? '' : $nodo->rDomicilio;
            array_push($listaAdquierente, $persona);
        }

        // Moto
        $moto->agnoFabricacion = $vehiculo[0]->agnoFabricacion;
        $moto->asientos = is_null($vehiculo[0]->asientos) ? '0' : $vehiculo[0]->asientos;
        $moto->carga = is_null($vehiculo[0]->asientos) ? '0' : $vehiculo[0]->carga;
        $moto->citModelo = is_null($vehiculo[0]->asientos) ? '' : $vehiculo[0]->citModelo;
        $moto->color = $vehiculo[0]->color;
        $moto->combustible = $vehiculo[0]->combustible;
        $moto->marca = $vehiculo[0]->marca;
        $moto->modelo = $vehiculo[0]->modelo;
        $moto->nroChasis = $vehiculo[0]->nroChasis;
        $moto->nroMotor = $vehiculo[0]->nroMotor;
        $moto->nroSerie = $vehiculo[0]->nroSerie;
        $moto->nroVin = $vehiculo[0]->nroVin;
        $moto->pbv = 1500; //$vehiculo[0]->pbv;
        $moto->puertas = is_null($vehiculo[0]->puertas) ? '0' : $vehiculo[0]->puertas;
        $moto->terminacionPPU = $vehiculo[0]->terminacionPPU;
        $moto->tipoVehiculo = $vehiculo[0]->tipoVehiculo;
        $moto->tCarga = $vehiculo[0]->tCarga;
        $moto->tPbv = $vehiculo[0]->tPbv;

        // Operador
        $operadorDTO->region = 13;
        $operadorDTO->runUsuario = '10796553';
        $operadorDTO->rEmpresa = '77880510';
        
        // Solicitante -> Cambiar
        $solicitanteDTO->calidad = $adquirientes[0]->tipo;
        $solicitanteDTO->calle = 'LAS TINAJAS'; //$adquirientes[0]->calle;
        $solicitanteDTO->comuna = '106'; //$adquirientes[0]->comuna;
        $solicitanteDTO->email = 'rodbay07@gmail.com'; //$adquirientes[0]->email;
        $solicitanteDTO->ltrDomicilio = '';
        $solicitanteDTO->nombresRazon = 'ROMAN ALEXIS'; //$adquirientes[0]->nombre;
        $solicitanteDTO->nroDomicilio = '1886'; //$adquirientes[0]->numero;
        $solicitanteDTO->runRut = '10796553'; // str_replace('.', '', str_replace('-', '', substr($adquirientes[0]->rut, 0, -1)));
        $solicitanteDTO->telefono = '979761113'; //$adquirientes[0]->telefono;
        $solicitanteDTO->aMaterno = 'RAVEST'; //$adquirientes[0]->aMaterno;
        $solicitanteDTO->aPaterno = 'PINTO'; //$adquirientes[0]->aPaterno;  
        $solicitanteDTO->cPostal = '';
        $solicitanteDTO->rDomicilio = ''; //is_null($adquirientes[0]->rDomicilio) ? '' : $adquirientes[0]->rDomicilio;

        $json = compact('compraParaDTO', 'comunidadDTO', 'documentoDTO', 'impuestoVerdeDTO', 'listaAdquierente', 'moto', 'observaciones', 'operadorDTO', 'solicitanteDTO');
        //return $json;
        

        // Llamar RC
        $parametros = array(
            'llave' => array(
                'consumidor' => 'ACOBRO',
                'servicio' => 'PRIMERA INSCRIPCION MOTOS',
                'tramite' => 'PRUEBA'
            ),
            'spieMoto' => $json
        );
        //return $parametros;


        $data = RegistroCivil::creaMoto($json);
        $salida = json_decode($data, true);

        if(!$salida['codigoresp']=='OK'){
            return view('general.ErrorRC', ['glosa' => $salida['glosa']]); 
        }
        return dd($salida);
        
    }


    public function updateRevisionFacturaMoto(Request $request, $id){
        
        $header = new stdClass;
        $detail = new stdClass;

        $factura = Factura::where('id_solicitud',$id)->first();
        $adquiriente = Adquiriente::where('solicitud_id',$id)->first();
        $compraPara = Para::where('solicitud_id',$id)->first();
        $solicitud = Solicitud::where('id',$id)->first();
        $reingreso = Reingreso::where('solicitud_id',$id)->whereIn('estado_id',[0,2,3])->first();
        $get_solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();
        
        //Log::info(json_encode($reingreso));

        if($factura != null){        

            $header->Folio = (string)$factura->num_factura;
            
            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)$factura->comuna;
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;

            $header->FchEmis = (string)$factura->fecha_emision;
            $header->RUTEmisor = (string)$factura->rut_emisor;
            $header->RznSoc = (string)$factura->razon_social_emisor;
            $header->MntTotal = (string)$factura->monto_total_factura;
            
        }
        // Generar Json
        $pbv = $request->get('pbv');
        if($factura->tipo_pbv == "K"){
            $pbv = round($pbv);
        }

        //dd($solicitud->sucursal->comuna);

        $datosCompraPara = null;
        if($compraPara != null){
            $datosCompraPara = array(
                'calidad' => $compraPara->tipo,
                'calle' => $compraPara->calle,
                'comuna' => $compraPara->comuna,
                'email' => is_null($compraPara->email) ? 'info@acobro.cl' : $compraPara->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $compraPara->nombre,
                'nroDomicilio' => $compraPara->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($compraPara->rut, 0, -1))),
                'telefono' => is_null($compraPara->telefono) ? '123456789' : $compraPara->telefono,
                'aMaterno' => $compraPara->aMaterno,
                'aPaterno' => $compraPara->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $compraPara->rDomicilio
            );
        }
        else{
            $datosCompraPara = array(
                'calidad' => '',
                'calle' => '',
                'comuna' => '',
                'email' => '',
                'ltrDomicilio' => '',
                'nombresRazon' => '',
                'nroDomicilio' => '',
                'runRut' => '',
                'telefono' => '',
                'aMaterno' => '',
                'aPaterno' => '',
                'cPostal' => '',
                'rDomicilio' => ''
            );
        }


        $datosReingreso = null;
        if($reingreso != null && $get_solicitud_rc != null){
            $datosReingreso = array(
                'fechaResExenta' => $request->get('fechaResExenta'),
                'fechaSolRech' => $request->get('fechaSolRech'),
                'nroResExenta' => $request->get('nroResExenta'),
                'nroSolicitud' => $reingreso->nroSolicitud,
                'ppu' => $reingreso->ppu
            );
            Log::info('adjunta datos de reingreso');
        }
        else{
            $datosReingreso = array(
                'fechaResExenta' => '',
                'fechaSolRech' => '',
                'nroResExenta' => '',
                'nroSolicitud' => '',
                'ppu' => ''
            );
            Log::info('no adjunta datos de reingreso');
        }

        $parametro = [
            'comunidadDTO' => [
                'cantidad' => ($adquiriente->cantidadIntegrantes == null)? 0 : $adquiriente->cantidadIntegrantes,
                'esComunidad' => ($adquiriente->tipo=="O")? 'SI':'NO'
            ],
            'documentoDTO' => [
                'emisor' => $header->RznSoc,
                'fecha' => str_replace('-', '', $header->FchEmis),
                'lugar' => $solicitud->sucursal->comuna, //Nro Comuna Sucursal -> Maipu
                'mbTotal' => $header->MntTotal,
                'numero' => $header->Folio,
                'tipo' => 'FACTURA ELECTRONICA',
                'rEmisor' => str_replace('.', '', str_replace('-', '', $header->RUTEmisor)),
                'tMoneda' => '$'
            ],
            'impuestoVerdeDTO' => [
                'cid' => '',
                'cit' => '',
                'mImpuesto' => '',
                'tFactura' => ''
            ],
            'listaAdquierente' => [
                'calidad' => $adquiriente->tipo,
                'calle' => $adquiriente->calle,
                'comuna' => $adquiriente->comuna,
                'email' => is_null($adquiriente->email) ? 'info@acobro.cl' : $adquiriente->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $adquiriente->nombre,
                'nroDomicilio' => $adquiriente->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($adquiriente->rut, 0, -1))),
                'telefono' => is_null($adquiriente->telefono) ? '123456789' : $adquiriente->telefono,
                'aMaterno' => $adquiriente->aMaterno,
                'aPaterno' => $adquiriente->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $adquiriente->rDomicilio
            ],
            'moto' => [
                'agnoFabricacion' => $request->get('agnoFabricacion'),
                'asientos' => $request->get('asientos'),
                'carga' => $request->get('carga'),
                'citModelo' => '',
                'color' => $request->get('color'),
                'combustible' => $request->get('combustible'),
                'marca' => $request->get('marca'),
                'modelo' => $request->get('modelo'),
                'nroChasis' => $request->get('nroChasis'),
                'nroMotor' => $request->get('nroMotor'),
                'nroSerie' => is_null($request->get('nroSerie')) ? '' : $request->get('nroSerie'),
                'nroVin' => is_null($request->get('nroVin')) ? '' : $request->get('nroVin'),
                'pbv' => is_null($pbv) ? '0' : $pbv,
                'puertas' => is_null($request->get('puertas')) ? '0' : $request->get('puertas'),
                'terminacionPPU' => $solicitud->termino_1,
                'tipoVehiculo' => is_null($request->get('tipoVehiculo')) ? 'MOTO' : $request->get('tipoVehiculo'),
                'tCarga' => $factura->tipo_carga,
                'tPbv' => $factura->tipo_pbv
            ],
            'observaciones' => '',
            'operadorDTO' => array(
                'region' => '13',
                'runUsuario' => '10796553',
                'rEmpresa' => '77880510'
            )
        ];

        $solicitanteDTO = null;

        if($compraPara != null){
            $solicitanteDTO = array(
                'calidad' => $compraPara->tipo,
                'calle' => $compraPara->calle,
                'comuna' => $compraPara->comuna,
                'email' => is_null($compraPara->email) ? 'info@acobro.cl' : $compraPara->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $compraPara->nombre,
                'nroDomicilio' => $compraPara->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($compraPara->rut, 0, -1))),
                'telefono' => is_null($compraPara->telefono) ? '123456789' : $compraPara->telefono,
                'aMaterno' => $compraPara->aMaterno,
                'aPaterno' => $compraPara->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $compraPara->rDomicilio
            );
        }
        else{
            $solicitanteDTO = array(
                'calidad' => $adquiriente->tipo,
                'calle' => $adquiriente->calle,
                'comuna' => $adquiriente->comuna,
                'email' => is_null($adquiriente->email) ? 'info@acobro.cl' : $adquiriente->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $adquiriente->nombre,
                'nroDomicilio' => $adquiriente->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($adquiriente->rut, 0, -1))),
                'telefono' => is_null($adquiriente->telefono) ? '123456789' : $adquiriente->telefono,
                'aMaterno' => $adquiriente->aMaterno,
                'aPaterno' => $adquiriente->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $adquiriente->rDomicilio
            );
        }

        $parametro['solicitanteDTO'] = $solicitanteDTO;
        $parametro['compraParaDTO'] = $datosCompraPara;
        $parametro['reIngreso'] = $datosReingreso;
        // Llamar RC
        $parametros = array(
            'llave' => array(
                'consumidor' => 'ACOBRO',
                'servicio' => 'PRIMERA INSCRIPCION MOTOS',
                'tramite' => 'PRUEBA'
            ),
            'spieMoto' => $parametro
        );
        //return json_encode($parametros);
        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3){
        
            $data = RegistroCivil::creaMoto(json_encode($parametro));

            //dd($data);

            $salida = json_decode($data, true);

            //return dd($salida);
            if(isset($salida['codigoresp'])){
                //dd((int)$salida['codigoresp']);
                $cod_salida_resp = (int)$salida['codigoresp'];
                if($cod_salida_resp==1 || $cod_salida_resp==0){
                    $nro_solicitud_rc = $salida['solicitudDTO']['numeroSol'];
                    $ppu_rc = $salida['solicitudDTO']['ppu'];
                    $fecha = $salida['solicitudDTO']['fecha'];
                    $hora = $salida['solicitudDTO']['hora'];
                    $oficina = $salida['solicitudDTO']['oficina'];
                    $tipo_sol = $salida['solicitudDTO']['tipoSol'];
                    @$observaciones = $salida['Observaciones'];

                    if($reingreso == null && $get_solicitud_rc == null){
                        $solicitud_rc = new SolicitudRC();
                        $solicitud_rc->fecha = $fecha;
                        $solicitud_rc->hora = $hora;
                        $solicitud_rc->numeroSol = $nro_solicitud_rc;
                        $solicitud_rc->oficina = $oficina;
                        $solicitud_rc->ppu = $ppu_rc;
                        $solicitud_rc->tipoSol = $tipo_sol;
                        $solicitud_rc->solicitud_id = $id;
                        $solicitud_rc->save();
                    }
                    else{
                        $solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();
                        $solicitud_rc->fecha = $fecha;
                        $solicitud_rc->hora = $hora;
                        $solicitud_rc->numeroSol = $nro_solicitud_rc;
                        $solicitud_rc->oficina = $oficina;
                        $solicitud_rc->ppu = $ppu_rc;
                        $solicitud_rc->tipoSol = $tipo_sol;
                        $solicitud_rc->solicitud_id = $id;
                        $solicitud_rc->save();

                    }

                    if(@sizeof($observaciones)==0){
                        $solicitud2 = Solicitud::find($id);
                        $solicitud2->estado_id = 6;
                        $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
                        $solicitud2->incluyePermiso = $request->get('incluyePermiso');
                        $solicitud2->incluyeTAG = $request->get('incluyeTAG');
                        $solicitud2->save();

                        if($reingreso != null && $get_solicitud_rc != null){
                            //$reingreso->observaciones = json_encode($observaciones);
                            $reingreso->estado_id = 1; //Reingreso aceptado
                            $reingreso->nroSolicitud = $nro_solicitud_rc;
                            $reingreso->save();
                        }
                    }
                    else{
                        $solicitud2 = Solicitud::find($id);
                        $solicitud2->estado_id = 12;
                        $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
                        $solicitud2->incluyePermiso = $request->get('incluyePermiso');
                        $solicitud2->incluyeTAG = $request->get('incluyeTAG');
                        $solicitud2->save();

                        if($reingreso != null && $get_solicitud_rc != null){
                            $reingreso->observaciones = json_encode($observaciones);
                            $reingreso->estado_id = 2; //Reingreso rechazado
                            $reingreso->nroSolicitud = $nro_solicitud_rc;
                            $reingreso->save();
                        }
                        else{
                            $new_reingreso = new Reingreso();
                            $new_reingreso->ppu = explode('-',str_replace('.','',$ppu_rc))[0];
                            $new_reingreso->nroSolicitud = $nro_solicitud_rc;
                            $new_reingreso->solicitud_id = $id;
                            $new_reingreso->estado_id = 0; //Pendiente de reingreso
                            $new_reingreso->observaciones = json_encode($observaciones);
                            $new_reingreso->save();
                        }
                    }
                    sleep(4);
                    $solicitud_rc = SolicitudRC::getSolicitud($id);
                    return view('solicitud.revision.docsIdentidadMoto', compact('header', 'id', 'nro_solicitud_rc', 'ppu_rc','solicitud_rc'));
                }
                else{
                    $solicitud2 = Solicitud::find($id);
                    $solicitud2->estado_id = 11;
                    $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
                    $solicitud2->incluyePermiso = $request->get('incluyePermiso');
                    $solicitud2->incluyeTAG = $request->get('incluyeTAG');
                    $solicitud2->save();
                    return view('general.ErrorRC', ['glosa' => $salida['glosa']]);
                }
            }
        }
        else{
            $solicitud2 = Solicitud::find($id);
            $solicitud2->estado_id = 6;
            $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
            $solicitud2->incluyePermiso = $request->get('incluyePermiso');
            $solicitud2->incluyeTAG = $request->get('incluyeTAG');
            $solicitud2->save();

            $solicitud_rc = null;
            $nro_solicitud_rc = null;
            $ppu_rc = null;
            return view('solicitud.revision.docsIdentidadMoto', compact('header', 'id', 'nro_solicitud_rc', 'ppu_rc','solicitud_rc'));
        }
    }

    public function updateRevisionFacturaAuto(Request $request, $id){
        $header = new stdClass;
        $detail = new stdClass;

        $factura = Factura::where('id_solicitud',$id)->first();
        $adquiriente = Adquiriente::where('solicitud_id',$id)->first();
        $compraPara = Para::where('solicitud_id',$id)->first();
        $solicitud = Solicitud::where('id',$id)->first();
        $reingreso = Reingreso::where('solicitud_id',$id)->whereIn('estado_id',[0,2,3])->first();
        $get_solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();

        $exige_impuestoverde = false;
        $tipo_vehiculo = trim($request->input('tipoVehiculo'));
        $pbv = trim($request->input('pbv'));
        $carga = trim($request->input('carga'));
        $tpbv = trim($request->input('tpbv'));
        $tipo_carga = trim($request->input('tCarga'));
        $asientos = trim($request->input('asientos'));

        /*
            If ((Tipo-Vehiculo == “AUTOMOVIL”) ||
            (Tipo-Vehiculo == “STATION WAGON”) ||
            (Tipo-Vehiculo == “JEEP”) ||
            (Tipo-Vehiculo == “LIMUSINA”)){
                Exige-Impuesto-Verde
            }

            If(Tipo-Vehiculo == “CAMIONETA”) {
                If((PBV < 3860) && (tPbv == “K”) ||
                    (PBV < 3.86) && (tPbv == “T”)) {
                    If((CARGA < 2000) && (tCarga == “K”) ||
                    (CARGA < 2) && (tCarga == “T”)) {
                        Exige-Impuesto-Verde
                    } Else {
                        NO- Exige-Impuesto-Verde
                    }
                } Else {
                    NO- Exige-Impuesto-Verde
                }
            }

            If(Tipo-Vehiculo == “MINIBUS”) {
                If(asientos < 10) {
                    If((PBV < 3860) && (tPbv == “K”) ||
                    (PBV < 3.86) && (tPbv == “T”)) {
                        Exige-Impuesto-Verde
                    } Else {
                        NO- Exige-Impuesto-Verde
                    }
                } Else {
                    NO- Exige-Impuesto-Verde
                }
            }

        */
        $pbv = trim($request->get('pbv'));
        if(trim($request->get('tpbv')) == "K"){
            $pbv = round($pbv);
        }

        if((trim($tipo_vehiculo) == "AUTOMOVIL") || (trim($tipo_vehiculo) == "STATION WAGON") || (trim($tipo_vehiculo) == "JEEP") || (trim($tipo_vehiculo) == "LIMUSINA")){
            $exige_impuestoverde = true;
        }

        if(trim($tipo_vehiculo) == "CAMIONETA") {
            if((($pbv < 3860) && ($tpbv == "K")) || (($pbv < 3.86) && ($tpbv == "T"))) {
                if((($carga < 2000) && ($tipo_carga == "K")) || (($carga < 2) && ($tipo_carga == "T"))) {
                    $exige_impuestoverde = true;
                } else {
                    $exige_impuestoverde = false;
                }
            } else {
                $exige_impuestoverde = false;
            }
        }

        if(trim($tipo_vehiculo) == "MINIBUS") {
            if($asientos < 10) {
                if((($pbv < 3860) && ($tpbv == "K")) || (($pbv < 3.86) && ($tpbv == "T"))) {
                    $exige_impuestoverde = true;
                } else {
                    $exige_impuestoverde = false;
                }
            } else {
                $exige_impuestoverde = false;
            }
        }

        if($factura != null){
            $header->Folio = (string)$factura->num_factura;
            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)$factura->comuna;
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;

            $header->FchEmis = (string)$factura->fecha_emision;
            $header->RUTEmisor = (string)$factura->rut_emisor;
            $header->RznSoc = (string)$factura->razon_social_emisor;
            $header->MntTotal = (string)$factura->monto_total_factura;
        }


        $datosCompraPara = null;
        if($compraPara != null){
            $datosCompraPara = array(
                'calidad' => $compraPara->tipo,
                'calle' => $compraPara->calle,
                'comuna' => $compraPara->comuna,
                'email' => is_null($compraPara->email) ? 'info@acobro.cl' : $compraPara->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $compraPara->nombre,
                'nroDomicilio' => $compraPara->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($compraPara->rut, 0, -1))),
                'telefono' => is_null($compraPara->telefono) ? '123456789' : $compraPara->telefono,
                'aMaterno' => $compraPara->aMaterno,
                'aPaterno' => $compraPara->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $compraPara->rDomicilio
            );
        }
        else{
            $datosCompraPara = array(
                'calidad' => '',
                'calle' => '',
                'comuna' => '',
                'email' => '',
                'ltrDomicilio' => '',
                'nombresRazon' => '',
                'nroDomicilio' => '',
                'runRut' => '',
                'telefono' => '',
                'aMaterno' => '',
                'aPaterno' => '',
                'cPostal' => '',
                'rDomicilio' => ''
            );
        }

        $datosReingreso = null;
        if($reingreso != null && $get_solicitud_rc != null){
            $datosReingreso = array(
                'fechaResExenta' => $request->get('fechaResExenta'),
                'fechaSolRech' => $request->get('fechaSolRech'),
                'nroResExenta' => $request->get('nroResExenta'),
                'nroSolicitud' => $reingreso->nroSolicitud,
                'ppu' => $reingreso->ppu
            );
            Log::info('adjunta datos de reingreso');
        }
        else{
            $datosReingreso = array(
                'fechaResExenta' => '',
                'fechaSolRech' => '',
                'nroResExenta' => '',
                'nroSolicitud' => '',
                'ppu' => ''
            );
            Log::info('no adjunta datos de reingreso');
        }

        // Generar Json
        $parametro = [
            'comunidadDTO' => [
                'cantidad' => ($adquiriente->cantidadIntegrantes == null)? 0 : $adquiriente->cantidadIntegrantes,
                'esComunidad' => ($adquiriente->tipo=="O")? 'SI':'NO'
            ],
            'documentoDTO' => [
                'emisor' => $header->RznSoc,
                'fecha' => str_replace('-', '', $header->FchEmis),
                'lugar' => $solicitud->sucursal->comuna, //Nro Comuna Sucursal -> Maipu
                'mbTotal' => $header->MntTotal,
                'numero' => $header->Folio,
                'tipo' => 'FACTURA ELECTRONICA',
                'rEmisor' => str_replace('.', '', str_replace('-', '', $header->RUTEmisor)),
                'tMoneda' => '$'
            ],
            'impuestoVerdeDTO' => [
                'cid' => ($exige_impuestoverde) ? trim($request->input('codigoCid')) : '',
                'cit' => ($exige_impuestoverde) ? trim($request->input('codigoCit')) : '',
                'mImpuesto' => ($exige_impuestoverde) ? trim($request->input('mImpuesto')) : '',
                'tFactura' => ($exige_impuestoverde) ? $header->MntTotal : ''
            ],
            'listaAdquierente' => [
                'calidad' => $adquiriente->tipo,
                'calle' => $adquiriente->calle,
                'comuna' => $adquiriente->comuna,
                'email' => is_null($adquiriente->email) ? 'info@acobro.cl' : $adquiriente->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $adquiriente->nombre,
                'nroDomicilio' => $adquiriente->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($adquiriente->rut, 0, -1))),
                'telefono' => is_null($adquiriente->telefono) ? '123456789' : $adquiriente->telefono,
                'aMaterno' => $adquiriente->aMaterno,
                'aPaterno' => $adquiriente->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $adquiriente->rDomicilio
            ],
            'vehiculoLM' => [
                'agnoFabricacion' => $request->get('agnoFabricacion'),
                'asientos' => $request->get('asientos'),
                'carga' => (trim($tipo_vehiculo) == "CAMIONETA")? $request->get('carga') : 0,
                'citModelo' => ((trim($tipo_vehiculo) == "AUTOMOVIL") || 
                (trim($tipo_vehiculo) == "STATION WAGON") || (trim($tipo_vehiculo) == "CAMIONETA") ||
                (trim($tipo_vehiculo) == "MINIBUS") ||
                (trim($tipo_vehiculo) == "JEEP") || (trim($tipo_vehiculo) == "LIMUSINA")) ? $request->get('codigoCit'): '',
                'color' => $request->get('color'),
                'combustible' => $request->get('combustible'),
                'marca' => $request->get('marca'),
                'modelo' => $request->get('modelo'),
                'nroChasis' => $request->get('nroChasis'),
                'nroMotor' => $request->get('nroMotor'),
                'nroSerie' => is_null($request->get('nroSerie')) ? '' : $request->get('nroSerie'),
                'nroVin' => is_null($request->get('nroVin')) ? '' : $request->get('nroVin'),
                'pbv' => is_null($request->get('pbv')) ? '0' : $request->get('pbv'),
                'puertas' => is_null($request->get('puertas')) ? '0' : $request->get('puertas'),
                'terminacionPPU' => $solicitud->termino_1,
                'tipoVehiculo' => is_null($request->get('tipoVehiculo')) ? 'MOTO' : $request->get('tipoVehiculo'),
                'tCarga' => $tipo_carga,
                'tPbv' => $tpbv
            ],
            'observaciones' => '',
            'operadorDTO' => array(
                'region' => '13',
                'runUsuario' => '10796553',
                'rEmpresa' => '77880510'
            )
        ];

        $solicitanteDTO = null;

        if($compraPara != null){
            $solicitanteDTO = array(
                'calidad' => $compraPara->tipo,
                'calle' => $compraPara->calle,
                'comuna' => $compraPara->comuna,
                'email' => is_null($compraPara->email) ? 'info@acobro.cl' : $compraPara->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $compraPara->nombre,
                'nroDomicilio' => $compraPara->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($compraPara->rut, 0, -1))),
                'telefono' => is_null($compraPara->telefono) ? '123456789' : $compraPara->telefono,
                'aMaterno' => $compraPara->aMaterno,
                'aPaterno' => $compraPara->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $compraPara->rDomicilio
            );
        }
        else{
            $solicitanteDTO = array(
                'calidad' => $adquiriente->tipo,
                'calle' => $adquiriente->calle,
                'comuna' => $adquiriente->comuna,
                'email' => is_null($adquiriente->email) ? 'info@acobro.cl' : $adquiriente->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $adquiriente->nombre,
                'nroDomicilio' => $adquiriente->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($adquiriente->rut, 0, -1))),
                'telefono' => is_null($adquiriente->telefono) ? '123456789' : $adquiriente->telefono,
                'aMaterno' => $adquiriente->aMaterno,
                'aPaterno' => $adquiriente->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $adquiriente->rDomicilio
            );
        }

        $parametro['solicitanteDTO'] = $solicitanteDTO;
        $parametro['compraParaDTO'] = $datosCompraPara;
        $parametro['reIngreso'] = $datosReingreso;
        // Llamar RC
        $parametros = array(
            'llave' => array(
                'consumidor' => 'ACOBRO',
                'servicio' => 'PRIMERA INSCRIPCION AUTOS',
                'tramite' => 'PRUEBA'
            ),
            'spieVws' => $parametro
        );
        //dd($parametros);
        //dd($parametro);
        //return json_encode($parametros);

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3){
            $data = RegistroCivil::creaAuto(json_encode($parametro));
            $salida = json_decode($data, true);

            //dd($salida);

            if(isset($salida['codigoresp'])){
                //dd((int)$salida['codigoresp']);
                $cod_salida_resp = (int)$salida['codigoresp'];
                if($cod_salida_resp==1 || $cod_salida_resp==0){
                    $nro_solicitud_rc = $salida['solicitudDTO']['numeroSol'];
                    $ppu_rc = $salida['solicitudDTO']['ppu'];
                    $fecha = $salida['solicitudDTO']['fecha'];
                    $hora = $salida['solicitudDTO']['hora'];
                    $oficina = $salida['solicitudDTO']['oficina'];
                    $tipo_sol = $salida['solicitudDTO']['tipoSol'];
                    @$observaciones = $salida['Observaciones'];

                    if($reingreso == null && $get_solicitud_rc == null){
                        $solicitud_rc = new SolicitudRC();
                        $solicitud_rc->fecha = $fecha;
                        $solicitud_rc->hora = $hora;
                        $solicitud_rc->numeroSol = $nro_solicitud_rc;
                        $solicitud_rc->oficina = $oficina;
                        $solicitud_rc->ppu = $ppu_rc;
                        $solicitud_rc->tipoSol = $tipo_sol;
                        $solicitud_rc->solicitud_id = $id;
                        $solicitud_rc->save();
                    }
                    else{
                        $solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();
                        $solicitud_rc->fecha = $fecha;
                        $solicitud_rc->hora = $hora;
                        $solicitud_rc->numeroSol = $nro_solicitud_rc;
                        $solicitud_rc->oficina = $oficina;
                        $solicitud_rc->ppu = $ppu_rc;
                        $solicitud_rc->tipoSol = $tipo_sol;
                        $solicitud_rc->solicitud_id = $id;
                        $solicitud_rc->save();
                    }


                    if(@sizeof($observaciones)==0){
                        $solicitud2 = Solicitud::find($id);
                        $solicitud2->estado_id = 6;
                        $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
                        $solicitud2->incluyePermiso = $request->get('incluyePermiso');
                        $solicitud2->incluyeTAG = $request->get('incluyeTAG');
                        $solicitud2->save();

                        if($reingreso != null && $get_solicitud_rc != null){
                            //$reingreso->observaciones = json_encode($observaciones);
                            $reingreso->estado_id = 1; //Reingreso aceptado
                            $reingreso->nroSolicitud = $nro_solicitud_rc;
                            $reingreso->save();
                        }
                    }
                    else{
                        $solicitud2 = Solicitud::find($id);
                        $solicitud2->estado_id = 12;
                        $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
                        $solicitud2->incluyePermiso = $request->get('incluyePermiso');
                        $solicitud2->incluyeTAG = $request->get('incluyeTAG');
                        $solicitud2->save();

                        if($reingreso != null && $get_solicitud_rc != null){
                            $reingreso->observaciones = json_encode($observaciones);
                            $reingreso->nroSolicitud = $nro_solicitud_rc;
                            $reingreso->estado_id = 2; //Reingreso rechazado
                            $reingreso->save();
                        }
                        else{
                            $new_reingreso = new Reingreso();
                            $new_reingreso->ppu = explode('-',str_replace('.','',$ppu_rc))[0];
                            $new_reingreso->nroSolicitud = $nro_solicitud_rc;
                            $new_reingreso->solicitud_id = $id;
                            $new_reingreso->estado_id = 0; //Pendiente de reingreso
                            $new_reingreso->observaciones = json_encode($observaciones);
                            $new_reingreso->save();
                        }
                    }

                    sleep(4);
                    $solicitud_rc = SolicitudRC::getSolicitud($id);
                    return view('solicitud.revision.docsIdentidadAuto', compact('header', 'id', 'nro_solicitud_rc', 'ppu_rc','solicitud_rc'));
                }
                else{
                    return view('general.ErrorRC', ['glosa' => $salida['glosa']]);
                }
            }
        }
        else{
            $solicitud2 = Solicitud::find($id);
            $solicitud2->estado_id = 6;
            $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
            $solicitud2->incluyePermiso = $request->get('incluyePermiso');
            $solicitud2->incluyeTAG = $request->get('incluyeTAG');
            $solicitud2->save();

            $solicitud_rc = null;
            $nro_solicitud_rc = null;
            $ppu_rc = null;

            return view('solicitud.revision.docsIdentidadAuto', compact('header', 'id', 'nro_solicitud_rc', 'ppu_rc','solicitud_rc'));
        }
    }

    public function updateRevisionFacturaCamion(Request $request, $id){
        $header = new stdClass;
        $detail = new stdClass;

        $factura = Factura::where('id_solicitud',$id)->first();
        $adquiriente = Adquiriente::where('solicitud_id',$id)->first();
        $compraPara = Para::where('solicitud_id',$id)->first();
        $solicitud = Solicitud::where('id',$id)->first();
        $tipo_vehiculo = trim($request->input('tipoVehiculo'));
        $reingreso = Reingreso::where('solicitud_id',$id)->whereIn('estado_id',[0,2,3])->first();
        $get_solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();

        if($factura != null){
            $header->Folio = (string)$factura->num_factura;
            $header->RUTRecep = (string)$factura->rut_receptor;
            $header->RznSocRecep = (string)$factura->razon_social_recep;
            $header->GiroRecep = (string)$factura->giro;
            $header->Contacto = (string)$factura->contacto;
            $header->DirRecep = (string)$factura->direccion;
            $header->CmnaRecep = (string)$factura->comuna;
            $header->CiudadRecep = (string)$factura->ciudad;
            $header->DirPostal = (string)$factura->direccion;

            $header->FchEmis = (string)$factura->fecha_emision;
            $header->RUTEmisor = (string)$factura->rut_emisor;
            $header->RznSoc = (string)$factura->razon_social_emisor;
            $header->MntTotal = (string)$factura->monto_total_factura;
        }

        $pbv = $request->get('pbv');
        if(trim($request->get('tpbv')) == "K"){
            $pbv = round($pbv);
        }

        $datosCompraPara = null;
        if($compraPara != null){
            $datosCompraPara = array(
                'calidad' => $compraPara->tipo,
                'calle' => $compraPara->calle,
                'comuna' => $compraPara->comuna,
                'email' => is_null($compraPara->email) ? 'info@acobro.cl' : $compraPara->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $compraPara->nombre,
                'nroDomicilio' => $compraPara->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($compraPara->rut, 0, -1))),
                'telefono' => is_null($compraPara->telefono) ? '123456789' : $compraPara->telefono,
                'aMaterno' => $compraPara->aMaterno,
                'aPaterno' => $compraPara->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $compraPara->rDomicilio
            );
        }
        else{
            $datosCompraPara = array(
                'calidad' => '',
                'calle' => '',
                'comuna' => '',
                'email' => '',
                'ltrDomicilio' => '',
                'nombresRazon' => '',
                'nroDomicilio' => '',
                'runRut' => '',
                'telefono' => '',
                'aMaterno' => '',
                'aPaterno' => '',
                'cPostal' => '',
                'rDomicilio' => ''
            );
        }

        $datosReingreso = null;
        if($reingreso != null && $get_solicitud_rc != null){
            $datosReingreso = array(
                'fechaResExenta' => $request->get('fechaResExenta'),
                'fechaSolRech' => $request->get('fechaSolRech'),
                'nroResExenta' => $request->get('nroResExenta'),
                'nroSolicitud' => $reingreso->nroSolicitud,
                'ppu' => $reingreso->ppu
            );
            Log::info('adjunta datos de reingreso');
        }
        else{
            $datosReingreso = array(
                'fechaResExenta' => '',
                'fechaSolRech' => '',
                'nroResExenta' => '',
                'nroSolicitud' => '',
                'ppu' => ''
            );
            Log::info('no adjunta datos de reingreso');
        }

        $parametro = [
            'comunidadDTO' => [
                'cantidad' => ($adquiriente->cantidadIntegrantes == null)? 0 : $adquiriente->cantidadIntegrantes,
                'esComunidad' => ($adquiriente->tipo=="O")? 'SI':'NO'
            ],
            'documentoDTO' => [
                'emisor' => $header->RznSoc,
                'fecha' => str_replace('-', '', $header->FchEmis),
                'lugar' => $solicitud->sucursal->comuna,
                //Nro Comuna Sucursal -> Maipu
                'mbTotal' => $header->MntTotal,
                'numero' => $header->Folio,
                'tipo' => 'FACTURA ELECTRONICA',
                'rEmisor' => str_replace('.', '', str_replace('-', '', $header->RUTEmisor)),
                'tMoneda' => '$'
            ],
            'impuestoVerdeDTO' => [
                'cid' => '',
                'cit' => '',
                'mImpuesto' => '',
                'tFactura' => ''
            ],
            'listaAdquierente' => [
                'calidad' => $adquiriente->tipo,
                'calle' => $adquiriente->calle,
                'comuna' => $adquiriente->comuna,
                'email' => is_null($adquiriente->email) ? 'info@acobro.cl' : $adquiriente->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $adquiriente->nombre,
                'nroDomicilio' => $adquiriente->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($adquiriente->rut, 0, -1))),
                'telefono' => is_null($adquiriente->telefono) ? '123456789' : $adquiriente->telefono,
                'aMaterno' => $adquiriente->aMaterno,
                'aPaterno' => $adquiriente->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $adquiriente->rDomicilio
            ],
            'cargaws' => [
                'agnoFabricacion' => $request->get('agnoFabricacion'),
                'asientos' => $request->get('asientos'),
                'carga' => $request->get('carga'),
                'citModelo' => '',
                'color' => $request->get('color'),
                'combustible' => $request->get('combustible'),
                'marca' => $request->get('marca'),
                'modelo' => $request->get('modelo'),
                'nroChasis' => $request->get('nroChasis'),
                'nroMotor' => $request->get('nroMotor'),
                'nroSerie' => is_null($request->get('nroSerie')) ? '' : $request->get('nroSerie'),
                'nroVin' => is_null($request->get('nroVin')) ? '' : $request->get('nroVin'),
                'pbv' => is_null($request->get('pbv')) ? '0' : $request->get('pbv'),
                'puertas' => '',
                'terminacionPPU' => $solicitud->termino_1,
                'tipoVehiculo' => is_null($request->get('tipoVehiculo')) ? 'CAMION' : $request->get('tipoVehiculo'),
                'tCarga' => $request->get('tCarga'),
                'tPbv' => trim($request->get('tpbv')),
                'tipoTraccion' => trim($request->get('tipoTraccion')),
                'tipoPotencia' => (trim((trim($tipo_vehiculo) == "CAMION") || 
                (trim($tipo_vehiculo) == "TRACTOCAMION")))? trim($request->get('tipoPotencia')) : '',
                'tipoCarroceria' => trim($request->get('tipoCarroceria')),
                'potencia' => (trim((trim($tipo_vehiculo) == "CAMION") || 
                (trim($tipo_vehiculo) == "TRACTOCAMION")))? trim($request->get('potenciaMotor')) : '',
                'otraCarroceria' => '',
                'nroDipsEjes' => trim($request->get('nroEjes'))
            ],
            'observaciones' => '',
            'operadorDTO' => array(
                'region' => '13',
                'runUsuario' => '10796553',
                'rEmpresa' => '77880510'
            )
        ];

        $solicitanteDTO = null;

        if($compraPara != null){
            $solicitanteDTO = array(
                'calidad' => $compraPara->tipo,
                'calle' => $compraPara->calle,
                'comuna' => $compraPara->comuna,
                'email' => is_null($compraPara->email) ? 'info@acobro.cl' : $compraPara->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $compraPara->nombre,
                'nroDomicilio' => $compraPara->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($compraPara->rut, 0, -1))),
                'telefono' => is_null($compraPara->telefono) ? '123456789' : $compraPara->telefono,
                'aMaterno' => $compraPara->aMaterno,
                'aPaterno' => $compraPara->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $compraPara->rDomicilio
            );
        }
        else{
            $solicitanteDTO = array(
                'calidad' => $adquiriente->tipo,
                'calle' => $adquiriente->calle,
                'comuna' => $adquiriente->comuna,
                'email' => is_null($adquiriente->email) ? 'info@acobro.cl' : $adquiriente->email,
                'ltrDomicilio' => '',
                'nombresRazon' => $adquiriente->nombre,
                'nroDomicilio' => $adquiriente->numero,
                'runRut' => str_replace('.', '', str_replace('-', '', substr($adquiriente->rut, 0, -1))),
                'telefono' => is_null($adquiriente->telefono) ? '123456789' : $adquiriente->telefono,
                'aMaterno' => $adquiriente->aMaterno,
                'aPaterno' => $adquiriente->aPaterno,
                'cPostal' => '',
                'rDomicilio' => $adquiriente->rDomicilio
            );
        }

        $parametro['solicitanteDTO'] = $solicitanteDTO;
        $parametro['compraParaDTO'] = $datosCompraPara;
        $parametro['reIngreso'] = $datosReingreso;

        $parametros = array(
            'llave' => array(
                'consumidor' => 'ACOBRO',
                'servicio' => 'SOLICITUD PRIMERA INSCRIPCION VEHICULO PESADOS',
                'tramite' => 'A'
            ),
            'spieCarga' => $parametro
        );
        //dd($parametros);
        //dd($parametro);
        //return json_encode($parametros);

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3){
            $data = RegistroCivil::creaCarga(json_encode($parametro));
            $salida = json_decode($data, true);

            //dd($salida);

            if(isset($salida['codigoresp'])){
                //dd((int)$salida['codigoresp']);
                $cod_salida_resp = (int)$salida['codigoresp'];
                if($cod_salida_resp==1 || $cod_salida_resp==0){
                    $nro_solicitud_rc = $salida['solicitudDTO']['numeroSol'];
                    $ppu_rc = $salida['solicitudDTO']['ppu'];
                    $fecha = $salida['solicitudDTO']['fecha'];
                    $hora = $salida['solicitudDTO']['hora'];
                    $oficina = $salida['solicitudDTO']['oficina'];
                    $tipo_sol = $salida['solicitudDTO']['tipoSol'];
                    @$observaciones = $salida['Observaciones'];

                    if($reingreso == null && $get_solicitud_rc == null){

                        $solicitud_rc = new SolicitudRC();
                        $solicitud_rc->fecha = $fecha;
                        $solicitud_rc->hora = $hora;
                        $solicitud_rc->numeroSol = $nro_solicitud_rc;
                        $solicitud_rc->oficina = $oficina;
                        $solicitud_rc->ppu = $ppu_rc;
                        $solicitud_rc->tipoSol = $tipo_sol;
                        $solicitud_rc->solicitud_id = $id;
                        $solicitud_rc->save();
                    }
                    else{
                        $solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();
                        $solicitud_rc->fecha = $fecha;
                        $solicitud_rc->hora = $hora;
                        $solicitud_rc->numeroSol = $nro_solicitud_rc;
                        $solicitud_rc->oficina = $oficina;
                        $solicitud_rc->ppu = $ppu_rc;
                        $solicitud_rc->tipoSol = $tipo_sol;
                        $solicitud_rc->solicitud_id = $id;
                        $solicitud_rc->save();

                    }

                    if(@sizeof($observaciones)==0){
                        $solicitud2 = Solicitud::find($id);
                        $solicitud2->estado_id = 6;
                        $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
                        $solicitud2->incluyePermiso = $request->get('incluyePermiso');
                        $solicitud2->incluyeTAG = $request->get('incluyeTAG');
                        $solicitud2->save();

                        if($reingreso != null && $get_solicitud_rc != null){
                            //$reingreso->observaciones = json_encode($observaciones);
                            $reingreso->estado_id = 1; //Reingreso aceptado
                            $reingreso->nroSolicitud = $nro_solicitud_rc;
                            $reingreso->save();
                        }
                    }
                    else{
                        $solicitud2 = Solicitud::find($id);
                        $solicitud2->estado_id = 12;
                        $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
                        $solicitud2->incluyePermiso = $request->get('incluyePermiso');
                        $solicitud2->incluyeTAG = $request->get('incluyeTAG');
                        $solicitud2->save();

                        if($reingreso != null && $get_solicitud_rc != null){
                            $reingreso->observaciones = json_encode($observaciones);
                            $reingreso->nroSolicitud = $nro_solicitud_rc;
                            $reingreso->estado_id = 2; //Reingreso rechazado
                            $reingreso->save();
                        }
                        else{
                            $new_reingreso = new Reingreso();
                            $new_reingreso->ppu = explode('-',str_replace('.','',$ppu_rc))[0];
                            $new_reingreso->nroSolicitud = $nro_solicitud_rc;
                            $new_reingreso->solicitud_id = $id;
                            $new_reingreso->estado_id = 0; //Pendiente de reingreso
                            $new_reingreso->observaciones = json_encode($observaciones);
                            $new_reingreso->save();
                        }
                    }

                    sleep(4);
                    $solicitud_rc = SolicitudRC::getSolicitud($id);
                    return view('solicitud.revision.docsIdentidadCamion', compact('header', 'id', 'nro_solicitud_rc', 'ppu_rc','solicitud_rc'));
                }
                else{
                    return view('general.ErrorRC', ['glosa' => $salida['glosa']]);
                }
            }
        }
        else{
            $solicitud2 = Solicitud::find($id);
            $solicitud2->estado_id = 6;
            $solicitud2->incluyeSOAP = $request->get('incluyeSOAP');
            $solicitud2->incluyePermiso = $request->get('incluyePermiso');
            $solicitud2->incluyeTAG = $request->get('incluyeTAG');
            $solicitud2->save();

            $solicitud_rc = null;
            $nro_solicitud_rc = null;
            $ppu_rc = null;

            return view('solicitud.revision.docsIdentidadCamion', compact('header', 'id', 'nro_solicitud_rc', 'ppu_rc','solicitud_rc'));
        }

    }

    public function ver($id)
    {    
        // $solicitud = Solicitud::DatosSolicitud($id);
        // $tramites = Solicitud::Tramites($id);
        // $para = Para::find($id);
        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;
        $cedula_cliente = $documentos->where('tipo_documento_id', '3')->first();
        $factura = $documentos->where('tipo_documento_id', '2')->first();

        $errors = new MessageBag();
        $errors->add('Documentos', 'Debe adjuntar todos los documentos solicitados.');

        $header = new stdClass;
        $detail = new stdClass;

        //Analiza Factura XML
        if (Storage::exists($file)) {
            $contents = Storage::get($file);
            $xmlResponse = simplexml_load_string($contents);

            if($xmlResponse->Documento->Encabezado){
                $header->Folio = (string)$xmlResponse->Documento->Encabezado->IdDoc->Folio;
                $header->FchEmis = (string)$xmlResponse->Documento->Encabezado->IdDoc->FchEmis;
                $header->RUTEmisor = (string)$xmlResponse->Documento->Encabezado->Emisor->RUTEmisor;
                $header->RznSoc = (string)$xmlResponse->Documento->Encabezado->Emisor->RznSoc;
                $header->GiroEmis = (string)$xmlResponse->Documento->Encabezado->Emisor->GiroEmis;
                $header->Sucursal = (string)$xmlResponse->Documento->Encabezado->Emisor->Sucursal;
                $header->DirOrigen = (string)$xmlResponse->Documento->Encabezado->Emisor->DirOrigen;
                $header->CmnaOrigen = (string)$xmlResponse->Documento->Encabezado->Emisor->CmnaOrigen;
                $header->CiudadOrigen = (string)$xmlResponse->Documento->Encabezado->Emisor->CiudadOrigen;
                $header->RUTRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->RUTRecep;
                $header->RznSocRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->RznSocRecep;
                $header->GiroRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->GiroRecep;
                $header->Contacto = (string)$xmlResponse->Documento->Encabezado->Receptor->Contacto;
                $header->DirRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->DirRecep;
                $header->CmnaRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->CmnaRecep;
                $header->CiudadRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->CiudadRecep;
                $header->DirPostal = (string)$xmlResponse->Documento->Encabezado->Receptor->DirPostal;
                $header->MntNeto = (string)$xmlResponse->Documento->Encabezado->Totales->MntNeto;
                $header->MntExe = (string)$xmlResponse->Documento->Encabezado->Totales->MntExe;
                $header->TasaIVA = (string)$xmlResponse->Documento->Encabezado->Totales->TasaIVA;
                $header->IVA = (string)$xmlResponse->Documento->Encabezado->Totales->IVA;
                $header->MntTotal = (string)$xmlResponse->Documento->Encabezado->Totales->MntTotal;
            }

            if($xmlResponse->Documento->Detalle){
                $detail->RUTEmisor = (string)$xmlResponse->Documento->Detalle->DscItem;
            }

        }
        return view('solicitud.ver', compact('header', 'detail', 'cedula_cliente', 'factura'));

    }

    public function verSolicitudes(){

        $solicitudes = Solicitud::getSolicitudesUser(auth()->user()->id);
        
        $SolicitudItem = array();
        foreach($solicitudes as $item){
            try{
                $item->cliente = Factura::select('razon_social_recep')->where('id_solicitud',$item->id)->first();
                
            }
            catch(Exception $e){
                $item->cliente = '';
            }
        }

        return view('solicitud.verSolicitudes', compact('solicitudes'));

    }

    public function verEstado(Request $request, $id){

        $solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();

        //dd($solicitud_rc);

        $parametro = [
            'PPU' => str_replace(".","",explode("-",$solicitud_rc->ppu)[0]),
            'Oficina' => $solicitud_rc->oficina,
            'NumeroSolicitud' => $request->get('id_solicitud_rc'),
            'Ano' => substr($solicitud_rc->fecha,0,4)
        ];

        //dd($parametro);


        $data = RegistroCivil::consultaEstadoSolicitud($parametro);

        $salida = json_decode($data, true);

        //dd($salida);
        foreach($salida as $index => $detalle){
            if($index != "Solicitud"){
                if(!is_array($detalle)){
                    echo '<label>'.$index.': </label> '.$detalle."<br>";
                }
                else{
                    foreach($detalle as $index2 => $detalle_sol){
                        if($index2 != "Rechazos"){
                            echo '<label>'.$index2.': </label> '.$detalle_sol."<br>";
                        }
                        else{
                            foreach($detalle_sol as $index3 => $rechazo){
                                echo '<label>'.$index3.': </label> '.$rechazo."<br>";
                            }
                        }
                    }
                }
            }
            else{
                foreach($detalle as $index2 => $detalle_sol){
                    if($index2 != "Rechazos"){
                        echo '<label>'.$index2.': </label> '.$detalle_sol."<br>";
                    }
                    else{
                        foreach($detalle_sol as $index3 => $rechazo){
                            if(!is_array($rechazo)){
                                echo '<label>'.$index3.': </label> '.$rechazo."<br>";
                            }
                            else{
                                foreach($rechazo as $index4 => $rech){
                                    echo '<label>'.($index4+1).': </label> '.$rech."<br>";
                                }
                            }
                        }
                    }
                }
            }
        }

        echo '<h2>Datos Registro RVM</h2>';

        $parametro = [
            'consumidor' => 'ACOBRO',
            'servicio' => 'CONSULTA SOLICITUD RVM',
            'ppu' => str_replace(".","",explode("-",$solicitud_rc->ppu)[0]),
            'nroSolicitud' => $request->get('id_solicitud_rc'),
            'anho' => substr($solicitud_rc->fecha,0,4)
        ];

        //dd($parametro);


        $data = RegistroCivil::consultaSolicitudRVM($parametro);

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
        echo '<button type="button" data-garantizaSol="'.$id.'" data-numsol="'.$solicitud_rc->numeroSol.'" class="btn btn-success btn-sm btnDescargaComprobante"><i class="fa fa-download"></i>  Descarga Comprobante</button>';
        die;
    }

    public function descargaComprobanteRVM(Request $request, $id){
        $solicitud_rc_id = $request->get('id_solicitud_rc');
        if($solicitud_rc_id != null){
            $solicitud_rc = SolicitudRC::where('solicitud_id',$id)->first();
            $parametro = [
                'consumidor' => 'ACOBRO',
                'servicio' => 'CONSULTA SOLICITUD RVM',
                'ppu' => $solicitud_rc->ppu,
                'nroSolicitud' => $request->get('id_solicitud_rc'),
                'anho' => substr($solicitud_rc->fecha,0,4)
            ];

            //dd($parametro);


            $data = RegistroCivil::consultaSolicitudRVM($parametro);

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
                $filename = 'comprobante_garantiza.pdf';

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
                echo json_encode(['error' => 'El comprobante de la inscripción no se encuentra disponible aún']);
                exit;
            }
        }
        else{
            $solicitud = Solicitud::with(['region','tipo_vehiculo','usuario','datos_factura','sucursal','adquiriente','adquiriente.comunas','paras','solicitud_rc','documentos','limitacion','limitacion_rc', 'limitacion.acreedor'])->where('id',$id)->first();
  
            $pdf = PDF::loadView('solicitud.pdf', ['solicitud' => $solicitud]);
            $fileName = 'solicitud'.$id.'.pdf';

            Storage::put('public/'.$fileName, $pdf->output());

            return response()->json([
                'file' => asset('storage/' . $fileName),
            ]);
        }
    }

    public function aprobacion($id)
    {
        $solicitud = Solicitud::DatosSolicitud($id);
        $tramites = Solicitud::Tramites($id);
        $para = Para::find($id);
        $documentos = Solicitud::DocumentosSolicitud($id);
        return view('solicitud.aprobacion', compact('solicitud', 'tramites', 'para', 'documentos'));
    }

    public function revision()
    {
        $solicitudes = Solicitud::PorAprobar();
        

        $SolicitudItem = array();
        foreach($solicitudes as $item){
            $tramites = DB::table('tipo_tramites_solicitudes')
            ->join('tipo_tramites', 'tipo_tramites.id', '=', 'tipo_tramites_solicitudes.tipoTramite_id')
            ->where('tipo_tramites_solicitudes.solicitud_id', '=', $item->id)
            ->select('tipo_tramites.name as tramite')
            ->get();

            $item->tramites = $tramites->implode('tramite', ', ');
            $item->nombreCliente = !is_null(Factura::select('razon_social_recep')->where('id_solicitud',$item->id)->first())? Factura::select('razon_social_recep')->where('id_solicitud',$item->id)->first(): '';


            $SolicitudItem[] = $item;
        }
        $solicitudes = collect($SolicitudItem);
        
        return view('solicitud.revision', compact('solicitudes'));
    }

    public function registrarPago(Request $request, $id){
        $solicitud = Solicitud::find($id);

        if(trim($request->get('monto_pago_form')) == "" || trim($request->get('monto_pago_form')) == 0){
            return json_encode(["status"=> "ERROR","msj" => "El monto a pagar no puede estar vacio o cero"]);
        }

        if($request->hasFile('Comprobante_PDF')){
            $file = $request->file('Comprobante_PDF');
            $path = Storage::disk('public')->putFileAs('', $file, $file->getClientOriginalName());
            $doc = new Documento();
            $doc->name = 'public/'.$path;
            $doc->type = 'pdf';
            $doc->description = 'Comprobante de inscripción RVM';
            $doc->solicitud_id = $solicitud->id;
            $doc->tipo_documento_id = 8;
            $doc->added_at = Carbon::now()->toDateTimeString();
            $doc->save();
            $solicitud->pagada = 1;
            $solicitud->monto_inscripcion = $request->get('monto_pago_form');
            $solicitud->save();

            return json_encode(["status"=> "OK","msj" => "Registro de pago realizado exitosamente"]);

        }
        else{
            return json_encode(["status"=> "ERROR","msj" => "No hay archivo adjunto"]);
        }
        
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $solicitud = Solicitud::find($id);
        $solicitud->delete();
        session()->forget('solicitud_id');
    }

}
