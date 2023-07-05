<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller{

    public function index(){
        return view('estadisticas.index');
    }

    public function getSolicitudesRCPorMes(){
        $data = DB::table('consume_rc')
        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as Month, tipo_convenio as Convenio, count(*) as Count"))
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), 'tipo_convenio')
        ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), 'asc')
        ->get();

        return json_encode($data);
    }

    public function getSolicitudesRCPorMesNombreServicio(){
        $data = DB::table('consume_rc')
        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as Month, nombre_ws as tipo_servicio, count(*) as Count"))
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), 'nombre_ws')
        ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), 'asc')
        ->get();

        return json_encode($data);
    }

    //Funcion para obtener el total de solicitudes por tipo de convenio
    public function getSolicitudesRCPorConvenio(){
        $data = DB::table('consume_rc')
        ->select(DB::raw("tipo_convenio as Convenio, count(*) as Count"))
        ->groupBy('tipo_convenio')
        ->orderBy("tipo_convenio", 'asc')
        ->get();

        return json_encode($data);

    }

    //Funcion para obtener el total de solicitudes por tipo de servicio consumido del RC
    public function getSolicitudesRCPorTipoServicio(){
        $data = DB::table('consume_rc')
        ->select(DB::raw("nombre_ws as tipo_servicio, count(*) as Count"))
        ->groupBy('nombre_ws')
        ->orderBy("nombre_ws", 'asc')
        ->get();

        return json_encode($data);

    }

    //Obtiene cantidad total de solicitudes por mes
    public function getCantidadSolicitudesMes(){
        $data = DB::table('consume_rc')
        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as Month, count(*) as Count"))
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
        ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), 'asc')
        ->get();

        return json_encode($data);
    }

    //Obtiene cantidad total de solicitudes enviadas a RC
    public function getCantidadSolicitudes(){
        $data = DB::table('consume_rc')
        ->select(DB::raw("count(*) as Count"))
        ->get();

        return json_encode($data);
    }

}