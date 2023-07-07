<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Tipo_Vehiculo;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuscadorController extends Controller
{


    public function tipoVehiculo()
    {
        $tipoVehiculo = Tipo_Vehiculo::all();
        return view('buscador.tipoVehiculo', compact('tipoVehiculo'));
    }

    public function rutadquiriente(){
        return view('buscador.rutadquiriente');
    }

    public function numFactura(){
        return view('buscador.numfactura');
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
                $class = $classes[$index % $classesCount];
                $html .= '<tr class="' . $class . '">';
                $html .= '<td scope="row">' . $item->id . '</td>';
                $html .= '<td>' . date('d-m-Y h:i A', strtotime($item->created_at)) . '</td>';
                $html .= '<td>' . $item->sucursales . '</td>';
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

                $html .= '<td>
                <button type="button" data-toggle="tooltip" data-placement="top" title="Continuar ingreso de solicitud donde había quedado" class="btn btn-dark btn-sm" onclick="location.href=\''.route('solicitud.continuarSolicitud', ['id' => $item->id,'reingresa'=> 0,'acceso'=>'ingreso']). '\'" data-old-onclick="location.href=\''.route('solicitud.revision.cedula', ['id' => $item->id]).'\'">
                    <li class="fa fa-pencil"></li> Continuar Ingreso</button>
                <br>
                <br>
                <button type="button" data-toggle="tooltip" data-placement="top" title="Reingresar solicitud" onclick="location.href=\''.route('solicitud.continuarSolicitud', ['id' => $item->id,'reingresa'=> true,'acceso'=>'ingreso']). '\'" class="btn btn-success"><i class="fa fa-refresh"></i> Reingresar</button>
                <br>
                <br>
                <button type="button" data-toggle="tooltip" data-placement="top" title="Generar comprobante solicitud" class="btn btn-danger btnDescargaPdfGarantiza" data-garantizaSol="'.$item->id.'"><i class="fa fa-file-pdf-o"></i> Generar comprobante</button>
                </td>';
            }
        }

        return $html;
    }
}