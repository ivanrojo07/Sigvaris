<?php

namespace App\Exports;

use App\Factura;
use App\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FacturasExport implements FromCollection, WithHeadings
{
    public function __construct($fecha)
    {
        $this->fecha = $fecha;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect(
            Venta::where('requiere_factura',1)->where('fecha',$this->fecha)->with('productos.ventas')->get()->pluck('productos')->flatten()->map( function($producto){
                // dd($producto->ventas->first());
                return [
                    'clave' => 1,
                    'cliente' => 'MOST ' . strtoupper( substr( $producto->ventas->first()->oficina->nombre, 0, 3) ),
                    'fecha_de_elaboracion' => date('Y-m-d'),
                    'numero_almacen_cabecera' => $producto->ventas->first()->oficina->nombre == 'Polanco' ? 2 : 7,
                    'numero_de_moneda' => 1,
                    'tipo_de_cambio' => 1,
                    'tipo_de_cambio_02' => "tienda: " . $producto->ventas->first()->oficina->nombre . " fecha venta: " . date('d-m-Y'),
                    // 'observaciones' => 'tienda: ' . $producto->ventas->first()->oficina->nombre . " fecha venta: " . date('d-m-Y'),
                    // 'observaciones' => 'tienda: ' . $producto->ventas->first()->oficina->nombre . " fecha venta: " . date('d-m-Y'),
                    'clave_del_vendedor' => strtoupper( substr( $producto->ventas->first()->oficina->nombre, 0, 3) ) . ", " . $producto->ventas->first()->oficina->nombre == 'Polanco' ? 8 : 7,
                    'nombre_del_paciente' => $producto->ventas->first()->paciente->full_name,
                    'fecha_entrega' => date('d-m-Y'),
                    'fecha_vencimiento' => date('d-m-Y'),
                    'precio_producto' => $producto->precio_publico_iva,
                    'descuento' => $producto->ventas->first()->promocion ? $producto->precio_publico_iva / $producto->ventas->first()->promocion->descuento_de * 100 : '0.00',
                    'descuento_02' => '',
                    'descuento_03' => '',
                    'comision' => '',
                    'esquema_impuestos' => 1,
                    'clave_articulo' => $producto->sku,
                    'cantidad' => $producto->pivot->cantidad,
                    'ieps' => '',
                    'impuesto_02' => '',
                    'impuesto_03' => '',
                    'iva' => 16,
                    'numero_almacen' => $producto->ventas->first()->oficina->nombre == 'Polanco' ? 2 : 7,
                    'observaciones' => '',
                ];
            } ),
        );
        return Factura::get();
    }

    public function headings(): array
    {
        return [
            'CLAVE',
            'OFICINA',
            'FECHA DE ELABORACIÓN',
            'NÚM. DE ALMACÉN',
            'NÚM. MONEDA',
            'TIPO DE CAMBIO',
            'OBSERVACIONES',
            'OFICINA',
            'NOMBRE PACIENTE',
            'FECHA ENTREGA',
            'FECHA VENCIMIENTO',
            'PRECIO',
            'DESC. 1',
            'DESC. 2',
            'DESC. 3',
            'COMISIÓN',
            'CLAVE DE ESQUEMA DE IMPUESTOS',
            'CLAVE DEL ARTICULO',
            'CANTIDAD',
            'I.E.P.S.',
            'IMPUESTO 2',
            'IMPUESTO 3',
            'I.V.A.',
            'NÚM. DE ALMACÉN',
            'OBSEREVACIONES DE PARTIDA'
        ];
    }
}
