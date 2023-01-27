<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class RegistroCivil{
    public static function PPUDisponible($parametro){
        //$wsdl = 'SpievAPI/WSDL/PPUDisponible_porTipo_PID.wsdl';
        $url = 'http://172.16.10.221/RC_API/PPUDisponible.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }


    public static function creaMoto($parametro){
        //$wsdl = 'SpievAPI/WSDL/CreaSpieMoto_PID.wsdl';
        $url = 'http://172.16.10.221/RC_API/creaMoto.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }

    public static function creaAuto($parametro){
        //$wsdl = 'SpievAPI/WSDL/CreaSpieNew.wsdl';
        $url = 'http://172.16.10.221/RC_API/creaAuto.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }

    public static function consultaEstadoSolicitud($parametro){
        //$wsdl = 'SpievAPI/WSDL/EstadoSolicitud_PID.wsdl';
        $url = 'http://172.16.10.221/RC_API/consultaEstadoSolicitud.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }

    public static function consultaSolicitudRVM($parametro){
        //$wsdl = 'SpievAPI/WSDL/PID_ConsultaSolicitudRVMI.wsdl';
        $url = 'http://172.16.10.221/RC_API/consultaSolicitudRVM.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }

    public static function subirDocumentos($parametro){
        //$wsdl = 'SpievAPI/WSDL/Documentos_PID.wsdl';
        $url = 'http://172.16.10.221/RC_API/cargaDocumentosPID.php';

        $curl = curl_init($url);

        //dd($parametro);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }

    public static function consultaLimitacion($parametro){
        //$wsdl = 'SpievAPI/WSDL/ConsultaLimitaConConsumidor_PID.wsdl';
        $url = 'http://172.16.10.221/RC_API/consultaLimitacion.php';
        $curl = curl_init($url);

        //dd($parametro);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }

    public static function creaCarga($parametro){
        //$wsdl = 'SpievAPI/WSDL/CreaSpieCarga_PID.wsdl';
        $url = 'http://172.16.10.221/RC_API/creaCarga.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }

    public static function LimPrimera($parametro){
        //$wsdl = 'SpievAPI/WSDL/PID_LimiSpie.wsdl';
        $url = 'http://172.16.10.221/RC_API/LimiPrimera.php';
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametro);

        $result = curl_exec($curl);

        return $result;
    }
}