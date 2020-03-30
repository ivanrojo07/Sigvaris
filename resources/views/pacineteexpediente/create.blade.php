@extends('paciente.show')
@section('submodulos')
<!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
    <link href="{{ asset('css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    <script src="{{ asset('js/plugins/piexif.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/plugins/sortable.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/fileinput.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/locales/fr.js')}}" type="text/javascript"></script>
    <script src="{{ asset('js/locales/es.js')}}" type="text/javascript"></script>
	<div class="card">
		<div class="card-header">
			<h4>Expediente:</h4>
		</div>
		<div class="card-body">
			<form role="form" method="POST" action="{{ route('pacientes.expediente.store',['paciente'=>$paciente]) }}" enctype="multipart/form-data">
				@csrf
				<div class="row form-group">
		    		<div class="col-3 col-sm-12 col-md-3 col-xl-3 form-group text-center">
		    			<label class="control-label" for="">Aviso de Privacidad</label>
		    			<input id="input-id2" type="file" accept=".pdf, .jpg, .jpeg, .png" class="file" name="aviso_privacidad" data-preview-file-type="text" >
		    		</div>
		    		<div class="col-3 col-sm-12 col-md-3 col-xl-3 form-group text-center">
		    			<label class="control-label" for="">Identificación</label>
		    			<input id="input-id3" type="file" accept=".pdf, .jpg, .jpeg, .png" class="file" name="identificacion" data-preview-file-type="text" >
		    		</div>
		    		<div class="col-3 col-sm-12 col-md-3 col-xl-3 form-group text-center">
		    			<label class="control-label" for="">Reversa de la Identificación </label>
		    			<input id="input-id6" type="file" accept=".pdf, .jpg, .jpeg, .png" class="file" name="identificacion2" data-preview-file-type="text" >
		    		</div>
		    		<div class="col-3 col-sm-12 col-md-3 col-xl-3 form-group text-center">
		    			<label class="control-label" for="">Inapam</label>
		    			<input id="input-id4" type="file" accept=".pdf, .jpg, .jpeg, .png" class="file" name="inapam" data-preview-file-type="text" >
		    		</div>
		    		<div class="col-3 col-sm-12 col-md-3 col-xl-3 form-group text-center">
		    			<label class="control-label" for="">receta</label>
		    			<input id="input-id5" type="file" accept=".pdf, .jpg, .jpeg, .png" class="file" name="receta" data-preview-file-type="text" >
		    		</div>
		    	</div>
		    	
				<div class="row form-group">
					<div class="col-sm-12 text-center">
						<button type="submit" class="btn btn-success">
						 	<strong>Guardar</strong>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	@if(isset($expediente))
	<script>
		$("#input-id2").fileinput({
	        theme: 'fas',
	        showUpload: true,
	        showCaption: true,
	        browseClass: "btn btn-primary btn-lg",
	        fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
	        overwriteInitial: true,
	        initialPreviewAsData: true,
	        initialPreview: [
	           "{{ url('/expedientes/'.$paciente->id.'/'.$expediente->aviso_privacidad) }}"
	        ]
	    });
	    $("#input-id3").fileinput({
	        theme: 'fas',
	        showUpload: true,
	        showCaption: true,
	        browseClass: "btn btn-primary btn-lg",
	        fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
	        overwriteInitial: true,
	        initialPreviewAsData: true,
	        initialPreview: [
	          "{{ url('/expedientes/'.$paciente->id.'/'.$expediente->identificacion) }}"
	        ]
	    });
	    $("#input-id6").fileinput({
	        theme: 'fas',
	        showUpload: true,
	        showCaption: true,
	        browseClass: "btn btn-primary btn-lg",
	        fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
	        overwriteInitial: true,
	        initialPreviewAsData: true,
	        initialPreview: [
	          "{{ url('/expedientes/'.$paciente->id.'/'.$expediente->identificacion2) }}"
	        ]
	    });

	    $("#input-id4").fileinput({
	        theme: 'fas',
	        showUpload: true,
	        showCaption: true,
	        browseClass: "btn btn-primary btn-lg",
	        fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
	        overwriteInitial: true,
	        initialPreviewAsData: true,
	        initialPreview: [
	           "{{ url('/expedientes/'.$paciente->id.'/'.$expediente->inapam) }}"
	        ]
	    });
	    $("#input-id5").fileinput({
	        theme: 'fas',
	        showUpload: true,
	        showCaption: true,
	        browseClass: "btn btn-primary btn-lg",
	        fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
	        overwriteInitial: true,
	        initialPreviewAsData: true,
	        initialPreview: [
	           "{{ url('/expedientes/'.$paciente->id.'/'.$expediente->receta) }}"
	        ]
	    });
	    
	</script>
	@endif
@endsection