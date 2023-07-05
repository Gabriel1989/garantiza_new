<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Polyfill\Intl\Idn\Info;
use Illuminate\Support\Facades\DB;

class Transferencia extends Model
{
    protected $table = 'transferencias';

    public function vehiculo(){
        return $this->hasOne(InfoVehiculoTransferencia::class, 'transferencia_id','id');
    }

    public function propietario(){
        return $this->hasOne(Propietario::class, 'transferencia_id','id');
    }

    public function notaria(){
        return $this->belongsTo(Notaria::class,'notaria_id','id');
    }

    public function documentos(){
        return $this->hasMany(Documento::class,'transferencia_id','id');
    }

    public function comprador(){
        return $this->hasOne(Comprador::class,'transferencia_id','id');
    }

    public function vendedor(){
        return $this->hasOne(Vendedor::class,'transferencia_id','id');
    }

    public function estipulante(){
        return $this->hasOne(Estipulante::class,'transferencia_id','id');
    }

    public function data_transferencia(){
        return $this->hasOne(TransferenciaData::class, 'transferencia_id','id');
    }

    public function limitacion(){
        return $this->hasOne(Limitacion::class,'transferencia_id','id');
    }

    public function limitacion_rc(){
        return $this->hasOne(LimitacionRC::class,'transferencia_id','id');
    }

    public function transferencia_rc(){
        return $this->hasOne(TransferenciaRC::class,'transferencia_id','id');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public static function PorAprobar(){
        return DB::table('transferencias')
        ->join('users', 'users.id', '=', 'transferencias.user_id')
        ->join('notarias', 'notarias.id', '=', 'transferencias.notaria_id')
        ->leftjoin('transferencias_rc', 'transferencias_rc.transferencia_id', '=', 'transferencias.id')
        ->leftjoin('reingresos','reingresos.transferencia_id','=','transferencias.id')
        ->leftjoin('documentos_rc','documentos_rc.transferencia_id','=','transferencias.id')
        ->leftjoin('limitaciones','limitaciones.transferencia_id','=','transferencias.id')
        ->leftjoin('limitaciones_rc', 'limitaciones_rc.transferencia_id','=','transferencias.id')
        ->whereIn('transferencias.estado_id', [1,2,3,4,5,6,7,11,12])
        ->select('transferencias.*', 'notarias.name as notarias','transferencias_rc.numeroSol','reingresos.nroSolicitud',
        'documentos_rc.numeroSol as numeroSolDocrc','limitaciones.id as id_limitacion', 'limitaciones_rc.id as id_limitacion_rc')
        ->get();
    }

    public static function getTransferenciaRC($id){
        return DB::table('transferencias_rc')
            ->where('transferencias_rc.transferencia_id', '=', $id)
            ->select('transferencias_rc.*')
            ->get();
    }

    public static function getCountFromUser($user){
        return DB::table('transferencias')
            ->where('transferencias.user_id', '=', $user)
            ->select(DB::raw('COUNT(*) as cantidad'))
            ->get();
    }

    public static function getCountFromUnterminated($user){
        return DB::table('transferencias')
            ->where('transferencias.user_id', '=', $user)
            ->whereIn('transferencias.estado_id', [1,2,3,4,5,6,7,11,12])
            ->select(DB::raw('COUNT(*) as cantidad'))
            ->get();
    }

    public static function getSolicitudesUser($user){
        return DB::table('transferencias')
            ->join('notarias', 'notarias.id', '=', 'transferencias.notaria_id')
            ->leftjoin('transferencias_rc', 'transferencias_rc.transferencia_id', '=', 'transferencias.id')
            ->leftjoin('reingresos','reingresos.transferencia_id','=','transferencias.id')
            ->leftjoin('documentos_rc','documentos_rc.transferencia_id','=','transferencias.id')
            ->leftjoin('limitaciones','limitaciones.transferencia_id','=','transferencias.id')
            ->leftjoin('limitaciones_rc', 'limitaciones_rc.transferencia_id','=','transferencias.id')
            ->where('transferencias.user_id', '=', $user)
            ->select('transferencias.id', 
                     'transferencias.created_at', 
                     'transferencias.estado_id',
                     'transferencias.pagada',
                     'transferencias.monto_inscripcion', 
                     'notarias.name as notarias',
                     'transferencias_rc.numeroSol',
                     'reingresos.nroSolicitud',
                     'documentos_rc.numeroSol as numeroSolDocrc',
                     'limitaciones.id as id_limitacion', 
                     'limitaciones_rc.id as id_limitacion_rc')
            ->get();
    }

    public static function sinTerminar($user){
        return DB::table('transferencias')
            ->join('notarias', 'notarias.id', '=', 'transferencias.notaria_id')
            ->leftjoin('transferencias_rc', 'transferencias_rc.transferencia_id', '=', 'transferencias.id')
            ->leftjoin('reingresos','reingresos.transferencia_id','=','transferencias.id')
            ->leftjoin('documentos_rc','documentos_rc.transferencia_id','=','transferencias.id')
            ->leftjoin('limitaciones','limitaciones.transferencia_id','=','transferencias.id')
            ->leftjoin('limitaciones_rc', 'limitaciones_rc.transferencia_id','=','transferencias.id')
            ->whereIn('transferencias.estado_id', [1,2,3,4,5,6,7,11,12])
            ->where('transferencias.user_id', '=', $user)
            ->select('transferencias.id', 
                     'transferencias.created_at',
                     'transferencias.estado_id',
                     'transferencias.pagada',
                     'transferencias.monto_inscripcion', 
                     'notarias.name as notarias',
                     'transferencias_rc.numeroSol',
                     'reingresos.nroSolicitud',
                     'documentos_rc.numeroSol as numeroSolDocrc',
                     'limitaciones.id as id_limitacion', 
                     'limitaciones_rc.id as id_limitacion_rc')
            ->get();
    }

    public static function DocumentosSolicitud($id){
        return DB::table('transferencias')
            ->join('documentos', 'documentos.transferencia_id', '=', 'transferencias.id')
            ->where('transferencias.id', '=', $id)
            ->select('documentos.*')
            ->get();
    }
}
