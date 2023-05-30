<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Acreedor extends Model{
    protected $table = 'acreedores';


    public function limitaciones(){
        return $this->hasMany(Limitacion::class,"acreedor_id","id");
    }
    
}






