<?php

namespace App\Services\Ventas;

use App\Producto;
use App\Venta;
use Carbon\Carbon;

class RealizarVentaProductosService
{

    public function make($venta, $productos, $request)
    {   
        // $respaldo = $request->producto_id;
        // // $auxCantidad = array_reverse($request->cantidad);
        // // $segundo = array_flip($request->producto_id);
        // // $auxProductoid = asort($respaldo);
        // // $request->cantidad = $auxCantidad;
        // // // $request->producto_id = $auxProductoid;
        // $request->producto_id = asort($respaldo);

        // dd($productos,$request->cantidad,$request->producto_id);
        // REALIZAMOS LA VENTA
        $venta->save();

        // POR CADA PRODUCTO COMPRADO, ALMACENAMOS LA CANTIDAD COMPRADO, EL PRECIO Y DECREMENTAMOS EL STOCK
        foreach ($productos as $i => $producto) {

               $precio = Producto::where('id',$request->producto_id[$i])->value('precio_publico');
               
            $venta->productos()->attach($request->producto_id[$i] , ['cantidad' => $request->cantidad[$i], 'precio' => $precio, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            $producto->decrement('stock', $request->cantidad[$i]);
        }
    }
}
