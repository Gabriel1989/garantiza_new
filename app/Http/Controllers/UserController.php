<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Concesionaria;
use App\Models\Rol;
use App\Models\User;
use App\Models\Notaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::allUsers();
        return view('usuario.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        $concesionarias = Concesionaria::all();
        $notarias = Notaria::all();
        return view('usuario.create', compact('roles', 'concesionarias','notarias'));
    }

    public function store(UserRequest $request)
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->rol_id = $request->get('rol_id');
        if($request->get('rol_id') == 7){
            $user->notaria_id = $request->get('notaria_id');
        }
        else if($request->get('rol_id') >=4 && $request->get('rol_id') < 7){
            $user->concesionaria_id = $request->get('concesionaria_id');
        }
        $user->activo = 1;
        $user->save();
        return redirect()->route('usuario.index')->with('mensaje', 'Usuario agregado correctamente');
    }

    public function password($id)
    {
        $usuario = User::findOrFail($id);
        return view('usuario.password', compact('usuario'));
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Rol::all();
        $concesionarias = Concesionaria::all();
        $notarias = Notaria::all();
        return view('usuario.edit', compact('usuario', 'roles', 'concesionarias','notarias'));
    }

    public function update(Request $request, $id)
    {
        $errors = new MessageBag();
        $tieneError = false;

        $usuario = User::findOrFail($id);
        if(is_null($request->get('name'))){
            $errors->add('Garantiza', 'Debe ingresar el nombre del usuario.');
            $tieneError = true;
        }

        if(is_null($request->get('rol_id'))){
            $errors->add('Garantiza', 'Debe seleccionar un Rol.');
            $tieneError = true;
        }

        
        if(is_null($request->get('concesionaria_id')) && is_null($request->get('notaria_id'))){
            $errors->add('Garantiza', 'Debe seleccionar una concesionaria o una notaria');
            $tieneError = true;
        }

        if($tieneError){
            return view('usuario.password', compact('usuario'))->withErrors($errors);
        }

        $usuario->name = $request->get('name');
        $usuario->rol_id = $request->get('rol_id');
        if($request->get('rol_id') == 7){
            $usuario->notaria_id = $request->get('notaria_id');
        }
        else if($request->get('rol_id') >=4 && $request->get('rol_id') < 7){
            $usuario->concesionaria_id = $request->get('concesionaria_id');
        }
        $usuario->save();
        return redirect()->route('usuario.index')->with('mensaje', 'Usuario modificado correctamente');

    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            if (User::destroy($id)) {
                return response()->json(['mensaje' => 'ok']);
            } else {
                return response()->json(['mensaje' => 'ng']);
            }
        } else {
            abort(404);
        }
    }
}       