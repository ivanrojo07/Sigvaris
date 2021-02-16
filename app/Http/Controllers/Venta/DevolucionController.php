<?php

namespace App\Http\Controllers\Venta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Ventas\RealizarDevolucionService;
use App\Producto;
use App\Venta;
use App\Descuento;
use App\Promocion; 
use App\HistorialCambioVenta;
class DevolucionController extends Controller
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
        return view('venta.devolucion.create', compact('venta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Venta $venta)
    {   
        // dd($request->input("tipo_cambio"));
        $realizarDevolucionService = new RealizarDevolucionService($request, $venta);
        if ($request->input("tipo_cambio")==2) {
            $MONTO=$request->input("MONTO");
            return view('devolucion.create', compact('venta','MONTO'));
        }else{
            $venta->paciente->saldo_a_favor += $request->input("MONTO");
            
            
            $venta->paciente->save();
            return redirect()->route('ventas.index');
        }
        
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
     public function calcularDiferencia(Request $request)
    {
         // dd($request);
        $venta = Venta::find($request->ventaId);
        //$productoQueSeraEntregado = Producto::where('sku', $request->skuProductoEntregado)->first();

        $precioProductoQueSeraDevuelto = $request->precioProductoDevuelto;

        $arrayPreciosProductos = $this->getArrayPreciosProductos($venta);
        $promo = Promocion::where('descuento_id',$venta->descuento_id)->value('descuento_de');
        $aux = $promo;
        $promo_unidad = Promocion::where('descuento_id',$venta->descuento_id)->value('unidad_descuento');
        // $totalVentaOriginal = $this->calcularTotalVentaOriginal($venta, $arrayPreciosProductos);
        // $promo = Promocion::where('descuento_id',$venta->descuento_id)->value('descuento_de');
        // $aux = $promo;

        // $arrayPreciosProductosConNuevoProducto = $this->getArrayPreciosProductosConNuevo($arrayPreciosProductos, $precioProductoQueSeraDevuelto);
        // $totalVentaBueva = $this->calcularTotalVentaNueva($venta, $totalVentaOriginal, $arrayPreciosProductosConNuevoProducto);
            $uno =0;
            $dos =0;
            $tres=0;
            $cuatro=0;
            $cinco=0;
            $uno = $precioProductoQueSeraDevuelto +($precioProductoQueSeraDevuelto*.16);
            $cuatro = $uno;

            //precio completo
        $diferencia = $precioProductoQueSeraDevuelto + ($precioProductoQueSeraDevuelto*0.16);
        //redondeamos
         $diferencia =  round($diferencia);
        $promocion=Promocion::where('descuento_id',$venta->descuento_id)->value('descuento_de');
        if ($venta->descuento_id !=null) {
                
            if ($promo_unidad == 'Procentaje' ||$promo_unidad == 'Procentaje1' ||$promo_unidad == 'Procentaje2') {
                # code...
                # precio original
                $uno = $precioProductoQueSeraDevuelto +($precioProductoQueSeraDevuelto*.16);
                // precio con el descuento aplicado
                $cuatro = $uno-$uno*$promo/100;
                $dos=$uno*$promo/100;
                $promocion = $promocion/100;
                
            }

            // if ($promo_unidad == 'sigCompra') {
            //     # code...
            //     $promocion =0;
            // }
           
        }
        if ($venta->cumpleDes) {
            # code...3.
            $uno = $tres;

                    $consulta = HistorialCambioVenta::where('venta_id',$venta->id)->where('descuento_cu',1)->get();
            if (count($consulta) >= 1) {

                    $diferencia=$cuatro;
            }else{
                $promocion=300;
                $cuatro = $cuatro-300;
                $diferencia=$cuatro;
            }
             $diferencia=round($cuatro);
    }   
     $diferencia=round($cuatro);

        
        return response()->json([
            'diferencia' => round($diferencia),
            'cumple'=>$venta->cumpleDes,
            'promo'=>$promocion,
            'promo_2' =>$promo_unidad,
            'uno'=>round($uno),
            'dos'=>round($dos),
            'tres'=>$tres,
            'cuatro'=>round($cuatro),
            'cinco'=>$precioProductoQueSeraDevuelto
        ]);
    }

    public function calcularTotalVentaNueva($venta, $totalVentaOriginal, $arrayPreciosProductosNuevos){

        $precioMenor = 0;

        $totalNuevaVentaSinHacerDescuento = $arrayPreciosProductosNuevos->map( function($item){
            return $item['precio'];
        } )->sum();
        if ($venta->descuento_id) {
            $Descuento=$this->ObtenerDescuento($venta,$arrayPreciosProductosNuevos,$totalNuevaVentaSinHacerDescuento);
        }else{
            $Descuento=0;
        }
        
        //dd($Descuento);
        return $totalNuevaVentaSinHacerDescuento-$Descuento;

        if (!is_null($venta->promocion) && $venta->promocion->unidad_descuento == 'Pieza') {
            $precioMenor = $arrayPreciosProductosNuevos->map( function($item){
                return $item['precio'];
            } )->min();
        }



        return $totalNuevaVentaSinHacerDescuento - $totalVentaOriginal;
        // return $totalNuevaVentaSinHacerDescuento - $precioMenor;

    }

    public function getArrayPreciosProductosConNuevo($arrayPreciosProductos, $precioProductoQueSeraDevuelto)
    {
        // return $arrayPreciosProductos;
        $key = $arrayPreciosProductos->search( function($arr) use($precioProductoQueSeraDevuelto){
            return $arr['precio'] == $precioProductoQueSeraDevuelto;
        } );

        $nuevo = $arrayPreciosProductos;

        $nuevo->pull($key);
        

        return $nuevo;
    }

    public function calcularTotalVentaOriginal($venta, $arrayPreciosProductos)
    {

        $precioMenor = 0;
        if (!is_null($venta->promocion) && $venta->promocion->unidad_descuento == 'Pieza') {
            $precioMenor = $venta->productos->pluck('pivot')->flatten()->min('precio');
        }
        return $arrayPreciosProductos->map( function($item){
            return $item['precio'];
        } )->sum() - $precioMenor;
    }

    public function getArrayPreciosProductos($venta)
    {
        $productosDeVentaOriginal = collect();

        
        foreach ($venta->productos as $producto) {

            for ($i = 0; $i < $producto->pivot->cantidad; $i++) {
                $productosDeVentaOriginal = $productosDeVentaOriginal->concat(
                    [[
                        'sku' => $producto->sku,
                        'precio' => $producto->pivot->precio
                    ]]
                );
            }
        }

        return $productosDeVentaOriginal;
    }
    public function ObtenerDescuento(Venta $venta,$arregloProdctuos,$totalNuevaVentaSinHacerDescuento)
    {
        //$descuento=$venta->descuento();
        //$promocion=$promocion[0];
        $promocion=Promocion::where('descuento_id',$venta->descuento_id)->get();
        $promocion=$promocion[0];
        //dd($arregloProdctuos);
        $descuento=0;
        switch ($promocion->tipo) {
            case 'A':
                if (count($arregloProdctuos)>=$promocion->compra_min) {
                    switch ($promocion->unidad_descuento) {
                        case 'Pesos':
                            $descuento=$promocion->descuento_de;
                            break;

                        case 'Procentaje1':
                            $CostoProductoBarato=999999999;
                            foreach ($arregloProdctuos as $producto) {
                                if($CostoProductoBarato>$producto["precio"]){
                                    $CostoProductoBarato=$producto["precio"];
                                }
                            }
                            $descuento=$CostoProductoBarato*($promocion->descuento_de/100);
                            break;

                        case 'Procentaje2':
                            $descuento=$venta->subtotal*($promocion->descuento_de/100);
                            break;

                         case 'Pieza':
                            $CostoProductoBarato=999999999;
                            foreach ($arregloProdctuos as $producto) {
                                if($CostoProductoBarato>$producto["precio"]){
                                    $CostoProductoBarato=$producto["precio"];
                                }
                            }
                            $descuento=$CostoProductoBarato;
                            break;
                        default:
                            $descuento=0;
                            break;
                    }
                }
                break;
            case 'B':
            if ($totalNuevaVentaSinHacerDescuento>=$promocion->compra_min) {
                    switch ($promocion->unidad_descuento) {
                       case 'Pesos':
                            $descuento=$promocion->descuento_de;
                            break;

                        case 'Procentaje1':
                            $CostoProductoBarato=999999999;
                            foreach ($arregloProdctuos as $producto) {
                                if($CostoProductoBarato>$producto["precio"]){
                                    $CostoProductoBarato=$producto["precio"];
                                }
                            }
                            $descuento=$CostoProductoBarato*($promocion->descuento_de/100);
                            break;

                        case 'Procentaje2':
                            $descuento=$venta->subtotal*($promocion->descuento_de/100);
                            break;

                         case 'Pieza':
                            $CostoProductoBarato=999999999;
                            foreach ($arregloProdctuos as $producto) {
                                if($CostoProductoBarato>$producto["precio"]){
                                    $CostoProductoBarato=$producto["precio"];
                                }
                            }
                            $descuento=$CostoProductoBarato;
                            break;
                        default:
                            $descuento=0;
                            break;
                    }
                }
                break;
            case 'C':
             switch ($promocion->unidad_descuento) {
                       case 'Pesos':
                            $descuento=$promocion->descuento_de;
                            break;

                        case 'Procentaje':
                            $descuento=$venta->subtotal*($promocion->descuento_de/100);
                            break;

                         case 'Pieza':
                            $CostoProductoBarato=999999999;
                            foreach ($arregloProdctuos as $producto) {
                                if($CostoProductoBarato>$producto["precio"]){
                                    $CostoProductoBarato=$producto["precio"];
                                }
                            }
                            $descuento=$CostoProductoBarato;
                            break;
                        default:
                            $descuento=0;
                            break;
                    }
                break;
            default:
                $descuento=0;
                break;
            }

        return $descuento;


    }
}
