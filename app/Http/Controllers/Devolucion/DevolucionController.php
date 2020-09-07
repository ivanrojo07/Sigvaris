<?php

namespace App\Http\Controllers\Devolucion;

use App\Devolucion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\DevolucionExport;
/* use App\Exports\SheetsDExport; */
use SheetsDExport;
use Maatwebsite\Excel\Facades\Excel;

class DevolucionController extends Controller
{
    //
    public function index(Request $request)
    {

    	# code...
    }
    public function cargarDevolucion(Request $request)
    {
    	$Devolucion=new Devolucion(
    		array(
    			'venta_id' => $request->input("venta_id"),
    			'monto' => $request->input("MONTO"),
    			'cuenta' => $request->input("cuenta"),
    			'beneficiario' => $request->input("beneficiario"),
    			'referencia' => $request->input("referencia"),
    			'clave' => $request->input("clave"),
    			'banco' => $request->input("banco")
    		)
    	);
    	$Devolucion->save();
    	$Devoluciones=Devolucion::get();
    	return view('devolucion.index', compact('Devoluciones'));
    }
    public function indexAll()
    {
    	$Devoluciones=Devolucion::get();
    	return view('devolucion.index', compact('Devoluciones'));
	}
	public function export() 
    {
        return Excel::download(new DevolucionExport, 'devoluciones.xlsx');
    }
}
