<?php

namespace App\Http\Controllers\Paciente;

use App\Paciente;
use App\Doctor;
use App\Nivel;
use UxWeb\SweetAlert\SweetAlert as Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(Auth::check()) {
                if(Auth::user()->role->pacientes)
                {
                    return $next($request);
                }                
                return redirect('/inicio');
                 
            }
            return redirect('/');            
        });
    }
    public function index(Request $request)
    {
        $busqueda=$request->search;
        if($busqueda)
        {
            $palabras_busqueda=explode(" ",$busqueda);
            $pacientes=Paciente::where(function($query)use($palabras_busqueda){

                foreach ($palabras_busqueda as $palabra) {
                    $query->where('nombre','like',"%$palabra%" )->orWhere('paterno','like',"%$palabra%")->orWhere('materno','like',"%$palabra%"); 
                }
            })->paginate(10);    
        }
        else
        {
            $pacientes = Paciente::paginate(10);    
        }
        
        return view('paciente.index', ['pacientes'=> $pacientes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $niveles = Nivel::get();
        return view('paciente.create', ['niveles'=>$niveles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        //dd(session()->get('oficina'));
        if($request->rfc && $request->nacimiento)
        {
            $paciente = Paciente::create($request->all());
            //dd(session()->get('oficina'));
            if(session()->get('oficina'))
            {
                //dd($paciente);
                $paciente->oficina_id=session()->get('oficina');
                $paciente->save();
            }
            
        }
        elseif($request->rfc==null && $request->nacimiento!=null)
        {   
            $time=strtotime($request->nacimiento); 
            $year=date("Y",$time);  
            $rfc=substr($request->paterno,0,2);
            $rfc.=substr($request->materno,0,1);
            $rfc.=substr($request->nombre,0,1);        
            $rfc.=substr($year,2,3);
            $rfc.=date("m",$time);
            $rfc.=date("d",$time);
            $paciente=new Paciente([
                'nombre'=>$request->nombre,
                'materno'=>$request->materno,
                'paterno'=>$request->paterno,
                'nacimiento'=>$request->nacimiento,
                'rfc'=>strtolower($rfc),
                'celular'=>$request->celular,
                'telefono'=>$request->telefono,
                'mail'=>$request->mail,
                'otro_doctor'=>$request->otro_doctor,
                'doctor_id'=>$request->doctor_id,
                'nivel_id'=>$request->nivel_id,
                //dd(session()->get('oficina')),

                
            ]);
            if (session()->get('oficina')) {

                $paciente->oficina_id=session()->get('oficina');
            }
           // dd($paciente);
            $paciente->save();
            
        }
        elseif($request->nacimiento==null && $request->rfc!=null){
            $birth=strtotime("19".substr($request->rfc,-6));
            $fecha=date('Y-m-d',$birth); 
            $paciente=new Paciente([
                'nombre'=>$request->nombre,
                'materno'=>$request->materno,
                'paterno'=>$request->paterno,
                'nacimiento'=>$fecha,
                'rfc'=>$request->rfc,
                'celular'=>$request->celular,
                'telefono'=>$request->telefono,
                'mail'=>$request->mail,
                'otro_doctor'=>$request->otro_doctor,
                'doctor_id'=>$request->doctor_id,
                'nivel_id'=>$request->nivel_id,
                //dd(session()->get('oficina')),
                
            ]);
            //dd($paciente);
            if (session()->get('oficina')) {
                
                $paciente->oficina_id=session()->get('oficina');
            }
            $paciente->save();  
            // dd($fecha);
        }
        else{
            $paciente=new Paciente([
                'nombre'=>$request->nombre,
                'materno'=>$request->materno,
                'paterno'=>$request->paterno,
                //'nacimiento'=>$fecha,
                //'rfc'=>$request->rfc,
                'celular'=>$request->celular,
                'telefono'=>$request->telefono,
                'mail'=>$request->mail,
                'otro_doctor'=>$request->otro_doctor,
                'doctor_id'=>$request->doctor_id,
                'nivel_id'=>$request->nivel_id,
                //dd(session()->get('oficina')),
                
            ]);
            //dd($paciente);
            if (session()->get('oficina')) {
                
                $paciente->oficina_id=session()->get('oficina');
            }
            $paciente->save();  
        }
        return redirect()->route('pacientes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show($paciente)
    {
        $temp = Paciente::find($paciente);
        return view('paciente.show',['paciente'=>$temp]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function edit($paciente)
    {
        $temp = Paciente::find($paciente);
        $niveles = Nivel::get();
        return view('paciente.edit',['paciente'=>$temp, 'niveles'=>$niveles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $paciente)
    {
        $temp = Paciente::find($paciente);
        $temp->update($request->all());
        return redirect()->route('pacientes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy($paciente)
    {
        $temp = Paciente::find($paciente);
        $temp->delete();
        $pacientes = Paciente::paginate(10);
        return view('paciente.index', ['pacientes'=>$pacientes]);
    }
}
