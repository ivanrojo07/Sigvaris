<?php

namespace App\Http\Controllers;

use App\Exports\DatosFiscalesExport;
use App\Exports\FacturasExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DatosFiscalesController extends Controller
{
    public function download(Request $request)
    {
        // dd($request->fecha);
        return Excel::download(new DatosFiscalesExport($request->fecha), 'datos-fiscales.xlsx');
    }
}
