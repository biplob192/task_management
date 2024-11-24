<div
    wire:ignore 
    x-data
	x-init="() => {
	var choices = new Choices($refs.{{ $attributes['name'] }}, {
		itemSelectText: '',
	});
	choices.passedElement.element.addEventListener(
	  'change',
	  function(event) {
			values = event.detail.value;
		    @this.set('{{ $attributes['wire:model'] }}', values);
	  },
	  false,
	);
	let selected = parseInt({{$attributes['selected']}}).toString();
	choices.setChoiceByValue(selected);
	}">
	<div class="form-group mb-3">
		<select id="{{ $attributes['name'] }}" wire-model="{{ $attributes['wire:model'] }}" wire:change="{{ $attributes['wire:change'] }}" x-ref="{{ $attributes['name'] }}" {{ $attributes['disabled'] }}>
			<option value="" >{{ $attributes['placeholder'][0] }}</option>
			@if(count($attributes['options'])>0)
				@foreach($attributes['options'] as $key=>$option)
					<option value="{{$option->id}}" >{{$option->name}}</option>
				@endforeach
			@endif
		</select>
    </div>
</div>