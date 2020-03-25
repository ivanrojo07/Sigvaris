<?php

namespace App\Http\Controllers\Paciente;

use App\Paciente;
use App\PacientesExpedientes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use UxWeb\SweetAlert\SweetAlert as Alert;

class PacienteExpedienteController extends Controller
{
     public function index(Paciente $paciente)
    {
        $expediente = $paciente->expediente;
        if ($expediente == null) {
            return view('pacineteexpediente.create',['paciente'=>$paciente]);
            //return redirect()->route('pacineteexpediente.create',['paciente'=>$paciente]);
        }
        else {
            return view('pacineteexpediente.view',['paciente'=>$paciente, 'expediente'=>$expediente]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Paciente $paciente)
    {
        
        if ($request->input('Actualizar')!=null) {
            return view('pacineteexpediente.create', ['paciente' => $paciente,'expediente' => $paciente->expediente]);
        }else{
            return view('pacineteexpediente.create', ['paciente' => $paciente]);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Paciente $paciente)
    {
       
        if ($request->aviso_privacidad && $request->file('aviso_privacidad')->isValid()) {
            $aviso_privacidad = explode("/",$request->aviso_privacidad->storeAs('expedientes/'.$paciente->id, 'aviso_privacidad.'.$request->aviso_privacidad->extension(), 'public'));
        }
        if ($request->identificacion && $request->file('identificacion')->isValid()) {
            $identificacion = explode("/",$request->identificacion->storeAs('expedientes/'.$paciente->id, 'identificacion.'.$request->identificacion->extension(), 'public'));
        }
        if ($request->inapam && $request->file('inapam')->isValid()) {
            $inapam = explode("/",$request->inapam->storeAs('expedientes/'.$paciente->id, 'inapam.'.$request->inapam->extension(), 'public'));
        }

        if (!isset($aviso_privacidad)) {
            $aviso_privacidad=null;
        }else{
            $aviso_privacidad=$aviso_privacidad[2];
        }
        if (!isset($identificacion)) {
            $identificacion=null;
        }else{
            $identificacion=$identificacion[2];
        }
        if (!isset($inapam)) {
            $inapam=null;
        }else{
             $inapam=$inapam[2];
        }
        $expediente = PacientesExpedientes::updateOrCreate(['paciente_id'=>$paciente->id],[
            
            'aviso_privacidad'=>$aviso_privacidad,
            'identificacion'=>$identificacion,
            'inapam'=>$inapam
        ]);
        Alert::success('Información Agregada', 'Se ha registrado correctamente la información');
        return view('pacineteexpediente.view',['paciente'=>$paciente,'expediente'=>$expediente]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmpleadoExpediente  $empleadoExpediente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente, PacientesExpedientes $empleadoExpediente)
    {
        return view('pacineteexpediente.view',['paciente'=>$paciente,'expediente'=>$paciente->expediente]);
    }
}
