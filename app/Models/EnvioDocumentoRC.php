<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class EnvioDocumentoRC extends Model
{
    protected $table = 'documentos_rc';

    public static function getSolicitud($id){
        return DB::table('documentos_rc')
            ->where('documentos_rc.solicitud_id', '=', $id)
            ->select('documentos_rc.*')
            ->get();
    }
}