<?php

namespace App\Http\Controllers\Doctor;

use App\Doctor;
use UxWeb\SweetAlert\SweetAlert as Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(Auth::check()) {
                if(Auth::user()->role->doctores)
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
            $doctores=Doctor::where(function($query)use($palabras_busqueda){

                foreach ($palabras_busqueda as $palabra) {
                    $query->where('nombre','like',"%$palabra%" )->orWhere('apellidopaterno','like',"%$palabra%")->orWhere('apellidomaterno','like',"%$palabra%"); 
                }
            })->where('activo', '!=', '0')->paginate(10);
        }
        else
        {
            $doctores = Doctor::where('activo', '!=', '0')->paginate(10);
        }
        

        return view('doctor.index', ['doctores'=> $doctores]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('doctor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request,Auth::user()->id);
        $doctor = Doctor::create($request->all());
        // $doctor->empleado_id =  $request->empleado_id;
        // $doctor->save();
        return redirect()->route('doctores.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show($doctor)
    {
        $temp = Doctor::find($doctor);
        return view('doctor.show',['doctor'=>$temp]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit($doctor)
    {
        $temp = Doctor::find($doctor);
        return view('doctor.edit',['doctor'=>$temp]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $doctor)
    {
        $temp = Doctor::find($doctor);
        $temp->nombre = $request->input('nombre');
        $temp->apellidopaterno = $request->input('apellidopaterno');
        $temp->apellidomaterno = $request->input('apellidomaterno');
        $temp->celular = $request->input('celular');
        $temp->mail = $request->input('mail');
        $temp->nacimiento = $request->input('nacimiento');
        $temp->save();
        return redirect()->route('doctores.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy($doctor)
    {
        $temp = Doctor::find($doctor);
        $temp->activo=0;
        $doctores = Doctor::where('activo', '!=', '0')->get();
        return view('doctor.index', ['doctores'=>$doctores]);
    }

    public function getDoctores(){
        $doctores = Doctor::where('activo', '!=', '0')->get();
        return view('doctor.options', ['doctores'=>$doctores]);
    }
    
    public function getDoctoresTable(Request $request){
        $doctores = Doctor::where('activo', '!=', '0');
        $doctores = $doctores
                            ->where('nombre',"like",$request->input('nombre')."%") 
                            ->orwhere('apellidopaterno',"like",$request->input('nombre')."%")
                            ->orwhere('apellidomaterno',"like",$request->input('nombre')."%")
                            ->get();
        return view('doctor.optionstable', ['doctores'=>$doctores]);
    }

    public function borrar(Doctor $doctor)
    {
 //        echo "<script language='javascript'> swal({
 //   title: '¡ERROR!',
 //   text: 'Esto es un mensaje de error',
 //   type: 'error',
 // });</script>";
        $doctor->activo=0;
        $doctor->save();
        return back();
    }
}


