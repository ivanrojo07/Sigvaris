<?php

namespace App\Http\Controllers\Venta;
use Carbon\Carbon;
use App\Venta;
use App\Paciente;
use App\Producto;
use App\Descuento;
use App\Promocion;
use App\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicos = Doctor::get();
        return view('venta.index_all', ['ventas'=>Venta::get(), 'medicos' =>$medicos]);
    }

    public function indexConPaciente(Paciente $paciente){
        return view('venta.index', ['ventas'=>$paciente->ventas,'paciente'=>$paciente]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hoy=Carbon::now()->toDateString();
        $descuentos = Descuento::where('inicio','<=',$hoy)->where('fin','>=',$hoy)->get();
        $productos = Producto::get();
        $pacientes = Paciente::get();
        return view('venta.create', ['pacientes'=>$pacientes, 'paciente'=>null, 'descuentos'=>$descuentos ,'productos'=>$productos,'folio'=>Venta::count()+1]);
    }

    public function createConPaciente(Paciente $paciente){
        $descuentos = Descuento::get();
        $productos = Producto::get();
        $pacientes = Paciente::get();
        return view('venta.create', ['pacientes'=>$pacientes, 'paciente'=>$paciente, 'descuentos'=>$descuentos, 'productos'=>$productos,'folio'=>Venta::count()+1]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $venta = new Venta($request->all());
        $venta->promocion_id=$request->promo_id;
        if(session()->get('oficina'))
            $venta->oficina_id=session()->get('oficina');
        $venta->save();
        for($i = 0; $i < sizeof($request->cantidad); $i++){
            $producto = Producto::find($request->producto_id[$i]);
            $venta->productos()->attach($producto->id, ['cantidad'=>$request->cantidad[$i], 'precio' =>$producto->precio_publico, 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d') ]);
        }
        return redirect()->route('ventas.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        //dd($venta->productos);
        return view('venta.show', ['venta'=>$venta]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        return view('venta.edit', ['venta'=>$venta]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        $venta->update($request->all());
        return view('venta.show',['venta'=>$venta]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index');
    }

    public function getVentas(Request $request)
    {
        $prod = [];
        $ventasxprenda = [];
        // Obtencion de las ventas por numero de piezas
        if($request->num_prendas != "" && $request->num_prendas != "0"){
            $ventas = Venta::with('paciente','descuento')->where('fecha','<=',$request->hasta)->where('fecha','>=',$request->desde)->get();
            foreach ($ventas as $v) {
                if($v->productos->count() == $request->num_prendas)
                    $ventasxprenda[] = $v;
            }
            $ventas = [];
            foreach ($ventasxprenda as $v)
                $ventas[] = $v;
        }
        else
            $ventas = Venta::with('paciente','descuento')->where('fecha','<=',$request->hasta)->where('fecha','>=',$request->desde)->get();

        // Obtención de Las ventas que contengan la prenda o prendas que se introdujeron en el campo prenda
        $arr = [];
        if($request->prenda != ""){
            $query = $request->prenda;
            $wordsquery = explode(' ',$query);
            $total_ventas = Venta::where('fecha','<=',$request->hasta)->where('fecha','>=',$request->desde)->get();
            foreach ($total_ventas as $venta) {
                $productos = $venta->productos()->where(function($q) use($wordsquery){
                    foreach ($wordsquery as $word) {
                        $q->orWhere('sku', 'LIKE', "%$word%")
                            ->orWhere('descripcion', 'LIKE', "%$word%")
                            ->orWhere('line', 'LIKE', "%$word%")
                            ->orWhere('upc', 'LIKE', "%$word%")
                            ->orWhere('precio_publico', 'LIKE', "%$word%")
                            ->orWhere('swiss_id', 'LIKE', "%$word%");
                    }
                })->get();
                if ($productos->count() != 0) 
                    $arr[] = $venta;
            }
            //dd($arr);
        }

        // Combinar las ventas de acuerdo a las dos busquedas anteriores
        $ventas_final = [];
        foreach ($ventas as $venta) {
            if (count($arr) != 0) {
                foreach ($arr as $v) {
                    if ($venta->id == $v->id) {
                        $ventas_final[] = $venta;
                    }
                }
            }
            else
                $ventas_final[] = $venta;
        }

        // Obtencion de las prendas MAS o MENOS vendidas
        if($request->mas != "")
            $consulta = DB::select("SELECT producto_id, SUM(cantidad) AS TotalVentas FROM producto_venta GROUP BY producto_id ORDER BY SUM(cantidad) DESC LIMIT 0 , 30 ");
        elseif($request->menos != "")
            $consulta = DB::select("SELECT producto_id, SUM(cantidad) AS TotalVentas FROM producto_venta GROUP BY producto_id ORDER BY SUM(cantidad) LIMIT 0 , 100 ");
        else
            $consulta = [];
        foreach ($consulta as $productos) {
            $prod[] = ["0" => Producto::find($productos->producto_id), "1" => $productos->TotalVentas];
        }
        return response()->json(["ventas" => $ventas_final, "consulta" => $prod]);
    }

    public function getVentasClientes(Request $request)
    {
        if($request->tipo == "primero"){
            $consulta = DB::select("SELECT paciente_id FROM ventas GROUP BY paciente_id HAVING COUNT(*) = 1 ");
        }
        elseif ($request->tipo == "consecutivo") {
            $consulta = DB::select("SELECT paciente_id FROM ventas GROUP BY paciente_id HAVING COUNT(*) > 1 ");
        }
        else{
            $consulta = [];
        }

        $ventas = [];
        foreach ($consulta as $paciente) {
            if ($request->desde && $request->hasta) {
                $ventastemp = Venta::where('paciente_id', $paciente->paciente_id)
                    ->where('fecha','<=',$request->hasta)->where('fecha','>=',$request->desde)
                    ->get();
            }
            else
                $ventastemp = Venta::where('paciente_id', $paciente->paciente_id)->get();
            
            foreach ($ventastemp as $v) {
                $cantidad = 0;
                foreach ($v->productos as $prod) {
                    $cantidad += $prod->pivot->cantidad;
                }
                $ventas[] = ['venta' =>$v, 'cantidad' => $cantidad];
            }
        }
        $suma_ventas = 0;
        $sumatoria_pacientes=[];
        foreach ($ventas as $vent) {
            $suma_ventas += $vent['venta']->total;
            $val=1;
            foreach ($sumatoria_pacientes as $p) {
                if($p==$vent['venta']->paciente->id)
                    $val = 0;
            }
            if($val)
                array_push($sumatoria_pacientes,$vent['venta']->paciente->id);
        }
        $totalClientes = count($sumatoria_pacientes);
        return response()->json(["ventas" => $ventas, 'total' => $suma_ventas, 'suma_pacientes' => $totalClientes]);
        

    }
}


//SELECT `producto_venta`.`producto_id`, SUM(`producto_venta`.`cantidad`) AS TotalVentas FROM `producto_venta` GROUP BY `producto_venta`.`producto_id` ORDER BY SUM(`producto_venta`.`cantidad`) DESC LIMIT 0 , 30 