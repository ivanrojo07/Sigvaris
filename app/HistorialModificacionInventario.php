<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialModificacionInventario extends Model
{
    protected $table = 'historial_modificaciones_inventario';
    protected $fillable=['user_id','producto_id','stock_anterior','stock_nuevo','motivo'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

}
