<?php

namespace App\Http\Controllers;
use App\piezasMa;
use App\Producto;
use Illuminate\Http\Request;

class PiezasMaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $pz = piezasMa::get();
       // dd($retex);
        return view('PiezasMayorValor.index', ['pz'=>$pz]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         return view('PiezasMayorValor.create');
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
           $piezas = piezasMa::where('SKU', $aux->sku)->get();
            // dd($aux);
            if (count($piezas) == 0 ) {
           $pzNew = new piezasMa;
           $pzNew->producto_id = $aux->id;
           $pzNew->SKU = $aux->sku;
           $pzNew->precio = $aux->precio_publico_iva;
           $pzNew->save();

            }
            // dd(count($piezas));
        }else{
            $aux  = Producto::where('sku',$request->nombre)->get()->last();
         // dd($request->nombre);
             $piezas= piezasMa::where('SKU', $aux->sku)->get();
               if (count($piezas) == 0 ) {
           $pzNew = new piezasMa;
           $pzNew->producto_id = $aux->id;
           $pzNew->SKU = $aux->sku;
            $pzNew->precio = $aux->precio_publico_iva;
           $pzNew->save();

         }
             // dd($piezas);
        }

        return redirect('piezasMa');

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(piezasMa $piezasMa)
    {
        //
        // dd($piezasMa);
        piezasMa::where('id',$piezasMa->id)->delete();
        // $piezasMa->delete();
        return  redirect('piezasMa');
    }
}
