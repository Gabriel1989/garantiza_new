<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Tipo_Vehiculo;
use App\Models\Solicitud;
use App\Models\Transferencia;
use App\Models\Propietario;
use App\Models\Factura;
use App\Models\Limitacion;
use App\Models\Reingreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class BuscadorController extends Controller
{

    /*******FUNCIONES BÚSQUEDA SPIEV **********/
    public function tipoVehiculo()
    {
        $tipoVehiculo = Tipo_Vehiculo::all();
        return view('buscador.spiev.tipoVehiculo', compact('tipoVehiculo'));
    }

    public function rutadquiriente(){
        return view('buscador.spiev.rutadquiriente');
    }

    public function numFactura(){
        return view('buscador.spiev.numfactura');
    }

    public function tipoVehiculoForm(Request $request)
    {
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            $solicitudes = Solicitud::getSolicitudesFiltro('tipo_vehiculo', $request->tiposVehi, true);
        } else {
            $solicitudes = Solicitud::getSolicitudesFiltro('tipo_vehiculo', $request->tiposVehi, false);
        }

        $html = $this->traerDataTable($solicitudes);
        
        return $html;
    }

    public function rutadquirienteForm(Request $request)
    {
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            $solicitudes = Solicitud::getSolicitudesFiltro('rut_adquiriente', $request->rut, true);
        } else {
            $solicitudes = Solicitud::getSolicitudesFiltro('rut_adquiriente', $request->rut, false);
        }

        $html = $this->traerDataTable($solicitudes);
        
        return $html;
    }

    public function numFacturaForm(Request $request)
    {
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            $solicitudes = Solicitud::getSolicitudesFiltro('numero_factura', $request->num_factura, true);
        } else {
            $solicitudes = Solicitud::getSolicitudesFiltro('numero_factura', $request->num_factura, false);
        }

        $html = $this->traerDataTable($solicitudes);
        
        return $html;
    }
    
    private function traerDataTable($solicitudes){
        $html = '';
        if (sizeof($solicitudes) > 0) {
            $classes = ['active', 'success', 'warning'];
            $classesCount = count($classes);
            foreach ($solicitudes as $index => $item) {
                try{
                    $item->cliente = Factura::select('razon_social_recep')->where('id_solicitud',$item->id)->first();
                    
                }
                catch(Exception $e){
                    $item->cliente = '';
                }
                $class = $classes[$index % $classesCount];
                $html .= '<tr class="' . $class . '">';
                $html .= '<td scope="row">' . $item->id . '</td>';
                $html .= '<td>' . date('d-m-Y h:i A', strtotime($item->created_at)) . '</td>';
                if(!(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3)){
                    $html .= '<td>' .$item->sucursales. '</td>';
                }
                else{
                    $html .= '<td>'.$item->concesionaria.'</td>';
                }
                $html .= '<td>';
                if ($item->estado_id == 1) {
                    $html .=
                        '<i class="fa fa-check green"></i>Solicitud creada
                    <br>
                    <i class="fa fa-times red"></i>Ingresar adquiriente
                    <br>
                    <i class="fa fa-times red"></i>Compra Para 
                    <br>
                    <i class="fa fa-times red"></i>Ingresar resumen solicitud
                    <br>
                    <i class="fa fa-times red"></i>Adjuntar documentación
                    <br>
                    <i class="fa fa-times red"></i>Ingresar limitación
                    <br>
                    <i class="fa fa-times red"></i>Proceso finalizado';

                } elseif ($item->estado_id == 2) {
                    $html .=
                        '<i class="fa fa-check green"></i>Solicitud creada
                        <br>
                        <i class="fa fa-check green"></i>Ingresar adquiriente
                        <br>
                        <i class="fa fa-times red"></i>Compra Para 
                        <br>
                        <i class="fa fa-times red"></i>Ingresar resumen solicitud
                        <br>
                        <i class="fa fa-times red"></i>Adjuntar documentación
                        <br>
                        <i class="fa fa-times red"></i>Ingresar limitación
                        <br>
                        <i class="fa fa-times red"></i>Proceso finalizado';

                } elseif ($item->estado_id == 3) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada
                              <br>
                              <i class="fa fa-check green"></i>Ingresar adquiriente
                              <br>';
                    if ($item->rut_para != null) {
                        $html .= '<i class="fa fa-check green"></i>Compra Para ingresado';
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Compra Para no aplica';
                    }
                    $html .= '<br><i class="fa fa-times red"></i>Ingresar resumen solicitud
                              <br>
                              <i class="fa fa-times red"></i>Adjuntar documentación
                              <br>
                              <i class="fa fa-times red"></i>Ingresar limitación
                              <br>
                              <i class="fa fa-times red"></i>Proceso finalizado';

                } elseif ($item->estado_id == 6 || $item->estado_id == 7) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada
                            <br>
                            <i class="fa fa-check green"></i>Ingresar adquiriente
                            <br>';
                    if ($item->rut_para != null) {
                        $html .= '<i class="fa fa-check green"></i>Compra Para ingresado';
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Compra Para no aplica';
                    }
                    $html .= '<br>';
                    if ($item->numeroSol != null) {
                        if ($item->nroSolicitud == null) {
                            $html .= '<i class="fa fa-check green"></i>Primera Inscripción creada en RC
                                    <br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Reingreso pendiente en RC
                                    <br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Ingresar resumen solicitud
                                <br>';
                    }

                    if ($item->numeroSolDocrc != null) {
                        $html .= '<i class="fa fa-check green"></i>Documentación enviada a RC
                                <br>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Documentación NO enviada a RC
                                <br>';
                    }
                    if ($item->id_limitacion != null) {
                        if ($item->id_limitacion_rc != null) {
                            $html .= '<i class="fa fa-check green"></i>Registrar limitación en RC
                                    <br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Ingresar limitación
                                    <br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Ingresar limitación
                                <br>';
                    }

                    if ($item->pagada && $item->monto_inscripcion > 0) {
                        $html .= '<i class="fa fa-check green"></i>Proceso finalizado';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Proceso finalizado';
                    }

                } elseif ($item->estado_id == 11) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada
                                <br>
                                <i class="fa fa-check green"></i>Ingresar adquiriente
                                <br>';
                    if ($item->rut_para != null) {
                        $html .= '<i class="fa fa-check green"></i>Compra Para ingresado';
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Compra Para no aplica';
                    }
                    $html .= '<br>
                                <i class="fa fa-check green"></i>Envío RC rechazado
                                <br>';
                    if ($item->numeroSolDocrc != null) {
                        $html .= '<i class="fa fa-check green"></i>Documentación enviada a RC
                        <br>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Documentación NO enviada a RC
                        <br>';
                    }
                    if ($item->id_limitacion != null) {
                        if ($item->id_limitacion_rc != null) {
                            $html .= '<i class="fa fa-check green"></i>Registrar limitación en RC
                            <br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Ingresar limitación
                            <br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Ingresar limitación
                        <br>';
                    }
                    $html .= '<i class="fa fa-times red"></i>Proceso finalizado';


                } elseif ($item->estado_id == 12) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada
                    <br>
                    <i class="fa fa-check green"></i>Ingresar adquiriente
                    <br>';
                    if ($item->rut_para != null) {
                        $html .= '<i class="fa fa-check green"></i>Compra Para ingresado';
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Compra Para no aplica';
                    }
                    $html .= '<br>';
                    if ($item->numeroSol != null) {
                        if ($item->nroSolicitud == null) {
                            $html .= '<i class="fa fa-check green"></i>Primera Inscripción creada en RC
                            <br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Reingreso pendiente en RC
                            <br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Ingresar resumen solicitud
                        <br>';
                    }

                    if ($item->numeroSolDocrc != null) {
                        $html .= '<i class="fa fa-check green"></i>Documentación enviada a RC
                                    <br>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Documentación NO enviada a RC
                                <br>';
                    }
                    if ($item->id_limitacion != null) {
                        if ($item->id_limitacion_rc != null) {
                            $html .= '<i class="fa fa-check green"></i>Registrar limitación en RC
                            <br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Ingresar limitación
                            <br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Ingresar limitación
                        <br>';
                    }
                    if ($item->pagada && $item->monto_inscripcion > 0) {
                        $html .= '<i class="fa fa-check green"></i>Proceso finalizado';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Proceso finalizado';
                    }
                }

                if (isset($item->cliente->razon_social_recep)) {
                    $html .= '<td>' . $item->cliente->razon_social_recep . '</td>';
                } else {
                    $html .= '<td></td>';
                }

                $html .= '<td>';
                if(!$item->pagada){ 
                    $html .= '<span style="background-color:#F00;color:#ffffff;">No pagada</span>';
                }else{ 
                    $html .= '<span style="background-color:#08bd08;color:#ffffff;">Pagada</span>';
                }
                $html .= '</td>';
                
                $html .= '<td>'.$item->monto_inscripcion.'</td>';

                $html .= '<td>
                    <label>SOAP';

                if (!is_null($item->incluyeSOAP)) {
                    if ($item->incluyeSOAP == 1) {
                        $html .= '<i class="fa fa-check green"></i>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>';
                    }
                } else {
                    $html .= '<i class="fa fa-times red"></i>';
                }

                $html .= '</label>
                    <br>
                    <label>TAG';
                if (!is_null($item->incluyeTAG)) {
                    if ($item->incluyeTAG == 1) {
                        $html .= '<i class="fa fa-check green"></i>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>';
                    }
                } else {
                    $html .= '<i class="fa fa-times red"></i>';
                }
                $html .= '</label>
                    <br>
                    <label>Permiso de circulación';
                if (!is_null($item->incluyePermiso)) {
                    if ($item->incluyePermiso == 1) {
                        $html .= '<i class="fa fa-check green"></i>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>';
                    }
                } else {
                    $html .= '<i class="fa fa-times red"></i>';
                }
                $html .= '</label>
                        </td>';

                if(!(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3)){
                    $html .= '<td>
                    <button type="button" data-trigger="tooltip" data-placement="top" title="Continuar ingreso de solicitud donde había quedado" class="btn btn-dark btn-sm" onclick="location.href=\''.route('solicitud.continuarSolicitud', ['id' => $item->id,'reingresa'=> 0,'acceso'=>'ingreso']). '\'" data-old-onclick="location.href=\''.route('solicitud.revision.cedula', ['id' => $item->id]).'\'">
                        <li class="fa fa-pencil"></li> Continuar Ingreso</button>
                    <br>
                    <br>
                    <button type="button" data-trigger="tooltip" data-placement="top" title="Reingresar solicitud" onclick="location.href=\''.route('solicitud.continuarSolicitud', ['id' => $item->id,'reingresa'=> true,'acceso'=>'ingreso']). '\'" class="btn btn-success"><i class="fa fa-refresh"></i> Reingresar</button>
                    <br>
                    <br>
                    <button type="button" data-trigger="tooltip" data-placement="top" title="Generar comprobante solicitud" class="btn btn-danger btnDescargaPdfGarantiza" data-garantizaSol="'.$item->id.'"><i class="fa fa-file-pdf-o"></i> Generar comprobante</button>
                    </td>';
                }
                else{
                    $html .= '<td>
                    <button type="button" data-trigger="tooltip" title="Revisar solicitud para posterior envío a RC" class="btn btn-dark btn-sm mb10" onclick="location.href=\''.route('solicitud.revision.cedula', ['id' => $item->id]).'\'">
                        <li class="fa fa-pencil"></li> Revisar</button>
                    <br>
                    <button type="button" data-trigger="tooltip" title="Registrar pago de solicitud para registro interno" class="btn btn-sm btn-primary btnRegistraPagos mb10" data-solicitud="'.$item->id.'" data-toggle="modal" data-target="#modal-pago-form" onclick="registrarPagoForm('.$item->id.')">
                        <li class="fa fa-money"></li> Registrar Pago</button>
                    </button>
                    <br>
                    <button type="button" data-trigger="tooltip" title="Ver documentos adjuntados a la solicitud" class="btn btn-sm btn-success btnVerDocsSolicitud mb10" data-solicitud="'.$item->id.'" data-toggle="modal" data-target="#modal-docs-form" onclick="verDocsSolicitud('.$item->id.')">
                        <li class="fa fa-file"></li> Ver Documentos</button>
                    </button>
                    <br>
                    <button type="button" data-trigger="tooltip" data-placement="top" title="Generar comprobante solicitud" class="btn btn-danger btnDescargaPdfGarantiza" data-garantizaSol="'. $item->id .'"><i class="fa fa-file-pdf-o"></i> Generar comprobante</button>
                    </td>';
                }

                $html .= '<td>';
                $solicitud_rc = Solicitud::getSolicitudRC($item->id);

                if(count($solicitud_rc) > 0){
                    $html .= '<button type="button" style="margin-bottom:5px;" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="'.$item->id.'" data-numsol="'. $solicitud_rc[0]->numeroSol .'" class="btn btn-dark btn-sm btnRevisaSolicitud">
                        <li class="fa fa-eye"></li> Revisar estado solicitud en RC
                    </button>';
                }    
                                    
                $limitacion_rc = Limitacion::getLimitacionRC($item->id);

                if(count($limitacion_rc) > 0){
                $html .= '<button type="button" style="white-space:normal;margin-bottom:5px;" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="'.$item->id.'" data-numsol="'. $limitacion_rc[0]->numSol .'" class="btn btn-dark btn-sm btnRevisaLimitacion">
                    <li class="fa fa-eye"></li> Revisar estado solicitud de limitación/prohibición en RC
                    </button>';
                }       

                $reingreso_rc = Reingreso::where('solicitud_id',$item->id)->get();

                if(count($reingreso_rc) > 0){
                    $html .= '<button type="button" data-toggle="modal" data-target="#modal_solicitud" data-reingreso="'.base64_encode($reingreso_rc).'" class="btn btn-dark btn-sm btnRevisaReingreso">
                        <li class="fa fa-eye"></li> Revisar estado de reingreso
                    </button>';
                }      


                $html .= '</td></tr>';


            }
        }

        return $html;
    }

    /**************FIN BÚSQUEDA SPIEV **************/



    /*************FUNCIONES BÚSQUEDA STEV ************/
    public function ppu(){
        return view('buscador.stev.ppu');
    }

    public function rutcomprador(){
        return view('buscador.stev.rutcomprador');
    }

    public function numerodoc(){
        return view('buscador.stev.numerodoc');
    }

    public function ppuForm(Request $request){
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            $solicitudes = Transferencia::getSolicitudesFiltro('ppu', $request->ppu, true);
        } else {
            $solicitudes = Transferencia::getSolicitudesFiltro('ppu', $request->ppu, false);
        }

        $html = $this->traerDataTableStev($solicitudes);
        
        return $html;

    }

    public function rutcompradorForm(Request $request){
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            $solicitudes = Transferencia::getSolicitudesFiltro('rut_comprador', $request->rut, true);
        } else {
            $solicitudes = Transferencia::getSolicitudesFiltro('rut_comprador', $request->rut, false);
        }

        $html = $this->traerDataTableStev($solicitudes);
        
        return $html;
    }

    public function numerodocForm(Request $request){
        if (Auth::user()->rol_id == 1 || Auth::user()->rol_id == 3) {
            $solicitudes = Transferencia::getSolicitudesFiltro('numero_doc', $request->num_doc, true);
        } else {
            $solicitudes = Transferencia::getSolicitudesFiltro('numero_doc', $request->num_doc, false);
        }

        $html = $this->traerDataTableStev($solicitudes);
        
        return $html;
    }


    private function traerDatatableStev($solicitudes){
        $html = '';
        if (sizeof($solicitudes) > 0) {
            $classes = ['active', 'success', 'warning'];
            $classesCount = count($classes);
            foreach ($solicitudes as $index => $item) {
                try{
                    $item->cliente = Propietario::select('nombre','razon_social','aPaterno','aMaterno')->where('transferencia_id',$item->id)->first();
                    
                }
                catch(Exception $e){
                    $item->cliente = '';
                }
                $class = $classes[$index % $classesCount];
                $html .= '<tr class="' . $class . '">';
                $html .= '<td scope="row">' . $item->id . '</td>';
                $html .= '<td>' . date('d-m-Y h:i A', strtotime($item->created_at)) . '</td>';
                $html .= '<td>'.$item->notarias.'</td>';
                $html .= '<td>';
                if ($item->estado_id == 1) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar comprador<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar vendedor<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar estipulante o no<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar resumen transferencia<br>';
                    $html .= '<i class="fa fa-times red"></i>Adjuntar documentación<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar limitación<br>';
                    $html .= '<i class="fa fa-times red"></i>Proceso finalizado<br>';
                } elseif ($item->estado_id == 2) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar comprador<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar vendedor<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar estipulante o no<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar resumen transferencia<br>';
                    $html .= '<i class="fa fa-times red"></i>Adjuntar documentación<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar limitación<br>';
                    $html .= '<i class="fa fa-times red"></i>Proceso finalizado<br>';
                } elseif($item->estado_id == 3){
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar comprador<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar vendedor<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar estipulante o no<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar resumen transferencia<br>';
                    $html .= '<i class="fa fa-times red"></i>Adjuntar documentación<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar limitación<br>';
                    $html .= '<i class="fa fa-times red"></i>Proceso finalizado<br>';
                } elseif($item->estado_id == 4){
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar comprador<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar vendedor<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar estipulante o no<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar resumen transferencia<br>';
                    $html .= '<i class="fa fa-times red"></i>Adjuntar documentación<br>';
                    $html .= '<i class="fa fa-times red"></i>Ingresar limitación<br>';
                    $html .= '<i class="fa fa-times red"></i>Proceso finalizado<br>';
                } elseif($item->estado_id == 5) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar comprador<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar vendedor<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar estipulante<br>';
                    if($item->numeroSol != null) {
                        if($item->nroSolicitud == null) {
                            $html .= '<i class="fa fa-check green"></i>Transferencia creada en RC<br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Reingreso pendiente en RC<br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Ingresar resumen transferencia<br>';
                    }
                    if($item->numeroSolDocrc != null) {
                        $html .= '<i class="fa fa-check green"></i>Documentación enviada a RC<br>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Documentación NO enviada a RC<br>';
                    }
                    if($item->id_limitacion != null) {
                        if($item->id_limitacion_rc != null) {
                            $html .= '<i class="fa fa-check green"></i>Registrar limitación en RC<br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Ingresar limitación<br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Ingresar limitación<br>';
                    }
                    if($item->pagada && $item->monto_inscripcion > 0) {
                        $html .= '<i class="fa fa-check green"></i>Proceso finalizado<br>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Proceso finalizado<br>';
                    }
                } elseif($item->estado_id == 12) {
                    $html .= '<i class="fa fa-check green"></i>Solicitud creada<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar comprador<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar vendedor<br>';
                    $html .= '<i class="fa fa-check green"></i>Ingresar estipulante<br>';
                
                    if($item->numeroSol != null) {
                        if($item->nroSolicitud == null) {
                            $html .= '<i class="fa fa-check green"></i>Transferencia creada en RC<br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Reingreso pendiente en RC<br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-check green"></i>Ingresar resumen transferencia<br>';
                    }
                
                    if($item->numeroSolDocrc != null) {
                        $html .= '<i class="fa fa-check green"></i>Documentación enviada a RC<br>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Documentación NO enviada a RC<br>';
                    }
                
                    if($item->id_limitacion != null) {
                        if($item->id_limitacion_rc != null) {
                            $html .= '<i class="fa fa-check green"></i>Registrar limitación en RC<br>';
                        } else {
                            $html .= '<i class="fa fa-check green"></i>Ingresar limitación<br>';
                        }
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Ingresar limitación<br>';
                    }
                
                    if($item->pagada && $item->monto_inscripcion > 0) {
                        $html .= '<i class="fa fa-check green"></i>Proceso finalizado<br>';
                    } else {
                        $html .= '<i class="fa fa-times red"></i>Proceso finalizado<br>';
                    }
                }
                $html .= '</td>';
                $html .= '<td>';
                if($item->cliente != ''){ 
                    if(is_null($item->cliente->nombre)){ 
                        $html .= $item->cliente->razon_social; 
                    }else{ 
                        $html .= $item->cliente->nombre .' '.$item->cliente->aPaterno.' '.$item->cliente->aMaterno;
                    } 
                } 
                $html .= '</td>';

                $html .= '<td>';
                if(!$item->pagada){ 
                    $html .= '<span style="background-color:#F00;color:#ffffff;">No pagada</span>';
                }else{ 
                    $html .= '<span style="background-color:#08bd08;color:#ffffff;">Pagada</span>';
                }
                $html .= '</td>';
                
                $html .= '<td>'.$item->monto_inscripcion.'</td>';

                if(!(auth()->user()->rol_id == 1 || auth()->user()->rol_id == 3)){
                    $html .= '<td>
                    <button type="button" data-trigger="tooltip" title="Continuar ingreso de solicitud donde había quedado" class="btn btn-dark btn-sm" onclick="location.href=\''.route('transferencia.continuarSolicitud', ['id' => $item->id,'reingresa'=> 0,'acceso'=>'ingreso']). '\'">
                        <li class="fa fa-pencil"></li> Continuar Ingreso</button>
                    <br>
                    <br>
                    <button type="button" data-trigger="tooltip" data-placement="top" title="Reingresar transferencia" onclick="location.href=\''.route('transferencia.continuarSolicitud', ['id' => $item->id,'reingresa'=> true,'acceso'=>'ingreso']). '\'" class="btn btn-success"><i class="fa fa-refresh"></i> Reingresar</button>
                    <br>
                    <br>
                    <button type="button" data-trigger="tooltip" data-placement="top" title="Generar comprobante solicitud" class="btn btn-danger btnDescargaPdfGarantiza" data-garantizaSol="'. $item->id .'"><i class="fa fa-file-pdf-o"></i> Generar comprobante</button>
                    </td>';
                }else{
                    $html .= '<td>
                    <button type="button" data-trigger="tooltip" title="Revisar solicitud para posterior envío a RC" class="btn btn-dark btn-sm mb10" onclick="location.href=\''.route('transferencia.revision.cedula', ['id' => $item->id]).'\'">
                        <li class="fa fa-pencil"></li> Revisar</button>
                    <br>
                    <button type="button" data-trigger="tooltip" title="Registrar pago de solicitud para registro interno" class="btn btn-sm btn-primary btnRegistraPagos mb10" data-solicitud="'.$item->id.'" data-toggle="modal" data-target="#modal-pago-form" onclick="registrarPagoForm('.$item->id.')">
                        <li class="fa fa-money"></li> Registrar Pago</button>
                    </button>
                    <br>
                    <button type="button" data-trigger="tooltip" title="Ver documentos adjuntados a la solicitud" class="btn btn-sm btn-success btnVerDocsSolicitud mb10" data-solicitud="'.$item->id.'" data-toggle="modal" data-target="#modal-docs-form" onclick="verDocsSolicitud('.$item->id.')">
                        <li class="fa fa-file"></li> Ver Documentos</button>
                    </button>
                    <br>
                    <button type="button" data-trigger="tooltip" data-placement="top" title="Generar comprobante solicitud" class="btn btn-danger btnDescargaPdfGarantiza" data-garantizaSol="'.$item->id.'"><i class="fa fa-file-pdf-o"></i> Generar comprobante</button>
                    </td>';
                }

                $html .= '<td>';
                $solicitud_rc = Transferencia::getTransferenciaRC($item->id);
                if(count($solicitud_rc) > 0){
                    $html .= '<button type="button" style="margin-bottom:5px;" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="'.$item->id.'" data-numsol="'. $solicitud_rc[0]->numeroSol .'" class="btn btn-dark btn-sm btnRevisaSolicitud">
                        <li class="fa fa-eye"></li> Revisar estado solicitud en RC
                    </button>';
                }    

                $limitacion_rc = Limitacion::getLimitacionTransferenciaRC($item->id);
                
                if(count($limitacion_rc) > 0){
                    $html .= '<button style="white-space:normal;margin-bottom:5px;" type="button" data-toggle="modal" data-target="#modal_solicitud" data-garantizaSol="'.$item->id.'" data-numsol="'. $limitacion_rc[0]->numSol .'" class="btn btn-dark btn-sm btnRevisaLimitacion">
                        <li class="fa fa-eye"></li> Revisar estado solicitud de limitación/prohibición en RC
                    </button>';
                }       

                $reingreso_rc = Reingreso::where('transferencia_id',$item->id)->get();

                if(count($reingreso_rc) > 0){
                    $html .= '<button type="button" data-toggle="modal" data-target="#modal_solicitud" data-reingreso="'.base64_encode($reingreso_rc).'" class="btn btn-dark btn-sm btnRevisaReingreso">
                        <li class="fa fa-eye"></li> Revisar estado de reingreso
                    </button>';
                }      

                $html .= '</td></tr>';
            }
        }
        return $html;
    }

    /************ FIN FUNCIONES BÚSQUEDA STEV ************/
}