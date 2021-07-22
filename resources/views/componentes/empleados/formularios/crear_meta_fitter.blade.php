<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalCrearMetaFitter">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear meta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('metas.store')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="empleado_id" value="{{$empleado->id}}">
                        <div class="col-12 col-md-4 mt-2">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="month" name="fecha_inicio" class="form-control" placeholder="2019-04"
                                pattern="2[0-9]{3,3}-((0[1-9])|(1[012]))" title="Escriba una fecha valida AAAA-MM" required min="{{date('Y-m')}}">
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label for="monto_venta">Monto de la venta</label>
                            <input type="number" min="0" step="0.01" name="monto_venta" class="form-control" required value="0">
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label for="num_pacientes_recompra">Número de pacientes de recompra</label>
                            <input type="number" min="0" name="num_pacientes_recompra" class="form-control" required value="0">
                        </div>
                        <div class="col-12 col-md-4 mt-2">
                            <label for="numero_recompras">Número de recompras</label>
                            <input type="number" min="0" step="0.01" name="numero_recompras" class="form-control" required value="0">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="montoVentas" class="text-mutted text-uppercase">Ventas por Mes</label>
                        <input type="number" step="0.01" class="form-control" name="ventasMes">
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numPacientesRecompra" class="text-mutted text-uppercase">Calcetin</label>
                        <input type="number" class="form-control" name="Calcetin">
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numRecompras" class="text-mutted text-uppercase">Leggings</label>
                        <input type="number" class="form-control" name="Leggings">
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numRecompras" class="text-mutted text-uppercase">Panti</label>
                        <input type="number" class="form-control" name="Panti">
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numRecompras" class="text-mutted text-uppercase">Media</label>
                        <input type="number" class="form-control" name="Media">
                    </div>
                     <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numRecompras" class="text-mutted text-uppercase">Muslo</label>
                        <input type="number" class="form-control" name="Muslo">
                    </div>
                     <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numRecompras" class="text-mutted text-uppercase">Tobimedias</label>
                        <input type="number" class="form-control" name="Tobi">
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numRecompras" class="text-mutted text-uppercase">Prendas de mayor valor</label>
                        <input type="number" class="form-control" name="mayorValor">
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 mt-2">
                        <label for="numRecompras" class="text-mutted text-uppercase">Prendas de menor valor</label>
                        <input type="number" class="form-control" name="menorValor">
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