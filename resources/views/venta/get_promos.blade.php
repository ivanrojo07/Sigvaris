<select class="form-control" name="promo_id" id="promo_id">
    <option value="">Seleccione un tipo de promocion</option>
    @foreach ($promociones as $promocion)
        <option value="{{$promocion->id}}">
        	{{$nombre}}
        </option>
    @endforeach    
</select>