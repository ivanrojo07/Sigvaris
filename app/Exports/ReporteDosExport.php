<?php

namespace App\Exports;
use App\Venta;
use App\Oficina;
use App\Paciente;
use App\Producto;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;

class ReporteDosExport implements FromView
    {

        public function __construct($fechaInicial,$fechaFinal,$ventas){ 
         $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
         $this->ventas = $ventas;
        
            }
	    public function view(): View
            {
        $fechaInicial =$this->fechaInicial;
        $fechaFinal =$this->fechaFinal;
        $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
                ->where('fecha', '<=', $fechaFinal)
                ->withCount('productos');
        $ventas = $ventas->get();
        return view('exports.reporteDos', [
            'ventas' =>$ventas
        ]);
    }
}



 //  public function ventasFecha($fechaInicial,$fechaFinal){
 //            $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
 //                ->where('fecha', '<=', $fechaFinal)
 //                ->withCount('productos');
 //        $ventas = $ventas->get();

 //        return $ventas;
 //    }
 //      public function ventas($fechaInicial,$fechaFinal){
 //            $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
 //                ->where('fecha', '<=', $fechaFinal)
 //                ->withCount('productos');
 //        $ventas = $ventas->get();

 //        return $ventas->fecha;
 //    }
 //      public function ventasNombre($fechaInicial,$fechaFinal){
 //            $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
 //                ->where('fecha', '<=', $fechaFinal)
 //                ->withCount('productos');
 //        $ventas = $ventas->get();

 //        return $ventas->paciente->nombre;
 //    }
 //      public function ventasPaterno($fechaInicial,$fechaFinal){
 //            $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
 //                ->where('fecha', '<=', $fechaFinal)
 //                ->withCount('productos');
 //        $ventas = $ventas->get();

 //        return $ventas->paciente->paterno;
 //    }
 //       public function ventasMaterno($fechaInicial,$fechaFinal){
 //            $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
 //                ->where('fecha', '<=', $fechaFinal)
 //                ->withCount('productos');
 //        $ventas = $ventas->get();

 //        return $ventas->paciente->materno;
 //    }
 //      public function ventasCantidad($fechaInicial,$fechaFinal){
 //            $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
 //                ->where('fecha', '<=', $fechaFinal)
 //                ->withCount('productos');
 //        $ventas = $ventas->get();

 //        return $ventas->cantidad_productos;
 //    }

 //    /**
 //    * @return \Illuminate\Support\Collection
 //    */
 //    public function collection()
 //    {
 //        // $ventas = $this->ventas($this->fechaInicial,$this->fechaFinal);
 //        // 
       
 //        return collect(
 //                 [
 //                'fecha'=>$this->ventasFecha($this->fechaInicial,$this->fechaFinal),
 //                'nombre'=>$this->ventasNombre($this->fechaInicial,$this->fechaFinal),
 //                'paterno'=>$this->ventasPaterno($this->fechaInicial,$this->fechaFinal),
 //                'materno'=>$this->ventasMaterno($this->fechaInicial,$this->fechaFinal),
 //                'cantidad_productos'=>$this->ventasCantidad($this->fechaInicial,$this->fechaFinal)
 //                     ]);
        
 //    }
 //    // public function collection()
 //    // {
 //    //     // $ventas = $this->ventas($this->fechaInicial,$this->fechaFinal);
 //    //     // 
       
 //    //     return collect(
 //    //            [
 //    //           'fecha'=>$this->ventas($this->fechaInicial,$this->fechaFinal)->fecha,
 //    //           'nombre'=>$this->ventas($this->fechaInicial,$this->fechaFinal)->paciente->nombre,
 //    //           'paterno'=>$this->ventas($this->fechaInicial,$this->fechaFinal)->paciente->paterno,
 //    //           'materno'=>$this->ventas($this->fechaInicial,$this->fechaFinal)->paciente->materno,
 //    //           'cantidad_productos'=>$this->ventas->cantidad_productos
 //    //                ]);
        
 //    // }

 //    public function headings(): array
 //    {
 //        return [
 //            'fecha',
 //            'nombre',
 //            'paterno',
 //            'materno',
 //            'NÚM. prendas'
 //        ];
 //    }