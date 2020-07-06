<?php

namespace App\Services\Ventas;

use App\HistorialCambioVenta;
use App\Producto;
use App\Venta;
use App\Descuento;
use App\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealizarDevolucionService
{

    protected $producto;

    public function __construct(Request $request, Venta $venta)
    {
        $this->setVenta($venta);
        $this->setProducto($request);
        $this->anadirProductoAStock();
        $this->eliminarProductoDeLaVenta();
        $this->anadirHistorialCambio($venta);
    }

    /**
     * =======
     * METHODS
     * =======
     */

    public function eliminarProductoDeLaVenta()
    {
        $this->venta
            ->productos()
            ->wherePivot('producto_id', $this->producto->id)
            ->detach();
    }

    public function anadirHistorialCambio(Venta $venta)
    {
        if ($venta->promocion_id) {

            HistorialCambioVenta::create([
                'tipo_cambio' => 'DEVOLUCIÓN',
                'responsable_id' => Auth::user()->id,
                'venta_id' => $this->venta->id,
                'producto_entregado_id' => null,
                'producto_devuelto_id' => $this->producto->id,
                'observaciones'=> "Monto devuelto: ".calcularDiferencia($venta,$this->producto->precio_publico)
            ]);
        }else{
            HistorialCambioVenta::create([
                'tipo_cambio' => 'DEVOLUCIÓN',
                'responsable_id' => Auth::user()->id,
                'venta_id' => $this->venta->id,
                'producto_entregado_id' => null,
                'producto_devuelto_id' => $this->producto->id,
                'observaciones'=> "Monto devuelto: ".$this->producto->precio_publico
            ]);
        }
    }

    public function anadirProductoAStock()
    {
            $this->producto->update([
                'stock' => $this->producto->stock + 1
            ]);
    }

    /**
     * =======
     * SETTERS
     * =======
     */

    public function setVenta($venta)
    {
        $this->venta = $venta;
    }

    public function setProducto($request)
    {
        $this->producto = Producto::where('id', $request->input("skuProductoDevuelto"))->first();
        dd($this->producto);
    }
    

    public function calcularDiferencia(Venta $venta,$precioProductoQueSeraDevuelto)
    {

        //$venta = Venta::find($request->ventaId);
        //$productoQueSeraEntregado = Producto::where('sku', $request->skuProductoEntregado)->first();

        //$precioProductoQueSeraDevuelto = $request->precioProductoDevuelto;

        $arrayPreciosProductos = $this->getArrayPreciosProductos($venta);

        $totalVentaOriginal = $this->calcularTotalVentaOriginal($venta, $arrayPreciosProductos);


        $arrayPreciosProductosConNuevoProducto = $this->getArrayPreciosProductosConNuevo($arrayPreciosProductos, $precioProductoQueSeraDevuelto);
        $totalVentaBueva = $this->calcularTotalVentaNueva($venta, $totalVentaOriginal, $arrayPreciosProductosConNuevoProducto);


        $diferencia = $totalVentaOriginal - $totalVentaBueva;
        
        return $diferencia;
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
