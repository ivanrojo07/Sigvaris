<?php

namespace App\Http\Controllers\Venta;

use App\Descuento;
use App\Promocion;
use App\Paciente;
use App\Producto;
use App\PromocionEnProducto;
use App\Venta;
use App\Folio;
use DateInterval;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class FoliosSigpesosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(Auth::check()) {
                if(Auth::user()->role->precargas)
                {
                    return $next($request);
                }                
             return redirect('/inicio');
                 
            }
            return redirect('/'); 
        });
    }
    public function index()
    {   
        return view('venta.foliosSigpesos.index', ['folios'=>Folio::get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('venta.foliosSigpesos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Folio = Folio::create($request->all());
        return view('venta.foliosSigpesos.index', ['folios'=>Folio::get()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function show(Descuento $descuento)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function edit(Descuento $descuento)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Descuento $descuento)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Descuento  $descuento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folio $folio)
    {
        //
         $folio->delete();
         return view('venta.foliosSigpesos.index', ['folios'=>Folio::get()]););
    }
    
}
