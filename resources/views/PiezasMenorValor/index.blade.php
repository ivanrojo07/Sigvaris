@extends('principal')
@section('content')
<script type="text/javascript">

	function confirmacion(doctor_id){
		swal("¿Esta seguro de eliminar este productos de menor valor?", {
  buttons: {
  	Si: true,
    cancel: "No",    
  },
})
.then((value) => {
  switch (value) {
 
    case "Si":
      swal({  
  text: "El productos de menor valor se eliminara permanentemente",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    swal("Se ha dado de baja la tienda", {
      icon: "success",
    });
  	$("#form-tienda"+doctor_id).submit()
  } else {
    swal("Se ha cancelado la baja");
  }
});
      break;     
    
  }
});
	}
</script>
<div class="container">
	<div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-6">
					<h1>Piezas de menor valor</h1>
				</div>
				<div class="col-6">
					<a class="btn btn-success" href="{{ route('piezasMe.create') }}">
						<strong><i class="fa fa-plus float-right"></i></strong>
					</a>
				</div>
				
			</div>


		</div>
		<div class="card-body">
			@if ($pz->count() == 0)
			{{-- true expr --}}
			<label>No hay productos de menor valor añadidos</label>
			@else
			{{-- false expr --}}
			<table id="precargas" class="table table-striped table-bordered table-hover"
				style="color:rgb(51,51,51); border-collapse: collapse; margin-bottom: 0px">
				<thead>
					<tr class="info">
						<th>ID</th>
						<th>producto id</th>
						<th>SKU</th>
					</tr>
				</thead>
				@foreach($pz as $re)
				<tr>
					<td>
						{{ $re->id }}
					</td>
					<td>{{ $re->producto_id }}</td>
					<td>{{ $re->SKU }}</td>
					<td>
					<div class="row">
							<div class="col-4">
								
								
							</div>
							<div class="col-4">
								<form id="form-tienda" role="form" method="POST" action="{{ route('piezasMe.destroy',['piezasMe'=>$re]) }}">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="DELETE">
									<button type="submit" class="btn btn-danger" role="button" id="butonBorrar" onclick="confirmacion()">
										<strong>
											<i class="fa fa-trash"></i>
										</strong>
									</button>
								</form>
							</div>
						</div>

				</tr>
				</td>
				</tbody>
				@endforeach
			</table>
			@endif
		</div>
	</div>

</div>
<script>
	$(document).ready(function () {
		$('#precargas').DataTable({
			"language": {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Buscar:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			}
		});
	});
</script>

@endsection