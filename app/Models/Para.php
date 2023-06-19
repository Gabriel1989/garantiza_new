<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Para extends Model
{
    protected $table = 'paras';

    public function comunas(){
        return $this->belongsTo(Comuna::class, 'comuna','Codigo');
    }
}
