<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\Devolucion;
use Illuminate\Database\Eloquent\Model\Devolución;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\Promocion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class DevolucionPExport implements FromCollection, WithHeadings,WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
   
    public function collection()
    {
        $now = Carbon::now('America/Mexico_City');

        

        return Devolucion::where('created_at', '>=',$now->format('Y-m-d'))
            ->get()
            //->pluck('productos')
             ->flatten()
            ->map(
                
                function ($Devolucion) {

                  // $Devoluciones = Devolucion::where('created_at','>=',$now->format('Y-m-d'))->get();
                  // dd($Devoluciones->id);

                return collect([
                    $Devolucion->venta_id,
                    date('Y-m-d h:i:s'),
                    $Devolucion->beneficiario,                 
                    $Devolucion->monto

                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Fecha de compra',
            'Paciente',
            'Sigpesos',
            'Monto'

        ];
    }
    public function title(): string
    {
        return 'Devoluciones deposito';
    }
}
