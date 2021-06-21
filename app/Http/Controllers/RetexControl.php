<?php

namespace App\Http\Controllers;

use App\Retex;
use App\Venta;
use App\Producto;
use Illuminate\Http\Request;
use App\Services\Ventas\StoreCambioFisicoService;


use App\Empleado;
use App\Folio;


class RetexControl extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return 0;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
           $venta = Venta::find($request)->first();
        $productos = $venta->productos;
        return view('retex.create', ['venta' =>  $venta,'productos' => $productos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Venta $venta)
    {
        //
        $Nuevo_pago = 0;
        $auxiliar =0;
        $producto = Producto::where("sku", $request->input("skuProductoRegresado"))->where("oficina_id",session('oficina'))->first();
        $productoQueSeraEntregado = Producto::where('sku', $request->input("skuProductoEntregado"))->where("oficina_id",session('oficina'))->first();

        $empleadosFitter = Empleado::fitters()->get();
        $ObserDevuelto =$request->observaciones;

        // if ($request->input("diferenciaPrecios")==0) {
        //     $saldo=0;
        //     $saldo_paciente=$venta->paciente->saldo_a_favor;
        //      $saldoA = $saldo_paciente;
        //     $saldo = 0;
        // }else{
        //     $saldo=$request->input("diferenciaPrecios");
        //         $saldo_paciente=$venta->paciente->saldo_a_favor;
        //          $saldoA = $saldo_paciente;
        //     // $saldo=round($saldo)+$productoQueSeraEntregado->precio_publico_iva;
    
        //     if ($saldo>0) {
        //          $Nuevo_pago=$saldo; 
        //          $auxiliar = $Nuevo_pago;
        //      } else{
        //          $saldoA = $saldo_paciente+abs($saldo);
        //          $auxiliar = 0;
        //           $saldo=$request->input("diferenciaPrecios");
        //              $saldo+=$saldo_paciente;
        //      }            
        // }
         $sigpesos_paciente=0;

        return view('retex.concluir',['producto'=>$productoQueSeraEntregado,
                                           'productoDebuelto'=>$producto,
                                           'ventaAnterior'=>$venta,
                                           'paciente'=>$venta->paciente,
                                           'saldo'=>0,
                                           'folio' => Venta::count() + 1,
                                           'empleadosFitter' => $empleadosFitter,
                                           'Folios' => Folio::get(),
                                           'ObserDevuelto'=>$ObserDevuelto,
                                           'VentaA'=>$venta->id,
                                           'Diferencia'=>$auxiliar,
                                           'saldoA'=>0,
                                           'precioOri' =>$request->precioOri,
                                           'precioNew'=>$request->precioNew,
                                           'sigpesos_a_favor'=>$sigpesos_paciente
                                       ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function show(Retex $retex)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function edit(Retex $retex)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Retex $retex)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retex $retex)
    {
        //
    }
}
