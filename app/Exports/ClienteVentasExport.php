<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\Promocion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClienteVentasExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Venta::where('fecha', '>=', date('Y-m-d'))
            ->where('oficina_id',1)
            ->get()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                function ($Venta) {
                    $SkuRe="";
                    foreach ($Venta->productos as $producto ) {
                        $SkuRe.=$producto->sku;
                    }
                return collect([
                    date('Y-m-d'),
                    Carbon::parse($Venta->fecha)->format('h:i:s'),
                    $Venta->id,
                    $index,
                    $Venta->paciente->nombre." ".$Venta->paciente->paterno." ".$Venta->paciente->materno,
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->nombre : "",
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->consultorios[0] != null  $Venta->paciente->doctor->consultorios[0]->hospital->nombre:"" : ""    ,
                    "",
                    $Venta->paciente->ventas()->count() == 1?  "1":"2",
                    $Venta->productos != null ? $Venta->productos()->pluck('cantidad')->sum():"",
                    $SkuRe,
                    "",
                    "",
                    $Venta->paciente->mail,
                    $Venta->paciente->telefono,
                    $Venta->paciente->celular

                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Nota de remisión',
            'Fecha de compra',
            'Nombre del paciente',
            'Nombre del Doctor',
            'Hospital ',
            'fecha receta',
            'Numero de visita',
            'Cantidad ',
            'Sku Vendido',
            'Producto negado ',
            'Estilo negado',
            'mail',
            'telefono',
            'celular'


        ];
    }
}
