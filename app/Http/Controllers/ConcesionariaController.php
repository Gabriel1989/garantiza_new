<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateConcesionaria;
use App\Models\Concesionaria;
use Illuminate\Http\Request;

class ConcesionariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $concesionarias = Concesionaria::all();
        return view('concesionaria.index', compact('concesionarias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('concesionaria.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidateConcesionaria $request)
    {
        $dv = substr($request->get('rut'), -1);
        $rut = str_replace('.', '', substr($request->get('rut'), 0, -2));

        $concesionaria = new Concesionaria;
        
        $concesionaria->name = $request->get('name');
        $concesionaria->rut = $rut;
        $concesionaria->dv = $dv;

        $concesionaria->save();

        return redirect()->route('concesionaria.index')->with('mensaje', 'Concesionaria agregada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $concesionaria = Concesionaria::find($id);
        return view('concesionaria.edit', compact('concesionaria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ValidateConcesionaria $request, $id)
    {
        $dv = substr($request->get('rut'), -1);
        $rut = str_replace('.', '', substr($request->get('rut'), 0, -2));

        $concesionaria = Concesionaria::find($id);
        
        $concesionaria->name = $request->get('name');
        $concesionaria->rut = $rut;
        $concesionaria->dv = $dv;

        $concesionaria->save();

        return redirect()->route('concesionaria.index')->with('mensaje', 'Concesionaria actualizada correctamente');
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
            if (Concesionaria::destroy($id)) {
                return response()->json(['mensaje' => 'ok']);
            } else {
                return response()->json(['mensaje' => 'ng']);
            }
        } else {
            abort(404);
        }

    }
}
