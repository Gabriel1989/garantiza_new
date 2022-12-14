<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalRequest;
use App\Models\Concesionaria;
use App\Models\Sucursal;
use App\Models\Comuna;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sucursales = Sucursal::all();
        return view('Sucursal.index', compact('sucursales'));
    }

    public function create()
    {
        $concesionarias = Concesionaria::all();
        $comunas = Comuna::allOrder();
        return view('Sucursal.create', compact('concesionarias', 'comunas'));
    }

    public function store(SucursalRequest $request)
    {
        Sucursal::create($request->all());
        return redirect()->route('sucursal.index')->with('mensaje', 'Sucursal agregada correctamente');
    }

    public function edit($id)
    {
        $concesionarias = Concesionaria::all();
        $comunas = Comuna::allOrder();
        $sucursal = Sucursal::findOrFail($id);
        return view('Sucursal.edit', compact('sucursal'), compact('concesionarias', 'comunas'));
    }

    public function update(Request $request, $id)
    {
        Sucursal::findOrFail($id)->update($request->all());
        return redirect()->route('sucursal.index')->with('mensaje', 'Sucursal actualizada correctamente');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            if (Sucursal::destroy($id)) {
                return response()->json(['mensaje' => 'ok']);
            } else {
                return response()->json(['mensaje' => 'ng']);
            }
        } else {
            abort(404);
        }
    }


}
