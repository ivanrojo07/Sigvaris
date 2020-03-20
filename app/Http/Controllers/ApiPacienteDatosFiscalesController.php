<?php

namespace App\Http\Controllers;

use App\Paciente;
use Illuminate\Http\Request;

class ApiPacienteDatosFiscalesController extends Controller
{
    public function get(Paciente $paciente)
    {
        return response()->json($paciente->datoFiscal);
    }
}