<?php

namespace App\Http\Controllers\Venta;

use UxWeb\SweetAlert\SweetAlert as Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Negado;

class NegadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('negado.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //dd($request->all());
        $negado = new Negado($request->all());
        $negado->save();
        return view('negado.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //$realizarDevolucionService = new RealizarDevolucionService($request, $venta);
        //return redirect()->route('ventas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(request $request)
    {
        //
        $negados=Negado::get();
        
        return view('negado.index', ['negados' => $negados]);

    }

    public function show2(request $request)
    {
        //
        $negados=Negado::get();
        if ($request->fechaInicioBusqueda) {
            $negados = $negados->where('fecha', '>=', $request->fechaInicioBusqueda);
             
        }

        if ($request->fechaFinBusqueda) {
            $negados = $negados->where('fecha', '<=', $request->fechaFinBusqueda);

        }
       
         
        //$negados = $negados->paginate(10);
        if ($request->fechaInicioBusqueda) {
            $negados->appends(['fechaInicioBusqueda' => $request->fechaInicioBusqueda]);
        }

        if ($request->fechaFinBusqueda) {
           $negados->appends(['fechaFinBusqueda' => $request->fechaFinBusqueda]);
        }
       

        
        return view('negado.index', ['negados' => $negados]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function edit(Negado $negado)
    {
        //
        return view('negado.edit', ['negado' => $negado]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar(Request $request, Negado $negado)
    {
        //
        $negado->update($request->all());
        $negados=Negado::get();
        return view('negado.index', ['negados' => $negados]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
