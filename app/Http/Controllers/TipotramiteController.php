<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTramiteRequest;
use App\Models\Tipo_Tramite;
use Illuminate\Http\Request;

class TipotramiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipos = Tipo_Tramite::all();
        return view('tipo_tramite.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipo_tramite.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoTramiteRequest $request)
    {
        Tipo_Tramite::create($request->all());
        return redirect()->route('tipo_tramite.index')->with('mensaje', 'Tipo de Trámite agregado correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipo = Tipo_Tramite::findOrFail($id);
        return view('tipo_tramite.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoTramiteRequest $request, $id)
    {
        Tipo_Tramite::findOrFail($id)->update($request->all());
        return redirect()->route('tipo_tramite.index')->with('mensaje', 'Tipo de Trámite actualizado correctamente');
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
            if (Tipo_Tramite::destroy($id)) {
                return response()->json(['mensaje' => 'ok']);
            } else {
                return response()->json(['mensaje' => 'ng']);
            }
        } else {
            abort(404);
        }
    }
}
