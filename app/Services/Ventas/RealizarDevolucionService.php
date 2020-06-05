<?php

namespace App\Services\Ventas;

use App\HistorialCambioVenta;
use App\Producto;
use App\Venta;
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
        $this->anadirHistorialCambio();
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

    public function anadirHistorialCambio()
    {
            HistorialCambioVenta::create([
                'tipo_cambio' => 'DEVOLUCIÓN',
                'responsable_id' => Auth::user()->id,
                'venta_id' => $this->venta->id,
                'producto_entregado_id' => null,
                'producto_devuelto_id' => $this->producto->id
            ]);
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
        $this->producto = Producto::where('sku', $request->skuProductoDevuelto)->first();
    }
}
