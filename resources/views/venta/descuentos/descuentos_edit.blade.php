@extends('principal')
@section('content')
<div class="container">
    <div class="card">
        @if($errors->any())
         <div class="alert alert-danger">
        {{$errors->first()}}
        </div>
        @endif
        <form class="" action="{{route('descuentos.update',['descuento'=>$descuento])}}" method="post">
            <div class="card-header">
                <h1>Editar Descuento</h1>
            </div>

            <div class="card-body">    
                {{ csrf_field() }}
                   {{ method_field('PUT') }}
                <div class="row">
                    <div class="form-group col-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required="" value="{{ $descuento->nombre }}">
                    </div>
                    <div class="form-group col-3">
                        <label for="inicio">De:</label>
                        <input type="date" class="form-control" name="inicio" id="inicio" required="" value="{{ $descuento->inicio }}">
                    </div>
                    <div class="form-group col-3">
                        <label for="fin">A:</label>
                        <input type="date" step="0.01" name="fin" class="form-control" id="fin" required="" value="{{ $descuento->fin }}">
                    </div>
                   
                </div>
                    <br>
                    <!-- <label>Tipo: </label> -->

                    <!-- <hr>   -->  
                    <!-- @if ($descuento->promociones->where('tipo','A')->first())
                 <div class="row">
                    <div class="form-group col-12">
                        <input type="checkbox" name="tipoA" id="tipoA" checked="">
                        <label>Compra: </label>
                        <input type="number" class="form-control " name="compra_minA" id="compra_minA" value="{{ $descuento->promociones->where('tipo','A')->first()->compra_min }}">
                    </div>
                    <div class="form-group col-4">
                        <label> Llevate: </label>
                        <input type="number" class="form-control " name="descuento_deA" id="descuento_deA" value="{{ $descuento->promociones->where('tipo','A')->first()->descuento_de }}">
                    </div>

                    @else
                    <div class="form-group col-12">
                        <input type="checkbox" name="tipoA" id="tipoA">
                        <label>Compra: </label>
                        <input type="number" class="form-control" name="compra_minA" id="compra_minA">
                        <label> Llevate: </label>
                        <input type="number" class="form-control" name="descuento_deA" id="descuento_deA">
                    </div>
                </div> -->
                    <!-- @endif -->
                   <!-- <hr> 
                    @if ($descuento->promociones->where('tipo','B')->first())
                    <div class="form-group col-12">
                        <input type="checkbox" name="tipoB" id="tipoB" checked="">
                        <label>Monto minimo de compra: </label>
                        <input type="number" class="form-control" name="compra_minB" id="compra_minB" value="{{ $descuento->promociones->where('tipo','B')->first()->compra_min }}">
                        <label>$ por un descuento de: </label>
                        <input type="number" class="form-control" name="descuento_deB" id="descuento_deB" value="{{ $descuento->promociones->where('tipo','B')->first()->descuento_de }}">
                        <select class="form-group col-1" name="unidad_descuentoB" id="unidad_descuentoB"  required="">                               
                                <option @if( $descuento->promociones->where('tipo','B')->first()->unidad_descuento == "$") selected @endif value="$">$</option>
                                <option @if( $descuento->promociones->where('tipo','B')->first()->unidad_descuento == "%") selected @endif value="%">%</option> 
                        </select>
                    </div> -->
                    <!-- @else
                    <div class="form-group col-12">
                        <input type="checkbox" name="tipoB" id="tipoB">
                        <label>Monto minimo de compra: </label>
                        <input type="number" class="form-control" name="compra_minB" id="compra_minB" >
                        <label>$ por un descuento de: </label>
                        <input type="number" class="form-control" name="descuento_deB" id="descuento_deB">
                        <select class="form-group col-1" name="unidad_descuentoB" id="unidad_descuentoB"  required="">                               
                                <option value="$">$</option>
                                <option value="%">%</option>                  
                        </select>
                    </div>
                    @endif
 -->
                        <!-- <hr>     -->
                   <!--  @if ($descuento->promociones->where('tipo','C')->first())
                    <div class="form-group col-12">
                        <input type="checkbox" name="tipoC" id="tipoC">
                        <label>Descuento por cumpleaños </label>
                        <input type="number" class="form-control" name="descuento_deC" id="descuento_deC">
                        <select class="form-group col-1" name="unidad_descuentoC" id="unidad_descuentoC">
                                <option @if( $descuento->promociones->where('tipo','B')->first()->unidad_descuento == "$") selected @endif value="$">$</option>
                                <option @if( $descuento->promociones->where('tipo','B')->first()->unidad_descuento == "%") selected @endif value="%">%</option>
                        </select>
                    </div> -->
                    <!-- @else -->
                   <!--  <div class="form-group col-12">
                        <input type="checkbox" name="tipoC" id="tipoC">
                        <label>Descuento por cumpleaños </label>
                        <input type="number" class="form-control" name="descuento_deC" id="descuento_deC" value="">
                        <select class="form-group col-1" name="unidad_descuentoC" id="unidad_descuentoC">
                                <option value="$">$</option>
                                <option value="%">%</option>
                        </select>
                    </div>
                    @endif
                   <hr> 
                    @if ($descuento->promociones->where('tipo','D')->first())
                    <div class="form-group col-12">
                        <input type="checkbox" name="tipoD" id="tipoD">
                        <label>Monto minimo de prendas: </label>
                        <input type="number" class="form-control" name="compra_minD" id="compra_minD">
                        <label> por un descuento de: </label>
                        <input type="number" class="form-control" name="descuento_deD" id="descuento_deD">
                        <select class="form-group col-1" name="unidad_descuentoD" id="unidad_descuentoD">
                                <option value="$">$</option>
                                <option value="%">%</option>
                        </select>
                    </div>
                    @else -->
                 <!--    <div class="form-group col-12">
                        <input type="checkbox" name="tipoD" id="tipoD">
                        <label>Monto minimo de prendas: </label>
                        <input type="number" class="form-control" name="compra_minD" id="compra_minD">
                        <label> por un descuento de: </label>
                        <input type="number" class="form-control" name="descuento_deD" id="descuento_deD">
                        <select class="form-group col-1" name="unidad_descuentoD" id="unidad_descuentoD">
                                <option value="$">$</option>
                                <option value="%">%</option>
                        </select>
                    </div>
                    @endif
                   
                    <div class="form-group col-12">
                        <input type="checkbox" name="tipoE" id="tipoE">
                        <label>Monto minimo de prendas: </label>
                        <input type="number" class="form-control" name="compra_minE" id="compra_minE">
                        <label> por: </label>
                        <input type="number" class="form-control" name="descuento_deE" id="descuento_deE">
                        <label>sigpesos</label>
                    </div> -->
                  
                    <!-- <div class="form-group col-12">
                        <input type="checkbox" name="tipoF" id="tipoF">
                        <label>Descuento de empleado: </label>
                        <input type="number" class="form-control" name="descuento_deF" id="descuento_deF">
                        <select class="form-group col-1" name="unidad_descuentoF" id="unidad_descuentoF">
                                
                               
                                <option value="$">$</option>
                                <option value="%">%</option>
                                
                        </select>
                    </div> -->

                    
                <!-- </div> -->
                <div class="col-3 pt-4">
                        <button type="submit" class="btn btn-success btn-lg btn-block">Actualizar </a>
                </div>
            </div>
        </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#tipoA').change(function(){
            if(this.checked)
            {
                $('#compra_minA').prop('required',true);
                $('#descuento_deA').prop('required',true);
            }
            else
            {
                $('#compra_minA').prop('required',false);
                $('#descuento_deA').prop('required',false);
            }            
        });

         $('#tipoB').change(function(){
            if(this.checked)
            {
                $('#compra_minB').prop('required',true);
                $('#descuento_deB').prop('required',true);
            }
            else
            {
                $('#compra_minB').prop('required',false);
                $('#descuento_deB').prop('required',false);
            }            
        });

          $('#tipoC').change(function(){
            if(this.checked)
            {
                $('#descuento_deC').prop('required',true);
            }
            else
            {
                $('#descuento_deC').prop('required',false);
            }            
        });

          $('#tipoD').change(function(){
            if(this.checked)
            {
                $('#compra_minD').prop('required',true);
                $('#descuento_deD').prop('required',true);
            }
            else
            {
                $('#compra_minD').prop('required',false);
                $('#descuento_deD').prop('required',false);
            }            
        });

          $('#tipoE').change(function(){
            if(this.checked)
            {
                $('#compra_minE').prop('required',true);
                $('#descuento_deE').prop('required',true);
            }
            else
            {
                $('#compra_minE').prop('required',false);
                $('#descuento_deE').prop('required',false);
            }            
        });

          $('#tipoF').change(function(){
            if(this.checked)
            {
                $('#descuento_deF').prop('required',true);
            }
            else
            {
                $('#descuento_deF').prop('required',false);
            }            
        });
    });
</script>
@endsection