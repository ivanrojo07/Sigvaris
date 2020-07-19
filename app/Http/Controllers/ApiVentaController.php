<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Venta;
use App\Descuento;
use App\Promocion;
use Illuminate\Http\Request;

class ApiVentaController extends Controller
{
    public function calcularDiferencia(Request $request)
    {

        $venta = Venta::find($request->ventaId);
        $productoQueSeraEntregado = Producto::where('sku', $request->skuProductoEntregado)->first();
        $precioProductoQueSeraDevuelto = $request->precioProductoDevuelto;

        $arrayPreciosProductos = $this->getArrayPreciosProductos($venta);

        $totalVentaOriginal = $this->calcularTotalVentaOriginal($venta, $arrayPreciosProductos);


        $arrayPreciosProductosConNuevoProducto = $this->getArrayPreciosProductosConNuevo($arrayPreciosProductos, $productoQueSeraEntregado, $precioProductoQueSeraDevuelto);
        $totalVentaBueva = $this->calcularTotalVentaNueva($venta, $totalVentaOriginal, $arrayPreciosProductosConNuevoProducto);


        $diferencia = $totalVentaOriginal - $totalVentaBueva;
        
        return response()->json([
            'arrayViejosProductos' => $arrayPreciosProductos,
            'arrayNuevoProdutos' => $arrayPreciosProductosConNuevoProducto,
            'totalVentaOriginal' => $totalVentaOriginal,
            'totalVentaNueva' => $totalVentaBueva,
            'diferencia' => $diferencia
        ]);

        return response()->json($request->input());
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
        
        dd($Descuento);
        return $totalNuevaVentaSinHacerDescuento-$Descuento;

        if (!is_null($venta->promocion) && $venta->promocion->unidad_descuento == 'Pieza') {
            $precioMenor = $arrayPreciosProductosNuevos->map( function($item){
                return $item['precio'];
            } )->min();
        }



        return $totalNuevaVentaSinHacerDescuento - $totalVentaOriginal;
        // return $totalNuevaVentaSinHacerDescuento - $precioMenor;

    }

    public function getArrayPreciosProductosConNuevo($arrayPreciosProductos, $productoQueSeraEntregado, $precioProductoQueSeraDevuelto)
    {
        // return $arrayPreciosProductos;
        $key = $arrayPreciosProductos->search( function($arr) use($precioProductoQueSeraDevuelto){
            return $arr['precio'] == $precioProductoQueSeraDevuelto;
        } );

        $nuevo = $arrayPreciosProductos;

        $nuevo->pull($key);
        $nuevo = $nuevo->concat([
            [
                'sku' => $productoQueSeraEntregado->sku,
                'precio' => $productoQueSeraEntregado->precio_publico,
            ]
        ]);
        

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
