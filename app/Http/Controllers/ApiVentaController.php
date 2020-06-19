<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Venta;
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

        return $totalNuevaVentaSinHacerDescuento;

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

        return $venta->productos;

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
}
