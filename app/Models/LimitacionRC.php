<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LimitacionRC extends Model
{
    protected $table = 'limitaciones_rc';

    public static function getSolicitud($id){
        return DB::table('limitaciones_rc')
            ->where('limitaciones_rc.solicitud_id', '=', $id)
            ->select('limitaciones_rc.*')
            ->get();
    }

    public static function getSolicitudTransferencia($id){
        return DB::table('limitaciones_rc')
            ->where('limitaciones_rc.transferencia_id', '=', $id)
            ->select('limitaciones_rc.*')
            ->get();
    }
}