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
use App\ProductoDamage;
use App\Services\Damage\AnadirProductoAlAmacenDamageService;

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
        
        $venta = Venta::find( $request->id_venta );
        $producto = Producto::where("sku",$request->input("sku"))->first();

        $anadirProductoAlAlmacenDamageService = new AnadirProductoAlAmacenDamageService($producto, 'paciente');
        $anadirProductoAlAlmacenDamageService->execute();

        $HistorialCambioVenta=new HistorialCambioVenta(
             array(
                'tipo_cambio'=>"Damage",
                'responsable_id'=>Auth::user()->id, 
                'venta_id' => $venta->id, 
                'observaciones' => '',
                'producto_devuelto_id' => $producto->id,
            )
        );

        $producto->update(['stock'=>$producto->stock-1]);

        $HistorialCambioVenta->save();

        $venta->productos()->detach( $producto->id );

        $medicos = Doctor::get();
        $ventas = Venta::orderBy('fecha','desct')->paginate(5);
        return redirect()->route('ventas.index');
    }
}
