<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class piezasMe extends Model
{
    protected $table = 'piezas_mes';
    protected $fillable = ['id','producto_id','SKU'];
    public $timestamps = true;
}
