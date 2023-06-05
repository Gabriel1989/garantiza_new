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

    public static function getTransferenciaRC($id){
        return DB::table('transferencias_rc')
            ->where('transferencias_rc.transferencia_id', '=', $id)
            ->select('transferencias_rc.*')
            ->get();
    }
}
