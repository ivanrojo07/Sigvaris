@extends('layouts.infoempleado')
@section('infoempleado')

	<div>
		<ul class="nav nav-pills nav-justified">
			<li role="presentation" class="nav-item"><a href="{{ route('empleados.show',['empleado'=>$empleado]) }}"  class="nav-link">Generales:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.datoslaborales.index',['empleado'=>$empleado]) }}" class="nav-link active">Laborales:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.certificaciones.index',['empleado'=>$empleado]) }}" class="nav-link ">Estudios:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.emergencias.index',['empleado'=>$empleado]) }}" class="nav-link">Emergencias:</a></li>

			<li role="presentation" class="nav-item"><a href="{{ route('empleados.vacaciones.index',['empleado'=>$empleado]) }}" class="nav-link">Vacaciones:</a></li>
			
			<li role="presentation" class="nav-item"><a href="{{ route('empleados.faltasDH.index',['empleado'=>$empleado]) }}" class="nav-link">Faltas:</a></li>
			
			<li role="presentation" class="nav-item"><a href="{{ route('empleados.permisos.index',['empleado'=>$empleado]) }}" class="nav-link ">Permisos:</a></li>
			
			<li role="presentation" class="nav-item"><a href="{{ route('empleados.faltas.index',['empleado'=>$empleado]) }}" class="nav-link">Administrativo:</a></li>
		</ul>
	</div>


	@if ($datoslab == null)
		<h3>Aún no tienes historial laboral</h3>
	@endif
	@if ($datoslab !=null)
			
		<div class="card">
		    <div class="card-header" align="center"><strong>Datos Laborales Actuales</strong></div>
		    <div class="card-body" >

		      	<div class="row"> 

						<div class="form-group col-3">
							<label class="control-label" for="fechacontratacion">Fecha de contratación:</label>
							
							<dd><strong> {{ $datoslab->fechacontratacion }}</strong></dd>
						</div>
						
						<div class="form-group col-3">
							<label class="control-label" for="contrato">Tipo de contrato:</label>
							@if($contrato==null)
							<dd>NO DEFINIDO</dd>
							@else
							<dd>{{ $contrato->nombre }}</dd>
							@endif
						</div>

		                <div class="form-group col-3">
							<label class="control-label" for="area">Área:</label>
							@if($area==null)
							<dd>NO DEFINIDO</dd>
							@else
							<dd>{{ $area->nombre }}</dd>
							@endif
							
						</div>
						
						<div class="form-group col-3">
							<label class="control-label" for="puesto">Puesto:</label>
							@if($puesto==null)
							<dd>NO DEFINIDO</dd>
							@else
							<dd>{{ $puesto->nombre }}</dd>
							@endif
						</div>

				</div>

				<div class="row">

					<div class="form-group col-3">
						<label class="control-label" for="lugartrabajo">Lugar de Trabajo:</label>
						<dd>{{ $datoslab->lugartrabajo }}</dd>
					</div>

					<div class="form-group col-3">
						<label class="control-label" for="salarionom">Salario Nóminal:</label>
						<dd>{{ $datoslab->salarionom }}</dd>
					</div>

					<div class="form-group col-3">
						<label class="control-label" for="salariodia">Salario Diario:</label>
						<dd>{{ $datoslab->salariodia }}</dd>
					</div>

					<div class="form-group col-3">
						<label class="control-label" for="periodopaga">Periodicidad de Pago:</label>
						<dd>{{ $datoslab->periodopaga }}</dd>
					</div>

				</div>

				<div class="row">

					<div class="form-group col-3">
						<label class="control-label" for="prestaciones">Prestaciones:</label>
						<dd>{{ $datoslab->prestaciones }}</dd>
					</div>
					<div class="form-group col-3">
						<label class="control-label" for="regimen">Régimen de Contratación:</label>
						<dd>{{ $datoslab->regimen }}</dd>
					</div>
					<div class="form-group col-3">
						<label class="control-label" for="hentrada">Hora de Entrada:</label>
						<dd>{{ $datoslab->hentrada }}</dd>
					</div>
					<div class="form-group col-3">
						<label class="control-label" for="hsalida">Hora de Salida:</label>
						<dd>{{ $datoslab->hsalida }}</dd>
					</div>

				</div>
				<div class="row">

					<div class="form-group col-3">
						<label class="control-label" for="hcomida">Tiempo de Comida:</label>
						<dd>{{ $datoslab->hcomida }}</dd>
					</div>
					
					<div class="form-group col-3">
						<label class="control-label" for="banco">Banco:</label>
						@if($datoslab->banco==null)
						<dd>NO DEFINIDO</dd>
						@else
						<dd>{{ $datoslab->banco }}</dd>
						@endif
					</div>

					<div class="form-group col-3">
						<label class="control-label" for="cuenta">Cuenta:</label>
						<dd>{{ $datoslab->cuenta }}</dd>
					</div>

					<div class="form-group col-3">
						<label class="control-label" for="clabe">CLABE:</label>
						<dd>{{ $datoslab->clabe }}</dd>
					</div>

				</div>


		        <div align="right">
		      		<a class="btn btn-success btn-md" href="{{ route('empleados.datoslaborales.edit',['empleado'=>$empleado,'actual'=>$datoslab]) }}">
						<strong>Agregar</strong>
					</a>
				</div>

				<div class="well well-sm" align="center">
					<h4 class="text-center">Historial laboral</h4>			
				</div>

				<table class="table table-striped table-bordered table-hover" style="color:rgb(51,51,51); 
				border-collapse: collapse; margin-bottom: 0px;">
					<thead>
						<tr class="info">
							<th>Área</th>
							<th>Puesto</th>
							
							<th>Salario Nominal</th>
							<th>Tipo de Contrato</th>
							<th>Fecha de Actualización</th>
						</tr>
					</thead>
				    <tbody>
					@foreach ($datoslaborales as $dato)
						<tr  class="active">

					 		@if($dato->area_id==null)
								<td>NO DEFINIDO</td>
							@else
							 <?php $a='';?>
								@foreach($areas as $area)
									@if($dato->area_id==$area->id)
									<?php $a=$area->nombre; ?>
									<td>{{$a}}</td>
									@endif
								@endforeach
							@endif


							@if($dato->puesto_id==null)
								<td>NO DEFINIDO</td>
							@else
							 <?php $p='';?>
								@foreach($puestos as $puesto)
									@if($dato->puesto_id==$puesto->id)
									<?php $p=$puesto->nombre; ?>
									<td>{{$p}}</td>
									@endif
								@endforeach
							@endif

							<td>{{$dato->salarionom}}</td>
							
							@if($dato->contrato_id==null)
								<td>NO DEFINIDO</td>
							@else
							 <?php $c='';?>
								@foreach($contratos as $contrato)
									@if($dato->contrato_id==$contrato->id)
									<?php $c=$contrato->nombre; ?>
									<td>{{$c}}</td>
									@endif
								@endforeach
							@endif

							<td>{{$dato->updated_at}}</td>  
						</tr>
					</tbody>
					@endforeach
				</table>
			</div>
      	</div>
	@endif

	@if ($empleado->puesto->nombre == "fitter")


		<div class="row">
			<div class="col-12 mt-3">
				<h4 class="text-center">Metas del fitter</h4>
			</div>
			<div class="col-12">
				<div class="row">
					<div class="col-12">
						<button type="button" class="btn btn-success rounded-0" data-toggle="modal" data-target="#modalCrearMetaFitter">Agregar</button>
					</div>
				</div>
			</div>
			<div class="col-12 mt-3">
				<table class="table table-striped table-bordered table-hover" style="color:rgb(51,51,51); border-collapse: collapse; margin-bottom: 0px;">
					<thead>
						<tr>
							<td>
								Fecha de inicio
							</td>
							<td>
								monto de venta
							</td>
							<td>
								número de pacientes de recompra
							</td>
							<td>
								número de recompras
							</td>
							<td>
								ventas obsoletos
							</td>
							<td>
								calcetin
							</td>
							<td>
								leggings
							</td>
							<td>
								muslo
							</td>
							<td>
								media
							</td>
							<td>
								panti
							</td>
							<td>
								tobimedias
							</td>
							<td>
								pz_mayor
							</td>
							<td>
								pz_menor
							</td>
						</tr>
					</thead>
					<tbody>
						@each('componentes.empleados.tablas.metas_fitter', $empleado->fitterMetas, 'meta')
						@foreach ($empleado->fitterMetas as $meta)
						<tr class="active" title="Has Click Aquì para Ver"	style="cursor: pointer" data-toggle="modal" data-target="#meta_{{$meta->id}}">
								<td>
									{{$meta->fecha_inicio}}
								</td>
								<td>
									{{$meta->monto_venta}}
								</td>
								<td>
									{{$meta->num_pacientes_recompra}}
								</td>
								<td>
									{{$meta->numero_recompras}}
								</td>
								<td>
									{{$meta->ventas_obsoletos}}
								</td>
								<td>
									{{$meta->calcetin}}
								</td>
								<td>
									{{$meta->leggings}}
								</td>
								<td>
									{{$meta->muslo}}
								</td>
								<td>
									{{$meta->media}}
								</td>
								<td>
									{{$meta->panti}}
								</td>
								<td>
									{{$meta->tobimedias}}
								</td>
								<td>
									{{$meta->pz_mayor}}
								</td>
								<td>
									{{$meta->pz_menor}}
								</td>
							</tr>
						@endforeach
						
					</tbody>
				</table>
				<br>
			</div>
		</div>
		@include('componentes.empleados.formularios.crear_meta_fitter', compact('empleado'))

	@endif

@endsection