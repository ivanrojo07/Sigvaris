<?php

namespace App\Http\Controllers\Venta;

use App\Producto;
use App\Venta;
use App\Doctor;
use App\HistorialCambioVenta;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DamageController extends Controller
{
    //

    public function index($id)
    {
        $venta=Venta::where('id',$id)->first();
        $productos=$venta->productos;
    	return view('venta.damage.index', ['productos'=>$productos,'venta'=>$venta]);
    }

    public function SerchProductoExit(Request $request){
    	$Producto=Producto::where('sku',$request->input('sku'))->get();;
    	if (count($Producto)==1) {
    		$Datos =array('Ex'=>1,'Producto'=>$Producto[0]);
    		return $Datos;
    	}else{
    		$Datos =array('Ex'=>0);
    		return $Datos;
    	}
    }
    public function Devolucion_Damage(Request $request)
    {
        
        $HistorialCambioVenta=new HistorialCambioVenta(
             array(
            'tipo_cambio'=>"Damage",
            'responsable_id'=>Auth::user()->id, 
            'venta_id' => $request->input("id_venta"), 
            'observaciones' => $request->input("damage"),
            'producto_devuelto_id' => Producto::where("sku",$request->input("sku"))->value('id')
        )

        );
        $Producto=Producto::where('sku',$request->input("sku"))->get();

        Producto::where('sku',$request->input("sku"))
                  ->update(['stock'=>$Producto[0]->stock-1]);

        $HistorialCambioVenta->save();
        $medicos = Doctor::get();
        $ventas = Venta::orderBy('fecha','desct')->paginate(5);
        return view('venta.index_all', ['ventas' => $ventas, 'medicos' => $medicos]);
    }
}
