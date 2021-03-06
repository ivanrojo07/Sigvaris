@extends('principal')
@section('content')
<div class="container mt-5">

    <input id="submenu" type="hidden" name="submenu" value="nav-crm">
    <div class="card">
        <div class="card-header ">
            <div class="row">
                <div class="col-4">
                    <h4>C.R.M.</h4>
                </div>
                <div class="col">
                    <button id="crear_crm_boton" type="button" class="btn btn-success" data-toggle="modal"
                        data-target="#crear_crm_modal">
                        <strong>Crear <i class="fa fa-plus"></i></strong>
                    </button>

                    <form id="crear_crm" name="crear_crm" action="{{route('crm.store')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="modal fade bd-example-modal-lg" id="crear_crm_modal" tabindex="-1" role="dialog"
                            aria-labelledby="myLargeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4> Crear CRM</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-row">
                                                <div class="form-group col-4 offset-4">
                                                        <label for="actual">✱Paciente</label>
                                                        <select class="form-control" name="paciente_id" id="paciente_id" required="">
                                                            <option value="">Selecciona paciente...</option>
                                                            @foreach($pacientes as $paciente)
                                                            <option value="{{$paciente->id}}">{{$paciente->nombre}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-4">
                                                <label for="actual">✱Fecha actual</label>
                                                <input type="date" class="form-control" value="{{date('Y-m-d')}}"
                                                    readonly="">
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="fecha_aviso">✱Fecha aviso</label>
                                                <input type="date" class="form-control" name="fecha_aviso" required="">
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="fecha_contacto">✱Fecha contacto</label>
                                                <input type="date" class="form-control" name="fecha_contacto"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-4">
                                                <label for="forma_contacto">✱Forma de contacto</label>
                                                <select class="form-control" name="forma_contacto" required="">
                                                    <option value="">Seleccionar</option>
                                                    <option value="Telefono">Telefono</option>
                                                    <option value="Mail">Mail</option>
                                                    <option value="Celular">Celular</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="estado">✱Estado</label>
                                                <select class="form-control" name="estado_id" required="">
                                                <option value="">Seleccionar</option>
                                                    @foreach($estados as $estado)
                                                    <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="hora">✱Hora</label>
                                                <input class="form-control" type="text" name="hora" required="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-4">
                                                <label for="observaciones">Observaciones</label>
                                                <textarea class="form-control" name="observaciones"></textarea>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="acuerdos">Acuerdos</label>
                                                <textarea class="form-control" name="acuerdos"></textarea>
                                            </div>
                                            <div class="form-group col-4">
                                                <label for="comentarios">Comentarios</label>
                                                <textarea class="form-control" name="comentarios"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" form="crear_crm" class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="modal fade bd-example-modal-lg" id="ver_crm_modal" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>
                                        Ver C.R.M.
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <label for="actual">Fecha actual</label>
                                            <input type="date" class="form-control" id="actual" value="" readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="fecha_aviso">Fecha aviso</label>
                                            <input type="date" class="form-control" id="fecha_aviso" name="fecha_aviso"
                                                value="" readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="fecha_contacto">Fecha contacto</label>
                                            <input type="date" class="form-control" id="fecha_contacto"
                                                name="fecha_contacto" value="" readonly="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <label for="forma_contacto">Forma de contacto</label>
                                            <input type="text" class="form-control" id="forma_contacto"
                                                name="forma_contacto" value="" readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="estado">Estado</label>
                                            <input type="text" class="form-control" id="estado" name="estado" value=""
                                                readonly="">
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="hora">Hora</label>
                                            <input class="form-control" type="text" id="hora" name="hora" value=""
                                                readonly="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-4">
                                            <label for="observaciones">Observaciones</label>
                                            <textarea class="form-control" id="observaciones" name="observaciones"
                                                value="" readonly=""></textarea>
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="acuerdos">Acuerdos</label>
                                            <textarea class="form-control" id="acuerdos" name="acuerdos" value=""
                                                readonly=""></textarea>
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="comentarios">Comentarios</label>
                                            <textarea class="form-control" id="comentarios" name="comentarios" value=""
                                                readonly=""></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" form="ver_crm" id="cerrar_ver_crm_modal"
                                        class="btn btn-danger">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Creación</th>
                        <th>Fecha Aviso</th>
                        <th>Fecha Contacto</th>
                        <th>Forma Contacto</th>
                        <th>Estado</th>
                        <th>Hora</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($crms))
                    <h5>No hay ningún crm registrado</h5>
                    @else
                    @foreach($crms as $crm)
                    <tr>
                        <td>{{$crm->paciente->nombre}}</td>
                        <td>{{$crm->created_at}}</td>
                        <td>{{$crm->fecha_aviso}}</td>
                        <td>{{$crm->fecha_contacto}}</td>
                        <td>{{$crm->forma_contacto}}</td>
                        <td>{{$crm->estado->nombre}}</td>
                        <td>{{$crm->hora}}</td>
                        <td>
                            <button type="button" onclick="mostrarCrm('{{$crm}}')" class="btn btn-primary">Ver</button>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="card-footer">

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#cerrar_ver_crm_modal').click(function () {
            $('#ver_crm_modal').modal('hide');
        });
    });
    function mostrarCrm(data) {
        var crm = JSON.parse(data);
        $('#observaciones').val(crm.observaciones);
        $('#acuerdos').val(crm.acuerdos);
        $('#comentarios').val(crm.comentarios);
        $('#fecha_aviso').val(crm.fecha_aviso);
        $('#fecha_contacto').val(crm.fecha_contacto);
        $('#forma_contacto').val(crm.forma_contacto);
        $('#estado').val(crm.estado.nombre);
        $('#hora').val(crm.hora);
        $('#ver_crm_modal').modal('show');
    }
</script>
@endsection