<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Para;
use App\Models\Solicitud;
use App\Models\SolicitudRC;
use App\Models\CompraPara;
use App\Models\EnvioDocumentoRC;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Helpers\RegistroCivil;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $para = Para::Where('solicitud_id', $id)->get();
        return view('documento.create', compact('solicitud', 'para'));
    }

    public function get($id){
        $documentos = Documento::where('solicitud_id',$id)->get();
        $data = '';
        if(sizeof($documentos)> 0){
            ob_start();
            foreach($documentos as $docs){
                echo '<tr id="'.$docs->name.'"><td>';
                echo '<a target="_blank" href="'.url(str_replace("public/","storage/",$docs->name)).'">'.url(str_replace("public/","storage/",$docs->name)).'</a>';
                echo '</td><td>'.$docs->description.'</td><td><button class="btn btn-danger eliminarArchivoDoc" data-solicitudid="'.$id.'" data-docname="'.$docs->name.'"><i class="fa fa-trash"></i></button></td></tr>';
            }
            $data = ob_get_contents();
            ob_clean();
            return json_encode(["status"=> "OK","data"=> $data]);
        }
        else{
            return json_encode(["status"=> "ERROR","data"=> '']);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->get('id');
        $solicitud = Solicitud::findOrFail($id);
        
        

        if($request->hasFile('Factura_XML')){
            $doc = new Documento();
            $doc->name = $request->file('Factura_XML')->store('public');
            $doc->type = 'xml';
            $doc->description = 'Factura en XML';
            $doc->solicitud_id = $id;
            $doc->tipo_documento_id = 1;
            $doc->added_at = Carbon::now()->toDateTimeString();
            $doc->save();
        }else{
            $errors = new MessageBag();
            $errors->add('Documentos', 'Debe adjuntar Factura XML.');
            $solicitud = Solicitud::findOrFail($id);
            $para = Para::Where('solicitud_id', $id)->get();
            return view('documento.create', compact('solicitud', 'para'))->withErrors($errors);
        }

        if($request->hasFile('Factura_PDF')){
            $doc = new Documento();
            $doc->name = $request->file('Factura_PDF')->store('public');
            $doc->type = 'pdf';
            $doc->description = 'Factura en PDF';
            $doc->solicitud_id = $id;
            $doc->tipo_documento_id = 2;
            $doc->added_at = Carbon::now()->toDateTimeString();
            $doc->save();
        }else{
            $errors = new MessageBag();
            $errors->add('Documentos', 'Debe adjuntar Factura PDF.');
            $solicitud = Solicitud::findOrFail($id);
            $para = Para::Where('solicitud_id', $id)->get();
            return view('documento.create', compact('solicitud', 'para'))->withErrors($errors);
        }

        if($request->hasFile('Cedula_PDF')){
            $doc = new Documento();
            $doc->name = $request->file('Cedula_PDF')->store('public');
            $doc->type = 'pdf';
            $doc->description = 'Cédula del Cliente';
            $doc->solicitud_id = $id;
            $doc->tipo_documento_id = 3;
            $doc->added_at = Carbon::now()->toDateTimeString();
            $doc->save();
        }else{
            $errors = new MessageBag();
            $errors->add('Documentos', 'Debe adjuntar Cédula del Cliente.');
            $solicitud = Solicitud::findOrFail($id);
            $para = Para::Where('solicitud_id', $id)->get();
            return view('documento.create', compact('solicitud', 'para'))->withErrors($errors);
        }

        if($solicitud->empresa==1){
            if($request->hasFile('Rol_PDF')){
                $doc = new Documento();
                $doc->name = $request->file('Rol_PDF')->store('public');
                $doc->type = 'pdf';
                $doc->description = 'Rol de Empresa';
                $doc->solicitud_id = $id;
                $doc->tipo_documento_id = 4;
                $doc->added_at = Carbon::now()->toDateTimeString();
                $doc->save();
            }else{
                $errors = new MessageBag();
                $errors->add('Documentos', 'Debe adjuntar Rol de la Empresa.');
                $solicitud = Solicitud::findOrFail($id);
                $para = Para::Where('solicitud_id', $id)->get();
                return view('documento.create', compact('solicitud', 'para'))->withErrors($errors);
            }
        }

        
        if(Para::find($id)){
            if($request->hasFile('CedulaPara_PDF')){
                $doc = new Documento();
                $doc->name = $request->file('CedulaPara_PDF')->store('public');
                $doc->type = 'pdf';
                $doc->description = 'Cédula de Compra Para';
                $doc->solicitud_id = $id;
                $doc->tipo_documento_id = 5;
                $doc->added_at = Carbon::now()->toDateTimeString();
                $doc->save();
            }else{
                $errors = new MessageBag();
                $errors->add('Documentos', 'Debe adjuntar Cédula Compra/Para.');
                $solicitud = Solicitud::findOrFail($id);
                $para = Para::Where('solicitud_id', $id)->get();
                return view('documento.create', compact('solicitud', 'para'))->withErrors($errors);
            }  
        }

        return redirect()->route('solicitud.show', ['id' => $id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
     * 
     */
    public function destroy(Request $request)
    {
        $id = $request->solicitud_id;
        $doc_name = $request->doc_name;
        $doc = Documento::where('solicitud_id',$id)->where('name',$doc_name)->first();
        $doc->delete();
        Storage::delete($doc_name);
        return json_encode(['status'=>'OK','msj'=>'Archivo eliminado exitosamente']);
    }


    public function CargaDocumentos(Request $request, $id){
        $solicitud = Solicitud::find($id);
        //Obtenemos factura guardada en bd y en storage para convertir el archivo a base64
        $documentos = Documento::where('solicitud_id', $id)->where('tipo_documento_id',2)->first();
        $base64_factura = $this->getFileAsBase64($documentos->name);

        $cedula_cliente = Documento::where('solicitud_id', $id)->where('tipo_documento_id',3)->first();
        $base64_cedula_cliente = '';
        if($cedula_cliente != null){
            $base64_cedula_cliente = $this->getFileAsBase64($cedula_cliente->name);
        }

        $base64_cedula_para = '';
        if (sizeof(CompraPara::getSolicitud($id)) > 0) {
            $cedula_para = Documento::where('solicitud_id', $id)->where('tipo_documento_id',5)->first();
            if($cedula_para != null){
                $base64_cedula_para = $this->getFileAsBase64($cedula_para->name);
            }
        }

        $base64_rol_empresa = '';
        if($solicitud->empresa==1){
            $rol_empresa = Documento::where('solicitud_id', $id)->where('tipo_documento_id',4)->first();
            if($rol_empresa != null){
                $base64_rol_empresa = $this->getFileAsBase64($rol_empresa->name);
            }
        }
        

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3){
            //Cédula de propietario
            $solicitud_rc = SolicitudRC::getSolicitud($id);
            $parametros = [
                'consumidor' => 'ACOBRO',
                'servicio' => 'INGRESO DOCUMENTOS RVM',
                'file' => ($base64_cedula_cliente == '')? base64_encode(file_get_contents($request->file('Cedula_PDF')->getRealPath())) : $base64_cedula_cliente,
                'patente' => str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]),
                'nro' => $solicitud_rc[0]->numeroSol,
                'tipo_sol' => 'P',
                'tipo_doc' => "PDF",
                'clasificacion' => 1,
                'fecha_ing' => date('d-m-Y'),
                'nombre' => ($base64_cedula_cliente == '')? $request->file('Cedula_PDF')->getClientOriginalName() : str_replace('public/','',$cedula_cliente->name)
            ];
            $data = RegistroCivil::subirDocumentos(json_encode($parametros));
            $salida = json_decode($data, true);
            //dd($salida);
            if (isset($salida['OUTPUT'])) {
                if ($salida['OUTPUT'] != "OK") {
                    Log::error('Error al subir cédula de adquiriente 1');
                    return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir cédula de adquiriente. Inténtelo nuevamente.']);
                }
            }
            else{
                Log::error('Error al subir cédula de adquiriente 2');
                return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir cédula de adquiriente. Inténtelo nuevamente.']);
            }
            sleep(4);

            //Log::info('compra para existe: '.CompraPara::getSolicitud($id));
            //Cédula de compra para
            if (sizeof(CompraPara::getSolicitud($id)) > 0) {
                $parametros2 = array(
                    'consumidor' => 'ACOBRO',
                    'servicio' => 'INGRESO DOCUMENTOS RVM',
                    'file' => ($base64_cedula_para == '')? base64_encode(file_get_contents($request->file('Cedula_Para_PDF')->getRealPath())) : $base64_cedula_para,
                    'patente' => str_replace(".", "", explode("-", $solicitud_rc[0]->ppu)[0]),
                    'nro' => $solicitud_rc[0]->numeroSol,
                    'tipo_sol' => 'P',
                    'tipo_doc' => "PDF",
                    'clasificacion' => 1,
                    'fecha_ing' => date('d-m-Y'),
                    'nombre' => ($base64_cedula_para == '')? $request->file('Cedula_Para_PDF')->getClientOriginalName() : str_replace('public/','',$cedula_para->name)
                );
                $data = RegistroCivil::subirDocumentos(json_encode($parametros2));
                $salida = json_decode($data, true);
                if (isset($salida['OUTPUT'])) {
                    if ($salida['OUTPUT'] != "OK") {
                        Log::error('Error al subir cédula de estipulante o compra para 1');
                        return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir cédula de estipulante o compra para. Inténtelo nuevamente.']);
                    }
                }
                else{
                    Log::error('Error al subir cédula de estipulante o compra para 2');
                    return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir cédula de estipulante o compra para. Inténtelo nuevamente']);
                }
            }
            sleep(4);
            //Factura
            $parametros3 = [
                'consumidor' => 'ACOBRO',
                'servicio' => 'INGRESO DOCUMENTOS RVM',
                'file' => $base64_factura,
                'patente' => str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]),
                'nro' => $solicitud_rc[0]->numeroSol,
                'tipo_sol' => 'P',
                'tipo_doc' => "PDF",
                'clasificacion' => 1,
                'fecha_ing' => date('d-m-Y'),
                'nombre' => str_replace('public/','',$documentos->name)
            ];
            $data = RegistroCivil::subirDocumentos(json_encode($parametros3));
            $salida = json_decode($data, true);
            if (isset($salida['OUTPUT'])) {
                if ($salida['OUTPUT'] != "OK") {
                    Log::error('Error al subir factura en PDF 1');
                    return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir factura en PDF. Inténtelo nuevamente']);
                }
            }
            else{
                Log::error('Error al subir factura en PDF 2');
                return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir factura en PDF. Inténtelo nuevamente']);
            }

            if($solicitud->empresa==1){
                //Rol de empresa
                $parametros4 = [
                    'consumidor' => 'ACOBRO',
                    'servicio' => 'INGRESO DOCUMENTOS RVM',
                    'file' => ($base64_rol_empresa == '')? base64_encode(file_get_contents($request->file('Rol_PDF')->getRealPath())) : $base64_rol_empresa,
                    'patente' => str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]),
                    'nro' => $solicitud_rc[0]->numeroSol,
                    'tipo_sol' => 'P',
                    'tipo_doc' => "PDF",
                    'clasificacion' => 1,
                    'fecha_ing' => date('d-m-Y'),
                    'nombre' => ($base64_rol_empresa == '')? $request->file('Rol_PDF')->getClientOriginalName() : str_replace('public/','',$rol_empresa->name)
                ];
                $data = RegistroCivil::subirDocumentos(json_encode($parametros4));
                $salida = json_decode($data, true);
                if (isset($salida['OUTPUT'])) {
                    if ($salida['OUTPUT'] != "OK") {
                        Log::error('Error al subir rol de empresa en PDF 1');
                        return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir rol de empresa en PDF. Inténtelo nuevamente']);
                    }
                }
                else{
                    Log::error('Error al subir rol de empresa en PDF 2');
                    return json_encode(['status'=>'ERROR','esRevision'=>true,'msj'=>'Error al subir rol de empresa en PDF. Inténtelo nuevamente']);
                }
            }

            $new_documento_rc = new EnvioDocumentoRC();
            $new_documento_rc->solicitud_id = $id;
            $new_documento_rc->ppu = str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]);
            $new_documento_rc->numeroSol = $solicitud_rc[0]->numeroSol;
            $new_documento_rc->save();

            $html = view('solicitud.pagos', compact('id', 'solicitud_rc'))->render();

            $html2 = view('solicitud.comprobante',compact('id','solicitud_rc'))->render();

            return json_encode(['status'=>'OK','esRevision'=>true,'html2'=>$html2,'html'=>$html,'msj'=>'Archivos enviados exitosamente a Registro Civil']);
        }
        else{
            if($request->hasFile('Cedula_PDF')){
                $file = $request->file('Cedula_PDF');
                $path = Storage::disk('public')->putFileAs('', $file, $file->getClientOriginalName());
                $doc = new Documento();
                $doc->name = 'public/'.$path;
                $doc->type = 'pdf';
                $doc->description = 'Cédula del Cliente';
                $doc->solicitud_id = $id;
                $doc->tipo_documento_id = 3;
                $doc->added_at = Carbon::now()->toDateTimeString();
                $doc->save();
            }else{
                if($base64_cedula_cliente == ''){
                    $errors = new MessageBag();
                    $errors->add('Documentos', 'Debe adjuntar Cédula del Cliente.');
                    return json_encode(['status'=>'ERROR','esRevision'=>false,'msj'=>'Error al subir cédula de adquiriente']);
                }
            }

            if (sizeof(CompraPara::getSolicitud($id)) > 0) {
                if($request->hasFile('Cedula_Para_PDF')){
                    $file = $request->file('Cedula_Para_PDF');
                    $path = Storage::disk('public')->putFileAs('', $file, $file->getClientOriginalName());
                    $doc = new Documento();
                    $doc->name = 'public/'.$path;
                    $doc->type = 'pdf';
                    $doc->description = 'Cédula de Compra Para';
                    $doc->solicitud_id = $id;
                    $doc->tipo_documento_id = 5;
                    $doc->added_at = Carbon::now()->toDateTimeString();
                    $doc->save();
                }else{
                    if($base64_cedula_para == ''){
                        $errors = new MessageBag();
                        $errors->add('Documentos', 'Debe adjuntar Cédula Compra/Para.');                    
                        return json_encode(['status'=>'ERROR','esRevision'=>false,'msj'=>'Error al subir cédula de estipulante o compra para']);
                    }
                }  
            }

            
            if($solicitud->empresa==1){
                if($request->hasFile('Rol_PDF')){
                    $file = $request->file('Rol_PDF');
                    $path = Storage::disk('public')->putFileAs('', $file, $file->getClientOriginalName());
                    $doc = new Documento();
                    $doc->name = 'public/'.$path;
                    $doc->type = 'pdf';
                    $doc->description = 'Rol de Empresa';
                    $doc->solicitud_id = $id;
                    $doc->tipo_documento_id = 4;
                    $doc->added_at = Carbon::now()->toDateTimeString();
                    $doc->save();
                }else{
                    if($base64_rol_empresa == ''){
                        $errors = new MessageBag();
                        $errors->add('Documentos', 'Debe adjuntar Rol de la Empresa.');
                        return json_encode(['status'=>'ERROR','esRevision'=>false,'msj'=>'Error al subir rol de empresa']);
                    }
                }
            }


            return json_encode(['status'=>'OK','msj'=>'Archivos guardados exitosamente','esRevision'=>false]);
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
