@extends('principal')
@section('content')

<div class="container-fluid">
	<div class="panel panel-group">
		<div class="panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-4">
						<h4>Empleados:</h4>
					</div>
					<div class="col-4 text-center">
						<a class="btn btn-success" href="{{ route('empleados.create')}}">
							<i class="fa fa-plus" aria-hidden="true"></i><strong> Agregar Empleado</strong>
						</a>
					</div>
			<div class="search-container">
			    <form action="empleados">
			      <input type="text" placeholder="Search.." name="search">
			      <button type="submit"><i class="fa fa-search"></i></button>
			    </form>
			</div>					

				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-12">
						@if(count($empleados) > 0)
							<table class="table table-striped table-bordered table-hover" style="color: rgb(51,51,51); border-collapse: collapse; margin-bottom: 0px;">
								<thead>
									<tr class="info">
										<th>#</th>
										<th>Nombre</th>
										<th>Apellido Paterno</th>
										<th>Apellido Materno</th>
										<th>R.F.C.</th>
										<th>Oficina</th>
										<th>Estatus
										</th>
										<th class="text-center">Acciones</th>
									</tr>
								</thead>
								@foreach ($empleados as $empleado)
									<tr class="active" title="Has Click Aquì para Ver" style="cursor: pointer" href="#{{$empleado->id}}">
										<td>{{$empleado->id}}</td>
										<td>{{$empleado->nombre}}</td>
										<td>{{$empleado->appaterno}}</td>
										<td>{{$empleado->apmaterno}}</td>
										<td>{{$empleado->rfc}}</td>
										<td>{{$empleado->oficina->nombre}}</td>
										<td>{{$empleado->activo?"Activo":"Inactivo"}}</td>
										<td class="text-center">
											<a class="btn btn-primary btn-sm" href="{{ route('empleados.show',['empleado'=>$empleado]) }}">
												<i class="fa fa-eye" aria-hidden="true"></i><strong> Ver</strong>
											</a>
											<a class="btn btn-info btn-sm" href="{{ route('empleados.edit',['empleado'=>$empleado]) }}">
												<i class="fa fa-pencil" aria-hidden="true"></i><strong> Editar</strong>
											</a>
											<a class="btn btn-danger btn-sm {{$empleado->activo?"":"d-none"}}" href="{{ url('empleados/'.$empleado->id.'/EmpledoBaja') }}">
												<i class="fas fa-trash-alt" aria-hidden="true"></i><strong> Borrar</strong>
											</a>
										</td>
									</tr>
								@endforeach
							</table>
						@else
							<h4>No hay empleados agregados.</h4>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{{ asset('js/peticion.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/vistarapida.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/forms.js') }}"></script>  			

@endsection
