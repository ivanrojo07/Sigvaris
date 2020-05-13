<?php

namespace App\Http\Controllers\Paciente;

use App\Crm;
use App\Estado;
use UxWeb\SweetAlert\SweetAlert as Alert;
use App\Paciente;
use App\Venta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FindCrmRequest;
use Illuminate\Support\Facades\Auth;

class PacienteCrmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                if (Auth::user()->role->crm) {
                    return $next($request);
                }
                return redirect('/inicio');
            }
            return redirect('/');
        });
    }
    public function index(request $request)
    {
        $crms = Crm::with('paciente');
        if ($request->fechaInicioBusqueda) {
            $crms = $crms->where('fecha_aviso', '>=', $request->fechaInicioBusqueda);
             
        }

        if ($request->fechaFinBusqueda) {
            $crms = $crms->where('fecha_aviso', '<=', $request->fechaFinBusqueda);

        }
       
        $crms=$crms->where('oficina_id',session('oficina'));
        
        $crms = $crms->paginate(10);
        if ($request->fechaInicioBusqueda) {
            $crms->appends(['fechaInicioBusqueda' => $request->fechaInicioBusqueda]);
        }

        if ($request->fechaFinBusqueda) {
           $crms->appends(['fechaFinBusqueda' => $request->fechaFinBusqueda]);
        }
       
        
        
        $estados = Estado::get();
        $pacientes = Paciente::get();
        $ventas =Venta::get();
        return view('crm.index', ['crms' => $crms, 'pacientes' => $pacientes, 'estados' => $estados,'ventas' => $ventas]);
        /*$estados = Estado::get();
        $pacientes = Paciente::get();
        $crms = Crm::paginate(10);
        if ($request->fechaInicioBusqueda) {
            $crms->appends(['fechaInicioBusqueda' => $request->fechaInicioBusqueda]);
        }

        if ($request->fechaFinBusqueda) {
           $crms->appends(['fechaFinBusqueda' => $request->fechaFinBusqueda]);
        }
        return view('crm.index', ['crms' => $crms, 'pacientes' => $pacientes, 'estados' => $estados]);
        */
    }

    public function indexWithFind(FindCrmRequest $request)
    {
        $crms = Crm::with('paciente');
        if ($request->fechaInicioBusqueda) {
            $crms = $crms->where('fecha_aviso', '>=', $request->fechaInicioBusqueda);
             
        }

        if ($request->fechaFinBusqueda) {
            $crms = $crms->where('fecha_aviso', '<=', $request->fechaFinBusqueda);

        }
       
         
        $crms = $crms->paginate(10);
        if ($request->fechaInicioBusqueda) {
            $crms->appends(['fechaInicioBusqueda' => $request->fechaInicioBusqueda]);
        }

        if ($request->fechaFinBusqueda) {
           $crms->appends(['fechaFinBusqueda' => $request->fechaFinBusqueda]);
        }
       
        
        
        $estados = Estado::get();
        $pacientes = Paciente::get();

        return view('crm.index', ['crms' => $crms, 'pacientes' => $pacientes, 'estados' => $estados]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$request->input('paciente_id')=$request->input('paciente_id1');
        $crm = Crm::create($request->all());
        if ($crm) {
            Alert::success('Crm registrado subido correctamente.');
            return redirect()->route('crm.index');
        } else {
            Alert::error('Error al registrar crm.');
            return redirect()->back();
        }
    }

    public function storePaciente(Request $request)
    {
        //$request->input('paciente_id')=$request->input('paciente_id1');
        $crm = Crm::create($request->all());
        if ($crm) {
            Alert::success('Crm registrado subido correctamente.');
            $estados = Estado::get();
            $paciente= Paciente::where('id',$request->input('paciente_id'))->get();
            return view('pacientecrm.index', ['paciente' => $paciente[0], 'estados' => $estados]);
        } else {
            Alert::error('Error al registrar crm.');
            return redirect()->back();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function show(Crm $crm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function edit(Crm $crm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crm $crm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Crm  $crm
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crm $crm)
    {
        //
    }

    public function getCrmCliente(Paciente $paciente)
    {
        $estados = Estado::get();
        return view('pacientecrm.index', ['paciente' => $paciente, 'estados' => $estados]);
    }
    
    public function getTabla_modalidad_ventas(Request $request)
    {
        $paciente = Paciente::where('id',$request->input('id'))->get();
        //var_dump($request->input('id'));
        $VentaCliente = Venta::where('paciente_id',$request->input('id'))->get();
        
        //dd($crmsCli);
        //$tablaUsuario = array( );
        $tablaUsuario="";
        foreach ($VentaCliente as $Venta) {
            /**array_push ($tablaUsuario,[$request->input('nombre'), $crm->created_at ? $crm->created_at->format('d-m-Y') : null,$crm['fecha_aviso']
                                       ,$crm['fecha_contacto'],$crm['forma_contacto'],$crm->estado['nombre']
                                       ,$crm['hora']]) ;**/

            
             $tablaUsuario.= '<tr class="active tupla">';
            $tablaUsuario.= "<td >".$Venta['id']."</td>"; 
            $tablaUsuario.= "<td >".$Venta->paciente->fullname."</td>";  
            $tablaUsuario.= "<td >$".number_format($Venta->total, 2)."</td>";
            $tablaUsuario.= "<td >";
            foreach ($Venta->productos as $Producto) {
                 $tablaUsuario.= $Producto->descripcion."<br>";
             } 
             $tablaUsuario.= "</td >";
            
            $tablaUsuario.= "<td >".\Carbon\Carbon::parse($Venta->fecha)->format('m/d/Y')."</td>";
            $tablaUsuario.= "</tr>";
        }
        //dd($tablaUsuario);
        //$tablaUsuario.="</table>";

        //return array('data' => $tablaUsuario );
        return $tablaUsuario;
    }

     public function getCrmClienteCrm(Request $request)
    {
        $paciente = Paciente::where('id',$request->input('id'))->get();
        //var_dump($request->input('id'));
        $crmsCliente = Crm::where('paciente_id',$request->input('id'))->get();
        $crms= Crm::get();
        //dd($crmsCli);
        //$tablaUsuario = array( );
        $tablaUsuario="";
        foreach ($crmsCliente as $crm) {
            $estados = Estado::where('id',$crm->estado_id)->get();
            /**array_push ($tablaUsuario,[$request->input('nombre'), $crm->created_at ? $crm->created_at->format('d-m-Y') : null,$crm['fecha_aviso']
                                       ,$crm['fecha_contacto'],$crm['forma_contacto'],$crm->estado['nombre']
                                       ,$crm['hora']]) ;**/

            
             $tablaUsuario.= '<tr class="active tupla" title="Has Click Aquì para ver o modificar" style="cursor: pointer"  id="crear_crm_boton"  data-toggle="modal" data-target="#ver_crm_modal" onclick="mostrarCrmHistorial('.$crm->id.',\''.$crm->estado['nombre'].'\')">';
            $tablaUsuario.= "<td >".$request->input('nombre')."</td>"; 
            $tablaUsuario.= "<td >".\Carbon\Carbon::parse($crm['created_at'])->format('m-d-Y')."</td>";  
            $tablaUsuario.= "<td >".\Carbon\Carbon::parse($crm['fecha_aviso'])->format('m-d-Y')."</td>";     
            $tablaUsuario.= "<td >".\Carbon\Carbon::parse($crm['fecha_contacto'])->format('m-d-Y')."</td>";  
            $tablaUsuario.= "<td >".$crm['forma_contacto']."</td>";
            $tablaUsuario.= "<td >".$crm->estado['nombre']."</td>";    
            $tablaUsuario.= "<td >".$crm['hora']."</td>";  
            $tablaUsuario.= "</tr>";
        }
        //dd($tablaUsuario);
        //$tablaUsuario.="</table>";

        //return array('data' => $tablaUsuario );
        return $tablaUsuario;
    }
    public function getCrm(Request $request)
    {
        
        $crm = Crm::where('id',$request->input('id'))->get();
        $crm=$crm[0];
        $paciente = Paciente::where('id',$crm->paciente_id)->get();
        return json_encode([$crm,$paciente[0]]);
    }
}
