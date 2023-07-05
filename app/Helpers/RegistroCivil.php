<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Models\ConsumeRC;

class RegistroCivil{
    /************CONEXIÓN SPIEV ***************/
    public static function PPUDisponible($parametro){
        //$wsdl = 'SpievAPI/WSDL/PPUDisponible_porTipo_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/PPUDisponible.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('PPUDisponible_porTipo_PID','SPIEV');

        return $result;
    }


    public static function creaMoto($parametro){
        //$wsdl = 'SpievAPI/WSDL/CreaSpieMoto_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/creaMoto.php';
        //http://181.212.92.138/RC_API/LimiPrimera.php
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('CreaSpieMoto_PID','SPIEV');

        return $result;
    }

    public static function creaAuto($parametro){
        //$wsdl = 'SpievAPI/WSDL/CreaSpieNew.wsdl';
        $url = 'http://181.212.92.141/RC_API/creaAuto.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('CreaSpieNew','SPIEV');

        return $result;
    }

    public static function consultaEstadoSolicitud($parametro){
        //$wsdl = 'SpievAPI/WSDL/EstadoSolicitud_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/consultaEstadoSolicitud.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('EstadoSolicitud_PID','SPIEV');

        return $result;
    }

    public static function consultaSolicitudRVM($parametro){
        //$wsdl = 'SpievAPI/WSDL/PID_ConsultaSolicitudRVMI.wsdl';
        $url = 'http://181.212.92.141/RC_API/consultaSolicitudRVM.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('PID_ConsultaSolicitudRVMI','SPIEV');

        return $result;
    }

    public static function subirDocumentos($parametro){
        //$wsdl = 'SpievAPI/WSDL/Documentos_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/cargaDocumentosPID.php';

        $curl = curl_init($url);

        //dd($parametro);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('Documentos_PID','SPIEV');

        return $result;
    }

    public static function consultaLimitacion($parametro){
        //$wsdl = 'SpievAPI/WSDL/ConsultaLimitaConConsumidor_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/consultaLimitacion.php';
        $curl = curl_init($url);

        //dd($parametro);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('ConsultaLimitaConConsumidor_PID','SPIEV');

        return $result;
    }

    public static function creaCarga($parametro){
        //$wsdl = 'SpievAPI/WSDL/CreaSpieCarga_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/creaCarga.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('CreaSpieCarga_PID','SPIEV');

        return $result;
    }

    public static function LimPrimera($parametro){
        //$wsdl = 'SpievAPI/WSDL/PID_LimiSpie.wsdl';
        $url = 'http://181.212.92.141/RC_API/LimiPrimera.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('PID_LimiSpie','SPIEV');

        return $result;
    }
    /*************** CONEXIÓN STEV DESDE ESTE PUNTO ***********/
    public static function consultaDatosVehiculo($parametro){
        //$wsdl = 'StevAPI/WSDL/PI_A_Vigente_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/STEV/CertAVigente.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('PI_A_Vigente_PID','STEV');

        return $result;
    }

    public static function creaStev($parametro){
        //$wsdl = 'StevAPI/WSDL/CreaStev_new.wsdl';
        $url = 'http://181.212.92.141/RC_API/STEV/CreaStev.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('CreaStev_new','STEV');

        return $result;
    }

    public static function limTransf($parametro){
        //$wsdl = 'StevAPI/WSDL/LimTransf.wsdl';
        $url = 'http://181.212.92.141/RC_API/STEV/LimTransf.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('LimTransf','STEV');

        return $result;
    }

    public static function consultaTransferencia($parametro){
        //$wsdl = 'StevAPI/WSDL/ConsultaTransferenciaConConsumidor_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/STEV/ConsultaTransf.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('ConsultaTransferenciaConConsumidor_PID','STEV');

        return $result;
    }

    public static function subirDocumentosStev($parametro){
        //$wsdl = 'StevAPI/WSDL/Documentos_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/STEV/CargaDocumentosPID.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('Documentos_PID','STEV');

        return $result;
    }

    public static function consultaSolicitudStev($parametro){
        //$wsdl = 'StevAPI/WSDL/EstadoSolicitud_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/STEV/consultaEstadoSolicitud.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('EstadoSolicitud_PID','STEV');

        return $result;
    }

    public static function consultaLimitacionStev($parametro){
        //$wsdl = 'SpievAPI/WSDL/ConsultaLimitaConConsumidor_PID.wsdl';
        $url = 'http://181.212.92.141/RC_API/STEV/consultaLimitacion.php';
        $curl = curl_init($url);

        //dd($parametro);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        self::creaConsumeRC('ConsultaLimitaConConsumidor_PID','STEV');

        return $result;
    }

    private static function creaConsumeRC($nombre_servicio,$convenio){
        $consumeRC = new ConsumeRC();
        $consumeRC->nombre_ws = $nombre_servicio;
        $consumeRC->tipo_convenio = $convenio;
        $consumeRC->save();
    }
}