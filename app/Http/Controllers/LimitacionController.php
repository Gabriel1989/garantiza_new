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

class LimitacionController extends Controller{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function ingresarLimitacionForm(){


        return view('solicitud.limitacion');
    }

    public function ingresaLimitacion(Request $request, $id){

        $adquiriente = Adquiriente::where('solicitud_id',$id)->first();
        //dd($adquiriente);
        $solicitud_rc = SolicitudRC::getSolicitud($id);
        //$factura = Factura::where('id_solicitud',$id)->first();

        $limitacion = new Limitacion();
        $limitacion->solicitud_id = $id;
        $limitacion->save();

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
                'nroChasis' => $request->get('nro_chasis'),
                'nroMotor' => $request->get('nro_motor'),
                'nroSerie' => is_null($request->get('nro_serie')) ? '' : $request->get('nro_serie'),
                'nroVin' => is_null($request->get('nro_vin')) ? '' : $request->get('nro_vin'),
            ),
            'acreedor'=> array(
                'runRut' => $request->get('runAcreedor'),
                'nombreRazon' => $request->get('nombreRazon')
            ),
            'documento' => [
                'fecha' => date('Ymd'),
                'lugar' => '94', //Nro Comuna Sucursal -> Maipu
                'numero' => $request->get('folio'),
                'tipoDoc' => trim($request->get('tipoDoc')),
                'autorizante' => trim($request->get('autorizante'))
            ],

        ];

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

                $limitacion_rc = new LimitacionRC();
                $limitacion_rc->fecha = $fecha;
                $limitacion_rc->hora = $hora;
                $limitacion_rc->numSol = $nro_limitacion_rc;
                $limitacion_rc->oficina = $oficina;
                $limitacion_rc->ppu = $ppu_rc;
                $limitacion_rc->tipoSol = $tipo_sol;
                $limitacion_rc->solicitud_id = $id;
                $limitacion_rc->save();
                sleep(4);
                $limitacion_rc_2 = LimitacionRC::getSolicitud($id);
                
            }
            else{
                return view('general.errorRC', ['glosa' => $salida['glosa']]);
            }
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
            if($index == "codigoresp"){
                $codigoresp = $detalle;
            }
            if($codigoresp != null){
                echo "<label>".$index.': </label> '.$detalle.'<br>';
            }
        }
        die;

    }



}




