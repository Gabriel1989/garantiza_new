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
            ->where('transferencias.user_id', '=', $user)
            ->select('transferencias.id', 
                     'transferencias.created_at', 
                     'notarias.name as notarias')
            ->get();
    }

    public static function sinTerminar($user){
        return DB::table('transferencias')
            ->join('notarias', 'notarias.id', '=', 'transferencias.notaria_id')
            ->whereIn('transferencias.estado_id', [1,2,3,4,5,6,7,11,12])
            ->where('transferencias.user_id', '=', $user)
            ->select('transferencias.id', 
                     'transferencias.created_at', 
                     'notarias.name as notarias')
            ->get();
    }
}
