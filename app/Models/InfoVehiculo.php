<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InfoVehiculo extends Model
{
    protected $table = 'info_vehiculo';

    protected $fillable = ['marca'];
    protected $guarded = ['id'];

    public static function getSolicitud($id){
        return DB::table('info_vehiculo')
            //->where('info_vehiculo.solicitud_id', '=', $id)
            ->where('info_vehiculo.id', '=', $id)
            ->select('info_vehiculo.*')
            ->get();
    }
}
