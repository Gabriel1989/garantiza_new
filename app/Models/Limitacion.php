<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Limitacion extends Model
{
    protected $table = 'limitaciones';

    public static function getSolicitud($id){
        return DB::table('limitaciones')
            ->where('limitaciones.solicitud_id', '=', $id)
            ->select('limitaciones.*')
            ->get();
    }

    public static function getLimitacionRC($id){
        return DB::table('limitaciones_rc')
            ->where('limitaciones_rc.solicitud_id', '=', $id)
            ->select('limitaciones_rc.*')
            ->get();
    }
}