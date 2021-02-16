<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'nombre',
        'materno',
        'paterno',
        'nacimiento',
        'rfc',
        'homoclave',
        'celular',
        'telefono',
        'mail',
        'otro_doctor',
        'doctor_id',
        'nivel_id',
        'oficina_id',
        'saldo_a_favor',
        'sigpesos_a_favor'
    ];

    public function consultorios()
    {
        return $this->morphMany('App\Consultorio', 'consultable');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function crms()
    {
        return $this->hasMany('App\Crm');
    }

    public function datoFiscal()
    {
        return $this->hasOne('App\DatoFiscal');
    }

    public function tallas()
    {
        return $this->hasMany('App\Talla');
    }

    public function historial()
    {
        return $this->hasMany('App\RegistroHistorial');
    }
    public function tutor()
    {
        return $this->hasOne('App\Tutor');
    }

    public function ventas()
    {
        return $this->hasMany('App\Venta');
    }
    public function negados()
    {
        return $this->hasMany('App\Negado');
    }

    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'nivel_id');
    }

    public function oficina()
    {
        return $this->belongsTo('App\Oficina');
    }
    public function expediente()
    {
        return $this->hasOne('App\PacientesExpedientes');
    }
    /**
     * Collections from relationship
     */

    public function productos()
    {
        return $this->ventas()->with('productos')->get()->pluck('productos')->flatten();
    }

    public function getFullnameAttribute()
    {
        return $this->nombre . ' ' . $this->paterno . ' ' . $this->materno;
    }

    public function totalProductos()
    {
        $ventas = Venta::where('paciente_id', $this->id)->get();

        $total_productos = 0;
        foreach ($ventas as $venta) {
            $total_productos += count($venta->productos()->get());
        }

        return $total_productos;
    }
     public function sigvariscards(){
            return $this->hasMany('App\sigvariscard');
        }

    /**
     * Scope methos
     */

    public function scopeNoCompradores($query)
    {
        return $query->doesntHave('ventas');
    }
}
