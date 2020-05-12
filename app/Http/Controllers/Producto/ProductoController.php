<?php

namespace App\Http\Controllers\Producto;

use App\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            if(Auth::check()) {
                if(Auth::user()->role->productos)
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
        //
        $productos = Producto::get();
        return view('producto.index', ['productos'=>$productos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('producto.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->input('stock'));
        Producto::updateOrCreate(['sku'=>$request->input('sku')], $request->all());
        //$producto = Producto::create($request->all());
        return redirect()->route('productos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return view('producto.show',['producto'=>$producto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        return view('producto.edit', ['producto'=>$producto]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->all());
        return view('producto.show',['producto'=>$producto]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index');
    }


    public function getProductosNombre(Request $request)
    {
        $ajaxProductos=array();
        $Productos=Producto::
                    where('sku','like',$request->input('nombre').'%')
                    ->orwhere('upc','like',$request->input('nombre').'%')
                    ->orwhere('swiss_id','like',$request->input('nombre').'%')
                    ->get();
        //dd($Productos);
        foreach ($Productos as $Producto) {
            $Producto->descripcion=str_replace("'", "´", $Producto->descripcion);
            array_push ($ajaxProductos,[$Producto->sku,$Producto->upc,$Producto->swiss_id,$Producto->descripcion,'$'.$Producto->precio_publico,'$'.$Producto->precio_publico_iva,'<input class="btn btn-success boton_agregar" type="hidden" id="producto_a_agregar'.$Producto->id.'" value=\''.json_encode($Producto).'\' onclick="agregarProducto(\'#producto_a_agregar'.$Producto->id.'\')">   </input> <button type="button" class="btn btn-success boton_agregar" onclick="agregarProducto(\'#producto_a_agregar'.$Producto->id.'\')"><i class="fas fa-plus"></i></button>']);
        }
        //dd($ajaxProductos);
        return json_encode(['data'=> $ajaxProductos]);
    }
    public function getProductoExists(Request $request)
    {
        if (Producto::where('sku',$request->input('sku'))
                    ->orWhere('upc',$request->input('sku'))
                    ->orWhere('swiss_id',$request->input('sku'))
                    ->exists()) {
            return 1;
        }else{
            return 0;
        }
        //dd(Producto::where('sku',$request->input('sku'))->get());
        //return Producto::where('sku',$request->input('sku'))->exists();
    }
    public function getProductoExistsDesc(Request $request)
    {
        $Producto=Producto::where('oficina_id',session('oficina'));
        if ($Producto::where('sku',$request->input('sku'))
                    ->orWhere('upc',$request->input('sku'))
                    ->orWhere('swiss_id',$request->input('sku'))
                    ->exists()) {
            $Producto=$Producto::where('sku',$request->input('sku'))
                    ->orWhere('upc',$request->input('sku'))
                    ->orWhere('swiss_id',$request->input('sku'))
                    ->get();
            if (count($Producto)==1) {
                $ProductoActualizar=Producto::updateOrCreate(['id'=>$Producto[0]->id],[
                    'stock'=>$Producto[0]->stock+1
                ]);
                return $Producto[0];
            }else{
                return 0;
            }

            
        }
        //dd($ajaxProductos);
        else{
            return 0;
        }
        //dd(Producto::where('sku',$request->input('sku'))->get());
        //return Producto::where('sku',$request->input('sku'))->exists();
    }
    
}
