<?php

namespace App\Http\Controllers;

use App\Models\Acreedor;
use Illuminate\Http\Request;


class AcreedorController extends Controller
{


    public function index()
    {
        $acreedores = Acreedor::all();
        return view('acreedor.index', compact('acreedores'));
    }

    public function edit($id)
    {
        $acreedor = Acreedor::find($id);
        return view('acreedor.edit', compact('acreedor'));
    }

    public function update(Request $request, $id)
    {
        $dv = substr($request->get('rut'), -1);
        $rut = str_replace('.', '', substr($request->get('rut'), 0, -2));

        $acreedor = Acreedor::find($id);
        
        $acreedor->nombre = $request->get('name');
        $acreedor->rut = $rut;

        $acreedor->save();

        return redirect()->route('acreedor.index')->with('mensaje', 'Acreedor actualizado correctamente');
    }

    public function store(Request $request)
    {
        $dv = substr($request->get('rut'), -1);
        $rut = str_replace('.', '', substr($request->get('rut'), 0, -2));

        $acreedor = new Acreedor();
        
        $acreedor->nombre = $request->get('name');
        $acreedor->rut = $rut;

        $acreedor->save();

        return redirect()->route('acreedor.index')->with('mensaje', 'Acreedor agregado correctamente');
    }


    public function destroy(Request $request, $id)
    {
        
        if ($request->ajax()) {
            if (Acreedor::destroy($id)) {
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
        return view('acreedor.create');
    }

}





