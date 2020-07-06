<?php

namespace App\Http\Controllers\Damage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Producto;
use App\ProductoDamage;
use App\Services\Damage\AnadirProductoAlAmacenDamageService;
use Illuminate\Support\Facades\Auth;

class DamageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productosDamage = ProductoDamage::orderBy('id','desc')->get();
        return view('damages.index', compact('productosDamage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $producto = Producto::where('sku', $request->sku)->first();

        $productosDamage = new ProductoDamage;
        $productosDamage->producto_id = $producto->id;
        $productosDamage->tipo_damage = 'fabrica';
        $productosDamage->user_id = Auth::user()->id;
        $productosDamage->descripcion = $request->descripcion;
        $productosDamage->save();

        $producto->decrement('stock');

        return redirect()->route('productos.damage');
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
    public function destroy($id)
    {
        //
    }
    public function reemplazo(Request $request)
    {
        $producto=Producto::where('id',$request->input('producto_id'))->get();
        $producto=$producto[0];
        $producto->update(['stock' => $producto->stock+1]);
        $productoDamage=ProductoDamage::where('id',$request->input('idDamag'))->get();
        $productoDamage=$productoDamage[0];
        $productoDamage->update(['tipo_damage' => 'Reemplazado']);
        
        $productosDamage = ProductoDamage::orderBy('id','desc')->get();
        return view('damages.index', compact('productosDamage'));
    }
}
