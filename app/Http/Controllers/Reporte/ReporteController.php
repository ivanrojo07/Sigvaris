<?php

namespace App\Http\Controllers\Reporte;

use App\Doctor;
use App\Empleado;
use App\Exports\ReporteDosExport;
use App\Exports\ReporteTresExport;
use App\Exports\ReporteCuatroAExport;
use App\Exports\ReporteCuatroBExport;
use App\Exports\ReporteCuatroDExport;
use App\Exports\ReporteCincoExport;
use App\Exports\ReporteDiezExport;
use App\Exports\ReporteFitterExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Oficina;
use App\Paciente;
use App\Producto;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{

    public function uno(Request $request)
    {
          ini_set('max_execution_time', 600);
        // dd($request->input());

        $pacientes_sin_compra = null;
        $fechas_pacientes_sin_compra = null;
        $num_pacientes_por_fecha = null;
        $empleadosFitter = Empleado::fitters()->get();

        $oficinas = Oficina::get();

        if ($request->input()) {

            $fechaInicial = $request->input('fechaInicial');
            $fechaFinal = $request->input('fechaFinal');

            if ($request->empleadoFitterId) {

                $pacientes_sin_compra = Empleado::where('id', $request->empleadoFitterId)
                    ->first()
                    ->pacientes()
                    ->with('ventas')
                    ->whereDoesntHave('ventas', function (Builder $query) use ($request) {
                        return $query->where('fecha', '>=', $request->fechaInicial)
                            ->where('fecha', '<=', $request->fechaFinal);
                    });
            } else {
                $pacientes_sin_compra = Paciente::noCompradores();
            }

            // SI SE SOLICITA UNA OFICINA EN ESPECIFICO, LA BUSCAMOS
            if ($request->oficinaId) {
                // dd($request->oficinaId);
                $pacientes_sin_compra = $pacientes_sin_compra
                    ->where('oficina_id', $request->oficinaId);
            }

            $pacientes_sin_compra = $pacientes_sin_compra->get();

            // ARREGLO DE FECHAS DE LOS PACIENTES QUE NO COMPRARON
            $fechas_pacientes_sin_compra = $pacientes_sin_compra;
            $fechas_pacientes_sin_compra = $fechas_pacientes_sin_compra->pluck('created_at')->flatten();
            $fechas_pacientes_sin_compra->transform(function ($fecha) {
                return $fecha->format('Y-m-d');
            });

            $num_pacientes_por_fecha = $fechas_pacientes_sin_compra->map(function ($fecha, $key) {
                return count(Paciente::noCompradores()->where('created_at', 'LIKE', '%' . $fecha . '%')->get());
            });
        }

        return view('reportes.uno', compact('pacientes_sin_compra', 'fechas_pacientes_sin_compra', 'num_pacientes_por_fecha', 'oficinas', 'empleadosFitter'));
    }

    public function dos(Request $request)
    {
          ini_set('max_execution_time', 600);
        $oficinas = Oficina::get();

        $empleadosFitter = Empleado::fitters()->get();

        // dd($empleadosFitter);

        if ($request->input()) {

            // dd($request->input());

            // OBTENEMOS EL RANGO DE FECHAS SOLICITADOS
            $fechaInicial = $request->input('fechaInicial');
            $fechaFinal = $request->input('fechaFinal');


            $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
                ->where('fecha', '<=', $fechaFinal)
                ->withCount('productos');

            if ($request->oficinaId) {
                $ventas->where('oficina_id', $request->oficinaId);
            }

            if ($request->empleadoFitterId) {
                $ventas->where('empleado_id', $request->empleadoFitterId);
            }


            $ventas = $ventas->get();

            return view('reportes.dos', compact('ventas', 'oficinas', 'empleadosFitter','fechaInicial','fechaFinal'));
        }

        return view('reportes.dos', compact('oficinas', 'empleadosFitter'));
    }

    public function tres(Request $request)
    {
        // dd($request);
          ini_set('max_execution_time', 600);
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
        $fechaInicial = $request->fechaInicial;
        $fechaFinal = $request->fechaFinal;
        $mesIni  = null;
        $anioIni = null ;
        $mesFin  = null ;
        $anioFin = null ;
        $fitter = null;
        $oficina= null;
        $arreglo=[];

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
                    $mesIni  =  $mesInicial;
                    $anioIni = $anioInicial ;
                    $mesFin  = $mesFinal ;
                    $anioFin = $anioFinal;

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
                $filter = $request->empleadoFitterId;
                $arregloFechasConVentas = $arregloFechasConVentas->where('empleado_id', $request->empleadoFitterId);
            }

            if ($request->oficina_id) {
                $odicina = $request->oficina_id;
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
                // dd($arregloFechasConVentas,$arregloTotalPacientesConUnProducto,$arregloTotalPacientesConMasDeUnProducto);

                // array_push($arreglo, $arregloFechasConVentas);
                array_push($arreglo, $arregloFechasConVentas);
                array_push($arreglo, $arregloTotalPacientesConUnProducto);
                array_push($arreglo, $arregloTotalPacientesConMasDeUnProducto);
                // $arreglo =array_push($arreglo, array($totalPacientesConMasDeUnaPrenda));
                // $arreglo =array_push($arreglo, array($arregloSumaPacientes));
                // $arreglo =array_push($arreglo, array($totalPacientesConUnaPrenda));
        }

        // dd( $arregloSumaPacientes );

        return view('reportes.tres', compact('arregloFechasConVentas', 'arregloTotalPacientesConUnProducto', 'arregloTotalPacientesConMasDeUnProducto', 'arregloSumaPacientes', 'totalPacientesConMasDeUnaPrenda', 'totalPacientesConUnaPrenda', 'oficinas','empleadosFitter','fechaInicial','fechaFinal','mesIni','anioIni', 'mesFin','anioFin','fitter','oficina','arreglo'));
    }

    public function cuatroa(Request $request)
    {

        // dd($request->input());
          ini_set('max_execution_time', 600);
        $pacientesConCompra = array();
        $totalProductosCompras = 0;
        $rangoFechas = array();
        $empleadosFitter = Empleado::fitters()->get();
        $oficinas = Oficina::get();
        $arreglo= [] ;
        $fechaIni=$request->fechaInicial;
        $fechaFin=$request->fechaFinal;
        $oficina = $request->oficina_id;
        $fitter=$request->empleadoFitterId;

        if ($request->input()) {

            // dd($request->input());

            // OBTENEMOS EL PERIODO DE TIEMPO DE BUSQUEDA
            $rangoFechas = array(
                "inicio" => $request->fechaInicial,
                "fin" => $request->fechaFinal
            );

            // OBTENEMOS LOS PACIENTES CON COMPRAS
            $pacientesConCompra = Paciente::has('ventas')->whereHas('ventas', function (Builder $query) use ($request) {
                $query->where('fecha', '>=', $request->fechaInicial)
                    ->where('fecha', '<=', $request->fechaFinal);
            });

            if ($request->oficina_id) {
                $pacientesConCompra = $pacientesConCompra->whereHas('ventas', function (Builder $query) use ($request) {
                    $query->where('oficina_id', $request->oficina_id);
                });
            }

            if ($request->empleadoFitterId) {
                $pacientesConCompra = $pacientesConCompra->whereHas('ventas', function (Builder $query) use ($request) {
                    $query->where('empleado_id', $request->empleadoFitterId);
                });
            }

            $pacientesConCompra = $pacientesConCompra->with(['ventas' => function ($query) use ($request) {
                $query->where('fecha', '>=', $request->fechaInicial)
                    ->where('fecha', '<=', $request->fechaFinal);
            }])
                ->get()
                ->filter( function($paciente){
                    return $paciente->ventas
                    ->pluck('productos')->flatten()
                    ->pluck('pivot')->flatten()
                    ->pluck('cantidad')->flatten()
                    ->sum() >= 1;
                } )
                ->unique();

            // return $pacientesConCompra;

            $totalProductosCompras = $pacientesConCompra
                ->pluck('ventas')
                ->flatten()
                ->pluck('productos')
                ->flatten()
                ->pluck('pivot')
                ->flatten()
                ->pluck('cantidad')
                ->sum();
        }
        array_push($arreglo, json_encode($pacientesConCompra));
        array_push($arreglo, $totalProductosCompras);
        // array_push($arreglo, $rangoFechas);
        // array_push($arreglo, $empleadosFitter);
        // array_push($arreglo, $oficinas);

        return view('reportes.cuatroa', compact('pacientesConCompra','totalProductosCompras', 'rangoFechas', 'empleadosFitter', 'oficinas','arreglo','fechaIni','fechaFin','fitter','oficina'));
    }

    public function cuatrob(Request $request)
    {   

             $oficinas = Oficina::get();
          ini_set('max_execution_time', 600);
        $skusConVentas = array();
        $totalPrendasVendidas = 0;
        $VentasPrendas = null;
        $Ventas = null;
        if ($request->input()) {
            // dd($request->oficina_id);
            // SKUS CON VENTAS EN EL INTERVALO SOLICITADO
               if ($request->oficina_id == null) {
               $skusConVentas = Producto::with(['ventas' => function ($query) use ($request) {
                return $query->where('fecha', '>=', $request->fechaInicial)->where('fecha', '<=', $request->fechaFinal);
            }, 'ventas.paciente'])
                ->whereHas('ventas', function (Builder $query) use ($request) {
                    return $query->where('fecha', '>=', $request->fechaInicial)->where('fecha', '<=', $request->fechaFinal);
                })
                ->get()
                ->groupBy('sku');
            }else{

                $skusConVentas = Producto::with(['ventas' => function ($query) use ($request) {
                return $query->where('fecha', '>=', $request->fechaInicial)->where('oficina_id',$request->oficina_id)->where('fecha', '<=', $request->fechaFinal);
            }, 'ventas.paciente'])
                ->whereHas('ventas', function (Builder $query) use ($request) {
                    return $query->where('fecha', '>=', $request->fechaInicial)
                        ->where('fecha', '<=', $request->fechaFinal);
                })
                ->get()
                ->groupBy('sku');
            }
            

            $totalPrendasVendidas = $skusConVentas->flatten()
                ->pluck('ventas')
                ->flatten()
                ->pluck('pivot')
                ->flatten()
                ->pluck('cantidad')
                ->sum();

            // return $skusConVentas->flatten()->pluck('ventas')->flatten()->pluck('pivot');
        }
        $VentasPrendas=$totalPrendasVendidas;
        $Ventas = $skusConVentas;
        if (count($Ventas)==0) {
            $Ventas=null;
        }
        // dd($Ventas);

        return view('reportes.cuatrob', compact('skusConVentas', 'totalPrendasVendidas','Ventas','VentasPrendas','oficinas'));
    }

    public function cuatroc(Request $request)
    {
          ini_set('max_execution_time', 600);
        $meses = null;
        $anios = null;
        $skus = null;
        $arrayMesesYAnios = array();
        $arregloTotalVentasPorMesYAnio = array();

        if ($request->input()) {
            $meses = $request->input('mes');
            $anios = $request->input('anio');

            for ($i = 0; $i < count($meses); $i++) {
                $arrayMesesYAnios[] = $meses[$i] . "-" . $anios[$i];

                $totalVentaPorMesYanio = Venta::whereYear('fecha', $anios[$i])
                    ->whereMonth('fecha', $meses[$i]);

                if ($request->input('sku')) {
                    $sku = $request->input('sku');
                    $totalVentaPorMesYanio = $totalVentaPorMesYanio->with(['productos' => function ($query) use ($sku) {
                        $query->where('sku', $sku);
                    }]);
                } else {
                    $totalVentaPorMesYanio = $totalVentaPorMesYanio->with('productos');
                }

                $arregloTotalVentasPorMesYAnio[] = count(
                    $totalVentaPorMesYanio
                        ->get()
                        ->pluck('productos')
                        ->flatten()
                );
                // $arregloTotalVentasPorMesYAnio[] = count( Venta::whereYear('') );

            }

            // dd($arregloTotalVentasPorMesYAnio);

            if ($request->input('sku')) {
                $skus = array_unique(Producto::where('sku', $sku)->pluck('sku')->toArray());
                // dd($skus);
            } else {
                $skus = array_unique(Producto::pluck('sku')->toArray());
            }
        }

        return view('reportes.cuatroc', compact('meses', 'anios', 'skus', 'arregloTotalVentasPorMesYAnio', 'arrayMesesYAnios'));
    }

    public function cuatrod(Request $request)
    {
        ini_set('max_execution_time', 600);
        $oficinas = Oficina::get();
        $anioInicial = null;
        $anioFinal = null;
        $aniosSolicitados = null;
        $productosPorAnio = null;
        $aniosYProductosPorMes = array();
         $suma_año=[];
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        );

        if ($request->input()) {
            $anioInicial = $request->input('anioInicial');
            $anioFinal = $request->input('anioFinal');

            // ARREGLO DE AÑOS SOLICITADOS
            $aniosSolicitados = [];
            for ($i = $anioInicial; $i <= $anioFinal; $i++) {
                $aniosSolicitados[] = (int) $i;

                $productosPorMes = [];

                foreach ($meses as $key => $mes) {
                    if ($request->oficina_id == null) {
                         $productosPorMes[] = Venta::whereYear('fecha', $i)->whereMonth('fecha', $key)->get()->map(function ($venta) {
                        return $venta->cantidad_productos;
                        });
                     }else{
                        $productosPorMes[] = Venta::whereYear('fecha', $i)->whereMonth('fecha', $key)->where('oficina_id',$request->oficina_id)->get()->map(function ($venta) {
                        return $venta->cantidad_productos;
                        });
                     }
                   
                    
                    // $productosPorMes[] = count(Venta::whereYear('fecha', $i)->whereMonth('fecha', $key)->get()->pluck('productos')->flatten()->pluck('pivot')->flatten()->pluck('cantidad')->flatten());
                }

                // return 'entra';

                // return Venta::whereYear('fecha','2020')->get()->pluck('productos')->flatten()->pluck('pivot')->flatten()->pluck('cantidad')->flatten()->sum();

                array_push($aniosYProductosPorMes, array($i => $productosPorMes));
            }
           
        $ProductosAño=[];

        foreach ($aniosYProductosPorMes as $key => $años) {

            foreach ($años as $key => $meses_) {
              
              // dd($meses);
               $suma_productos=[];
                foreach ($meses_ as $key => $total) {
                    //El total de los productos que se vendieron n°
                    $Sum=0;
                    if (count($total) != 0) {

                        // dd($total,count($total));
                        foreach ($total as $key => $suma) {
                             $Sum+=$suma;
                            //aqui hago la suma de la cantidad por producto y la guardo en el arreglo
                        }
                        array_push($suma_productos , $Sum);
                    }else{
                        //Aqui agrego que tiene 0 productos ese mes
                        array_push($suma_productos, 0);
                    }


                }

                 array_push($suma_año, array($suma_productos));
              
            }
           
              // array_push($ProductosAño, $suma_año);
         
        }
        }

        // dd($aniosYProductosPorMes);

        return view('reportes.cuatrod', compact('anioInicial', 'anioFinal', 'meses', 'aniosSolicitados', 'productosPorAnio', 'aniosYProductosPorMes','suma_año','oficinas'));
    }

    public function cinco(Request $request)
    {
         ini_set('max_execution_time', 600);
        $pacientes = null;
         $oficinas = Oficina::get();
        $anios = [];
        $aniosPacientes = [];
          $numPacientesPrimeraVez = [];
            $numPacientesRecompra = [];
        $meses = [];
        $opcion = null;
        $numPacientesPorAnio = null;

        if ($request->input()) {
            $opcion = $request->input('opciones');
            $anioInicial = $request->input('anioInicial');
            $anioFinal = $request->input('anioFinal');

            // Obtenemos los años validos
            for ($i = $anioInicial; $i <= $anioFinal; $i++) {
                $anios[] = $i;

                if ($opcion == 'primeraVez') {
                    $numPacientesPorAnio[] = count(Paciente::whereYear('created_at', $i)->doesnthave('ventas')->get());
                    $numPacientesRecompra[]=  count(Paciente::whereYear('created_at', $i)->has('ventas')->get());
                }

                if ($opcion == 'recompra') {
                       $numPacientesPrimeraVez[]= count(Paciente::whereYear('created_at', $i)->doesnthave('ventas')->get());;
                    $numPacientesPorAnio[] = count(Paciente::whereYear('created_at', $i)->has('ventas')->get());
                }
            }

            // dd($numPacientesPorAnio);
            $meses = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        }

        return view('reportes.cinco', compact('pacientes', 'anios', 'meses', 'opcion', 'numPacientesPorAnio','numPacientesPrimeraVez','numPacientesRecompra','oficinas'));
    }

    public function nueve(Request $request)
    {

        if ($request->input()) {
            $fechaInicial = $request->input('fechaInicial');
            $fechaFinal = $request->input('fechaFinal');

            $ventasPorSku = Venta::where('fecha', '>=', $fechaInicial)
                ->where('fecha', '<=', $fechaFinal)
                ->with('productos')->get()
                ->pluck('productos')->flatten()->groupBy('sku');

            // dd($ventasPorSku);

            // with('productos')->get()->pluck('productos')->flatten()

            // return $ventasPorSku->flatten()->pluck('pivot')->flatten();

            return view('reportes.nueve', compact('ventasPorSku'));
        }

        return view('reportes.nueve');
    }

    public function diez(Request $request)
    {   
        $año_ini = 2020;
        $año_fin = 2020;
        // dd($request,substr($request->fechaInicial, 0,4),$request->fechaFinal);
         $oficinas = Oficina::get();

           ini_set('max_execution_time', 900);

        $mesesSolicitados = [];
        $añosSolicitados =[];

        $mesesString = array(
            "01" => "Enero",
            "02" => "Febrero",
            "03" => "Marzo",
            "04" => "Abril",
            "05" => "Mayo",
            "06" => "Junio",
            "07" => "Julio",
            "08" => "Agosto",
            "09" => "Septiembre",
            "10" => "Octubre",
            "11" => "Noviembre",
            "12" => "Diciembre"
        );
        $doctores = collect();

        if ($request->input()) {
            $año_ini = substr($request->fechaInicial, 0,4);
          $año_fin = substr($request->fechaFinal, 0,4);
            $mesInicio = explode("-", $request->fechaInicial)[1];
            $mesFinal = explode("-", $request->fechaFinal)[1];
            array_push($añosSolicitados, $año_ini);
            array_push($añosSolicitados, $año_fin);
            for ($i = $mesInicio; $i <= $mesFinal; $i++) {

                $numMes = (int) $i;
                // dd($numMes);

                if ($i < 10) {
                    $mesesSolicitados[] = "0" . $numMes;
                } else {
                    $mesesSolicitados[] = (string) $numMes;
                }
            }

            // dd($mesesSolicitados);

            $doctores = Doctor::get();
        }

        return view('reportes.diez', compact('doctores', 'mesesSolicitados', 'mesesString','año_ini','año_fin','añosSolicitados','oficinas'));
    }

    public function once()
    {
        return "reporte 11";
    }

    public function dosAnt(Request $request)
    {

        $pacientes = array();
        $ventas = array();

        if ($request->input()) {
            $fechaInicial = $request->input('fecha_inicial');
            $fechaFinal = $request->input('fecha_final');
            $pacientes = Paciente::get();
            $ventas = Venta::where('fecha', '>=', $fechaInicial)->where('fecha', '<=', $fechaFinal)->get();
            // dd($ventas);


        }

        return view('reportes.prendas', compact('pacientes', 'ventas'));
    }

    public function cuatro(Request $request)
    {

        $fechaInicial = $request->input('fecha_inicial');
        $fechaFinal = $request->input('fecha_final');
        // dd($fechaInicial);

        $dataList = array();
        $sku_y_num_pacentes = array();
        $pacientes = array();
        $fecha_y_paciente = array();

        if ($request->input('categorias') == 'prendas') {
            // dd($request->input());
            $pacientes = Paciente::get();
            $arreglo = array();
            foreach ($pacientes as $paciente) {
                $arreglo = array_merge($arreglo, array($paciente->id => $paciente->totalProductos()));
            }
            $dataList = array_count_values($arreglo);
            // dd($dataList);
        }

        if ($request->input('categorias') == 'sku') {

            $skus = DB::table('productos')
                ->select('sku')
                ->groupBy('sku')
                ->get();

            // $arreglo_pacientes_id = array();

            foreach ($skus as $sku) {
                $productos = Producto::where('sku', $sku->sku)->get();
                $num_pacientes_de_sku = [];

                foreach ($productos as $producto) {
                    $ventas = $producto->ventas()->get();

                    foreach ($ventas as $venta) {
                        $num_pacientes_de_sku[] = $venta->paciente_id;
                    }
                    // dd($num_pacientes_de_sku);
                }

                $num_pacientes_de_sku = array_unique($num_pacientes_de_sku);
                $nuevo_array = array($sku->sku => count($num_pacientes_de_sku));
                $sku_y_num_pacentes = array_merge($sku_y_num_pacentes, $nuevo_array);
            }
            // dd($sku_y_num_pacentes);
        }

        if ($request->input('categorias') == 'primeraVez') {
            $pacientes = Paciente::noCompradores()->where('created_at', '>=', $fechaInicial)->where('created_at', '<=', $fechaFinal)->get();

            return $pacientes;

            foreach ($pacientes as $paciente) {
            }
        }

        return view('reportes.pacientes', compact('dataList', 'sku_y_num_pacentes', 'pacientes'));
    }

    public function siete()
    {
        return view('reportes.productos');
    }

    public function cortecaja(Request $request){


        if ($request->fecha!=null) {
            # code...
            echo "La fecha es: ".$request->fecha;
        }else{
         return view('reportes.cortecaja');
        }
    }

    /**
     * Obtiene las ventas que ha realizado un fitter en un rango de fechas si se envia un request,
     * en otro caso solo muestra la vista con los campos para hacer la busqueda.
     * 
     * @param Request request 
     * 
     * @return Vista con los datos de ventas y metas del fitter
     */
    public function reporteVentasfitter(Request $request)
    {
        // dd($request->empleadoFitterId==0);
        $oficinas = Oficina::get();
          ini_set('max_execution_time', 600);

        $empleadosFitter = Empleado::fitters()->get();
        $fitter = null;
        $fecha_ini= null;
        $fecha_fin= null;
        // Se usa datosVentasMes para guardar los datos de ventas
        // de un fitter en un rango de fechas de un mes.
        $datosVentasMes = [];
        // dd($empleadosFitter);
          // if ($request->empleadoFitterId==0) {
          //   $fechaInicial = Carbon::createFromFormat('Y-m-d','2016-01-01');
          //      $fechaFinal = Carbon::now();
          //       // $fechaFinal   = Carbon::createFromFormat('Y-m-d', $ahora );
          //      // dd($fechaInicial->format('Y-m'),$ahora->format('Y-m') );
          //       foreach ($empleadosFitter as $key => $value) {
          //           $fitter = Empleado::findOrFail($value->id);
          //           if ($fitter->id != 4) {
          //               array_push($datosVentasMes, $this->getDatosVentaFitterXMes($fechaInicial, $fechaFinal, $fitter, $request));
          //           }
                    
                     
                   
          //       }
          //        dd($datosVentasMes);
          //   }

        if ($request->input()) {
            // OBTENEMOS EL RANGO DE FECHAS SOLICITADOS

            $fechaInicial = Carbon::createFromFormat('Y-m-d', $request->input('fechaInicial') . '-01');
            $fechaFinal   = Carbon::createFromFormat('Y-m-d', $request->input('fechaFinal') . '-01');
            $fecha_ini=$fechaInicial;
            $fecha_fin= $fechaFinal;
            // dd( $request->input('fechaInicial'),$request->input('fechaFinal'));
            
        // dd($request->empleadoFitterId==0);
      
         

        $datosVentasMesFitters = [];
        // dd($empleadosFitter);
          if ($request->empleadoFitterId==0) {
             ini_set('max_execution_time', 600);
            // $fechaInicial = Carbon::createFromFormat('Y-m-d','2016-01-01');
            //    $fechaFinal = Carbon::now();
                // $fechaFinal   = Carbon::createFromFormat('Y-m-d', $ahora );
               // dd($fechaInicial->format('Y-m'),$ahora->format('Y-m') );
                foreach ($empleadosFitter as $key => $value) {
                    $fitter = Empleado::findOrFail($value->id);
                    // if ($fitter->id != 4) {
                        array_push($datosVentasMesFitters, $this->getDatosVentaFitterXMes($fechaInicial, $fechaFinal, $fitter, $request));
                    // }
                    
                     
                   
                }
                // array_push($datosVentasMesFitters, $datosVentasMes);
                 // dd($datosVentasMes);
                 return view('reportes.metasfitter', compact('datosVentasMesFitters','oficinas', 'empleadosFitter', 'datosVentasMes', 'fitter','fecha_ini','fecha_fin'));
            }

            $difAnio = $fechaFinal->year - $fechaInicial->year;
            $difMes  = $fechaFinal->month - $fechaInicial->month;
            $fechaInOneMes = $difAnio === 0 && $difMes === 0 ? true : false;
            $fitter = Empleado::findOrFail($request->empleadoFitterId);
            $fitter = Empleado::findOrFail($request->empleadoFitterId);
          

            if ($request->pleadoFitterId && $fechaInOneMes) {
                $datosVentasMes = $this->getDatosVentaFitterXMes($fechaInicial, $fechaFinal, $fitter, $request);
            } else if ($request->empleadoFitterId && !$fechaInOneMes) {
                // Rango de fechas en mas de un mes se genera por meses la informacion
                $datosVentasMes = $this->getDatosVentaFitterMeses($fechaInicial, $fechaFinal, $fitter, $request);
            } else {
                $pacientes_sin_compra = Paciente::noCompradores();
            }
        }

        return view('reportes.metasfitter', compact('oficinas', 'empleadosFitter', 'datosVentasMes', 'fitter','fecha_ini','fecha_fin'));
    }

    /**
     * Obtiene las ventas de un fitter con los datos de meta, valor y porcentaje para
     * monto de ventas, pacientes que compran más de una prenda y pacientes de recompra.
     * 
     * @param date - $fechaInicial: de la busqueda
     * @param date - $fechaFinal: fecha maxima de busqueda
     * @param Empleado - $fitter: empleado del que se obtendran los datos
     * @param Request - $request: datos del formulario para la busqueda
     * 
     * @return Array - datosVentasMes 
     */
    private function getDatosVentaFitterXMes($fechaInicial, $fechaFinal, $fitter, $request)
    {

        // Rango de fecha en el mismo mes
        $datosVentasMes = ["montoVenta" => [], "pacientes" => [], "recompras" => [], "totales" => []];
        $fechaFinal = $fechaFinal->endOfMonth();

        // OBTENEMOS SUS VENTAS
        $ventasfitter = $fitter->ventas()
            ->whereBetween('fecha', [$fechaInicial->toDateString(), $fechaFinal->toDateString()])
            ->get();

        $metaFitter  = $fitter->fitterMetas()
            ->whereBetween('fecha_inicio', [$fechaInicial->toDateString(), $fechaFinal->toDateString()])
            ->get()->last();
            // dd();
            // $monto = collect($metaFitter->monto_venta);
        foreach ($ventasfitter as $venta) {
            // dd($venta);
            $datosVentasMes["montoVenta"][] = [
                "meta"       => $metaFitter->monto_venta,
                "valor"      => $venta->total,
                "porcentaje" => (($venta->total * 100) / $metaFitter->monto_venta)
                // "porcentaje" => (($venta->total * 100) /100)
            ];
            // if (!isset(  $venta->productos   )   ) {
            //     # code...
            //     # 
            //     dd($venta->productos,$venta,$datosVentasMes);
            // }
                // dd($venta->productos,$venta->productos->count());
            // OBTENEMOS SI EN UNA VENTA SE COMPRA MAS DE UNA PRENDA
            if ($venta->productos != null) 
            {        if ($venta->productos->count() > 1 && $venta->productos != null) {
                            $datosVentasMes["pacientes"][] = [
                                "meta"  => $metaFitter ? $metaFitter->num_pacientes_recompra : 0,
                                // "meta"  => $metaFitter ? 1 : 1,
                                "valor" => 1,
                                // TODO: ver si ese valor de 1 esta bien
                                "porcentaje" => ((100) / $metaFitter->num_pacientes_recompra)
                                 // "porcentaje" => ((100) /100)
                            ];
                        } else {
                            $datosVentasMes["pacientes"][] = [
                                "meta" => $metaFitter ? $metaFitter->num_pacientes_recompra : 0,
                                // "meta" => $metaFitter ? 1 : 1,
                                "valor" => "-",
                                "porcentaje" => "-"
                            ];
                        }}
            // if (!isset(  $venta->paciente->ventas   )   ) {
            //     # code...
            //     # 
            //     dd($venta->paciente,$venta,$datosVentasMes);
            // }
            if($venta->paciente != null)
             {  if ($venta->paciente->ventas->count() > 1 && $venta->paciente != null) {
                          $datosVentasMes["recompras"][] = [
                              "meta" => $metaFitter ? $metaFitter->numero_recompras : 0,
                                // "meta" => $metaFitter ? 1 : 1,
                              "valor" => 1,
                              "porcentaje" => ((100) / $metaFitter->numero_recompras)
                               // "porcentaje" => ((100) / 100)
                          ];
                      } else {
                          $datosVentasMes["recompras"][] = [
                              "meta" => $metaFitter ? $metaFitter->numero_recompras : 0,
                               // "meta" => $metaFitter ? 1 : 1,
                              "valor" => "-",
                              "porcentaje" => "-"
                          ];
                      }
                  }



        }

        $sumValor = 0;
        $sumPorcentaje = 0;
        foreach ($datosVentasMes["montoVenta"] as $key => $fila) {
            if ($fila["valor"] != "-") {
                $sumValor += $fila["valor"];
                $sumPorcentaje += $fila["porcentaje"];
            }
        }
        $datosVentasMes["totales"]["montoVenta"] = ["valor" => $sumValor, "porcentaje" => $sumPorcentaje];

        $sumValor = 0;
        $sumPorcentaje = 0;
        foreach ($datosVentasMes["pacientes"] as $key => $fila) {
            if ($fila["valor"] != "-") {
                $sumValor += $fila["valor"];
                $sumPorcentaje += $fila["porcentaje"];
            }
        }
        $datosVentasMes["totales"]["pacientes"] = ["valor" => $sumValor, "porcentaje" => $sumPorcentaje];

        $sumValor = 0;
        $sumPorcentaje = 0;
        foreach ($datosVentasMes["recompras"] as $key => $fila) {
            if ($fila["valor"] != "-") {
                $sumValor += $fila["valor"];
                $sumPorcentaje += $fila["porcentaje"];
            }
        }
        $datosVentasMes["totales"]["recompras"] = ["valor" => $sumValor, "porcentaje" => $sumPorcentaje];
        return $datosVentasMes;
    }

    /**
     * Obtiene las ventas de un fitter en un rango de fechas de mas de un mes para 
     * el monto de venta, pacientes con compra mayor a una prenda y pacientes de 
     * recompra, asi como el total de cada uno de los campos.
     * 
     * @param date - $fechaInicial: de la busqueda
     * @param date - $fechaFinal: fecha maxima de busqueda
     * @param Empleado - $fitter: empleado del que se obtendran los datos
     * @param Request - $request: datos del formulario para la busqueda
     * 
     * @return Array - datosVentasMes 
     */
    private function getDatosVentaFitterMeses($fechaInicial, $fechaFinal, $fitter, $request)
    {
        setlocale(LC_ALL, 'es_ES');
        $mes = $fechaInicial->formatLocalized('%B'); // mes en idioma español        

        $datosVentasMes = [];
        $fechaFinal = $fechaFinal->endOfMonth();

        while ($fechaInicial->lessThanOrEqualTo($fechaFinal)) {
            $datosMes = $this->getDatosVentaFitterXMes($fechaInicial, $fechaFinal, $fitter, $request);

            if ($datosMes["totales"]["montoVenta"]["valor"] !== 0) {
                $datosVentasMes[] = [
                    "mes"   => ucfirst($fechaInicial->formatLocalized('%B')),
                    "metas" => [
                        "montoVenta" => $datosMes["montoVenta"][0]["meta"],
                        "pacientes"  => $datosMes["pacientes"][0]["meta"],
                        "recompras"  => $datosMes["recompras"][0]["meta"],
                    ],
                    $datosMes["totales"],
                ];
            }

            $fechaInicial->addMonth();
        }

        $sumMonto = 0;
        $sumPacientes = 0;
        $sumRecompras = 0;
        $sumMetas = [0, 0, 0];

        foreach ($datosVentasMes as $row) {
            $sumMonto += $row[0]["montoVenta"]["valor"];
            $sumPacientes += $row[0]["pacientes"]["valor"];
            $sumRecompras += $row[0]["recompras"]["valor"];
            $sumMetas[0] += $row["metas"]["montoVenta"];
            $sumMetas[1] += $row["metas"]["pacientes"];
            $sumMetas[2] += $row["metas"]["recompras"];
        }

        $datosVentasMes["totales"] = [
            "montoVenta" => [$sumMetas[0], $sumMonto, (($sumMonto * 100) / $sumMetas[0])],
            "pacientes"  => [$sumMetas[1], $sumPacientes, (($sumPacientes * 100) / $sumMetas[1])],
            "recompras"  => [$sumMetas[2], $sumRecompras, (($sumRecompras * 100) / $sumMetas[2])]
        ];
        return $datosVentasMes;
    }


    public function exportdos(Request $request){
        ini_set('max_execution_time', 600);
        $fechaInicial =$request->fechaInicial;
        $fechaFinal =$request->fechaFinal;
        $ventas = Venta::has('paciente')->has('productos')->where('fecha', '>=', $fechaInicial)
                ->where('fecha', '<=', $fechaFinal)
                ->withCount('productos');
        $ventas = $ventas->get();
      

        return Excel::download(new ReporteDosExport($fechaInicial,$fechaFinal,$ventas), 'Prendas vendidas por paciente.xlsx');
   
        

    }
     public function exportTres(Request $request){

        // dd($request->arreglo);
        ini_set('max_execution_time', 600);
        return Excel::download(new ReporteTresExport($request->arreglo), 'Prendas vendidas por rango de fecha.xlsx');

       
    }

    public function exportCuatroA(Request $request){

          
        ini_set('max_execution_time', 600);
        return Excel::download(new ReporteCuatroAExport($request->arreglo,$request->totalProductosCompras1), '% prendas compradas x paciente.xlsx');

       
    }
    public function exportCuatroB(Request $request){

        // dd($request->arreglo);
        ini_set('max_execution_time', 600);
        return Excel::download(new ReporteCuatroBExport($request->Ventas,$request->VentasPrendas), 'Prendas por SKU.xlsx');

       
    }
        public function exportCuatroD(Request $request){

        // dd($request);
        ini_set('max_execution_time', 600);
        $meses = json_decode($request->meses_);
        // dd($meses);
     
        return Excel::download(new ReporteCuatroDExport($request->anio_ini,$request->anio_fin,$meses,$request), 'Total prendas vendidas por año.xlsx');

      
    }

        public function exportCinco(Request $request){

        // dd($request->pacientes_);
        // $meses = json_decode($request->meses_);
        // dd($meses);
     
        return Excel::download(new ReporteCincoExport($request->pacientes_,$request->anios_,$request->meses_,$request->numPacientesPorAnio,$request->opcion), 'Pacientes nuevos y recompra.xlsx');

       
    }

     public function exportDiez(Request $request){

        // dd($request);
        // $meses = json_decode($request->meses_);0
        // 
        // dd($meses);
        ini_set('max_execution_time', 600);
     
        return Excel::download(new ReporteDiezExport($request->mesesString,$request->doctores,$request->mesesSolicitados), 'Pacientes por medico.xlsx');

       
    }
    public function exportFitter(Request $request){

          $oficinas = Oficina::get();

        $empleadosFitter = Empleado::fitters()->get();
        $fitter = null;
        // $fecha_ini= null;
        // $fecha_fin= null;
        // Se usa datosVentasMes para guardar los datos de ventas
        // de un fitter en un rango de fechas de un mes.
        $datosVentasMes = [];
            
        // if ($request->input()) {
        //     // OBTENEMOS EL RANGO DE FECHAS SOLICITADOS

            $fechaInicial = $request->fecha_ini;
            $fechaFinal   = $request->fecha_fin;
            // substr($fechaInicial, 0,7);
        //     // $fecha_ini=$fechaInicial;
        //     // $fecha_fin= $fechaFinal;

        //     // $difAnio = $fechaFinal->year - $fechaInicial->year;
        //     // $difMes  = $fechaFinal->month - $fechaInicial->month;
        //     // $fechaInOneMes = $difAnio === 0 && $difMes === 0 ? true : false;
        //     
                 $uno = substr($fechaInicial, 0,7);
                 $dos =  substr($fechaFinal, 0,7);

                 // dd($uno,$dos,$request);
            // $fitter = Empleado::findOrFail();
          $fechaInicial = Carbon::createFromFormat('Y-m-d', $uno.'-01');
            $fechaFinal   = Carbon::createFromFormat('Y-m-d',$dos.'-01');
            $fitteer = json_decode($request->fitter_);
               $fitter = Empleado::findOrFail($fitteer->id);
             // dd($request,$datosVentasMes,$fitter);
        //     if ($request->pleadoFitterId && $fechaInOneMes) {
        //         $datosVentasMes = $this->getDatosVentaFitterXMes($fechaInicial, $fechaFinal, $fitter, $request);
        //     } else if ($request->empleadoFitterId && !$fechaInOneMes) {
        //         // Rango de fechas en mas de un mes se genera por meses la informacion
                // $datosVentasMes = $this->getDatosVentaFitterMeses($fechaInicial, $fechaFinal, $fitter, $request);
        //         dd($request,$datosVentasMes);
        //     } else {
        //         $pacientes_sin_compra = Paciente::noCompradores();
        //         dd($request,$datosVentasMes);
        //     }
        // }
                // dd($datosVentasMes);
        // ini_set('max_execution_time', 600);
     
        return Excel::download(new ReporteFitterExport($request->ventasMes,$datosVentasMes), 'Ventas de fitter.xlsx');

       
    }









}
