<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    protected $table='descuentos';

    protected $fillable = [
        'id',
        'nombre',
        'inicio',
        'fin',
        'descripcion'
    ];
    
    public function ventas(){
        return $this->hasMany('App\Venta');
    }

    public function promociones(){
        return $this->hasMany('App\Promocion','descuento_id');
    }

    public function promocionesProductos(){
        return $this->hasMany('App\PromocionEnProducto');
    }
}
