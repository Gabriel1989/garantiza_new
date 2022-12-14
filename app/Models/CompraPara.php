<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CompraPara extends Model
{
    protected $table = 'paras';

    protected $fillable = ['rut', 
                           'nombre', 
                           'aPaterno', 
                           'aMaterno', 
                           'tipo', 
                           'calle', 
                           'numero', 
                           'rDomicilio', 
                           'comuna',
                           'telefono',
                           'email',
                           'solicitud_id',
                        ];

    protected $guarded = ['id'];

    public static function getSolicitud($id){
        return DB::table('paras')
            ->where('paras.solicitud_id', '=', $id)
            ->select('paras.*')
            ->get();
    }
}
