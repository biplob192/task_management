
	<div class="square-switch">
		<input type="checkbox"  name="{{$name}}" id="{{$id}}" switch="none" @if($checked) checked @endif {{ $attributes->merge(['class'=>""])}}/>
		<label class="toggle-switch-custom" for="{{$id}}" data-on-label="Active" data-off-label="Inactive"></label>
	</div>
