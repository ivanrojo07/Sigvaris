<?php

namespace App\Http\Controllers\Venta;

use Carbon\Carbon;
use App\Venta;
use App\Paciente;
use App\Producto;
use App\Descuento;
use App\Promocion;
use App\Doctor;
use App\Empleado;
use App\Crm;
use App\sigvariscard;
use App\DatoFiscal;
use App\Folio;
use App\Banco;
use App\Factura;
use App\Sigpesosventa;
use App\HistorialCambioVenta;
use App\ProductoDamage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Ventas\RealizarVentaProductosService;
use App\Services\Ventas\RealizarGarexVentaService;
use App\Services\Ventas\RealizarRetexVentaService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{

    public function __construct(RealizarVentaProductosService $realizarVentaProductos,RealizarGarexVentaService $RealizarGarexVentaService, RealizarRetexVentaService $RealizarRetexVentaService)
    {
        $this->realizarVentaProductosService = $realizarVentaProductos;
        $this->RealizarGarexVentaService = $RealizarGarexVentaService;
        $this->RealizarRetexVentaService = $RealizarRetexVentaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $medicos = Doctor::get();

        $ventas = Venta::orderBy('id', 'desc');

        if ($request->fecha_inicio) {
            $ventas = $ventas->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->fecha_fin) {
            $ventas = $ventas->where('fecha', '<=', $request->fecha_fin);
        }
        if ($request->numero_folio) {
            $ventas = $ventas->where('id', $request->numero_folio);
        }
        if ($request->apellido_paterno) {
            $ventas = $ventas->whereHas('paciente', function(Builder $query) use ($request){
                $query->where('paterno', 'LIKE', '%' . $request->apellido_paterno .'%');
            } );
        }

        //Poner ventas en historial cortes de caja 
        $ventas = $ventas->where('oficina_id', session('oficina'));
        $ventas = $ventas->orderBy('fecha', 'desct')->paginate(5);
        return view('venta.index_all', ['ventas' => $ventas, 'medicos' => $medicos])->withInput($request->input());
    }

    public function indexConPaciente(Paciente $paciente)
    {
        $pac = $paciente->ventas()->orderBy('id', 'desc')->get();

        return view('venta.index', ['ventas' => $pac, 'paciente' => $paciente]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hoy = Carbon::now('America/Mexico_City')->toDateString();
        $descuentos = Descuento::where('inicio', '<=', $hoy)->where('fin', '>=', $hoy)->get();
        $productos = Producto::where('id', '<', 1)->get();
        $pacientes = Paciente::where('id', '<', 1)->get();
        $empleadosFitter = Empleado::fitters()->get();
        $Bancos = Banco::get();
        return view('venta.create', [
            'pacientes' => null,
            'paciente' => null,
            'descuentos' => $descuentos,
            'productos' => $productos,
            'folio' => Venta::count() + 1,
            'empleadosFitter' => $empleadosFitter,
            'Folios' => Folio::get(),
            'Bancos' => $Bancos
        ]);
    }

    public function createConPaciente(Paciente $paciente)
    {
        //dd($paciente);
        $hoy = Carbon::now()->toDateString();
        $descuentos = Descuento::where('inicio', '<=', $hoy)->where('fin', '>=', $hoy)->get();
        $productos = Producto::where('id', '<', 1)->get();
        $pacientes = Paciente::get();
        $empleadosFitter = Empleado::fitters()->get();
        $Bancos = Banco::get();
        //dd($pacientes);
        return view('venta.PEcreate', [
            'pacientes' => $pacientes,
            'paciente' => $paciente,
            'descuentos' => $descuentos,
            'productos' => $productos,
            'folio' => Venta::count() + 1,
            'empleadosFitter' => $empleadosFitter,
            'Folios' => Folio::get(),
            'Bancos' => $Bancos
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        // dd($request);
        
        $venta = new Venta($request->all());
        if (!isset($request->producto_id) || is_null($request->producto_id)) {
            return redirect()
                ->back()
                ->withErrors(['No se seleccionó ningún producto.'])
                ->withInput($request->input());
        }
        //dd($request->PagoEfectivo+$request->PagoTarjeta==$request->total);
        $auxiliar = (int)$request->sigpesos_usar;
        $Paciente=Paciente::where("id",$request->paciente_id)->first();


            if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 6 ) {

            if ($request->deposito_folio == null && $request->transferencia_folio == null ) {
                # code...
                if ($request->deposito_total != 0 || $request->transferencia_total != 0) {

                    return redirect()
                     ->back()
                     ->withErrors(['Debes introducir algun folio en transferencia u deposito'])
                     ->withInput($request->input());
                    
                }
                
            }else{
                $venta->num_transferencia = $request->transferencia_total;
                $venta->num_deposito = $request->deposito_total;
                $venta->folio_transferencia = $request->transferencia_folio;
                $venta->folio_deposito = $request->deposito_folio;
            }
        }
     



        if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ||$request->input('tipoPago') == 5) {

        if ($request->saldo_a_usar<=$Paciente->saldo_a_favor) {

             
             $saldo_paciente =$Paciente->saldo_a_favor+$request->sigpesos;

             $actualizacion =  $Paciente->saldo_a_favor -$request->saldo_a_usar; 
             
             $venta->PagoSaldo=$request->saldo_a_usar;
            
             $Paciente->update(['saldo_a_favor' => $actualizacion]); 

              // $saldo_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
              // $Paciente->update(['sigpesos_a_favor' => $saldo_paciente]);  
              
             }else{
                
           return redirect()
                ->back()
                ->withErrors(['Error saldo a favor insuficiente'])
                ->withInput($request->input());
        }
        
           if (!($request->PagoEfectivo + $request->PagoTarjeta + $request->saldo_a_usar+ $request->sigpesos_usar + $request->transferencia_total + $request->deposito_total== round($request->total, 2))) {
            return redirect()
                ->back()
                ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
                ->withInput($request->input());
        }


        }
         if ($request->INAPAM_ == 1) {
          # code...
          $venta->desc_inapam = $request->descuento;
          // dd($venta->desc_inapam);
            }
        
      
         
        /*
        if (!is_null($request->digitos_targeta) && ($request->digitos_targeta<1000)) {
            return redirect()
                ->back()
                ->withErrors(['Error con ultimos 4 digitos de tarjeta'])
                ->withInput($request->input());
        }
        if (isset($request->descuentoCum)&&$request->descuentoCum!=0) {
            # code...
            $request->cumpleDes=1;
        }*/
        // PREPARAR DATOS DE LA VENTA
        // 
        // 
         // = intval($request->sigpesos_usar); 

       
         $venta->sigpesos = $auxiliar;


        $venta->oficina_id = session()->get('oficina');

        // dd($venta);

        // GUARDAMOS EL FITTER DE LA VENTA
        if ($request->empleado_id) {
            $venta->empleado_id = $request->empleado_id;
        } else {
            $venta->empleado_id = Auth::user()->empleado->id;
            // dd('Empleado fitter'.Auth::user()->empleado );
        }

        // dd('VENTA QUE SERÁ GUARDADA'.$venta);

        $productos = Producto::find($request->producto_id);
        //agrgar codigo para hacer crm 

        // REALIZAR VENTA
        $this->realizarVentaProductosService->make($venta, $productos, $request);
        $this->RealizarGarexVentaService->make($venta, $request);


        if ($request->facturar == "1") {
            $venta->update(['requiere_factura' => 1]);
                DatoFiscal::updateOrCreate(
                ['paciente_id' => $request->paciente_id],
                [
                    'calle' => $request->calle,
                    'tipo_persona' => $request->tipo_persona,
                    'nombre_o_razon_social' => $request->nombre_o_razon_social,
                    'regimen_fiscal' => $request->regimen_fiscal,
                    'correo' => $request->correo,
                    'rfc' => $request->rfc,
                    'homoclave'=> $request->homoclave,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
                    'uso_cfdi' => $request->uso_cfdi
                ]
             );
            $Factura = new Factura(
                    array(
                    'venta_id'=>$venta->id,
                    'nombre'=>$venta->paciente->getFullnameAttribute(),
                    'rfc'=>$request->rfc,
                    'regimen_fiscal'=> $request->regimen_fiscal,
                    'correo'=>$request->correo,
                    'calle'=> $request->calle,
                    'num_ext'=>$request->num_ext,
                    'num_int'=>$request->num_int,      
                    'ciudad'=>$request->ciudad,
                    'municipio'=>$request->alcaldia_o_municipio,              
                    'cp' =>$request->codigo_postal
            )
          );
          $Factura->save();  
        }
        $sigvariscard = new sigvariscard(
            array(
                'paciente_id'=> $request->paciente_id,
                'folio'=>$request->SigvarisCardFolio,
                'tipo'=>$request->SigvarisCard,
                'venta_id'=>$venta->id
            )
        );
        $sigvariscard->save();

            
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 1,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addMonths(5),
                'fecha_aviso' => Carbon::now()->addMonths(5),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 5,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addDays(8),
                'fecha_aviso' => Carbon::now()->addDays(8),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();


             if ($request->sigpesos_usar>0) {
                if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ) {
             //Sigpesos 
             foreach ($request->folio as $key => $folio) {
                    # code...
                    
            $new_fo = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    $existe = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    if ($existe = true) {
                        DB::table('sigpesosventa')->where('folio','=', $folio)->where('usado','=',0)->increment('usado');
                        // dd("Actualizado");
                        DB::table('sigpesosventa')->where('folio','=', $folio)->update(['venta_id' => $venta->id]);
                        // DB::table('sigpesosventa')->where('folio','=', $folio)->update(['tipo' =>'pago']);
                       
                    }
                    // dd($new_fo);
                    if($new_fo == false ){
                        $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key],
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'pago',
                        'usado'=>1
                         ]);
                        
                        $Sigpesos->save();
                       
                        
                    }
                    
                        # code...
                    
                           
                      }

                    }    
                }
                  
        if ($request->descuento_id == 41) {
            $folio = Folio::find(1);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 1000,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'esencial',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 // $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                // $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }if ($request->descuento_id == 30) {
              $folio = Folio::find(6);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 300,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'cupon producto negado',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 // $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                // $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }

        if ($request->cumpleDes==1) {
            //Obtenemos la informacion del folio de tipo cumpleaños sigpesos
            $folio_id = Folio::where('descripcion','Cumpleaños')->value('id');
            $rango_inferior = Folio::where('descripcion','Cumpleaños')->value('rango_inferior');
            // dd($rango_inferior);
            //hago una consulta en la table de sigpesos venta en donde me trae el ultimo folio que se añadio de este tipo de folio
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio_id)->orderBy('id','desc')->value('folio');
                //si es igual a 0 entonces asigno el valor del rango inferior ya que sera el primero 
             if ($ultimo == 0) {
               $ultimo = $rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 300,
                        'folio' => $ultimo+1,  
                        'folio_id' => $folio_id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'cumpleaños',
                        'usado'=>1

                    ]);
                $Sigpesos->save();
        }



              if ($request->sigpesos != 0) {
             $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }
            
        
        //Actualizar saldo a favor 
        //
            // $Paciente=Paciente::where("id",$request->paciente_id)->first();
            //  $saldo_paciente =$Paciente->saldo_a_favor+$request->sigpesos;

            //  $actualizacion =  $Paciente->saldo_a_favor -$request->sigpesos_usar; 
            //  $Paciente->update(['saldo_a_favor' => $actualizacion]);  
            if ($Paciente->sigpesos_a_favor>0) {
                $sigpesos_paciente = $Paciente->sigpesos_a_favor-$request->sigpesos_usar;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }else if($request->sigpesos>0){

                $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 
            }
             
             // $saldo_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos_usar;
             //    $Paciente->update(['sigpesos_a_favor' => $saldo_paciente]);  
       
        // REDIRIGIR A LAS VENTAS REALIZADAS
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
        $origen = DB::table('productos_damage')->select('origin_id')->where('destinate_id',$venta->id)->get();
        $cambio = DB::table('historial_cambios_venta')->select('venta_id')->where('destinate_id',$venta->id)->get();
        
        return view('venta.show', ['venta' => $venta,'origen'=>$origen,'cambio'=>$cambio]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        return view('venta.edit', ['venta' => $venta]);
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
        return view('venta.show', ['venta' => $venta]);
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

        // OBTENEMOS LAS PRENDAS POR EL NUMERO DE PIEZAS
        /*if ($request->num_prendas != "" && $request->num_prendas != "0") {
            $ventas = Venta::with('paciente', 'descuento')->where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)->get();
            foreach ($ventas as $v) {
                if ($v->productos->count() == $request->num_prendas)
                    $ventasxprenda[] = $v;
            }
            $ventas = [];
            foreach ($ventasxprenda as $v)
                $ventas[] = $v;
        } else*/
        $ventas = Venta::where('oficina_id', session('oficina'));
        $ventas = $ventas->with('paciente', 'descuento')->where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)->get();

        // Obtención de Las ventas que contengan la prenda o prendas que se introdujeron en el campo prenda
        $arr = [];
        /*if ($request->prenda != "") {
            $query = $request->prenda;
            $wordsquery = explode(' ', $query);
            $total_ventas = Venta::where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)->get();
            foreach ($total_ventas as $venta) {
                $productos = $venta->productos()->where(function ($q) use ($wordsquery) {
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
        }*/

        // Combinar las ventas de acuerdo a las dos busquedas anteriores
        $ventas_final = [];
        foreach ($ventas as $venta) {
            if (count($arr) != 0) {
                foreach ($arr as $v) {
                    if ($venta->id == $v->id) {
                        $ventas_final[] = $venta;
                    }
                }
            } else
                $ventas_final[] = $venta;
        }

        // Obtencion de las prendas MAS o MENOS vendidas
        /*if ($request->mas != "")
            $consulta = DB::select("SELECT producto_id, SUM(cantidad) AS TotalVentas FROM producto_venta GROUP BY producto_id ORDER BY SUM(cantidad) DESC LIMIT 0 , 30 ");
        elseif ($request->menos != "")
            $consulta = DB::select("SELECT producto_id, SUM(cantidad) AS TotalVentas FROM producto_venta GROUP BY producto_id ORDER BY SUM(cantidad) LIMIT 0 , 100 ");
        else
            $consulta = [];
        foreach ($consulta as $productos) {
            $prod[] = ["0" => Producto::find($productos->producto_id), "1" => $productos->TotalVentas];
        }*/
        return response()->json(["ventas" => $ventas_final, "consulta" => $prod]);
    }

    public function getVentasClientes(Request $request)
    {
        if ($request->tipo == "primero") {
            $consulta = DB::select("SELECT paciente_id FROM ventas GROUP BY paciente_id HAVING COUNT(*) = 1 ");
        } elseif ($request->tipo == "consecutivo") {
            $consulta = DB::select("SELECT paciente_id FROM ventas GROUP BY paciente_id HAVING COUNT(*) > 1 ");
        } else {
            $consulta = [];
        }

        $ventas = [];
        foreach ($consulta as $paciente) {
            if ($request->desde && $request->hasta) {
                $ventastemp = Venta::where('paciente_id', $paciente->paciente_id)
                    ->where('fecha', '<=', $request->hasta)->where('fecha', '>=', $request->desde)
                    ->get();
            } else
                $ventastemp = Venta::where('paciente_id', $paciente->paciente_id)->get();

            foreach ($ventastemp as $v) {
                $cantidad = 0;
                foreach ($v->productos as $prod) {
                    $cantidad += $prod->pivot->cantidad;
                }
                $ventas[] = ['venta' => $v, 'cantidad' => $cantidad];
            }
        }
        $suma_ventas = 0;
        $sumatoria_pacientes = [];
        foreach ($ventas as $vent) {
            $suma_ventas += $vent['venta']->total;
            $val = 1;
            foreach ($sumatoria_pacientes as $p) {
                if ($p == $vent['venta']->paciente->id)
                    $val = 0;
            }
            if ($val)
                array_push($sumatoria_pacientes, $vent['venta']->paciente->id);
        }
        $totalClientes = count($sumatoria_pacientes);
        return response()->json(["ventas" => $ventas, 'total' => $suma_ventas, 'suma_pacientes' => $totalClientes]);
    }



    public function ventaDamage(Request $request)
    {
            // dd($request);
              
        // $saldo_a_favor=$request->input('montonegativo');
        $saldo_a_favor= $request->saldo_a_favor;
        
        
        // PREPARAR DATOS DE LA VENTA
        $venta = new Venta($request->all());
        
        $Paciente=Paciente::where("id",$request->paciente_id)->first();
        $Paciente->update(['saldo_a_favor' => abs($saldo_a_favor)]);
            $venta_cu = Venta::where("id",$request->VentaAnterior)->first();
                  if ($venta_cu->descuento_cu == null && $venta_cu->cumpleDes ==1) {
                        $venta_cu->update(['descuento_cu' => 1]);
            }

            if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 6 ) {

            if ($request->deposito_folio == null && $request->transferencia_folio == null ) {
                # code...
                if ($request->deposito_total != 0 || $request->transferencia_total != 0) {

                    return redirect()
                     ->back()
                     ->withErrors(['Debes introducir algun folio en transferencia u deposito'])
                     ->withInput($request->input());
                    
                }
                
            }else{
                $venta->num_transferencia = $request->transferencia_total;
                $venta->num_deposito = $request->deposito_total;
                $venta->folio_transferencia = $request->transferencia_folio;
                $venta->folio_deposito = $request->deposito_folio;
            }
        }

        // if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ||$request->input('tipoPago') == 5) {

        // if ($request->saldo_a_usar<=$Paciente->saldo_a_favor) {

    
        //     $saldo_paciente =$Paciente->saldo_a_favor+$request->sigpesos;

        //      $actualizacion =  $Paciente->saldo_a_favor -$request->saldo_a_usar; 
             
        //      $venta->PagoSaldo=$request->saldo_a_usar;
            
        //      $Paciente->update(['saldo_a_favor' => $actualizacion]);    
        //      }else{
                
        //         return redirect()->route('ventas.index')->withErrors(['EL DAMAGE NO SE PUDO REALIZAR´PORQUE NO CUENTA CON SALDO SUFICIENTE']);          
        //          }

        //       if (!($request->PagoEfectivo + $request->PagoTarjeta + $request->saldo_a_usar+ $request->sigpesos_usar + $request->transferencia_total + $request->deposito_total== round($request->total, 2))) {
        //     return redirect()
        //         ->back()
        //         ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
        //         ->withInput($request->input());
        // }else{

        //  }
        //  // return redirect()->route('ventas.index');
        
        // }
          if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ||$request->input('tipoPago') == 5) {

        if ($request->saldo_a_usar<=$Paciente->saldo_a_favor) {

             
             $saldo_paciente =$Paciente->saldo_a_favor+$request->sigpesos;

             $actualizacion = $Paciente->saldo_a_favor - $request->saldo_a_usar; 
             
             $venta->PagoSaldo=$request->saldo_a_usar;
                // dd($actualizacion, $request->saldo_a_favor,$Paciente->saldo_a_favor);
             $Paciente->update(['saldo_a_favor' => $actualizacion]); 

              // $saldo_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
              // $Paciente->update(['sigpesos_a_favor' => $saldo_paciente]);  
              
             }else{
                
           return redirect()
                ->back()
                ->withErrors(['Error saldo a favor insuficiente'])
                ->withInput($request->input());
        }
        
           if (!($request->PagoEfectivo + $request->PagoTarjeta + $request->saldo_a_usar+ $request->sigpesos_usar + $request->transferencia_total + $request->deposito_total== round($request->total, 2))) {
            return redirect()
                ->back()
                ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
                ->withInput($request->input());
        }


        }

        
// dd($venta_cu->descuento_cu);
//      
        // if ($request->saldo_a_favor>$Paciente->saldo_a_favor) {
        //     $actualizacion = $request->saldo_a_favor; 
        //     $Paciente->update(['saldo_a_favor' => $actualizacion]);  
        // }
         
        // $saldo_paciente =$request->saldo_a_favor;
            
        // $Paciente->update(['saldo_a_favor' => $saldo_paciente]); 
        $venta->oficina_id = session()->get('oficina');
         $auxiliar = (int)$request->sigpesos_usar;
         $venta->sigpesos = $auxiliar;
        // GUARDAMOS EL FITTER DE LA VENTA
        if ($request->empleado_id) {
            $venta->empleado_id = $request->empleado_id;
        } else {
            $venta->empleado_id = Auth::user()->empleado->id;
            // dd('Empleado fitter'.Auth::user()->empleado );
        }

        // dd('VENTA QUE SERÁ GUARDADA'.$venta);

        $productos = Producto::find($request->producto_id);
        //agrgar codigo para hacer crm 

        // REALIZAR VENTA
        $this->realizarVentaProductosService->make($venta, $productos, $request);
        $this->RealizarGarexVentaService->make($venta, $request);

        if ($request->facturar == "1") {
            $venta->update(['requiere_factura' => 1]);
            DatoFiscal::updateOrCreate(
                ['paciente_id' => $request->paciente_id],
                [
                    'calle' => $request->calle,
                    'tipo_persona' => $request->tipo_persona,
                    'nombre_o_razon_social' => $request->nombre_o_razon_social,
                    'regimen_fiscal' => $request->regimen_fiscal,
                    'correo' => $request->correo,
                    'rfc' => $request->rfc,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
                    'uso_cfdi' => $request->uso_cfdi
                ]
            );
        }
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 1,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addMonths(5),
                'fecha_aviso' => Carbon::now()->addMonths(5),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 5,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addDays(8),
                'fecha_aviso' => Carbon::now()->addDays(8),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
         $sigvariscard = new sigvariscard(
            array(
                'paciente_id'=> $request->paciente_id,
                'folio'=>$request->SigvarisCardFolio,
                'tipo'=>$request->SigvarisCard,
                'venta_id'=>$venta->id
            )
        );
        $sigvariscard->save();

           if ($request->sigpesos_usar>0) {
                if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ) {
             //Sigpesos 
             foreach ($request->folio as $key => $folio) {
                    # code...
                    
            $new_fo = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    $existe = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    if ($existe = true) {
                        DB::table('sigpesosventa')->where('folio','=', $folio)->where('usado','=',0)->increment('usado');
                        // dd("Actualizado");
                        DB::table('sigpesosventa')->where('folio','=', $folio)->update(['venta_id' => $venta->id]);
                        // DB::table('sigpesosventa')->where('folio','=', $folio)->update(['tipo' =>'pago']);
                       
                    }
                    // dd($new_fo);
                    if($new_fo == false ){
                        $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key],
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'pago',
                        'usado'=>1
                         ]);
                        
                        $Sigpesos->save();
                       
                        
                    }
                    
                        # code...
                    
                           
                      }

                    }    
                }
                  if ($request->descuento_id == 41) {
            $folio = Folio::find(1);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 1000,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'esencial',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }if ($request->descuento_id == 30) {
              $folio = Folio::find(6);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 300,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'cupon producto negado',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }
              if ($request->sigpesos != 0) {
             $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }

        $HistorialCambioVenta = new HistorialCambioVenta(
            array(
                'tipo_cambio' => "Damage",
                'responsable_id' => Auth::user()->id,
                'venta_id' => $request->VentaAnterior,
                'observaciones' => '',
                'destinate_id'=>$venta->id,
                'producto_devuelto_id' => $request->productoDevuelto,
                'producto_entregado_id' => $productos[0]->id,
                'precioOri'=>$request->precioOri,
                'precioNew' =>$request->precioNew,
                'pagosaldo'=>$request->saldo_a_usar
            )
        );


        $productosDamage = new ProductoDamage;
        $productosDamage->producto_id = $request->productoDevuelto;
        $productosDamage->tipo_damage = $request->TipoDamage;
        $productosDamage->user_id = Auth::user()->id;
        $productosDamage->destinate_id = $request->folio_nuevo;
        $productosDamage->origin_id = $request->VentaAnterior;
        $productosDamage->descripcion = $request->DesDamage;
        $productosDamage->save();

        $consulta = HistorialCambioVenta::where('venta_id',$request->VentaAnterior)->where('descuento_cu',1)->get();
        // dd(count($consulta));

        

        // $venta_cu = Venta::where("id",$request->VentaAnterior)->first();
         if ($venta_cu->descuento_cu == 1 ) {
                        $HistorialCambioVenta->descuento_cu = 1;
            }

        if (count($consulta) >= 1) {
            $HistorialCambioVenta->descuento_cu = 0;
        }

        $HistorialCambioVenta->save();
         if ($Paciente->sigpesos_a_favor>0) {
                $sigpesos_paciente = $Paciente->sigpesos_a_favor-$request->sigpesos_usar;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }else if($request->sigpesos>0){

                $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 
            }
        // $Paciente=Paciente::where("id",$request->paciente_id)->first();
        // $Paciente->update(['saldo_a_favor' => $saldo_paciente]);

        // REDIRIGIR A LAS VENTAS REALIZADAS
        return redirect()->route('ventas.index');
    }





     public function ventaRetex(Request $request)
    {   
        // dd($request);
        // $saldo_a_favor=$request->input('montonegativo');
            $saldo_a_favor= $request->saldo_a_favor;
            
        // PREPARAR DATOS DE LA VENTA
        $venta = new Venta($request->all());
        $venta->oficina_id = session()->get('oficina');
         $auxiliar = (int)$request->sigpesos_usar;
         $venta->sigpesos = $auxiliar;
            $venta_cu = Venta::where("id",$request->VentaAnterior)->first();
            if ($venta_cu->descuento_cu == null && $venta_cu->cumpleDes ==1) {
                        $venta_cu->update(['descuento_cu' => 1]);
            }
             if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 6 ) {

            if ($request->deposito_folio == null && $request->transferencia_folio == null ) {
                # code...
                if ($request->deposito_total != 0 || $request->transferencia_total != 0) {

                    return redirect()
                     ->back()
                     ->withErrors(['Debes introducir algun folio en transferencia u deposito'])
                     ->withInput($request->input());
                    
                }
                
            }else{
                $venta->num_transferencia = $request->transferencia_total;
                $venta->num_deposito = $request->deposito_total;
                $venta->folio_transferencia = $request->transferencia_folio;
                $venta->folio_deposito = $request->deposito_folio;
            }
        }
                 

        $Paciente=Paciente::where("id",$request->paciente_id)->first();
        
        $Paciente->update(['saldo_a_favor' => abs($saldo_a_favor)]);

        // GUARDAMOS EL FITTER DE LA VENTA
        if ($request->empleado_id) {
            $venta->empleado_id = $request->empleado_id;
        } else {
            $venta->empleado_id = Auth::user()->empleado->id;
            // dd('Empleado fitter'.Auth::user()->empleado );
        }

             if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ||$request->input('tipoPago') == 5) {

        if ($request->saldo_a_usar<=$Paciente->saldo_a_favor) {

             
             $saldo_paciente =$Paciente->saldo_a_favor+$request->sigpesos;

             $actualizacion = $Paciente->saldo_a_favor - $request->saldo_a_usar; 
             
             $venta->PagoSaldo=$request->saldo_a_usar;
                // dd($actualizacion, $request->saldo_a_favor,$Paciente->saldo_a_favor);
             $Paciente->update(['saldo_a_favor' => $actualizacion]); 

              // $saldo_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
              // $Paciente->update(['sigpesos_a_favor' => $saldo_paciente]);  
              
             }else{
                
           return redirect()
                ->back()
                ->withErrors(['Error saldo a favor insuficiente'])
                ->withInput($request->input());
        }
        
           if (!($request->PagoEfectivo + $request->PagoTarjeta + $request->saldo_a_usar+ $request->sigpesos_usar + $request->transferencia_total + $request->deposito_total== round($request->total, 2))) {
            return redirect()
                ->back()
                ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
                ->withInput($request->input());
        }


        }
        // dd('VENTA QUE SERÁ GUARDADA'.$venta);

        $productos = Producto::find($request->producto_id);
        //agrgar codigo para hacer crm 

        // REALIZAR VENTA
        $this->realizarVentaProductosService->make($venta, $productos, $request);
        $this->RealizarGarexVentaService->make($venta, $request);
         $this->RealizarRetexVentaService->make($venta, $request);
         // dd('hola');
        // $auxiliar = (int)$request->sigpesos_usar;
        //  $venta->sigpesos = $auxiliar;

        if ($request->facturar == "1") {
            $venta->update(['requiere_factura' => 1]);
            DatoFiscal::updateOrCreate(
                ['paciente_id' => $request->paciente_id],
                [
                    'calle' => $request->calle,
                    'tipo_persona' => $request->tipo_persona,
                    'nombre_o_razon_social' => $request->nombre_o_razon_social,
                    'regimen_fiscal' => $request->regimen_fiscal,
                    'correo' => $request->correo,
                    'rfc' => $request->rfc,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
                    'uso_cfdi' => $request->uso_cfdi
                ]
            );
        }
   
       

        if ($request->input('tipoPago') == 4 || $request->input('tipoPago') == 3) {
            if ($request->input('sigpesos_usar')>0) {
                foreach ($request->folio as $key => $folio) {
                    # code...
                    $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key]
                    ]);
                    $Sigpesos->save();
                }
            }
        }

          if ($request->input('tipoPago') == 5) {

        if ($request->saldo_a_usar<=$Paciente->saldo_a_favor) {

    
             $saldo_paciente = $Paciente->saldo_a_favor+$request->sigpesos;

             $actualizacion =  $Paciente->saldo_a_favor - $request->saldo_a_usar; 
             
             $venta->PagoSaldo=$request->saldo_a_usar;
            
             $Paciente->update(['saldo_a_favor' => $actualizacion]);      
             }else{
                
                return redirect()
                ->back()
                ->withErrors(['Error saldo a favor insuficiente'])
                ->withInput($request->input());           
        }
        
        //    if (!($request->PagoEfectivo + $request->PagoTarjeta + $request->saldo_a_usar+ $request->sigpesos_usar == round($request->total, 2))) {
        //     return redirect()
        //         ->back()
        //         ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
        //         ->withInput($request->input());

        // }
        }

        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 1,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addMonths(5),
                'fecha_aviso' => Carbon::now()->addMonths(5),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 5,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addDays(8),
                'fecha_aviso' => Carbon::now()->addDays(8),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $sigvariscard = new sigvariscard(
            array(
                'paciente_id'=> $request->paciente_id,
                'folio'=>$request->SigvarisCardFolio,
                'tipo'=>$request->SigvarisCard,
                'venta_id'=>$venta->id
            )
        );
        $sigvariscard->save();
          if ($request->sigpesos_usar>0) {
                if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ) {
             //Sigpesos 
             foreach ($request->folio as $key => $folio) {
                    # code...
                    
            $new_fo = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    $existe = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    if ($existe = true) {
                        DB::table('sigpesosventa')->where('folio','=', $folio)->where('usado','=',0)->increment('usado');
                        // dd("Actualizado");
                        DB::table('sigpesosventa')->where('folio','=', $folio)->update(['venta_id' => $venta->id]);
                        // DB::table('sigpesosventa')->where('folio','=', $folio)->update(['tipo' =>'pago']);
                       
                    }
                    // dd($new_fo);
                    if($new_fo == false ){
                        $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key],
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'pago',
                        'usado'=>1
                         ]);
                        
                        $Sigpesos->save();
                       
                        
                    }
                    
                        # code...
                    
                           
                      }

                    }    
                }
                  if ($request->descuento_id == 41) {
            $folio = Folio::find(1);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 1000,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'esencial',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }if ($request->descuento_id == 30) {
              $folio = Folio::find(6);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 300,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'cupon producto negado',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }
              if ($request->sigpesos != 0) {
             $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }

        // HistorialCambioVenta::create([
        //     'tipo_cambio' => 'CAMBIO PRODUCTO',
        //     'responsable_id' => Auth::user()->id,
        //     'venta_id' => $request->VentaAnterior,
        //     'destinate_id'=>$venta->id,
        //     'producto_entregado_id' =>  $productos[0]->id,
        //     'producto_devuelto_id' => $request->productoDevuelto,
        //     'observaciones' => $request->observacionesDevuelto
        // ]);

        $HistorialCambioVenta = new HistorialCambioVenta(
            array(
                'tipo_cambio' => 'RETEX DEL PRODUCTO',
            'responsable_id' => Auth::user()->id,
            'venta_id' => $venta->id-1,
            'destinate_id'=>$venta->id,
            'producto_entregado_id' =>  $productos[0]->id,
            'producto_devuelto_id' => $request->productoDevuelto,
            'observaciones' => $request->observacionesDevuelto,
            'precioOri'=>$request->precioOri,
            'precioNew' =>$request->precioNew,
            'pagosaldo'=>$request->saldo_a_usar
            )
        );

        $ProductoDevuelto = Producto::where('id', $request->productoDevuelto)->first();

        $ProductoDevuelto->update([
            'stock' => $ProductoDevuelto->stock + 1
        ]);

       //  $consulta = HistorialCambioVenta::where('venta_id',$request->VentaAnterior)->where('descuento_cu',1)->get();
       // if ($venta_cu->descuento_cu == 1 ) {
       //                  $HistorialCambioVenta->descuento_cu = 1;
       //      }
       //   if (count($consulta) >= 1) {
       //      $HistorialCambioVenta->descuento_cu = 0;
       //  }

            $HistorialCambioVenta->save();
             if ($Paciente->sigpesos_a_favor>0) {
                $sigpesos_paciente = $Paciente->sigpesos_a_favor-$request->sigpesos_usar;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }else if($request->sigpesos>0){

                $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 
            }
       

        // REDIRIGIR A LAS VENTAS REALIZADAS
        return redirect()->route('ventas.index');


    }


    public function ventaCambio(Request $request)
    {
        // $saldo_a_favor=$request->input('montonegativo');
            $saldo_a_favor= $request->saldo_a_favor;
            
        // PREPARAR DATOS DE LA VENTA
        $venta = new Venta($request->all());
        $venta->oficina_id = session()->get('oficina');
         $auxiliar = (int)$request->sigpesos_usar;
         $venta->sigpesos = $auxiliar;
            $venta_cu = Venta::where("id",$request->VentaAnterior)->first();
            if ($venta_cu->descuento_cu == null && $venta_cu->cumpleDes ==1) {
                        $venta_cu->update(['descuento_cu' => 1]);
            }
             if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 6 ) {

            if ($request->deposito_folio == null && $request->transferencia_folio == null ) {
                # code...
                if ($request->deposito_total != 0 || $request->transferencia_total != 0) {

                    return redirect()
                     ->back()
                     ->withErrors(['Debes introducir algun folio en transferencia u deposito'])
                     ->withInput($request->input());
                    
                }
                
            }else{
                $venta->num_transferencia = $request->transferencia_total;
                $venta->num_deposito = $request->deposito_total;
                $venta->folio_transferencia = $request->transferencia_folio;
                $venta->folio_deposito = $request->deposito_folio;
            }
        }
                 

        $Paciente=Paciente::where("id",$request->paciente_id)->first();
        
        $Paciente->update(['saldo_a_favor' => abs($saldo_a_favor)]);

        // GUARDAMOS EL FITTER DE LA VENTA
        if ($request->empleado_id) {
            $venta->empleado_id = $request->empleado_id;
        } else {
            $venta->empleado_id = Auth::user()->empleado->id;
            // dd('Empleado fitter'.Auth::user()->empleado );
        }

             if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ||$request->input('tipoPago') == 5) {

        if ($request->saldo_a_usar<=$Paciente->saldo_a_favor) {

             
             $saldo_paciente =$Paciente->saldo_a_favor+$request->sigpesos;

             $actualizacion = $Paciente->saldo_a_favor - $request->saldo_a_usar; 
             
             $venta->PagoSaldo=$request->saldo_a_usar;
                // dd($actualizacion, $request->saldo_a_favor,$Paciente->saldo_a_favor);
             $Paciente->update(['saldo_a_favor' => $actualizacion]); 

              // $saldo_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
              // $Paciente->update(['sigpesos_a_favor' => $saldo_paciente]);  
              
             }else{
                
           return redirect()
                ->back()
                ->withErrors(['Error saldo a favor insuficiente'])
                ->withInput($request->input());
        }
        
           if (!($request->PagoEfectivo + $request->PagoTarjeta + $request->saldo_a_usar+ $request->sigpesos_usar + $request->transferencia_total + $request->deposito_total== round($request->total, 2))) {
            return redirect()
                ->back()
                ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
                ->withInput($request->input());
        }


        }
        // dd('VENTA QUE SERÁ GUARDADA'.$venta);

        $productos = Producto::find($request->producto_id);
        //agrgar codigo para hacer crm 

        // REALIZAR VENTA
        $this->realizarVentaProductosService->make($venta, $productos, $request);
        $this->RealizarGarexVentaService->make($venta, $request);

        // $auxiliar = (int)$request->sigpesos_usar;
        //  $venta->sigpesos = $auxiliar;

        if ($request->facturar == "1") {
            $venta->update(['requiere_factura' => 1]);
            DatoFiscal::updateOrCreate(
                ['paciente_id' => $request->paciente_id],
                [
                    'calle' => $request->calle,
                    'tipo_persona' => $request->tipo_persona,
                    'nombre_o_razon_social' => $request->nombre_o_razon_social,
                    'regimen_fiscal' => $request->regimen_fiscal,
                    'correo' => $request->correo,
                    'rfc' => $request->rfc,
                    'num_ext' => $request->num_ext,
                    'num_int' => $request->num_int,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
                    'uso_cfdi' => $request->uso_cfdi
                ]
            );
        }
   
       

        if ($request->input('tipoPago') == 4 || $request->input('tipoPago') == 3) {
            if ($request->input('sigpesos_usar')>0) {
                foreach ($request->folio as $key => $folio) {
                    # code...
                    $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key]
                    ]);
                    $Sigpesos->save();
                }
            }
        }

          if ($request->input('tipoPago') == 5) {

        if ($request->saldo_a_usar<=$Paciente->saldo_a_favor) {

    
             $saldo_paciente = $Paciente->saldo_a_favor+$request->sigpesos;

             $actualizacion =  $Paciente->saldo_a_favor - $request->saldo_a_usar; 
             
             $venta->PagoSaldo=$request->saldo_a_usar;
            
             $Paciente->update(['saldo_a_favor' => $actualizacion]);      
             }else{
                
                return redirect()
                ->back()
                ->withErrors(['Error saldo a favor insuficiente'])
                ->withInput($request->input());           
        }
        
        //    if (!($request->PagoEfectivo + $request->PagoTarjeta + $request->saldo_a_usar+ $request->sigpesos_usar == round($request->total, 2))) {
        //     return redirect()
        //         ->back()
        //         ->withErrors(['Error con importes de montos en efectivo o tarjeta'])
        //         ->withInput($request->input());

        // }
        }

        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 1,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addMonths(5),
                'fecha_aviso' => Carbon::now()->addMonths(5),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $CRM = new Crm(
            array(
                'paciente_id' => $request->input('paciente_id'),
                'estado_id'   => 5,
                'hora'        => '00:00',
                'forma_contacto' => 'Telefono',
                'fecha_contacto' => Carbon::now()->addDays(8),
                'fecha_aviso' => Carbon::now()->addDays(8),
                'oficina_id' => session('oficina')

            )
        );
        $CRM->save();
        $sigvariscard = new sigvariscard(
            array(
                'paciente_id'=> $request->paciente_id,
                'folio'=>$request->SigvarisCardFolio,
                'tipo'=>$request->SigvarisCard,
                'venta_id'=>$venta->id
            )
        );
        $sigvariscard->save();
          if ($request->sigpesos_usar>0) {
                if ($request->input('tipoPago') == 3 ||$request->input('tipoPago') == 4 ) {
             //Sigpesos 
             foreach ($request->folio as $key => $folio) {
                    # code...
                    
            $new_fo = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    $existe = DB::table('sigpesosventa')->where('folio',$folio)->exists();
                    if ($existe = true) {
                        DB::table('sigpesosventa')->where('folio','=', $folio)->where('usado','=',0)->increment('usado');
                        // dd("Actualizado");
                        DB::table('sigpesosventa')->where('folio','=', $folio)->update(['venta_id' => $venta->id]);
                        // DB::table('sigpesosventa')->where('folio','=', $folio)->update(['tipo' =>'pago']);
                       
                    }
                    // dd($new_fo);
                    if($new_fo == false ){
                        $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => $request->monto[$key],
                        'folio' => $folio,
                        'folio_id' => $request->lista[$key],
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'pago',
                        'usado'=>1
                         ]);
                        
                        $Sigpesos->save();
                       
                        
                    }
                    
                        # code...
                    
                           
                      }

                    }    
                }
                  if ($request->descuento_id == 41) {
            $folio = Folio::find(1);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 1000,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'esencial',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }if ($request->descuento_id == 30) {
              $folio = Folio::find(6);
        // Contamos los registros en Sigpesosventa, y aqui sera el consecutivo que tendra el folio
        // 
            $ultimo = DB::table('sigpesosventa')->where('folio_id','=',$folio->id)->orderBy('id','desc')->value('folio');
           $cuenta = Sigpesosventa::count();
           // $prueba = Sigpesosventa ::where('folio_id','=',$folio->id)->orderBy('id','desc')->get();
           if ($ultimo == 0) {
               $ultimo = $folio->rango_inferior;
           }
             $Sigpesos = new Sigpesosventa([
                        'venta_id' => $venta->id,
                        'monto' => 300,
                        'folio' => $ultimo+1,
                        'folio_id' => $folio->id,
                        'paciente_id'=>$request->paciente_id,
                        'tipo'=>'cupon producto negado',
                        'usado'=>0

                    ]);
                $Sigpesos->save();
                 $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                 // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]);
        }
              if ($request->sigpesos != 0) {
             $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }

        // HistorialCambioVenta::create([
        //     'tipo_cambio' => 'CAMBIO PRODUCTO',
        //     'responsable_id' => Auth::user()->id,
        //     'venta_id' => $request->VentaAnterior,
        //     'destinate_id'=>$venta->id,
        //     'producto_entregado_id' =>  $productos[0]->id,
        //     'producto_devuelto_id' => $request->productoDevuelto,
        //     'observaciones' => $request->observacionesDevuelto
        // ]);

        $HistorialCambioVenta = new HistorialCambioVenta(
            array(
                'tipo_cambio' => 'CAMBIO PRODUCTO',
            'responsable_id' => Auth::user()->id,
            'venta_id' => $request->VentaAnterior,
            'destinate_id'=>$venta->id,
            'producto_entregado_id' =>  $productos[0]->id,
            'producto_devuelto_id' => $request->productoDevuelto,
            'observaciones' => $request->observacionesDevuelto,
            'precioOri'=>$request->precioOri,
            'precioNew' =>$request->precioNew,
            'pagosaldo'=>$request->saldo_a_usar
            )
        );

        $ProductoDevuelto = Producto::where('id', $request->productoDevuelto)->first();

        $ProductoDevuelto->update([
            'stock' => $ProductoDevuelto->stock + 1
        ]);

        $consulta = HistorialCambioVenta::where('venta_id',$request->VentaAnterior)->where('descuento_cu',1)->get();
       if ($venta_cu->descuento_cu == 1 ) {
                        $HistorialCambioVenta->descuento_cu = 1;
            }
         if (count($consulta) >= 1) {
            $HistorialCambioVenta->descuento_cu = 0;
        }

            $HistorialCambioVenta->save();
             if ($Paciente->sigpesos_a_favor>0) {
                $sigpesos_paciente = $Paciente->sigpesos_a_favor-$request->sigpesos_usar;
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 

            }else if($request->sigpesos>0){

                $sigpesos_paciente = $Paciente->sigpesos_a_favor+$request->sigpesos;
                // dd($sigpesos_paciente);
                $Paciente->update(['sigpesos_a_favor' => $sigpesos_paciente]); 
            }
       

        // REDIRIGIR A LAS VENTAS REALIZADAS
        return redirect()->route('ventas.index');
    }
}


//SELECT `producto_venta`.`producto_id`, SUM(`producto_venta`.`cantidad`) AS TotalVentas FROM `producto_venta` GROUP BY `producto_venta`.`producto_id` ORDER BY SUM(`producto_venta`.`cantidad`) DESC LIMIT 0 , 30 
