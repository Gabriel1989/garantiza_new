<?php

namespace App\Http\Controllers;

use App\Helpers\RegistroCivil;
use App\Helpers\Funciones;
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
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\MessageBag;

use stdClass;

class SolicitudController extends Controller
{
    public function create()
    {
        $sucursales = Sucursal::all();
        $tipo_vehiculos = Tipo_Vehiculo::all();
        return view('solicitud.create', compact('sucursales', 'tipo_vehiculos'));
    }

    public function store(SolicitudRequest $request)
    {
        $solicitud = new Solicitud();
        $solicitud->sucursal_id = $request->get('sucursal_id');
        $solicitud->user_id = Auth::user()->id;
        $solicitud->estado_id = 1;
        $solicitud->tipoVehiculos_id = $request->get('tipoVehiculos_id');
        $solicitud->save();

        if($request->hasFile('Factura_XML')){
            $doc = new Documento();
            $doc->name = $request->file('Factura_XML')->store('public');
            $doc->type = 'xml';
            $doc->description = 'Factura en XML';
            $doc->solicitud_id = $solicitud->id;
            $doc->tipo_documento_id = 1;
            $doc->added_at = Carbon::now()->toDateTimeString();
            $doc->save();
        }else{
            $errors = new MessageBag();
            $errors->add('Documentos', 'Debe adjuntar Factura XML.');
            return redirect()->route('solicitud.create')->withErrors($errors);
        }

        return redirect()->route('solicitud.adquirientes', ['id' => $solicitud->id]);
    }

    public function adquirientes($id){
        $header = new stdClass;

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
        $comunas = Comuna::allOrder();
        return view('solicitud.adquirientes', compact('id', 'comunas', 'header'));
    }

    /*
     * Se debe validar el largo de los datos
     */
    public function saveAdquirientes(Request $request, $id){
        // Si tipo de persona es comunidad, se deben validar los otros adquirientes
        $errors = new MessageBag();
        if(is_null($request->rut)) $errors->add('Garantiza', 'Debe Ingresar el Rut del Adquiriente.');
        if(is_null($request->nombre)) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Adquiriente.');
        if(is_null($request->calle)) $errors->add('Garantiza', 'Debe Ingresar la dirección del Adquiriente.');
        if(is_null($request->numero)) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Adquiriente.');
        if($request->comuna=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Adquiriente.');
        if(is_null($request->email)) $errors->add('Garantiza', 'Debe Ingresar el email del Adquiriente.');
        if(is_null($request->telefono)) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Adquiriente.');
        if($request->tipoPersona=='O'){
            // Revisa los datos del segundo adquiriente
            if(is_null($request->rut2)) $errors->add('Garantiza', 'Debe Ingresar el Rut del Segundo Adquiriente.');
            if(is_null($request->nombre2)) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Segundo Adquiriente.');
            if(is_null($request->calle2)) $errors->add('Garantiza', 'Debe Ingresar la dirección del Segundo Adquiriente.');
            if(is_null($request->numero2)) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Segundo Adquiriente.');
            if($request->comuna2=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Segundo Adquiriente.');
            if(is_null($request->email2)) $errors->add('Garantiza', 'Debe Ingresar el email del Segundo Adquiriente.');
            if(is_null($request->telefono2)) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Segundo Adquiriente.');
            // Revisa si viene un tercer adquiriente
            if(!is_null($request->rut3)){
                if(is_null($request->nombre3)) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Tercer Adquiriente.');
                if(is_null($request->calle3)) $errors->add('Garantiza', 'Debe Ingresar la dirección del Tercer Adquiriente.');
                if(is_null($request->numero3)) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Tercer Adquiriente.');
                if($request->comuna3=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Tercer Adquiriente.');
                if(is_null($request->email3)) $errors->add('Garantiza', 'Debe Ingresar el email del Tercer Adquiriente.');
                if(is_null($request->telefono3)) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Tercer Adquiriente.');
            }
        }
        if($errors->count()>0) return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);

        DB::beginTransaction();

        // Graba adquiriente principal
        $adquiriente = new Adquiriente();
        $adquiriente->solicitud_id = $id;
        $adquiriente->rut = $request->get('rut');
        $adquiriente->nombre = $request->get('nombre');
        $adquiriente->aPaterno = $request->get('aPaterno');
        $adquiriente->aMaterno = $request->get('aMaterno');
        $adquiriente->calle = $request->get('calle');
        $adquiriente->numero = $request->get('numero');
        $adquiriente->rDomicilio = $request->get('rDomicilio');
        $adquiriente->comuna = $request->get('comuna');
        $adquiriente->telefono = $request->get('telefono');
        $adquiriente->email = $request->get('email');
        $adquiriente->tipo = $request->get('tipoPersona')=='O' ? 'N' : $request->get('tipoPersona');

        if(!$adquiriente->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al grabar Adquiriente.');
            return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
        }

        // Graba segundo adquiriente
        if($request->tipoPersona=='O'){
            $adquiriente = new Adquiriente();
            $adquiriente->solicitud_id = $id;
            $adquiriente->rut = $request->get('rut2');
            $adquiriente->nombre = $request->get('nombre2');
            $adquiriente->aPaterno = $request->get('aPaterno2');
            $adquiriente->aMaterno = $request->get('aMaterno2');
            $adquiriente->calle = $request->get('calle2');
            $adquiriente->numero = $request->get('numero2');
            $adquiriente->rDomicilio = $request->get('rDomicilio2');
            $adquiriente->comuna = $request->get('comuna2');
            $adquiriente->telefono = $request->get('telefono2');
            $adquiriente->email = $request->get('email2');
            $adquiriente->tipo = 'N';

            if(!$adquiriente->save()){
                DB::rollBack();
                $errors->add('Garantiza', 'Problemas al grabar segundo Adquiriente.');
                return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
            }

            // Graba tercer adquiriente
            if(!is_null($request->rut3)){
                $adquiriente = new Adquiriente();
                $adquiriente->solicitud_id = $id;
                $adquiriente->rut = $request->get('rut3');
                $adquiriente->nombre = $request->get('nombre3');
                $adquiriente->aPaterno = $request->get('aPaterno3');
                $adquiriente->aMaterno = $request->get('aMaterno3');
                $adquiriente->calle = $request->get('calle3');
                $adquiriente->numero = $request->get('numero3');
                $adquiriente->rDomicilio = $request->get('rDomicilio3');
                $adquiriente->comuna = $request->get('comuna3');
                $adquiriente->telefono = $request->get('telefono3');
                $adquiriente->email = $request->get('email3');
                $adquiriente->tipo = 'N';

                if(!$adquiriente->save()){
                    DB::rollBack();
                    $errors->add('Garantiza', 'Problemas al grabar tercer Adquiriente.');
                    return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
                }
            }
        }

        $solicitud = Solicitud::find($id);
        $solicitud->estado_id = 2;
        if(!$solicitud->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al actualizar estado de Solicitud.');
            return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
        }

        DB::commit();
        return redirect()->route('solicitud.compraPara', ['id' => $id]);
    }

    public function compraPara($id){
        $comunas = Comuna::allOrder();
        $adquirentes = Adquiriente::getSolicitud($id);
        return view('solicitud.compraPara', compact('id', 'comunas', 'adquirentes'));
    }

    public function saveCompraPara(Request $request, $id){
        $errors = new MessageBag();
        if(!is_null($request->rut)){
            if(is_null($request->nombre)) $errors->add('Garantiza', 'Debe Ingresar el Nombre del Compra/Para.');
            if(is_null($request->calle)) $errors->add('Garantiza', 'Debe Ingresar la dirección del Compra/Para.');
            if(is_null($request->numero)) $errors->add('Garantiza', 'Debe Ingresar el número de la dirección del Compra/Para.');
            if($request->comuna=="0") $errors->add('Garantiza', 'Debe Ingresar la comuna del Compra/Para.');
            if(is_null($request->email)) $errors->add('Garantiza', 'Debe Ingresar el email del Compra/Para.');
            if(is_null($request->telefono)) $errors->add('Garantiza', 'Debe Ingresar el teléfono del Compra/Para.');
        }
        if($errors->count()>0) return redirect()->route('solicitud.compraPara', ['id' => $id])->withErrors($errors);

        DB::beginTransaction();

        // Graba adquiriente principal
        if(!is_null($request->rut)){
            $para = new CompraPara();
            $para->solicitud_id = $id;
            $para->rut = $request->get('rut');
            $para->nombre = $request->get('nombre');
            $para->aPaterno = $request->get('aPaterno');
            $para->aMaterno = $request->get('aMaterno');
            $para->calle = $request->get('calle');
            $para->numero = $request->get('numero');
            $para->rDomicilio = $request->get('rDomicilio');
            $para->comuna = $request->get('comuna');
            $para->telefono = $request->get('telefono');
            $para->email = $request->get('email');
            $para->tipo = $request->get('tipoPersona');
        
            if(!$para->save()){
                DB::rollBack();
                $errors->add('Garantiza', 'Problemas al grabar CompraPara.');
                return redirect()->route('solicitud.compraPara', ['id' => $id])->withErrors($errors);
            }
        }

        $solicitud = Solicitud::find($id);
        $solicitud->estado_id = 3;
        if(!$solicitud->save()){
            DB::rollBack();
            $errors->add('Garantiza', 'Problemas al actualizar estado de Solicitud.');
            return redirect()->route('solicitud.adquirientes', ['id' => $id])->withErrors($errors);
        }

        DB::commit();

        $solicitud = Solicitud::getTipoVehiculo($id);
        $ruta = '';
        
        switch($solicitud[0]->tipo){
            case 1:
                $ruta = 'solicitud.datosAuto';
                break;
            case 2:
                $ruta = 'solicitud.datosMoto';
                break;
            case 3:
                $ruta = 'solicitud.datosCamion';
                break;
        }

        return redirect()->route($ruta, ['id' => $id]);
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
                $documentos = Solicitud::DocumentosSolicitud($item->id);
                $file = $documentos->where('tipo_documento_id', '1')->first()->name;

                if (Storage::exists($file)) {
                    $contents = Storage::get($file);
                    $xmlResponse = simplexml_load_string($contents);

                    $encabezado = $this->getNode($xmlResponse, 'Encabezado');
                    if($encabezado){
                        $item->cliente = (string)$encabezado->Receptor->RznSocRecep;
                    }
                }
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



        //return 'datos Auto';
        //return view('solicitud.compraPara', compact('id', 'comunas'));
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
        //dd($id);
        $header = new stdClass;
        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;
        $cedula_cliente = $documentos->where('tipo_documento_id', '3')->first();
        if (Storage::exists($file)) {
            
            $contents = Storage::get($file);
            $xmlResponse = simplexml_load_string($contents);
            if($xmlResponse->Documento->Encabezado){
                $header->RUTRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->RUTRecep;
                $header->RznSocRecep = (string)$xmlResponse->Documento->Encabezado->Receptor->RznSocRecep;
            }
        }
       
        return view('solicitud.revision.cedula', compact('header', 'cedula_cliente', 'id'));
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
            $solicitud->estado_id = $motivo;
            $solicitud->save();
            return redirect()->route('solicitud.revision');
        }

        return redirect()->route(Funciones::FlujoRevision(1, $id), ['id' => $id]);
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
            return view('general.errorRC', ['glosa' => $ppu['glosa']]); 
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
            return view('general.errorRC', ['glosa' => $salida['glosa']]); 
        }
        return dd($salida);
        
    }


    public function updateRevisionFacturaMoto(Request $request, $id){
        
        $header = new stdClass;
        $detail = new stdClass;

        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;
        
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
                $detail->DscItem = (string)$xmlResponse->Documento->Detalle->DscItem;
                $detail->DscItem = str_replace("\n", "", $detail->DscItem);
                $detalle = explode('^^', $detail->DscItem);
            }
        }
        // Generar Json
        $parametro = [
            'compraParaDTO' => [
                'calidad' => 'N',
                'calle' => $request->get('DirRecep'),
                'comuna' => $request->get('comuna'),
                'email' => is_null($request->get('email')) ? 'info@acobro.cl' : $request->get('email'),
                'ltrDomicilio' => '',
                'nombresRazon' => $request->get('RznSocRecep'),
                'nroDomicilio' => $request->get('nroDomicilio'),
                'runRut' => str_replace('.', '', str_replace('-', '', substr($request->get('RUTRecep'), 0, -1))),
                'telefono' => is_null($request->get('telefono')) ? '123456789' : $request->get('telefono'),
                'aMaterno' => $request->get('aMaterno'),
                'aPaterno' => $request->get('aPaterno'),
                'cPostal' => '',
                'rDomicilio' => ''
            ],
            'comunidadDTO' => [
                'cantidad' => '0',
                'esComunidad' => 'NO'
            ],
            'documentoDTO' => [
                'emisor' => $header->RznSoc,
                'fecha' => str_replace('-', '', $header->FchEmis),
                'lugar' => '94', //Nro Comuna Sucursal -> Maipu
                'mbTotal' => $header->MntTotal,
                'numero' => $header->Folio,
                'tipo' => 'FACTURA ELECTRONICA',
                'rEmisor' => str_replace('.', '', str_replace('-', '', substr($header->RUTEmisor, 0, -1))),
                'tMoneda' => '$'
            ],
            'impuestoVerdeDTO' => [
                'cid' => '',
                'cit' => '',
                'mImpuesto' => '',
                'tFactura' => ''
            ],
            'listaAdquierente' => [
                'calidad' => 'N',
                'calle' => $request->get('DirRecep'),
                'comuna' => $request->get('comuna'),
                'email' => is_null($request->get('email')) ? 'info@acobro.cl' : $request->get('email'),
                'ltrDomicilio' => '',
                'nombresRazon' => $request->get('RznSocRecep'),
                'nroDomicilio' => $request->get('nroDomicilio'),
                'runRut' => str_replace('.', '', str_replace('-', '', substr($request->get('RUTRecep'), 0, -1))),
                'telefono' => is_null($request->get('telefono')) ? '123456789' : $request->get('telefono'),
                'aMaterno' => $request->get('aMaterno'),
                'aPaterno' => $request->get('aPaterno'),
                'cPostal' => '',
                'rDomicilio' => ''
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
                'pbv' => is_null($request->get('pbv')) ? '0' : $request->get('pbv'),
                'puertas' => is_null($request->get('puertas')) ? '0' : $request->get('puertas'),
                'terminacionPPU' => $request->get('PPU'),
                'tipoVehiculo' => is_null($request->get('tipoVehiculo')) ? 'MOTO' : $request->get('tipoVehiculo'),
                'tCarga' => 'K',
                'tPbv' => 'K'
            ],
            'observaciones' => '',
            'operadorDTO' => [
                'region' => '13',
                'runUsuario' => str_replace('.', '', str_replace('-', '', substr($request->get('Sol_RUTRecep'), 0, -1))), // Rut Garantiza
                'rEmpresa' => str_replace('.', '', str_replace('-', '', substr($request->get('Sol_RUTRecep'), 0, -1))), // Rut Garantiza
            ],
            'solicitanteDTO' => [
                'calidad' => 'N',
                'calle' => $request->get('Sol_DirRecep'),
                'comuna' => $request->get('Sol_comuna'),
                'email' => $request->get('Sol_email'),
                'ltrDomicilio' => '',
                'nombresRazon' => $request->get('Sol_RznSocRecep'),
                'nroDomicilio' => $request->get('Sol_nroDomicilio'),
                'runRut' => '13041719', //str_replace('.', '', str_replace('-', '', substr($request->get('Sol_RUTRecep'), 0, -1))),
                'telefono' => $request->get('Sol_telefono'),
                'aMaterno' => $request->get('Sol_aMaterno'),
                'aPaterno' => $request->get('Sol_aPaterno'),
                'cPostal' => '',
                'rDomicilio' => ''
            ],
        ];
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

        
        $data = RegistroCivil::creaMoto($parametro);
        $salida = json_decode($data, true);

        if(!$salida['codigoresp']=='OK'){
            return view('general.errorRC', ['glosa' => $salida['glosa']]); 
        }
        return dd($salida);
        
        // OJO
        //return redirect()->route('solicitud.revision.facturaMoto', ['id' => $id]);
    }

    public function updateRevisionFacturaAuto(Request $request, $id){
        $header = new stdClass;
        $detail = new stdClass;

        $documentos = Solicitud::DocumentosSolicitud($id);
        $file = $documentos->where('tipo_documento_id', '1')->first()->name;

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
                $detail->DscItem = (string)$xmlResponse->Documento->Detalle->DscItem;
                $detail->DscItem = str_replace("\n", "", $detail->DscItem);
                $detalle = explode('^^', $detail->DscItem);
            }
        }

        // Generar Json
        $parametro = [
            'compraParaDTO' => [
                'calidad' => 'N',
                'calle' => $request->get('DirRecep'),
                'comuna' => $request->get('comuna'),
                'email' => is_null($request->get('email')) ? 'info@acobro.cl' : $request->get('email'),
                'ltrDomicilio' => '',
                'nombresRazon' => $request->get('RznSocRecep'),
                'nroDomicilio' => $request->get('nroDomicilio'),
                'runRut' => str_replace('.', '', str_replace('-', '', substr($request->get('RUTRecep'), 0, -1))),
                'telefono' => is_null($request->get('telefono')) ? '123456789' : $request->get('telefono'),
                'aMaterno' => $request->get('aMaterno'),
                'aPaterno' => $request->get('aPaterno'),
                'cPostal' => '',
                'rDomicilio' => ''
            ],
            'comunidadDTO' => [
                'cantidad' => '0',
                'esComunidad' => 'NO'
            ],
            'documentoDTO' => [
                'emisor' => $header->RznSoc,
                'fecha' => str_replace('-', '', $header->FchEmis),
                'lugar' => '94', //Nro Comuna Sucursal -> Maipu
                'mbTotal' => $header->MntTotal,
                'numero' => $header->Folio,
                'tipo' => 'FACTURA ELECTRONICA',
                'rEmisor' => str_replace('.', '', str_replace('-', '', substr($header->RUTEmisor, 0, -1))),
                'tMoneda' => '$'
            ],
            'impuestoVerdeDTO' => [
                'cid' => '',
                'cit' => '',
                'mImpuesto' => '',
                'tFactura' => ''
            ],
            'adquierenteDTO' => [
                'calidad' => 'N',
                'calle' => $request->get('DirRecep'),
                'comuna' => $request->get('comuna'),
                'email' => is_null($request->get('email')) ? 'info@acobro.cl' : $request->get('email'),
                'ltrDomicilio' => '',
                'nombresRazon' => $request->get('RznSocRecep'),
                'nroDomicilio' => $request->get('nroDomicilio'),
                'runRut' => str_replace('.', '', str_replace('-', '', substr($request->get('RUTRecep'), 0, -1))),
                'telefono' => is_null($request->get('telefono')) ? '123456789' : $request->get('telefono'),
                'aMaterno' => $request->get('aMaterno'),
                'aPaterno' => $request->get('aPaterno'),
                'cPostal' => '',
                'rDomicilio' => ''
            ],
            'livMedWs' => [
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
                'puertas' => is_null($request->get('puertas')) ? '0' : $request->get('puertas'),
                'terminacionPPU' => $request->get('PPU'),
                'tipoVehiculo' => is_null($request->get('tipoVehiculo')) ? 'MOTO' : $request->get('tipoVehiculo'),
                'tCarga' => 'K',
                'tPbv' => 'K'
            ],
            'observaciones' => '',
            'operadorDTO' => [
                'region' => '13',
                'runUsuario' => str_replace('.', '', str_replace('-', '', substr($request->get('Sol_RUTRecep'), 0, -1))), // Rut Garantiza
                'rEmpresa' => str_replace('.', '', str_replace('-', '', substr($request->get('Sol_RUTRecep'), 0, -1))), // Rut Garantiza
            ],
            'solicitanteDTO' => [
                'calidad' => 'N',
                'calle' => $request->get('Sol_DirRecep'),
                'comuna' => $request->get('Sol_comuna'),
                'email' => $request->get('Sol_email'),
                'ltrDomicilio' => '',
                'nombresRazon' => $request->get('Sol_RznSocRecep'),
                'nroDomicilio' => $request->get('Sol_nroDomicilio'),
                'runRut' => '13041719', //str_replace('.', '', str_replace('-', '', substr($request->get('Sol_RUTRecep'), 0, -1))),
                'telefono' => $request->get('Sol_telefono'),
                'aMaterno' => $request->get('Sol_aMaterno'),
                'aPaterno' => $request->get('Sol_aPaterno'),
                'cPostal' => '',
                'rDomicilio' => ''
            ],
        ];
        // Llamar RC
        $parametros = array(
            'llave' => array(
                'consumidor' => 'ACOBRO',
                'servicio' => 'PRIMERA INSCRIPCION AUTOS',
                'tramite' => 'PRUEBA'
            ),
            'spieMoto' => $parametro
        );
        //return json_encode($parametros);

        
        $data = RegistroCivil::creaAuto($parametro);
        $salida = json_decode($data, true);

        if(!$salida['codigoresp']=='OK'){
            return view('general.errorRC', ['glosa' => $salida['glosa']]); 
        }
        return dd($salida);
        
        // OJO
        //return redirect()->route('solicitud.revision.facturaMoto', ['id' => $id]);
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
                $item->cliente = '';
                $documentos = Solicitud::DocumentosSolicitud($item->id);
                $file = $documentos->where('tipo_documento_id', '1')->first()->name;

                if (Storage::exists($file)) {
                    $contents = Storage::get($file);
                    $xmlResponse = simplexml_load_string($contents);

                    $encabezado = $this->getNode($xmlResponse, 'Encabezado');
                    if($encabezado){
                        $item->cliente = (string)$encabezado->Receptor->RznSocRecep;
                    }
                    
                }
            }
            catch(Exception $e){
                $item->cliente = '';
            }
        }

        return view('solicitud.verSolicitudes', compact('solicitudes'));

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
            $SolicitudItem[] = $item;
        }
        $solicitudes = collect($SolicitudItem);
        
        return view('solicitud.revision', compact('solicitudes'));
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
