<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistorialCambioVenta extends Model
{
    protected $table = "historial_cambios_venta";
    protected $fillable = ['tipo_cambio', 'responsable_id', 'venta_id', 'producto_entregado_id', 'producto_devuelto_id', 'observaciones'];

    /**
     * =============
     * RELATIONSHIPS
     * =============
     */

    public function responsable()
    {
        return $this->belongsTo('App\User', 'responsable_id', 'id');
    }

    public function productoEntregado()
    {
        return $this->belongsTo('App\Producto', 'producto_entregado_id', 'id');
    }

    public function productoDevuelto()
    {
        return $this->belongsTo('App\Producto', 'producto_devuelto_id', 'id');
    }
}
