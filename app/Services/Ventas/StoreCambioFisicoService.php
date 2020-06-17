<?php

namespace App\Services\Ventas;

use App\HistorialCambioVenta;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreCambioFisicoService
{

    protected $productoEntregado;
    protected $productoDevuelto;
    protected $venta;
    protected $paciente;

    public function __construct(Request $request, $venta)
    {
        // dd($request->input());
        $this->setVenta($venta);
        $this->setPaciente($venta->paciente);
        $this->setProductoEntregado($request);
        $this->setProductoDevuelto($request);
        $this->actualizarInventario();
        $this->anadirCambioProductoAHistorial($request);
        $this->actualizarVenta();
        $this->actualizarSaldoAFavorPaciente($request->diferenciaPrecios);
        // dd($this->productoDevuelto);
    }

    /**
     * =======
     * METHODS
     * =======
     */

    public function actualizarSaldoAFavorPaciente($saldo)
    {
        if ($saldo > 0) {
            $this->paciente->saldo_a_favor += $saldo;
            $this->paciente->save();
        }
    }

    public function actualizarVenta()
    {
        $this->venta->productos()->detach($this->productoDevuelto->id);
        $this->venta->productos()->attach($this->productoEntregado, [
            'cantidad' => 1,
            'precio' => $this->productoEntregado->precio_publico_iva
        ]);
    }

    public function actualizarInventario()
    {
        $this->productoEntregado->update([
            'stock' => $this->productoEntregado->stock - 1
        ]);
        $this->productoDevuelto->update([
            'stock' => $this->productoDevuelto->stock + 1
        ]);
    }

    public function anadirCambioProductoAHistorial($request)
    {
        // dd($request);
        HistorialCambioVenta::create([
            'tipo_cambio' => 'CAMBIO PRODUCTO',
            'responsable_id' => Auth::user()->id,
            'venta_id' => $this->venta->id,
            'producto_entregado_id' => $this->productoEntregado->id,
            'producto_devuelto_id' => $this->productoDevuelto->id,
            'observaciones' => $request->observaciones
        ]);
    }

    /**
     * =======
     * SETTERS
     * =======
     */

    public function setPaciente($paciente)
    {
        $this->paciente = $paciente;
    }

    public function setVenta($venta)
    {
        $this->venta = $venta;
    }

    public function setProductoEntregado($request)
    {
        $this->productoEntregado = Producto::where('sku', $request->skuProductoEntregado)->first();
    }

    public function setProductoDevuelto($request)
    {
        $this->productoDevuelto = Producto::where('sku', $request->skuProductoRegresado)->first();
    }
}
