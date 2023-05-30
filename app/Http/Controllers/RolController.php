<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::all();
        return view('rol.index', compact('roles'));
    }

    public function edit($id)
    {
        $rol = Rol::find($id);
        return view('rol.edit', compact('rol'));
    }

    public function update(Request $request, $id)
    {

        $rol = Rol::find($id);
        
        $rol->name = $request->get('name');

        $rol->save();

        return redirect()->route('rol.index')->with('mensaje', 'Rol actualizado correctamente');
    }

    public function store(Request $request)
    {

        $rol = new Rol();
        
        $rol->name = $request->get('name');
        

        $rol->save();

        return redirect()->route('rol.index')->with('mensaje', 'Rol agregado correctamente');
    }


    public function destroy(Request $request, $id)
    {
        
        if ($request->ajax()) {
            if (Rol::destroy($id)) {
                return response()->json(['mensaje' => 'ok']);
            } else {
                return response()->json(['mensaje' => 'ng']);
            }
        } else {
            abort(404);
        }

    }

    public function create()
    {
        return view('rol.create');
    }
}
