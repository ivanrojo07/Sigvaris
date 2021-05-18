@extends('paciente.show')
@section('submodulos')
<div class="card">
	<div class="card-header">
		<h4>Datos fiscales</h4>
	</div>
	<div class="card-body" id="formulario">
		<form action="{{route('pacientes.datos_fiscales.store',['paciente'=>$paciente->id])}}" method="POST">
			@csrf
			<div class="row">
				<div class="col-3 form-group">
					<label class="control-label">✱Tipo de persona:</label>
					<select name="tipo_persona" class="form-control">
						<option value="">Seleccione el tipo de persona</option>
						<option value="física" {{$paciente->datoFiscal ? $paciente->datoFiscal->tipo_persona == 'física' ? 'selected' : '' : ''}}>física</option>
						<option value="moral" {{$paciente->datoFiscal ? $paciente->datoFiscal->tipo_persona == 'moral' ? 'selected' : '' : ''}}>moral</option>
					</select>
				</div>
				<div class="col-3 form-group">
					<label class="control-label">✱Nombre o razon social:</label>
					<input type="text" name="nombre_o_razon_social" class="form-control" required=""
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->nombre_o_razon_social : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">✱Regimen fiscal:</label>
					<input type="text" name="regimen_fiscal" class="form-control" required=""
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->regimen_fiscal : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">✱Homoclave:</label>
					<input type="text" name="homoclave" class="form-control" required="" id="homoclave"
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->homoclave : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Correo:</label>
					<input type="email" name="correo" class="form-control" id="correo"
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->correo : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">RFC:</label>
					<input type="text" name="rfc" class="form-control" id="rfc"
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->rfc : ''}}">
				</div>
			</div>

			<div class="row">
				<div class="col-3 form-group">
					<label class="control-label">Calle</label>
					<input type="text" name="calle" class="form-control" 
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->calle : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Numero exterior</label>
					<input type="text" name="num_ext" class="form-control" 
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->num_ext : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Numero interior</label>
					<input type="text" name="num_int" class="form-control"
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->num_int : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Colonia</label>
					<input type="text" name="colonia" class="form-control" 
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->colonia : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Ciudad</label>
					<input type="text" name="ciudad" class="form-control" 
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->ciudad : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Alcaldia o municipio</label>
					<input type="text" name="alcaldia_o_municipio" class="form-control"
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->alcaldia_o_municipio : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">Estado</label>
					<input type="text" name="estado" class="form-control" 
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->estado : ''}}">
				</div>
				<div class="col-3 form-group">
					<label class="control-label">C.P.</label>
					<input type="text" name="codigo_postal" class="form-control"
						value="{{$paciente->datoFiscal ? $paciente->datoFiscal->codigo_postal : ''}}">
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-12">
					<button type="submit" class="btn btn-success rounded-0">
						<i class="fa fa-check"></i> Guardar
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

@endsection