<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notaria;

class NotariaController extends Controller
{
    public function index()
    {
        $notarias = Notaria::all();
        return view('notaria.index', compact('notarias'));
    }

    public function edit($id)
    {
        $notaria = Notaria::find($id);
        return view('notaria.edit', compact('notaria'));
    }

    public function update(Request $request, $id)
    {

        $notaria = Notaria::find($id);
        
        $notaria->name = $request->get('name');
        $notaria->codigo_notaria_rc = $request->get('codigo_notaria_rc');

        $notaria->save();

        return redirect()->route('notaria.index')->with('mensaje', 'Notaria actualizada correctamente');
    }

    public function store(Request $request)
    {

        $notaria = new Notaria();
        
        $notaria->name = $request->get('name');
        $notaria->codigo_notaria_rc = $request->get('codigo_notaria_rc');

        $notaria->save();

        return redirect()->route('notaria.index')->with('mensaje', 'Notaria agregada correctamente');
    }


    public function destroy(Request $request, $id)
    {
        
        if ($request->ajax()) {
            if (Notaria::destroy($id)) {
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
        return view('notaria.create');
    }
}
