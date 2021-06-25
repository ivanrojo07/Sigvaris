<?php

namespace App\Services\Ventas;

use App\Producto;
use App\Venta;
use App\Garex;
use App\Retex;
use Carbon\Carbon;
use DB;

class RealizarRetexVentaService
{

    public function make($venta, $request)
    {   
        // dd("Serviocio garex",$venta,$request);
    
        $venta->save();
        $date = Carbon::now();
        $date->addMonth(3);
      
        $folio_id = count(DB::table('retex_ventas')->get())+1;
         $venta->retex()->attach($venta->id, ['id'=>$folio_id,'SKU' => $request->skuProductoEntregado, 'folio' =>  $request->retex_folio, 'total_a_pagar' =>  $venta->total,'created_at' => Carbon::now(), 'updated_at' => Carbon::now(),'fecha_fin' => $request->garex_fin,'status'=>1]);
             

                }
      
    }

