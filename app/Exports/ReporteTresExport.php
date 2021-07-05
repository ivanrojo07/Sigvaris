<?php

namespace App\Exports;
use App\Venta;
use App\Oficina;
use App\Paciente;
use App\Producto;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReporteTresExport implements FromView
    {

        public function __construct($request){ 
         $this->request = $request;
        
            }
	    public function view(): View
            {


        $oficinas = Oficina::get();
        $empleadosFitter = Empleado::fitters()->get();

        $ventas = null;
        $rangoDias = null;
        $arregloTotalPacientesConUnProducto = array();
        $arregloTotalPacientesConMasDeUnProducto = array();
        $arregloFechasConVentas = array();
        $arregloSumaPacientes = array();
        $totalPacientesConMasDeUnaPrenda = 0;
        $totalPacientesConUnaPrenda = 0;


        if ($request->input()) {

            if ($request->opcionBusqueda == 'dia') {
                // OBTENEMOS EL DÍA DE INICIO Y DÍA DE FIN
                $fechaInicial = $request->input('fechaInicial');
                $fechaFinal = $request->input('fechaFinal');

                // OBTENEMOS ARREGLO DE LAS FECHAS CON VENTAS
                $arregloFechasConVentas = Venta::where('fecha', '>=', $fechaInicial)
                    ->where('fecha', '<=', $fechaFinal);
            } else if ($request->opcionBusqueda == "semana") {

                $fechaInicial = Carbon::parse($request->fechaInicial)->startOfWeek()->toDateString();
                $fechaFinal = Carbon::parse($request->fechaFinal)->endOfWeek()->toDateString();

                $arregloFechasConVentas = Venta::where('fecha', '>=', $fechaInicial)
                    ->where('fecha', '<=', $fechaFinal);
            } else if ($request->opcionBusqueda == 'mes') {
                $mesInicial = explode("-", $request->mesInicial)[1];
                $anioInicial = explode("-", $request->mesInicial)[0];
                $mesFinal = explode("-", $request->mesFinal)[1];
                $anioFinal = explode("-", $request->mesFinal)[0];

                $arregloFechasConVentas = Venta::whereYear('fecha', '>=', $anioInicial)
                    ->whereYear('fecha', '<=', $anioFinal)
                    ->whereMonth('fecha', '>=', $mesInicial)
                    ->whereMonth('fecha', '<=', $mesFinal);
            } else if ($request->opcionBusqueda == 'trimestre') {

                $mesInicial = explode("-", $request->mesInicial)[1];
                $anioInicial = explode("-", $request->mesInicial)[0];
                $fechaFinal = Carbon::parse($anioInicial . "-" . $mesInicial . "-01")->addMonths(3)->format('Y-m-d');
                $mesFinal = explode("-", $fechaFinal)[1];
                $anioFinal = explode("-", $fechaFinal)[0];

                $arregloFechasConVentas = Venta::whereYear('fecha', '>=', $anioInicial)
                    ->whereYear('fecha', '<=', $anioFinal)
                    ->whereMonth('fecha', '>=', $mesInicial)
                    ->whereMonth('fecha', '<=', $mesFinal);
            }

            if ($request->empleadoFitterId) {
                $arregloFechasConVentas = $arregloFechasConVentas->where('empleado_id', $request->empleadoFitterId);
            }

            if ($request->oficina_id) {
                $arregloFechasConVentas = $arregloFechasConVentas
                    ->where('oficina_id', $request->oficina_id);
            }

            $arregloFechasConVentas = $arregloFechasConVentas->orderBy('fecha')
                ->pluck('fecha')
                ->all();

            $arregloFechasConVentas = array_unique($arregloFechasConVentas);
            $arregloFechasConVentas = array_values($arregloFechasConVentas);

            // dd($arregloFechasConVentas);

            // POR CADA FECHA OBTENEMOS A LOS PACIENTES CON UN PRODUCTO COMPRADO
            foreach ($arregloFechasConVentas as $key => $fecha) {
                $totalPacientesConUnProducto = Venta::where('fecha', $fecha)
                    ->with('paciente')
                    ->get()
                    ->filter(function ($venta) {
                        return $venta->cantidad_productos == 1;
                    })
                    ->pluck('paciente')
                    ->unique()
                    ->flatten();
                // dd($totalPacientesConUnProducto);
                $totalPacientesConUnProducto = count($totalPacientesConUnProducto);
                $arregloTotalPacientesConUnProducto[] = $totalPacientesConUnProducto;
            }


            // $arregloTotalPacientesConUnProducto = array_values($arregloTotalPacientesConUnProducto);
            // dd( $arregloTotalPacientesConUnProducto );

            // POR CADA FECHA OBTENEMOS A LOS PACIENTES CON MAS DE UN PRODUCTO COMPRADO
            foreach ($arregloFechasConVentas as $key => $fecha) {
                $totalPacientesConMasDeUnProducto = Venta::where('fecha', $fecha)
                    // ->has('productos')
                    ->with('paciente')
                    ->get()
                    ->filter(function ($venta) {
                        return $venta->cantidad_productos > 1;
                    })
                    ->pluck('paciente_id')
                    ->flatten()
                    ->unique()
                    ->toArray();
                // dd($totalPacientesConMasDeUnProducto);
                $totalPacientesConMasDeUnProducto = array_unique($totalPacientesConMasDeUnProducto);
                $totalPacientesConUnProducto = count($totalPacientesConMasDeUnProducto);
                $arregloTotalPacientesConMasDeUnProducto[] = $totalPacientesConUnProducto;
            }
            $arregloTotalPacientesConMasDeUnProducto = array_values($arregloTotalPacientesConMasDeUnProducto);

            // dd($arregloFechasConVentas);
            // dd($arregloTotalPacientesConMasDeUnProducto);
            // dd($arregloTotalPacientesConMasDeUnProducto);

            // dd( $arregloFechasConVentas );

            $totalPacientesConMasDeUnaPrenda = Paciente::with(['ventas' => function ($query) use ($arregloFechasConVentas) {
                return $query->whereIn('fecha', $arregloFechasConVentas);
            }])
            ->get()
            ->filter( function($paciente){
                return $paciente->ventas->
                    pluck('productos')->flatten()->
                    pluck('pivot')->flatten()->
                    pluck('cantidad')->flatten()->
                    sum() > 1;
            } )
            ->unique()
            ->count();

            $totalPacientesConUnaPrenda = Paciente::with(['ventas' => function ($query) use ($arregloFechasConVentas) {
                return $query->whereIn('fecha', $arregloFechasConVentas);
            }])
            ->get()
            ->filter( function($paciente){
                return $paciente->ventas->
                    pluck('productos')->flatten()->
                    pluck('pivot')->flatten()->
                    pluck('cantidad')->flatten()->
                    sum() == 1;
            } )
            ->unique()
            ->count();

            $arregloSumaPacientes[] = array_sum($arregloTotalPacientesConUnProducto);
            $arregloSumaPacientes[] = array_sum($arregloTotalPacientesConMasDeUnProducto);
        }

        dd( $arregloSumaPacientes );

        // return view('reportes.tres', compact('arregloFechasConVentas', 'arregloTotalPacientesConUnProducto', 'arregloTotalPacientesConMasDeUnProducto', 'arregloSumaPacientes', 'totalPacientesConMasDeUnaPrenda', 'totalPacientesConUnaPrenda', 'oficinas', 'empleadosFitter'));

        return view('exports.reporteTres', [
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