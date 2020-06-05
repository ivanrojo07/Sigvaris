<?php

namespace App\Http\Controllers\Producto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Negado;
use Illuminate\Support\Facades\Auth;

class ApiProductoNegadoController extends Controller
{
    public function getNextFolio(Request $request)
    {
        $productoNegado = Negado::whereNotNull('folio')
            ->orderBy('id','desc')
            ->where('oficina_id', $request->input('oficina_id') )
            ->first();
        $folio = $productoNegado ? $productoNegado->folio : 0;

        return response()->json([
            'folio' => intval($folio)+1
        ]);
    }
}
