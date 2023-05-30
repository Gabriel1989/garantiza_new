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

    public function acreedor(){
        return $this->belongsTo(Acreedor::class, 'acreedor_id','id');
    }

    public function tipodocumento(){
        return $this->belongsTo(Tipo_Documento::class, 'tipo_documento_id','id');
    }
}