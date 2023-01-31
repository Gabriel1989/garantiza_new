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
}