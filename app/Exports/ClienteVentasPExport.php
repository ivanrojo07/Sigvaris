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
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;
class ClienteVentasPExport implements FromCollection, WithHeadings, WithTitle
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
                    $SkuPre= "";
                    $contador = $Venta->productos()->pluck('cantidad');
                    $aux = 0;
                    // $descu = HistorialCambioVenta::where('venta_id',$Venta->id)->where('descuento_cu',1)->get();
                     // $produc = Producto::where('id',$descu->producto_id)->get();
                    foreach ($Venta->productos as $producto ) {
                      
                       //      if ($producto->sku == $produc->sku) {
                       //     $SkuPre.=$producto->precio_publico_iva-300;
                       // }
                        $SkuRe.=$producto->sku." - ".$contador[$aux]."| ";
                        $SkuPre.=$producto->precio_publico_iva." - ".$contador[$aux]."| ";
                        $aux++;
                    }
                return collect([
                     $Venta->id,
                    date('Y-m-d'),                    
                    $Venta->paciente->nombre." ".$Venta->paciente->paterno." ".$Venta->paciente->materno,
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->nombre : "",
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->consultorios()->first() != null ? $Venta->paciente->doctor->consultorios()->first()->nombre:"" : ""    ,
                    "",
                    $Venta->paciente->ventas()->count() == 1?  "1":"2",
                    $Venta->productos != null ? $Venta->productos()->pluck('cantidad')->sum():"",
                    $SkuRe,
                    $SkuPre,

                     DB::table('productos_damage')->where('origin_id','=',$Venta->id)->exists() ? "SI":" NO",
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
            'Precio Sku',
            'damage',
            'Producto negado ',
            'Estilo negado',
            'mail',
            'telefono',
            'celular'


        ];
    }
    public function title(): string
    {
        return 'Clientes';
    }
}