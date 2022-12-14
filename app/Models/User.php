<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $fillable = [
        'name', 'email', 'password', 'rol_id',
    ];

    public static function allUsers(){
        return DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.rol_id')
            ->select('users.*', 'roles.name as rol')
            ->get();
    }
}
