@extends('paciente.show')
@section('submodulos')

<div class="row my-5">
    <div class="col-4 px-5">
        <h4>Medidas</h4>
    </div>
    <input id="submenu" type="hidden" name="submenu" value="nav-tallas">
</div>
<div class="row">
    <div class="col-8 offset-2">
        <form role="form" name="form-talla" id="form-cliente" method="POST" action="{{ route('pacientes.tallas.store', ['paciente'=>$paciente]) }}"
            name="form">
            {{ csrf_field() }}


            <div class="row mb-3">
                <div class="form-group col-6">
                    <div class="form-group col-12">
                        <label for="nivel">Sexo:</label>
                        <select class="form-control" name="sexo" id="sexo">
                            <option value="">Seleccione</option>
                            <option value="dama">Dama</option>
                            <option value="caballero">Caballero</option>
                        </select>
                    </div>
                    <h5>Compresión</h5>
                    <div class="form-group col-12">
                        <label for="nivel">Pierna:</label>
                        <select class="form-control compresion" name="pierna" id="pierna">
                            <option value="">Ninguna</option>
                            <option value="15-20 mmHG">15-20 mmHG</option>
                            <option value="18-25 mmHG">18-25 mmHG</option>
                            <option value="20-30 mmHG">20-30 mmHG</option>
                            <option value="30-40 mmHG">30-40 mmHG</option>
                        </select>
                    </div>
                    <div class="form-group col-12">
                        <label for="nivel">Brazo:</label>
                        <select class="form-control compresion" name="brazo" id="brazo">
                            <option value="">Ninguna</option>
                            <option value="15-20 mmHG">15-20 mmHG</option>
                            <option value="20-30 mmHG">20-30 mmHG</option>
                        </select>
                    </div>

                </div>
                <div class="form-group col-3 estilos-pierna">
                    <h5>Estilos Pierna</h5>
                    <div class="form-check">
                        <input name="tobimedia" class="form-check-input cb-pierna-estilo" type="checkbox" value="false" id="tobimedia">
                        <label class="form-check-label" for="tobimedia">
                            tobimedia
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="media" class="form-check-input cb-pierna-estilo" type="checkbox" value="false" id="media">
                        <label class="form-check-label" for="media">
                            media
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="pantimedia" class="form-check-input cb-pierna-estilo" type="checkbox" value="false" id="pantimedia">
                        <label class="form-check-label" for="pantimedia">
                            pantimedia
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="calcetin" class="form-check-input cb-pierna-estilo" type="checkbox" value="false" id="calcetin">
                        <label class="form-check-label" for="calcetin">
                            calcetin
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="pantorrillera" class="form-check-input cb-pierna-estilo" type="checkbox" value="false" id="pantorrillera">
                        <label class="form-check-label" for="pantorrillera">
                            pantorrillera
                        </label>
                    </div>
                    <div class="form-check">
                        <input name="leggins" class="form-check-input cb-pierna-estilo" type="checkbox" value="false" id="leggins">
                        <label class="form-check-label" for="leggins">
                            leggins
                        </label>
                    </div>
                </div>
                <div class="form-group col-3 estilos-brazo">
                    <h5>Estilos Brazo</h5>
                    <div class="form-check">
                        <input name="guante" class="form-check-input cb-brazo-estilo" type="checkbox" value="false" id="guante">
                        <label class="form-check-label" for="guante">
                            guante
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6 estilos-pierna">
                    <h5>Medidas Pierna</h5>
                    <div class="row">
                        <div class="col-8 ">
                        </div>
                        <div class="col-2 text-right">
                            <h6>Izq&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h6>
                        </div>
                        <div class="col-2 text-right">
                            <h6>Dcha&nbsp;&nbsp;&nbsp;</h6>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="col-sm-8">
                            <span class="input-group-text">Circunferencia Tobillo</span>
                        </div>
                        <input name="circunferencia_tobillo_izq" id="circunferencia_tobillo_izq" type="text" step="any" class="form-control medidas-pierna">
                        <input name="circunferencia_tobillo_dcha" id="circunferencia_tobillo_dcha" type="text"  class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2">
                        <div class="col-sm-8">
                            <span class="input-group-text">Circunferencia Pantorrilla</span>
                        </div>
                        <input name="circunferencia_pantorrilla_izq" id="circunferencia_pantorrilla_izq" type="text" step="any" class="form-control medidas-pierna">
                        <input name="circunferencia_pantorrilla_dcha" id="circunferencia_pantorrilla_dcha" type="text" step="any" class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2">
                        <div class="col-sm-8">
                            <span class="input-group-text">Altura Pantorrilla</span>
                        </div>
                        <input name="altura_pantorrilla_izq" id="altura_pantorrilla_izq" type="text" step="any" class="form-control medidas-pierna">
                        <input name="altura_pantorrilla_dcha" id="altura_pantorrilla_dcha" type="text" step="any" class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2">
                        <div class="col-sm-8">
                            <span class="input-group-text">Circunferencia Muslo</span>
                        </div>
                        <input name="circunferencia_muslo_izq" id="circunferencia_muslo_izq" type="text" step="any" class="form-control medidas-pierna">
                        <input name="circunferencia_muslo_dcha" id="circunferencia_muslo_dcha" type="text" step="any" class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2">
                        <div class="col-sm-8">
                            <span class="input-group-text">Altura Pierna</span>
                        </div>
                        <input name="altura_pierna_izq" id="altura_pierna_izq" type="text" step="any" class="form-control medidas-pierna">
                        <input name="altura_pierna_dcha" id="altura_pierna_dcha" type="text" step="any" class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2 circunferencia_cadera">
                        <div class="col-sm-8">
                            <span class="input-group-text">Circunferencia cadera</span>
                        </div>
                        <input name="circunferencia_cadera" id="circunferencia_cadera" type="text" step="any" class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2">
                        <div class="col-sm-8">
                            <span class="input-group-text">Calzado</span>
                        </div>
                        <input name="calzado_izq" id="calzado_izq" type="text" step="any" class="form-control medidas-pierna">
                        <input name="calzado_dcha" id="calzado_dcha" type="text" step="any" class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2 peso">
                        <div class="col-sm-8">
                            <span class="input-group-text">Peso</span>
                        </div>
                        <input name="peso" id="peso" type="text" step="any" class="form-control medidas-pierna">
                    </div>
                    <div class="input-group mb-2 estatura">
                            <div class="col-sm-8">
                                <span class="input-group-text">Estatura</span>
                            </div>
                            <input name="estatura" id="estatura" type="text" step="any" class="form-control medidas-pierna">
                        </div>
                </div>
                <div class="form-group col-6 estilos-brazo">
                        <h5>Medidas Brazo</h5>
                        <div class="row">
                            <div class="col-8 ">
                            </div>
                            <div class="col-2 text-right">
                                <h6>Izq&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h6>
                            </div>
                            <div class="col-2 text-right">
                                <h6>Dcha&nbsp;&nbsp;&nbsp;</h6>
                            </div>
                        </div>
                        
                        <div class="input-group mb-2">
                            <div class="col-sm-8">
                                <span class="input-group-text">Circunferencia Palma</span>
                            </div>
                            <input name="circunferencia_plama_izq" id="circunferencia_plama_izq" type="text" step="any" class="form-control medidas-brazo">
                            <input name="circunferencia_plama_dcha" id="circunferencia_plama_dcha" type="text" step="any" class="form-control medidas-brazo">
                        </div>
                        <div class="input-group mb-2">
                            <div class="col-sm-8">
                                <span class="input-group-text">Circunferencia Muñeca</span>
                            </div>
                            <input name="circunferencia_munieca_izq" id="circunferencia_munieca_izq" type="text" step="any" class="form-control medidas-brazo">
                            <input name="circunferencia_munieca_dcha" id="circunferencia_munieca_dcha" type="text" step="any" class="form-control medidas-brazo">
                        </div>
                        <div class="input-group mb-2">
                            <div class="col-sm-8">
                                <span class="input-group-text">Circunferencia parte media</span>
                            </div>
                            <input name="circunferencia_media_izq" id="circunferencia_media_izq" type="text" step="any" class="form-control medidas-brazo">
                            <input name="circunferencia_media_dcha" id="circunferencia_media_dcha" type="text" step="any" class="form-control medidas-brazo">
                        </div>
                        <div class="input-group mb-2">
                            <div class="col-sm-8">
                                <span class="input-group-text">Talla</span>
                            </div>
                            <input name="talla_izq" id="talla_izq" type="text" step="any" class="form-control medidas-brazo">
                            <input name="talla_dcha" id="talla_dcha" type="text" step="any" class="form-control medidas-brazo">
                        </div>
                    </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <strong>Guardar</strong>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    $(document).ready(function(){   
        $('.estilos-brazo').hide();
        $('.estilos-pierna').hide();

        $('input[type="checkbox"]').change(function(){
            verificarcheck();
            if($(this).prop('checked')){
                $(this).val('1');
            }else{
                $(this).val('0');
            }
        });
        
        $('.compresion').change(function(){
            verificarcheck();
        });



        $('#sexo').change(function(){
            verificarcheck();
        });
    });

    function verificarcheck(){
        if($('#pierna').val() != ''){
            $('.estilos-pierna').show();
            if($('#sexo').val() == 'dama'){

                //circunferencia cadera 
                $('.circunferencia_cadera').show();
                $('#circunferencia_cadera').show();
                $('#circunferencia_cadera').val('');

                //peso
                $('.peso').show();
                $('#peso').show();
                $('#peso').val('');

                //estarura
                $('.estatura').show();
                $('#estatura').show();
                $('#estatura').val('');

            }else{
                //circunferencia cadera 
                $('.circunferencia_cadera').hide();
                $('#circunferencia_cadera').hide();
                $('#circunferencia_cadera').val('');

                //peso
                $('.peso').hide();
                $('#peso').hide();
                $('#peso').val('');

                //estarura
                $('.estatura').hide();
                $('#estatura').hide();
                $('#estatura').val('');
            }
        }else{
            $('.estilos-pierna').hide();
            $('input.cb-pierna-estilo').prop('checked', false);
            $('input.cb-pierna-estilo').val('false');
            $('input.medidas-pierna').val("");
        }

        if($('#brazo').val() != ''){
            $('.estilos-brazo').show();
        }else{
            $('.estilos-brazo').hide();
            $('input.cb-brazo-estilo').prop('checked', false);
            $('input.cb-brazo-estilo').val('false');
            $('input.medidas-brazo').val("");
        }

        
    }
</script>

@endsection