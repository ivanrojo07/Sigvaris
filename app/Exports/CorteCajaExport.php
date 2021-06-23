<?php

namespace App\Exports;

use App\Venta;
use App\Factura;
use App\HistorialCambioVenta;
use Carbon\Carbon;
use App\Descuento;
use App\sigvariscard;
use App\sigpesosventa;
use Illuminate\Database\Eloquent\Model\Devolución;
use App\Promocion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class CorteCajaExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
            $now = Carbon::now('America/Mexico_City');
            $index=0;
            // $DAMAGE = DB::table('Productos_damage')->where('origin_id','=',)->select('origin_id');


       // dd($now);
        return Venta::where('fecha', '>=', $now->format('Y-m-d'))
            ->where('oficina_id',2)
            ->get()
            //->pluck('productos')
            ->flatten()
            ->map(
                
                function ($Venta,$index) {
                $Sigp="";
                //dd($Venta->productos()->pluck('cantidad')->sum());
                $index++;
                  foreach ($Venta->SigpesosVenta as $Sigpesos ) {
                       
                        $Sigp.=$Sigpesos->folio." - ";
                        
                        
                    }
                //dd($Venta->requiere_factura);
                return collect([
                    date('Y-m-d'),
                    Carbon::parse($Venta->fecha)->format('h:i:s'),
                    $Venta->id,
                    $index,
                    $Venta->paciente->nombre." ".$Venta->paciente->paterno." ".$Venta->paciente->materno,
                    $Venta->paciente->doctor != null ? $Venta->paciente->doctor->nombre : "",
                    $Venta->paciente->ventas()->count() == 1?  "1":"2",
                    $Venta->id,
                    $Venta->empleado != null ? $Venta->empleado->nombre : "",
                    $Venta->productos != null ? $Venta->productos()->pluck('cantidad')->sum():"",
                    HistorialCambioVenta::where('destinate_id','=',$Venta->id)->exists()? HistorialCambioVenta::where('destinate_id','=',$Venta->id)->value('precioOri')." - ".HistorialCambioVenta::where('destinate_id','=',$Venta->id)->value('precioNew'):"",
                    $Venta->total,
                    $Venta->PagoEfectivo,
                    $Venta->sigpesos,
                    $Venta->PagoSaldo,
                    $Venta->PagoTarjeta,

                    // $Venta->banco!= null ? $Venta->banco =="AMEX"? $Venta->PagoTarjeta:"" :"",
                    // $Venta->banco!= null ? $Venta->banco =="AMEX"? $Venta->digitos_targeta:"" :"",

                    // $Venta->banco!= null ? $Venta->banco !="AMEX"? $Venta->PagoTarjeta:"" :"",
                    // $Venta->banco!= null ? $Venta->banco !="AMEX"? $Venta->digitos_targeta:"" :"",
                     // strcmp($Venta->banco,"AMEX") === 1 ? $Venta->PagoTarjeta:"",
                    strcmp($Venta->banco,"AMEX") === 1 ? $Venta->digitos_targeta :"",
                    //  strcmp($Venta->banco,"AMEX") === 0 ? $Venta->PagoTarjeta:"",
                    // strcmp($Venta->banco,"AMEX") === 0 ? $Venta->digitos_targeta :"",
                    $Venta->num_transferencia,
                    $Venta->folio_transferencia,
                    $Venta->num_deposito,
                    $Venta->folio_deposito,
                    // "",
                    // "",
                    $Venta->requiere_factura == 1 ? "SI":"NO",
                    //Factura::where('venta_id',$Venta->id)->exists()? "Si":"No",
                    $Venta->empleado != null ? $Venta->empleado->nombre : "",
                    "",
                     HistorialCambioVenta::where('venta_id',$Venta->id)->where('tipo_cambio','RETEX DEL PRODUCTO')? HistorialCambioVenta::where('destinate_id','=',$Venta->id)->value('venta_id'):"",
                     DB::table('garex_ventas')->where('venta_id','=',$Venta->id)->exists()?DB::table('garex_ventas')->where('venta_id','=',$Venta->id)->get():"",
                    HistorialCambioVenta::where('venta_id',$Venta->id)->exists()? "Si":"No",

                    DB::table('productos_damage')->where('origin_id','=',$Venta->id)->exists() ? "SI":" NO",
                 DB::table('productos_damage')->where('destinate_id','=',$Venta->id)->exists()?DB::table('productos_damage')->where('destinate_id','=',$Venta->id)->value('origin_id') : "" ,
                    HistorialCambioVenta::where('destinate_id','=',$Venta->id)->where('tipo_cambio','CAMBIO PRODUCTO')->exists()? HistorialCambioVenta::where('destinate_id','=',$Venta->id)->value('venta_id'):"",
                    $Venta->cumpleDes ==1 ? "300":"0",

                sigpesosventa::where('tipo','cumpleaños')->where('venta_id',$Venta->id)->exists()?sigpesosventa::where('tipo','cumpleaños')->where('venta_id',$Venta->id)->value('folio'): "",
                    // DB::table('sigpesosventa')->where('venta_id','=',$Venta->id)->exists()?DB::table('sigpesosventa')->where('venta_id','=',$Venta->id)->where('tipo','!=','esencial')->value('folio'):"",
                    $Sigp,
                    sigvariscard::where('venta_id',$Venta->id)->value('Tipo'),
                    sigvariscard::where('venta_id',$Venta->id)->value('folio'),

                    DB::table('devoluciones')->where('venta_id','=',$Venta->id)->exists()?"-".DB::table('devoluciones')->where('venta_id','=',$Venta->id)->value('monto'):"",
                     // DB::table('Productos_damage')->find($request->lista[$key]);
                    $Venta->promocion_id != null ? Descuento::where("id",$Venta->descuento_id)->value('nombre') : "",
                    "",
                    $Venta->desc_inapam != null ? $Venta->desc_inapam : "0"
                   

                ]);
            });
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Hora',
            'Nota de remisión',
            'Partida',
            'DETALLE PACIENTE',
            'NOMBRE DEL MÉDICO QUE ENVÍA',
            'No. Vista',
            'NOTA DE REMISION',
            'CIERRE VENTA',
            'PZAS POR PACIENTE',
            'PrecioOriginal / PrecioNueva',
            'TOTAL VENTA',
            'PAGO EFECTIVO',
            'PAGO SIGPESOS',
            'PAGO SALDO A FAVOR',

            'PAGO TARJETA ',
            'DIGITOS 4 ULTIMOS ',

            'PAGO TRANSFERENCIA',
            'FOLIO TRANSFERENCIA',

            'PAGO DEPOSITO',
            'FOLIO DEPOSITO',
            
            'FACTURA',
            'Generó',
            'Envió',
            'Garex/Retex',
            'Folio Garex/Retex',
            'Cambio fisico',
            'Damage',
            'Folio Damage', 
            'Folio Cambio Fisico',
            'Descuento_cumpleaños',
            'Folio Cumpleaños',
            'Folio Sigpesos',
            'Tipo SigvarisCard',
            'Folio SigvarisCard', 
            'Devolucion',
            'Descuento',
            'Notas Observaciones',
            'INAPAM DESCUENTO'
            




        ];
    }
    public function title(): string
    {
        return 'Corte de Caja';
    }
}
