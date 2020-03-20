<?php

namespace App\Http\Controllers\Paciente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Paciente;

class PacienteDatosFiscalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Paciente $paciente)
    {
        return view("paciente.datos_fiscales.create", compact("paciente"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Paciente $paciente)
    {
        // dd($request->input());
        $paciente->datoFiscal()->updateOrCreate([
            'paciente_id' => $paciente->id
        ], [
            'tipo_persona' => $request->tipo_persona,
            'nombre_o_razon_social' => $request->nombre_o_razon_social,
            'regimen_fiscal' => $request->regimen_fiscal,
            'homoclave' => $request->homoclave,
            'correo' => $request->correo,
            'rfc' => $request->rfc,
            'calle' => $request->calle,
            'num_ext' => $request->num_ext,
            'num_int' => $request->num_int,
            'colonia' => $request->colonia,
            'ciudad' => $request->ciudad,
            'alcaldia_o_municipio' => $request->alcaldia_o_municipio,
            'estado' => $request->estado,
            'codigo_postal' => $request->codigo_postal,
        ]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        dd($request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
