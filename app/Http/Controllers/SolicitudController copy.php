<?php

namespace App\Http\Controllers;

use App\Helpers\RegistroCivil;
use App\Helpers\Funciones;
use App\Http\Requests\SolicitudRequest;
use App\Models\Comuna;
use App\Models\Para;
use App\Models\Solicitud;
use App\Models\Sucursal;
use App\Models\Tipo_Tramite;
use App\Models\Tipo_Vehiculo;
use App\Models\TipoTramite_Solicitud;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

use stdClass;

class SolicitudController extends Controller
{
    public function create()
    {
        $sucursales = Sucursal::all();
        $tipo_vehiculos = Tipo_Vehiculo::all();
        $tipo_tramites = Tipo_Tramite::all();
        return view('solicitud.create', compact('sucursales', 'tipo_vehiculos', 'tipo_tramites'));
    }


    public function store(SolicitudRequest $request)
    {
        // Agrega Validaciones
        $tramites = explode(',', $request->get('tipotramite'));
        
        foreach($tramites as $tramite){
            if($tramite=='1' && !$request->has('ppu')){
                $errors = new MessageBag();
                $errors->add('PPU', 'Debe seleccionar 3 PPU');
                $sucursales = Sucursal::all();
                $tipo_vehiculos = Tipo_Vehiculo::all();
                $tipo_tramites = Tipo_Tramite::all();
                return view('solicitud.create', compact('sucursales', 'tipo_vehiculos', 'tipo_tramites'))->withErrors($errors);
            }
        }

        if($request->has('switchCompraPara')||$request->get('switchCompraPara')=='1'){
            $request->validate([
                'rut_para' => 'required',
                'name_para' => 'required',
                'domicilio_para' => 'required',
            ]);
        }


        // Grabación de Solicitud
        DB::beginTransaction();
        
        $solicitud = new Solicitud();
        $solicitud->rutCliente = $request->get('rut');
        $solicitud->nombreCliente = $request->get('name');
        $solicitud->creditoDirecto = $request->get('credito');
        $solicitud->prenda = $request->get('prenda');
        $solicitud->observaciones = $request->get('comment');
        $solicitud->sucursal_id = $request->get('sucursal_id');

        foreach($tramites as $tramite){
            if($tramite=='1'){
                $a_sel_ppu = explode(',', $request->get('sel_ppu'));
            
                $solicitud->termino_1 = $a_sel_ppu[0];
                $solicitud->termino_2 = $a_sel_ppu[1];
                $solicitud->termino_3 = $a_sel_ppu[2];
            }
        }

        if(!$request->has('empresa')){
            $solicitud->empresa = 0;
        }else{
            $solicitud->empresa = $request->get('empresa');
        }

        $solicitud->user_id = Auth::user()->id;
        $solicitud->estado_id = 1;
        $solicitud->tipoVehiculos_id = $request->get('tipovehiculo_id');

        if(!$solicitud->save()){
            DB::rollBack();
            $errors = new MessageBag();
            $errors->add('Garantiza', 'Problemas al Guardar la Solicitud. Por favor, comunicarse con el administrador del Sistema.');
            $sucursales = Sucursal::all();
            $tipo_vehiculos = Tipo_Vehiculo::all();
            $tipo_tramites = Tipo_Tramite::all();
            return view('solicitud.create', compact('sucursales', 'tipo_vehiculos', 'tipo_tramites'))->withErrors($errors);
        }

        // Grabación Compra/Para
        if($request->has('switchCompraPara')||$request->get('switchCompraPara')=='1'){
            $para = new Para();
            $para->rutPara = $request->get('rut_para');
            $para->namePara = $request->get('name_para');
            $para->addressPara = $request->get('domicilio_para');
            $para->solicitud_id = $solicitud->id;
            if(!$para->save()){
                DB::rollBack();
                $errors = new MessageBag();
                $errors->add('Garantiza', 'Problemas al Guardar la Solicitud. Por favor, comunicarse con el administrador del Sistema.');
                $sucursales = Sucursal::all();
                $tipo_vehiculos = Tipo_Vehiculo::all();
                $tipo_tramites = Tipo_Tramite::all();
                return view('solicitud.create', compact('sucursales', 'tipo_vehiculos', 'tipo_tramites'))->withErrors($errors);
            }
        }
        
        // Grabación Tipos de Trámite
        $tramites = explode(',', $request->get('tipotramite'));
        foreach($tramites as $tramite){
            if(strlen($tramite) > 0){
                $tipo_tramite = new TipoTramite_Solicitud();
                $tipo_tramite->tipoTramite_id = $tramite;
                $tipo_tramite->solicitud_id = $solicitud->id;
                if(!$tipo_tramite->save()){
                    DB::rollBack();
                    $errors = new MessageBag();
                    $errors->add('Garantiza', 'Problemas al Guardar la Solicitud. Por favor, comunicarse con el administrador del Sistema.');
                    $sucursales = Sucursal::all();
                    $tipo_vehiculos = Tipo_Vehiculo::all();
                    $tipo_tramites = Tipo_Tramite::all();
                    return view('solicitud.create', compact('sucursales', 'tipo_vehiculos', 'tipo_tramites'))->withErrors($errors);
                }
            }
        }

        $solicitud->estado_id = 2;
        $solicitud->save();

        DB::commit();

        return redirect()->route('documento.create', ['id' => $solicitud->id]);
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
