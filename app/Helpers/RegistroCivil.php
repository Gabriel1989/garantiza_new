<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class RegistroCivil{
    public static function PPUDisponible($parametro){
        $url = 'http://localhost/RC_API/PPUDisponible.php';
        $response = HTTP::post($url, $parametro);
        return $response;
    }


    public static function creaMoto($parametro){
        $url = 'http://localhost/RC_API/creaMoto.php';
        $response = HTTP::post($url, $parametro);
        return $response;
    }
}