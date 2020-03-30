@extends('paciente.show')
@section('submodulos')
	@php
	$aviso_privacidad = explode(".", $expediente->aviso_privacidad);
	$aviso_privacidad = $aviso_privacidad[1];
	if ($expediente->identificacion!=null) {
		$identificacion = explode(".", $expediente->identificacion);
		$identificacion = $identificacion[1];
	}
	if ($expediente->inapam!=null) {
		$inapam = explode(".", $expediente->inapam);
		$inapam = $inapam[1];
	}
	if ($expediente->receta!=null) {
		$receta = explode(".", $expediente->receta);
		$receta = $receta[1];
	}
	
	@endphp
	<div class="card">
		<div class="card-header">
			<h4>Expediente:</h4>
		</div>
		<div class="card-body">
			<div class="card-deck">	

				@if($aviso_privacidad != "pdf")
				<div class="Portfolio m-4">
					<a href="#!">
						<img class="card" src="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->aviso_privacidad) }}" width="200px" height="200px" alt="">
					</a>
					<div class="desc">Aviso de privacidad</div>
				</div>
				@endif
				@if($expediente->identificacion!=null)
					@if($identificacion != "pdf")
					<div class="Portfolio m-4">
						<a href="#!">
							<img class="card" src="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->identificacion) }}" width="200px" height="200px" alt="">
						</a>
						<div class="desc">Identificación</div>
					</div>
					@endif
				@endif
				@if($expediente->inapam!=null)
					@if($inapam != "pdf")
					<div class="Portfolio m-4">
						<a href="#!">
							<img class="card" src="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->inapam) }}" width="200px" height="200px" alt="">
						</a>
						<div class="desc">inapam</div>
					</div>
					@endif
				@endif

				@if($expediente->receta!=null)
					@if($receta != "pdf")
					<div class="Portfolio m-4">
						<a href="#!">
							<img class="card" src="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->receta) }}" width="200px" height="200px" alt="">
						</a>
						<div class="desc">receta</div>
					</div>
					@endif
				@endif
				<!-- ######## BOTONES PARA VER PDF  ###########-->

				<div class="row">
					@if($aviso_privacidad == "pdf")
					<div class="row m-4 my-auto">
						<div class="col-md-12 text-center">
							<a class="btn btn-info" target="_blank" href="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->aviso_privacidad) }}">
								ver Aviso de privacidad
							</a>
						</div>
					</div>
					@endif
					@if($expediente->identificacion!=null)
						@if($identificacion == "pdf")
						<div class="row m-4 my-auto">
							<div class="col-md-12 text-center">
								<a class="btn btn-info" target="_blank" href="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->identificacion) }}">
									ver Identificación
								</a>
							</div>
						</div>
						@endif
					@endif
					@if($expediente->inapam!=null)
						@if($inapam == "pdf")
						<div class="row m-4 my-auto">
							<div class="col-md-12 text-center">
								<a class="btn btn-info" target="_blank" href="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->inapam) }}">
									ver inapam
								</a>
							</div>
						</div>
						@endif
					@endif
					@if($expediente->receta!=null)
						@if($receta == "pdf")
						<div class="row m-4 my-auto">
							<div class="col-md-12 text-center">
								<a class="btn btn-info" target="_blank" href="{{ url('/expedientes/'.$paciente->id.'/'.$expediente->receta) }}">
									ver receta
								</a>
							</div>
						</div>
						@endif
					@endif
					
				</div>

			</div>
		</div>
		<div class="row">
            <div class="col-4 offset-4 text-center">
                <a class="btn btn-success" href="{{ route('pacientes.expediente.create', ['paciente' => $paciente,'Actualizar'=>1]) }}">Editar</a>
            </div>    
        </div>
		<br><br>
@endsection
