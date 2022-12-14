<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Concesionaria;
use App\Models\Rol;
use App\Models\User;
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
        return view('usuario.create', compact('roles', 'concesionarias'));
    }

    public function store(UserRequest $request)
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->rol_id = $request->get('rol_id');
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
        return view('usuario.edit', compact('usuario', 'roles', 'concesionarias'));
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

        if(is_null($request->get('concesionaria_id'))){
            $errors->add('Garantiza', 'Debe seleccionar una concesionaria.');
            $tieneError = true;
        }

        if($tieneError){
            return view('usuario.password', compact('usuario'))->withErrors($errors);
        }

        $usuario->name = $request->get('name');
        $usuario->rol_id = $request->get('rol_id');
        $usuario->concesionaria_id = $request->get('concesionaria_id');
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


/*
        $errors = new MessageBag();
        $usuario = User::findOrFail($id);
        
        $tieneError = false;

        // Validación para cambio de password
        if($request->has('new_pass_1')){
            if(is_null($request->get('old_pass'))){
                $errors->add('Garantiza', 'El ingreso de la password actual es obligatorio.');
                $tieneError = true;
            }

            if(is_null($request->get('new_pass_1'))){
                $errors->add('Garantiza', 'El ingreso de la nueva password es obligatorio.');
                $tieneError = true;
            }

            if(is_null($request->get('new_pass_2'))){
                $errors->add('Garantiza', 'El reingreso de la nueva password es obligatorio.');
                $tieneError = true;
            }

            if($request->get('new_pass_1')!=$request->get('new_pass_2')){
                $errors->add('Garantiza', 'La nueva password no fue reingresada correctamente.');
                $tieneError = true;
            }

            if(Hash::make($request->get('old_pass'))!=$usuario->password){
                $errors->add('Garantiza', 'La password actual es incorrecta.');
                $tieneError = true;
            }

            if($tieneError){
                return view('usuario.password', compact('usuario'))->withErrors($errors);
            }
        }

        return 'Listo';
*/        