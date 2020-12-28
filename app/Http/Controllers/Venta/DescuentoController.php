<?php

namespace App\Http\Controllers\Venta;

use App\Descuento;
use App\Promocion;
use App\Paciente;
use App\Producto;
use App\PromocionEnProducto;
use App\Venta;
use App\Folio;
use App\Sigpesosventa;
use DateInterval;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use DB;

class DescuentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(Auth::check()) {
                if(Auth::user()->role->precargas)
                {
                    return $next($request);
                }                
             return redirect('/inicio');
                 
            }
            return redirect('/'); 
        });
    }
    public function index()
    {   
        return view('venta.descuentos.descuentos', ['descuentos'=>Descuento::get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $productos = Producto::get();
        return view('venta.descuentos.descuentos_create', ['productos' => $productos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $descuento = Descuento::create($request->all());
        $Promocion = Promocion::create(['tipo'=>$request->tipoDes, 
                                        'compra_min'=>$request->compra_min, 'unidad_compra'=>$request->unidad_compra,
                                        'descuento_de'=>$request->descuento_de, 'unidad_descuento'=>$request->unidad_descuento, 
                                        'descuento_id'=>$descuento->id,'aceptSigPesos'=>$request->aceptSigPesos]);
        /**if($request->tipoA)
        {
            $promoA=Promocion::create(['tipo'=>'A', 'compra_min'=>$request->compra_minA, 'unidad_compra'=>'prendas',
                                        'descuento_de'=>$request->descuento_deA, 'unidad_descuento'=>'prendas', 
                                        'descuento_id'=>$descuento->id]);
        }

        if($request->tipoB)
        {
            $promoB=Promocion::create(['tipo'=>'B','compra_min'=>$request->compra_minB,'unidad_compra'=>'$',
                                        'descuento_de'=>$request->descuento_deB,
                                        'unidad_descuento'=>$request->unidad_descuentoB, 'descuento_id'=>$descuento->id]);
        }

        if($request->tipoC)
        {
            $promoC=Promocion::create(['tipo'=>'C','compra_min'=>0,'unidad_compra'=>'$',
                                        'descuento_de'=>$request->descuento_deC,
                                        'unidad_descuento'=>$request->unidad_descuentoC,'descuento_id'=>$descuento->id]);
        }

        if($request->tipoD)
        {
            $promoD=Promocion::create(['tipo'=>'D','compra_min'=>$request->compra_minD,'unidad_compra'=>'prendas',
                                        'descuento_de'=>$request->descuento_deD,
                                        'unidad_descuento'=>$request->unidad_descuentoD,'descuento_id'=>$descuento->id]);
        }

        if($request->tipoE)
        {
            $promoE=Promocion::create(['tipo'=>'E','compra_min'=>$request->compra_minE,'unidad_compra'=>'prendas',
                                        'descuento_de'=>$request->descuento_deE,'unidad_descuento'=>'sigpesos',
                                        'descuento_id'=>$descuento->id]);
        }

        if($request->tipoF)
        {
            $promoF=Promocion::create(['tipo'=>'F','compra_min'=>0,'unidad_compra'=>'$',
                                        'descuento_de'=>$request->descuento_deF,
                                        'unidad_descuento'=>$request->unidad_descuentoF,'descuento_id'=>$descuento->id]);
        }
        if($request->tipoG)
        {
            for ($i=0; $i < count($request->producto_id); $i++) { 
                // dd($request->descuento_deG);
                $promoG = PromocionEnProducto::create(['descuento_id'=>$descuento->id, 'producto_id'=>$request->producto_id[$i],
                                                       'descuento'=>$request->descuento_deG, 'unidad_descuento'=>$request->unidad_descuentoG]);
            }
        }
        if($request->tipoH)
        {
                // dd($request->descuento_deG);
                $promoH=Promocion::create(['tipo'=>'H','compra_min'=>$request->compra_minH,'unidad_compra'=>'$',
                                        'descuento_de'=>$request->descuento_deH,'unidad_descuento'=>'sigpesos',
                                        'descuento_id'=>$descuento->id]);
            
        }
        */
        return redirect()->route('descuentos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function show(Descuento $descuento)
    {
        //dd(date("z",strtotime($descuento->inicio)));
        // dd($descuento->promociones->where('tipo','A')->first()->compra_min);
        //dd($descuento->promociones->where('tipo','B')->first());
        return view('venta.descuentos.descuentos_show',['descuento'=>$descuento]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function edit(Descuento $descuento)
    {
        if (count($descuento->promocionesProductos) > 0) {
            return view('venta.descuentos.edit_Descuento_Prod',['descuento'=>$descuento]);
        }
        return view('venta.descuentos.descuentos_edit',['descuento'=>$descuento]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descuento $descuento)
    {
        $descuento->update($request->all());
        if($request->tipoA)
        {
            $promoA=new Promocion;
            $promoA->tipo='A';
            $promoA->compra_min=$request->compra_minA;
            $promoA->unidad_compra='prendas';
            $promoA->descuento_de=$request->descuento_deA;
            $promoA->unidad_descuento='prendas';
            $promoA->descuento_id=$descuento->id;
            $promoA->save();
        }

        if($request->tipoB)
        {
            $promoB=new Promocion;
            $promoB->tipo='B';
            $promoB->compra_min=$request->compra_minB;
            $promoB->unidad_compra='$';
            $promoB->descuento_de=$request->descuento_deB;
            $promoB->unidad_descuento=$request->unidad_descuentoB;
            $promoB->descuento_id=$descuento->id;
            $promoB->save();
        }

        if($request->tipoC)
        {
            $promoC=new Promocion;
            $promoC->tipo='C';
            $promoC->compra_min=0;
            $promoC->unidad_compra='$';
            $promoC->descuento_de=$request->descuento_deC;
            $promoC->unidad_descuento=$request->unidad_descuentoC;
            $promoC->descuento_id=$descuento->id;
            $promoC->save();
        }

        if($request->tipoD)
        {
            $promoD=new Promocion;
            $promoD->tipo='D';
            $promoD->compra_min=$request->compra_minD;
            $promoD->unidad_compra='prendas';
            $promoD->descuento_de=$request->descuento_deD;
            $promoD->unidad_descuento=$request->unidad_descuentoD;
            $promoD->descuento_id=$descuento->id;
            $promoD->save();
        }

        if($request->tipoE)
        {
            $promoE=new Promocion;
            $promoE->tipo='D';
            $promoE->compra_min=$request->compra_minE;
            $promoE->unidad_compra='prendas';
            $promoE->descuento_de=$request->descuento_deE;
            $promoE->unidad_descuento='sigpesos';
            $promoE->descuento_id=$descuento->id;
            $promoE->save();
        }

        if($request->tipoF)
        {
            $promoF=new Promocion;
            $promoF->tipo='F';
            $promoF->compra_min=0;
            $promoF->unidad_compra='$';
            $promoF->descuento_de=$request->descuento_deF;
            $promoF->unidad_descuento=$request->unidad_descuentoF;
            $promoF->descuento_id=$descuento->id;
            $promoF->save();
        }
        return redirect('descuentos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Descuento $descuento)
    {
        //
         $descuento->delete();
         
         return view('venta.descuentos.descuentos', ['descuentos'=>Descuento::get()]);
    }

    public function getPromos(Descuento $descuento)
    {
        return view('venta.get_promos',['promociones'=>$descuento->promociones, 'nombre' =>$descuento->descripcion]);
    }

    public function getDescuento(Promocion $promocion,Request $request)
    {
       // dd($request->input("productos_id"));
      //   $response = array(
      //     'status' => 'success',
      //     'subtotal' => $request->subtotal,
      //     'paciente_id'=>$request->paciente_id,
      //     'total_productos'=>$request->total_productos
      // );
      // return response()->json($response);
        //promocion tipo A -> minimo de prendas 
        //promocion tipo B -> monto minimo
        //promocion tipo C -> Convenio
        
        $response=array();
        switch ($promocion->tipo) {
            case 'A':
                if ($request->total_productos>=$promocion->compra_min) {
                    switch ($promocion->unidad_descuento) {
                        case 'Pesos':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$promocion->descuento_de
                            );
                            break;
                        case 'Procentaje1':
                            $CostoProductoBarato=999999999;
                            foreach ($request->input("productos_id") as $producto_id) {
                                if($CostoProductoBarato>Producto::where('id',$producto_id)->value("precio_publico")){
                                    $CostoProductoBarato=Producto::where('id',$producto_id)->value("precio_publico");
                                }
                            }
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$CostoProductoBarato*($promocion->descuento_de/100)
                            );
                            break;
                        case 'Procentaje2':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$request->subtotal*($promocion->descuento_de/100)
                            );
                            break;
                        case 'sigCompra':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>$promocion->descuento_de,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>0,
                                'sigCom'=>$promocion->descuento_de
                            );
                            break;
                         case 'Pieza':
                            $CostoProductoBarato=999999999;
                            foreach ($request->input("productos_id") as $producto_id) {
                                if($CostoProductoBarato>Producto::where('id',$producto_id)->value("precio_publico")){
                                    $CostoProductoBarato=Producto::where('id',$producto_id)->value("precio_publico");
                                }
                            }
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$CostoProductoBarato
                            );
                            break;
                        default:
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>0
                            );
                            break;
                    }
                }
                break;
            case 'B':
            if ($request->subtotal>=$promocion->compra_min) {
                    switch ($promocion->unidad_descuento) {
                        case 'Pesos':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$promocion->descuento_de
                            );
                            break;
                        case 'Procentaje1':
                            $CostoProductoBarato=999999999;
                            foreach ($request->input("productos_id") as $producto_id) {
                                if($CostoProductoBarato>Producto::where('id',$producto_id)->value("precio_publico")){
                                    $CostoProductoBarato=Producto::where('id',$producto_id)->value("precio_publico");
                                }
                            }
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$CostoProductoBarato*($promocion->descuento_de/100)
                            );
                            break;
                        case 'Procentaje2':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$request->subtotal*($promocion->descuento_de/100)
                            );
                            break;
                        case 'sigCompra':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>$promocion->descuento_de,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>0,
                                'sigCom'=>$promocion->descuento_de
                            );
                            break;
                         case 'Pieza':
                            $CostoProductoBarato=999999999;
                            foreach ($request->input("productos_id") as $producto_id) {
                                if($CostoProductoBarato>Producto::where('id',$producto_id)->value("precio_publico")){
                                    $CostoProductoBarato=Producto::where('id',$producto_id)->value("precio_publico");
                                }
                            }
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$CostoProductoBarato
                            );
                            break;
                        default:
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>0
                            );
                            break;
                    }
                }
                break;
            case 'C':
             switch ($promocion->unidad_descuento) {
                        case 'Pesos':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$promocion->descuento_de
                            );
                            break;
                        case 'Procentaje':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$request->subtotal*($promocion->descuento_de/100)
                            );
                            break;
                        case 'sigCompra':
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>$promocion->descuento_de,
                                'total'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'sigCom'=>$promocion->descuento_de
                            );
                            break;
                         case 'Pieza':
                            $CostoProductoBarato=999999999;
                            foreach ($request->input("productos_id") as $producto_id) {
                                if($CostoProductoBarato>Producto::where('id',$producto_id)->value("precio_publico")){
                                    $CostoProductoBarato=Producto::where('id',$producto_id)->value("precio_publico");
                                }
                            }
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>$CostoProductoBarato
                            );
                            break;
                        default:
                            $response=array(
                                'status'=>1,
                                'sigpesos'=>0,
                                'aceptsp'=>$promocion->aceptSigPesos,
                                'total'=>0
                            );
                            break;
                    }
                break;
            default:
                $response=array(
                    'status'=>1,
                    'sigpesos'=>0,
                    'aceptsp'=>$promocion->aceptSigPesos,
                    'total'=>0
                );
                break;
            }

        return response()->json($response);
    }

    public function getFolios(Request $request){

        //Esta variable tendra el id y monto del tipo de sigpesos
$folio = Folio::find($request->id);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
$ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
        // dd($ultimo);
            $response=array(
                                'folio'=>$ultimo+1,
                                'monto'=>$folio->monto,
                                
                            );
        return response()->json($response);
    }

public function foliospaciente (Paciente $paciente){
    
         $ultimo = DB::table('sigpesosventa')->where('paciente_id','=',$paciente->id)->where('usado','=',0)->orderBy('id','desc')->value('folio');
         $monto =DB::table('sigpesosventa')->where('paciente_id','=',$paciente->id)->orderBy('id','desc')->value('monto');
          $folio_descrip =DB::table('sigpesosventa')->where('paciente_id','=',$paciente->id)->orderBy('id','desc')->value('folio_id');
        $folio_id =DB::table('folios')->where('id','=',$folio_descrip)->orderBy('id','desc')->value('descripcion');


        $response=array(
                                'folio'=>$ultimo,
                                'monto'=>$monto,
                                'descripcion'=>$folio_id
                                
                            );
        return response()->json($response);

    }




    public function getSigpesos(Paciente $paciente)
    {
        //dd(isset($paciente->nacimiento));
        if (isset($paciente->nacimiento)) {
            if (\Carbon\Carbon::parse($paciente->nacimiento)->month==\Carbon\Carbon::now()->month) {
                $sigpesosCumpleaños=300;
                
                //$ventasCumple=$paciente->ventas->whereDateBetween('fecha',(new Carbon)->subDays(31)->toDateString(),(new Carbon)->now()->toDateString() )->get();
                $ventasCumple=Venta::where('paciente_id',$paciente->id)->whereDate('fecha','>=',\Carbon\Carbon::now()->subDays(31)->toDateString() )->get();
                foreach ($ventasCumple as $venta) {
                    if ($venta->cumpleDes==1) {
                        $sigpesosCumpleaños=0;
                    }
                }
            }else{
                $sigpesosCumpleaños=0;
            }
        }else{
            $sigpesosCumpleaños=0;
        }

        if(isset($paciente->ventas->last()->created_at))
        {
            $intervalo = new DateInterval('P6M');
            
            $hoy=Carbon::now();
            $expira=$paciente->ventas->last()->created_at->add($intervalo);
            //dd($intervalo);
            if($expira>$hoy){
                if (!isset($paciente->ventas->last()->sigpesos)) {
                    //return $sigpesosCumpleaños;
                    return response()->json(array(
                        'cumple'=>$sigpesosCumpleaños,
                        'sigpesos'=>0
                    ));
                }else{
                    return response()->json(array(
                        'cumple'=>$sigpesosCumpleaños,
                        'sigpesos'=>$paciente->ventas->last()->sigpesos
                    ));
                    //return $sigpesosCumpleaños+$paciente->ventas->last()->sigpesos;
                }
                
            }
            else{
                //return $sigpesosCumpleaños;
                return response()->json(array(
                        'cumple'=>$sigpesosCumpleaños,
                        'sigpesos'=>0
                    ));
            }
        }
        //return $sigpesosCumpleaños;
        return response()->json(array(
                        'cumple'=>$sigpesosCumpleaños,
                        'sigpesos'=>0
                    ));
    }

}
