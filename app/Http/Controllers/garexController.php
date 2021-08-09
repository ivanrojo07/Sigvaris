<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Garex;
use DB;
class garexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $garex = Garex::get();
       // dd($retex);
        return view('garex.index', ['garex'=>$garex]);
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }
    public function getGarex(Request $request){

        // dd($request->nombre);
        $sku= Garex::where('sku',$request->nombre)->get();
        // dd( $sku);
           if ( count($sku)>0) {
                  $ajaxGarex=array();
        $garex=Garex::where('sku',$request->nombre)->get();
        $folios = count(DB::table('garex_ventas')->get());
        foreach ($garex as $ga) {
            // dd($ga, $folios);
            array_push ($ajaxGarex,[ 
                                        '<span garexId="'.$ga->id.'" class="garex_precio">'.$ga->id.'</span>',
                                        '<span garexId="'.$ga->id.'" class="garex_precio" name="garex_precio">'.'$'.$ga->precio_publico_iva.' </span>',
                                        '<button type="button" class="btn btn-success botonSeleccionGarex rounded-0" garexId="'.$ga->id.'" precio_publico_iva="'.$ga->precio_publico_iva.' "onclick="agregarGarex(\'#garex_precio\')" value=\''.json_encode($ga).'\'>
                                            <i class="fas fa-plus"></i>
                                        </button>',
                                        '<span  garexId="'.$ga->id.'garex_precio" name="garex_precio" id="garex_precio" value=\''.json_encode($ga).'\'>'.$ga->sku.'-'.$folios.'</span>',
                                        '<span hidden garexId="'.$ga->id.'garex_precio" name="folios_garex" id="folios_garex" value=\''.json_encode($folios).'\'>'.$ga->id.'</span>',
                                        // '<input type="text" class="btn btn-success boton_agregar" id="garex_a_agregar" value=\''.json_encode($ga).'\'  onclick="agregarGarex(\'#garex_a_agregar\')>',

                                        ]);
        }


        return json_encode(['data'=> $ajaxGarex]);
           } else{

            return json_encode(['data'=> '']);;
           }
     
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
