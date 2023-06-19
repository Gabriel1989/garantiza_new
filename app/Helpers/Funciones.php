<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Funciones{



    /*  FlujoRevision
       ----------------
        $deDonde: Quiere decir en qué parte del fujo se encuentra. Los valores posibles son:
                1. Revisión de Cédula del Cliente
                2. Revisión de Rol
                3. Revisión de Cédula de Compra Para
                4. Indicación de qué terminación de PPU se utilizará
                5. Mover datos a entrada de Registro Civil

        retorna: La dirección route a dónde debe dirigirse
    */
    public static function FlujoRevision($deDonde, $id){

        // Revisa si cliente es empresa
        if($deDonde==1){
            $salida = DB::table('solicitudes')
                ->where('solicitudes.id', '=', $id)
                ->select('solicitudes.empresa')
                ->get();
            
            if($salida[0]->empresa==0){
                $deDonde = 2;
            }else{
                return'solicitud.revision.rol';
            }
        }

        // Revisa si cliente tiene Compra Para
        if($deDonde==2){
            $salida = DB::table('paras')
                ->where('paras.id', '=', $id)
                ->select(DB::raw('count(*) as cantidad'))
                ->get();
            
            if($salida[0]->cantidad==0){
                $deDonde = 3;
            }else{
                return'solicitud.revision.paras';
            }
        }

        // Revisa si cliente tiene Compra Para
        if($deDonde==3){
            $salida = DB::table('tipo_tramites_solicitudes')
                ->where('tipo_tramites_solicitudes.solicitud_id', '=', $id)
                ->where('tipo_tramites_solicitudes.tipoTramite_id', '=', 1)
                ->select(DB::raw('count(*) as cantidad'))
                ->get();
            
            if($salida[0]->cantidad==0){
                $deDonde = 4;
            }else{
                return'solicitud.revision.PPU';
            }
        }

        if($deDonde==4){
            return'solicitud.revision.entradaRC';
        }

    }

    public static function calcularDigitoVerificador($rut)
    {
        $rut = str_replace('.', '', $rut); // Eliminar puntos
        $rut = strrev($rut); // Invertir el RUT
    
        $suma = 0;
        $multiplo = 2;
    
        for ($i = 0; $i < strlen($rut); $i++) {
            $suma += $rut[$i] * $multiplo;
            $multiplo = ($multiplo < 7) ? $multiplo + 1 : 2;
        }
    
        $resto = $suma % 11;
        $dv = 11 - $resto;
    
        // Casos especiales
        if ($dv == 11) {
            return 0;
        } elseif ($dv == 10) {
            return 'K';
        } else {
            return $dv;
        }
    }
}