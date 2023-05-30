<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rechazo;

class RechazoController extends Controller
{
    public function index()
    {
        $rechazos = Rechazo::all();
        return view('rechazo.index', compact('rechazos'));
    }

    public function edit($id)
    {
        $rechazo = Rechazo::find($id);
        return view('rechazo.edit', compact('rechazo'));
    }

    public function update(Request $request, $id)
    {

        $rechazo = Rechazo::find($id);
        
        $rechazo->motivo = $request->get('name');

        $rechazo->save();

        return redirect()->route('rechazo.index')->with('mensaje', 'Rechazo actualizado correctamente');
    }

    public function store(Request $request)
    {

        $rechazo = new Rechazo();
        
        $rechazo->motivo = $request->get('name');
        

        $rechazo->save();

        return redirect()->route('rechazo.index')->with('mensaje', 'Rechazo agregado correctamente');
    }


    public function destroy(Request $request, $id)
    {
        
        if ($request->ajax()) {
            if (Rechazo::destroy($id)) {
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
        return view('rechazo.create');
    }
}
