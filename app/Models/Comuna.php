<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comuna extends Model
{
    protected $table = 'comunas';
    protected $fillable = ['Codigo', 'Nombre'];
    protected $guarded = ['id'];

    public static function allOrder(){
        return DB::table('comunas')
            ->orderBy('Nombre')
            ->select('comunas.*')
            ->get();
    }
}

