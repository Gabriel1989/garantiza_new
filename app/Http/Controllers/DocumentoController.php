<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Para;
use App\Models\Solicitud;
use App\Models\SolicitudRC;
use App\Models\CompraPara;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Helpers\RegistroCivil;

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function CargaDocumentos(Request $request, $id){

        $solicitud_rc = SolicitudRC::getSolicitud($id);

        //$base64string = base64_encode(file_get_contents($request->file('Cedula_PDF')->getRealPath()));
        //file_put_contents($request->file('Cedula_PDF')->getClientOriginalName(), base64_decode($base64string));
        //exit;
        //dd($request);
        $parametros = [
            'consumidor' => 'ACOBRO',
            'servicio' => 'INGRESO DOCUMENTOS RVM',
            'file' => base64_encode(file_get_contents($request->file('Cedula_PDF')->getRealPath())),
            'patente' => str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]),
            'nro' => $solicitud_rc[0]->numeroSol,
            'tipo_sol' => 'P',
            'tipo_doc' => "PDF",
            'clasificacion' => 1,
            'fecha_ing' => date('d-m-Y'),
            'nombre' => $request->file('Cedula_PDF')->getClientOriginalName()
        ];

        //dd($parametros);

        $data = RegistroCivil::subirDocumentos(json_encode($parametros));
        //var_dump($data); die;
        $salida = json_decode($data, true);

        var_dump($salida); die;

        if(!is_null(CompraPara::getSolicitud($id))){
            $parametros = array(
                'consumidor' => 'ACOBRO',
                'servicio' => 'INGRESO DOCUMENTOS RVM',
                'file' => base64_encode(file_get_contents($request->file('Cedula_Para_PDF')->getRealPath())),
                'patente' => str_replace(".","",explode("-",$solicitud_rc[0]->ppu)[0]),
                'nro' => $solicitud_rc[0]->numeroSol,
                'tipo_sol' => 'P',
                'tipo_doc' => "PDF",
                'clasificacion' => 1,
                'fecha_ing' => date('d-m-Y'),
                'nombre' => $request->file('Cedula_Para_PDF')->getClientOriginalName()
            );
    
            $data = RegistroCivil::subirDocumentos($parametros);
            $salida = json_decode($data, true);

        }
    }
}
