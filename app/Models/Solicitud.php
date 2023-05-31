<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    public function paras()
    {
        return $this->hasOne(Para::class);
    }

    public function sucursal(){
        return $this->belongsTo(Sucursal::class,'sucursal_id','id');
    }

    public function documentos(){
        return $this->hasMany(Documento::class,'solicitud_id','id');
    }

    public static function Tramites($id){
        return DB::table('tipo_tramites_solicitudes')
            ->join('tipo_tramites', 'tipo_tramites.id', '=', 'tipo_tramites_solicitudes.tipoTramite_id')
            ->where('tipo_tramites_solicitudes.solicitud_id', '=', $id)
            ->select('tipo_tramites_solicitudes.*', 'tipo_tramites.name')
            ->get();
    }

    public static function DatosSolicitud($id){
        return DB::table('solicitudes')
            ->join('tipo_vehiculos', 'tipo_vehiculos.id', '=', 'solicitudes.tipoVehiculos_id')
            ->where('solicitudes.id', '=', $id)
            ->select('solicitudes.*', 'tipo_vehiculos.name')
            ->get();
    }

    public static function DocumentosSolicitud($id){
        return DB::table('solicitudes')
            ->join('documentos', 'documentos.solicitud_id', '=', 'solicitudes.id')
            ->where('solicitudes.id', '=', $id)
            ->select('documentos.*')
            ->get();
    }

    public static function PorAprobar(){
        return DB::table('solicitudes')
            ->join('users', 'users.id', '=', 'solicitudes.user_id')
            ->join('concesionarias', 'concesionarias.id', '=', 'users.concesionaria_id')
            ->whereIn('solicitudes.estado_id', [1,2,3,4,5,6,7,11,12])
            ->select('solicitudes.*', 'concesionarias.name as concesionaria')
            ->get();
    }

    public static function PPU_Solicitud($id){
        return DB::table('solicitudes')
            ->join('sucursales', 'sucursales.id', '=', 'solicitudes.sucursal_id')
            ->where('solicitudes.id', '=', $id)
            ->select('solicitudes.termino_1', 'solicitudes.termino_2', 'solicitudes.termino_3', 'sucursales.region')
            ->get();
    }

    public static function getTipoVehiculo($id){
        return DB::table('solicitudes')
            ->join('tipo_vehiculos', 'tipo_vehiculos.id', '=', 'solicitudes.tipoVehiculos_id')
            ->where('solicitudes.id', '=', $id)
            ->select('tipo_vehiculos.tipo')
            ->get();
    }

    public static function getCountFromUser($user){
        return DB::table('solicitudes')
            ->where('solicitudes.user_id', '=', $user)
            ->select(DB::raw('COUNT(*) as cantidad'))
            ->get();
    }

    public static function getCountFromUnterminated($user){
        return DB::table('solicitudes')
            ->where('solicitudes.user_id', '=', $user)
            ->whereIn('solicitudes.estado_id', [1,2,3,4,5,6,7,11,12])
            ->select(DB::raw('COUNT(*) as cantidad'))
            ->get();
    }

    public static function sinTerminar($user){
        return DB::table('solicitudes')
            ->join('sucursales', 'sucursales.id', '=', 'solicitudes.sucursal_id')
            ->whereIn('solicitudes.estado_id', [1,2,3,4,5,6,7,11,12])
            ->where('solicitudes.user_id', '=', $user)
            ->select('solicitudes.id', 
                     'solicitudes.created_at', 
                     'sucursales.name as sucursales')
            ->get();
    }
    
    public static function getSolicitudesUser($user){
        return DB::table('solicitudes')
            ->join('sucursales', 'sucursales.id', '=', 'solicitudes.sucursal_id')
            ->where('solicitudes.user_id', '=', $user)
            ->select('solicitudes.id', 
                     'solicitudes.created_at', 
                     'sucursales.name as sucursales')
            ->get();
    }


    public static function getSolicitudRC($id){
        return DB::table('solicitudes_rc')
            ->where('solicitudes_rc.solicitud_id', '=', $id)
            ->select('solicitudes_rc.*')
            ->get();
    }
}


