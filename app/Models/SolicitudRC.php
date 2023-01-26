<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SolicitudRC extends Model
{
    protected $table = 'solicitudes_rc';

    public static function getSolicitud($id){
        return DB::table('solicitudes_rc')
            ->where('solicitudes_rc.solicitud_id', '=', $id)
            ->select('solicitudes_rc.*')
            ->get();
    }
}