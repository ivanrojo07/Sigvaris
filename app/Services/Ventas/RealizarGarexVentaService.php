<?php

namespace App\Services\Ventas;

use App\Producto;
use App\Venta;
use App\Garex;
use Carbon\Carbon;
use DB;

class RealizarGarexVentaService
{

    public function make($venta, $request)
    {   
        // dd("Serviocio garex",$venta,$request);
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
        $date = Carbon::now();
        $date->addMonth(3);
        if (isset($request->garex)) {
             foreach ($request->garex as $i => $garex) {
                if (strlen($garex)==12) {
                    $SKU = Producto::where('upc', $request->skuProductoEntregado)>value('SKU');
                }else{
                     $SKU = Producto::where('SKU',$garex)->value('SKU');
                }   

              
               // $professions = ;
               $folio = count(DB::table('garex_ventas')->get());
               $folio_id = $folio+1;
               $folio = 'GAREXT01-'.$folio;
                if ($SKU == null) {
                    $SKU = '143CA13';
                }
            $venta->garex()->attach($venta->id, ['id'=>$folio_id,'SKU' => $SKU, 'folio' =>  $folio, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),'fecha_fin' => $date->toDateString(),'status'=>0]);
             
            // $producto->decrement('stock', $request->cantidad[$i]);
        }
        }
        // POR CADA PRODUCTO COMPRADO, ALMACENAMOS LA CANTIDAD COMPRADO, EL PRECIO Y DECREMENTAMOS EL STOCK
       
        // dd($garex,$folio,$SKU,$date->toDateString(),$request->garex[0]);
    }
}
