<?php

namespace App\Services\Damage;

use App\Producto;
use App\ProductoDamage;

class AnadirProductoAlAmacenDamageService
{

    protected $productoDamage;
    protected $producto;
    protected $tipoDamage;

    public function __construct(Producto $producto, $tipoDamage)
    {
        $this->producto = $producto;
        $this->tipoDamage = $tipoDamage;
    }

    public function execute()
    {
        $this->productoDamage = new ProductoDamage;
        $this->productoDamage->producto_id = $this->producto->id;
        $this->productoDamage->tipo_damage = $this->tipoDamage;
        $this->productoDamage->save();
    }
}
