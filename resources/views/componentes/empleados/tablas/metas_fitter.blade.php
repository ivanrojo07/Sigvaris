<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="meta_{{$meta->id}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Meta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <form action="{{route('metas.update',["id"=>$meta->id])}}" method="POST">
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div class="row">
                                <div class="col-12 col-md-4 mt-2">
                                    <label for="fecha_inicio">Fecha de inicio</label>
                                    <input type="month" name="fecha_inicio" class="form-control" required  value="{{date_format(date_create($meta->fecha_inicio), 'Y-m')}}">
                                </div>
                                <div class="col-12 col-md-4 mt-2">
                                    <label for="monto_venta">Monto de la venta</label>
                                    <input type="number" min="0" step="0.01" name="monto_venta" class="form-control" required value="{{$meta->monto_venta}}">
                                </div>
                                <div class="col-12 col-md-4 mt-2">
                                    <label for="num_pacientes_recompra">Número de pacientes de recompra</label>
                                    <input type="number" min="0" name="num_pacientes_recompra" class="form-control" required value="{{$meta->num_pacientes_recompra}}">
                                </div>
                                <div class="col-12 col-md-4 mt-2">
                                    <label for="numero_recompras">Número de recompras</label>
                                    <input type="number" min="0" step="0.01" name="numero_recompras" class="form-control" required value="{{$meta->numero_recompras}}">
                                </div>
                                <div class="col-12 col-md-4 mt-2">
                                    <label for="numero_recompras">Ventas Obsoletos</label>
                                    <input type="number" min="0" step="0.01" name="numero_recompras" class="form-control" required value="{{$meta->ventas_obsoletas}}">
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success rounded-0">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>