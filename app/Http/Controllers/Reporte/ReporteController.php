<?php

namespace App\Http\Controllers\Reporte;

use App\Doctor;
use App\Empleado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Oficina;
use App\Paciente;
use App\Producto;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{

    public function uno(Request $request)
    {

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

        $oficinas = Oficina::get();

        $empleadosFitter = Empleado::fitters()->get();

        // dd($empleadosFitter);

        if ($request->input()) {

            // dd($request->input());

            // OBTENEMOS EL RANGO DE FECHAS SOLICITADOS
            $fechaInicial = $request->input('fechaInicial');
            $fechaFinal = $request->input('fechaFinal');


            $ventasPorFechaPorPaciente = Venta::where('fecha', '>=', $fechaInicial)
                ->where('fecha', '<=', $fechaFinal)
                ->withCount('productos');

            if ($request->oficinaId) {
                $ventasPorFechaPorPaciente->where('oficina_id', $request->oficinaId);
            }

            if ($request->empleadoFitterId) {
                $ventasPorFechaPorPaciente->where('empleado_id', $request->empleadoFitterId);
            }


            $ventasPorFechaPorPaciente = $ventasPorFechaPorPaciente->get()
                ->groupBy('paciente_id')
                ->transform(function ($item, $k) {
                    return $item->groupBy(function ($date) {
                        return Carbon::parse($date->fecha)->format('Y-m-d'); // grouping by years
                        //return Carbon::parse($date->created_at)->format('m'); // grouping by months
                    });
                });

            foreach ($ventasPorFechaPorPaciente as $venta) {
                $prendasVendidasPorPaciente =  $venta
                    ->pluck('productos_count')
                    ->flatten()
                    ->sum();
            }

            // return $ventasPorFechaPorPaciente;

            return view('reportes.dos', compact('ventasPorFechaPorPaciente', 'oficinas', 'empleadosFitter'));
        }

        return view('reportes.dos', compact('oficinas', 'empleadosFitter'));
    }

    public function tres(Request $request)
    {
        $oficinas = Oficina::get();
        $empleadosFitter = Empleado::fitters()->get();

        $ventas = null;
        $rangoDias = null;
        $arregloTotalPacientesConUnProducto = array();
        $arregloTotalPacientesConMasDeUnProducto = array();
        $arregloFechasConVentas = array();
        $arregloSumaPacientes = array();

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
                    ->has('productos', '=', 1)
                    ->with('paciente')
                    ->get()
                    ->pluck('paciente')
                    ->flatten();
                $totalPacientesConUnProducto = count($totalPacientesConUnProducto);
                $arregloTotalPacientesConUnProducto[] = $totalPacientesConUnProducto;
            }
            $arregloTotalPacientesConUnProducto = array_values($arregloTotalPacientesConUnProducto);

            // POR CADA FECHA OBTENEMOS A LOS PACIENTES CON MAS DE UN PRODUCTO COMPRADO
            foreach ($arregloFechasConVentas as $key => $fecha) {
                $totalPacientesConMasDeUnProducto = Venta::where('fecha', $fecha)
                    ->has('productos', '>', 1)
                    ->with('paciente')
                    ->get()
                    ->pluck('paciente_id')
                    ->flatten()
                    ->toArray();
                $totalPacientesConMasDeUnProducto = array_unique($totalPacientesConMasDeUnProducto);
                $totalPacientesConUnProducto = count($totalPacientesConMasDeUnProducto);
                $arregloTotalPacientesConMasDeUnProducto[] = $totalPacientesConUnProducto;
            }
            $arregloTotalPacientesConMasDeUnProducto = array_values($arregloTotalPacientesConMasDeUnProducto);

            // dd($arregloFechasConVentas);
            // dd($arregloTotalPacientesConMasDeUnProducto);
            // dd($arregloTotalPacientesConMasDeUnProducto);

            $arregloSumaPacientes[] = array_sum($arregloTotalPacientesConUnProducto);
            $arregloSumaPacientes[] = array_sum($arregloTotalPacientesConMasDeUnProducto);
        }

        return view('reportes.tres', compact('arregloFechasConVentas', 'arregloTotalPacientesConUnProducto', 'arregloTotalPacientesConMasDeUnProducto', 'arregloSumaPacientes', 'oficinas', 'empleadosFitter'));
    }

    public function cuatroa(Request $request)
    {

        $pacientesConCompra = array();
        $totalProductosCompras = 0;
        $rangoFechas = array();

        if ($request->input()) {

            // OBTENEMOS EL PERIODO DE TIEMPO DE BUSQUEDA
            $rangoFechas = array(
                "inicio" => $request->fechaInicial,
                "fin" => $request->fechaFinal
            );

            // OBTENEMOS LOS PACIENTES CON COMPRAS
            $pacientesConCompra = Paciente::whereHas('ventas', function (Builder $query) use ($request) {
                $query->where('fecha', '>=', $request->fechaInicial)
                    ->where('fecha', '<=', $request->fechaFinal);
            })
                ->with('ventas.productos')
                ->get();

            $totalProductosCompras = $pacientesConCompra
                ->pluck('ventas')
                ->flatten()
                ->where('fecha', '>=', $rangoFechas["inicio"])
                ->where('fecha', '<=', $rangoFechas["fin"])
                ->pluck('productos')
                ->flatten()
                ->pluck('pivot')
                ->flatten()
                ->pluck('cantidad')->sum();
        }

        return view('reportes.cuatroa', compact('pacientesConCompra', 'rangoFechas', 'totalProductosCompras'));
    }

    public function cuatrob(Request $request)
    {

        $skusConVentas = array();
        $totalPrendasVendidas = 0;

        if ($request->input()) {

            // SKUS CON VENTAS EN EL INTERVALO SOLICITADO
            $skusConVentas = Producto::with(['ventas' => function ($query) use ($request) {
                return $query->where('fecha', '>=', $request->fechaInicial)
                    ->where('fecha', '<=', $request->fechaFinal);
            }, 'ventas.paciente'])
                ->whereHas('ventas', function (Builder $query) use ($request) {
                    return $query->where('fecha', '>=', $request->fechaInicial)
                        ->where('fecha', '<=', $request->fechaFinal);
                })
                ->get()
                ->groupBy('sku');

            $totalPrendasVendidas = $skusConVentas->flatten()
                ->pluck('ventas')
                ->flatten()
                ->pluck('pivot')
                ->flatten()
                ->pluck('cantidad')
                ->sum();

            // return $skusConVentas->flatten()->pluck('ventas')->flatten()->pluck('pivot');
        }

        return view('reportes.cuatrob', compact('skusConVentas', 'totalPrendasVendidas'));
    }

    public function cuatroc(Request $request)
    {

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

        $anioInicial = null;
        $anioFinal = null;
        $aniosSolicitados = null;
        $productosPorAnio = null;
        $aniosYProductosPorMes = array();
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
                    $productosPorMes[] = count(Venta::whereYear('fecha', $i)->whereMonth('fecha', $key)->get()->pluck('productos')->flatten());
                }

                array_push($aniosYProductosPorMes, array($i => $productosPorMes));
            }
        }

        // dd($aniosYProductosPorMes);

        return view('reportes.cuatrod', compact('anioInicial', 'anioFinal', 'meses', 'aniosSolicitados', 'productosPorAnio', 'aniosYProductosPorMes'));
    }

    public function cinco(Request $request)
    {

        $pacientes = null;
        $anios = [];
        $aniosPacientes = [];
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
                }

                if ($opcion == 'recompra') {
                    $numPacientesPorAnio[] = count(Paciente::whereYear('created_at', $i)->has('ventas')->get());
                }
            }

            // dd($numPacientesPorAnio);
            $meses = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        }

        return view('reportes.cinco', compact('pacientes', 'anios', 'meses', 'opcion', 'numPacientesPorAnio'));
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

            // with('productos')->get()->pluck('productos')->flatten()

            return view('reportes.nueve', compact('ventasPorSku'));
        }

        return view('reportes.nueve');
    }

    public function diez(Request $request)
    {

        $mesesSolicitados = [];

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

            $mesInicio = explode("-", $request->fechaInicial)[1];
            $mesFinal = explode("-", $request->fechaFinal)[1];

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

        return view('reportes.diez', compact('doctores', 'mesesSolicitados', 'mesesString'));
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

            foreach ($pacientes as $paciente) { }
        }

        return view('reportes.pacientes', compact('dataList', 'sku_y_num_pacentes', 'pacientes'));
    }

    public function siete()
    {
        return view('reportes.productos');
    }
}
