@extends('principal')
@section('content')
<script type="text/javascript">

	function confirmacion(doctor_id){
		swal("¿Esta seguro de eliminar este paciente?", {
  buttons: {
  	Si: true,
    cancel: "No",    
  },
})
.then((value) => {
  switch (value) {
 
    case "Si":
      swal({  
  text: "El paciente se eliminara permanentemente",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    swal("Se ha dado de baja al paciente", {
      icon: "success",
    });
  	$("#form-doctor"+doctor_id).submit()
  } else {
    swal("Se ha cancelado la baja");
  }
});
      break;     
    
  }
});
	}
// 	function confirmacion(doctor_id){
// 		swal({
//   title: "¿Eliminar doctor?",
//   text: "¿Estas seguro de eliminar este doctor?",
//   icon: "warning",
//   buttons: true,
//   dangerMode: true,
// })
// .then((willDelete) => {
//   if (willDelete) {
//     swal("Poof! Your imaginary file has been deleted!", {
//       icon: "success",
//     });
//   	$("#form-doctor"+doctor_id).submit()
//   } else {
//     swal("Your imaginary file is safe!");
//   }
// });
// 	}
</script>
<script>
	$(document).ready(function(){
	// $('#tablaPacientes').DataTable();
		$('.borrar').click(function()
        {
            //$('#confirm-delete').modal('show');
           $('#deleteUserForm').attr('action', '/pacientes/' + $(this).attr('paciente-id'));
        })
	});

</script>
<div class="modal fade" id="confirm-delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar paciente</h4>
            </div>
            <div class="modal-body">
                <label>¿Estás seguro de eliminar el paciente?</label>
            </div>
            <div class="modal-footer">
            	<form role="form" name="doctorborrar" id="deleteUserForm" method="POST"
					 name="form">
					{{ csrf_field() }}
					{{ method_field('DELETE') }}
					<button type="submit" class="btn btn-danger"><i
							class="far fa-trash-alt"></i><strong> Si</strong></button>
				</form>
                <a class="btn btn-primary" data-dismiss="modal">No
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-12 col-lg-6">
		<form action="/pacientes">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-6">
							<label for="">BUSQUEDA</label>
							<input type="text" placeholder="Search.." name="search" class="form-control"
								value="{{request()->input('search')}}">
						</div>
						<div class="col-6">
							<label for="">ORDENAR POR</label>
							<select name="ordenar_por" class="form-control">
								<option  {{request()->input('ordenar_por') == 'nombre' ? 'selected' : ''}} value="nombre">Nombre</option>
								<option  {{request()->input('ordenar_por') == 'materno' ? 'selected' : ''}} value="materno">Apellido materno</option>
								<option {{request()->input('ordenar_por') == 'paterno' ? 'selected' : ''}} value="paterno">Apellido paterno</option>
							</select>
						</div>
					</div>
					{{-- <div class="search-container"> --}}
					{{-- </div> --}}
				</div>
				<div class="card-footer">
					<div class="col-12">
						<button type="submit" class="btn btn-success btn-block"><i
								class="fa fa-search">Buscar</i></button>
					</div>
				</div>
		</form>
	</div>
</div>
</div>

<div class="card mt-4">
	<div class="card-header">
		<div class="row">
			<div class="col-4">
				<h4>Pacientes</h4>
			</div>
			<div class="col-4 text-center">
				<a href="{{ route('pacientes.create') }}" class="btn btn-success">
					<i class="fa fa-plus"></i><strong> Agregar Paciente</strong>
				</a>
			</div>
			{{-- <div class="search-container">
				<form action="/pacientes">
					<input type="text" placeholder="Search.." name="search">
					<button type="submit"><i class="fa fa-search"></i></button>
				</form>
			</div> --}}
		</div>
	</div>
</div>
<div class="card-body">
	<div class="row">
		<div class="col-12">
			<table class="table table-striped table-bordered table-hover" style="margin-bottom: 0px"
				id="tablaPacientes">
				<thead>
					<tr class="info">
						<th>Id Paciente</th>
						<th>Nombre</th>
						<th>Apellido Paterno</th>
						<th>Apellido Materno</th>
						<th>Nacimiento</th>
						<th>Operación</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pacientes as $paciente)
					<tr>
						<td>{{ $paciente->id }}</td>
						<td>{{ $paciente->nombre }}</td>
						<td>{{ $paciente->paterno }}</td>
						<td>{{ $paciente->materno }}</td>
						<td>{{ $paciente->nacimiento }}</td>
						<td>


							<div class="row">
								<div class="col-auto pr-2">
									<a href="{{route('pacientes.show', ['paciente'=>$paciente])}}"
										class="btn btn-primary"><i class="fas fa-eye"></i><strong> Ver</strong></a>
									<a href="{{route('pacientes.edit', ['paciente'=>$paciente])}}"
										class="btn btn-warning"><i class="fas fa-edit"></i><strong> Editar</strong></a>

									<a href="{{route('pacientes.venta', ['paciente' => $paciente])}}"
										class="btn btn-secondary float-right"><i class="fas fa-dollar-sign"></i><strong>
											Punto de venta</strong></a>

								</div>
								{{-- <button type="submit" class="btn btn-danger borrar" paciente-id="{{ $paciente->id }}" data-toggle="modal" data-target="#confirm-delete" >
									<i class="far fa-trash-alt"></i>
									<strong> Borrar</strong>
								</button>--}}
								 <div class="col pl-0">
									<form role="form" name="doctorborrar" id="form-doctor{{ $paciente->id}}" method="POST"
										action="{{ route('pacientes.destroy', ['paciente'=>$paciente]) }}" name="form">
										{{ csrf_field() }}
										{{ method_field('DELETE') }}
										<button class="btn btn-danger " type="button" id="butonBorrar" onclick="confirmacion({{$paciente->id}})"><i
												class="far fa-trash-alt"></i><strong> Borrar</strong></button>
									</form>
								</div>
							</div>

						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{{$pacientes->links()}}
		</div>
	</div>
</div>
</div>

{{-- 
<script type="text/javascript">
	$(document).ready(function () {
		$('#tablaPacientes').DataTable();
</script> --}}


@endsection