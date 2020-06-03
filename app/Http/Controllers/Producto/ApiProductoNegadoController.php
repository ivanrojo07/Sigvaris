<?php

namespace App\Http\Controllers\Producto;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Negado;

class ApiProductoNegadoController extends Controller
{
    public function getNextFolio()
    {
        $productoNegado = Negado::whereNotNull('folio')->orderBy('id','desc')->first();
        $folio = $productoNegado ? $productoNegado->folio : 0;

        return response()->json([
            'folio' => intval($folio)+1
        ]);
    }
}
