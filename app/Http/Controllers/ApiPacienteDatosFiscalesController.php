<?php

namespace App\Http\Controllers;

use App\Paciente;
use Illuminate\Http\Request;

class ApiPacienteDatosFiscalesController extends Controller
{
    public function get(Paciente $paciente)
    {
        return response()->json([
            'datosFiscales' => $paciente->datoFiscal,
            'saldo_a_favor' => $paciente->saldo_a_favor
        ]);
    }
    public function getinapam(Paciente $paciente)
    {
    	if ($paciente->expediente()->first()!=null) {
            if ($paciente->expediente()->first()->inapam==null) {
                return response()->json("1");
            }
        } else {
            return response()->json("1");
        }
        return response()->json("0");
        
    }
}