<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('productos/negados/folios/next', 'Producto\ApiProductoNegadoController@getNextFolio');
Route::get('productos/sku/{sku}', 'Producto\ApiProductoController@getProductoBySku');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('pacientes/{paciente}/datos_fiscales', 'ApiPacienteDatosFiscalesController@get');

Route::get('pacientes/{paciente}/inapam', 'ApiPacienteDatosFiscalesController@getinapam');
Route::get('ventas/calcular-diferencia', 'ApiVentaController@calcularDiferencia');