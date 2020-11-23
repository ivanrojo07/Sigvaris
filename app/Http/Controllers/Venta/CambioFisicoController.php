<?php

namespace App\Http\Controllers\Venta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Ventas\StoreCambioFisicoService;
use App\Venta;
use App\Producto;
use App\Empleado;
use App\Folio;

class CambioFisicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Venta $venta)
    {
        return view('venta.cambios_fisicos.create',compact('venta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Venta $venta)
    {

        $Nuevo_pago = 0;
        $auxiliar =0;
        $producto = Producto::where("sku", $request->input("skuProductoRegresado"))->where("oficina_id",session('oficina'))->first();
        $productoQueSeraEntregado = Producto::where('sku', $request->input("skuProductoEntregado"))->where("oficina_id",session('oficina'))->first();

        $empleadosFitter = Empleado::fitters()->get();
        $ObserDevuelto =$request->observaciones;

        if ($request->input("diferenciaPrecios")==0) {
            $saldo=$productoQueSeraEntregado->precio_publico_iva;
            $saldo_paciente=$venta->paciente->saldo_a_favor;
             $saldoA = $saldo_paciente;
            $saldo = 0;
        }else{
            $saldo=$request->input("diferenciaPrecios");
                $saldo_paciente=$venta->paciente->saldo_a_favor;
                 $saldoA = $saldo_paciente;
            // $saldo=round($saldo)+$productoQueSeraEntregado->precio_publico_iva;
    
            if ($saldo>0) {
                 $Nuevo_pago=$saldo; 
                 $auxiliar = $Nuevo_pago;
             } else{
                 $saldoA = $saldo_paciente+$saldo;
                 $auxiliar = 0;
                  $saldo=$request->input("diferenciaPrecios");
                     $saldo+=$saldo_paciente;
             }            
        }

        return view('venta.cambios_fisicos.concluir',['producto'=>$productoQueSeraEntregado,
                                           'productoDebuelto'=>$producto,
                                           'ventaAnterior'=>$venta,
                                           'paciente'=>$venta->paciente,
                                           'saldo'=>$saldo,
                                           'folio' => Venta::count() + 1,
                                           'empleadosFitter' => $empleadosFitter,
                                           'Folios' => Folio::get(),
                                           'ObserDevuelto'=>$ObserDevuelto,
                                           'VentaA'=>$venta->id,
                                           'Diferencia'=>$auxiliar,
                                           'saldoA'=>$saldoA
                                       ]);

        //$storeCambioFisicoService = new StoreCambioFisicoService($request, $venta);
        //return redirect()->route('ventas.index');
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
}
