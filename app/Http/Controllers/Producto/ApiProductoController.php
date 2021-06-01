<?php

namespace App\Http\Controllers\Producto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Producto;

class ApiProductoController extends Controller
{
    public function getProductoBySku($sku){
    	if (strlen($sku)==12) {
    		# code...
    	$producto = Producto::where('upc',$sku)->first();
    	}else{
    		$producto = Producto::where('sku',$sku)->first();
    	}
        

        return is_null($producto) ? 
            response()->json($producto, 404) :
            response()->json($producto, 200);

        return response()->json($producto);
    }
}
