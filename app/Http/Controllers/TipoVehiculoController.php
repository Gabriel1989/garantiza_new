<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoVehiculoRequest;
use App\Models\Tipo_Vehiculo;
use Illuminate\Http\Request;

class TipoVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = Tipo_Vehiculo::all();
        return view('tipo_vehiculo.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipo_vehiculo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoVehiculoRequest $request)
    {
        Tipo_Vehiculo::create($request->all());
        return redirect()->route('tipo_vehiculo.index')->with('mensaje', 'Tipo de Vehículo agregado correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo = Tipo_Vehiculo::findOrFail($id);
        return view('tipo_vehiculo.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoVehiculoRequest $request, $id)
    {
        Tipo_Vehiculo::findOrFail($id)->update($request->all());
        return redirect()->route('tipo_vehiculo.index')->with('mensaje', 'Tipo de Vehículo actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            if (Tipo_Vehiculo::destroy($id)) {
                return response()->json(['mensaje' => 'ok']);
            } else {
                return response()->json(['mensaje' => 'ng']);
            }
        } else {
            abort(404);
        }
    }
}
