<?php

namespace App\Http\Controllers;

use App\piezasMe;
use App\Producto;
use Illuminate\Http\Request;

class PiezasMeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       $pz = piezasMe::get();
       // dd($retex);
        return view('PiezasMenorValor.index', ['pz'=>$pz]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
          return view('PiezasMenorValor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
         if (strlen($request->nombre) == 12) {
            $aux  = Producto::where('upc',$request->nombre)->get()->last();
           $piezas = piezasMe::where('SKU', $aux->sku)->get();
            // dd($aux);
            if (count($piezas) == 0 ) {
           $pzNew = new piezasMe;
           $pzNew->producto_id = $aux->id;
           $pzNew->SKU = $aux->sku;
           $pzNew->precio = $aux->precio_publico_iva;
           $pzNew->save();

            }
            // dd(count($piezas));
        }else{
            $aux  = Producto::where('sku',$request->nombre)->get()->last();
         // dd($request->nombre);
             $piezas= piezasMe::where('SKU', $aux->sku)->get();
               if (count($piezas) == 0 ) {
           $pzNew = new piezasMe;
           $pzNew->producto_id = $aux->id;
           $pzNew->SKU = $aux->sku;
           $pzNew->save();

         }
             // dd($piezas);
        }

        return redirect('piezasMe');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\piezasMe  $piezasMe
     * @return \Illuminate\Http\Response
     */
    public function show(piezasMe $piezasMe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\piezasMe  $piezasMe
     * @return \Illuminate\Http\Response
     */
    public function edit(piezasMe $piezasMe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\piezasMe  $piezasMe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, piezasMe $piezasMe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\piezasMe  $piezasMe
     * @return \Illuminate\Http\Response
     */
    public function destroy(piezasMe $piezasMe)
    {
        //
         $piezasMe->delete();
        return  redirect('piezasMe');
    }
}
