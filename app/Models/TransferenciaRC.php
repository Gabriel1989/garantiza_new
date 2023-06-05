<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransferenciaRC extends Model{

    protected $table = 'transferencias_rc';

    public static function getSolicitud($id){
        return DB::table('transferencias_rc')
            ->where('transferencias_rc.transferencia_id', '=', $id)
            ->select('transferencias_rc.*')
            ->get();
    }
}