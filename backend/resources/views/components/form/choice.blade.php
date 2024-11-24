@props([
    'label' => '',
    'error' => false,
	'placeholder' => 'Select Options',
])


<div 
    wire:ignore
    x-data
	x-init="() => {
	var choices = new Choices($refs.{{ $attributes['name'] }}, {
		itemSelectText: '',
		removeItems: true,
	    removeItemButton: true,
		placeholder: true,
	});
		choices.passedElement.element.addEventListener(
		  'change',
		  function(event) {
			
				values = getSelectValues($refs.{{ $attributes['name'] }});
				@this.set('{{ $attributes['wire:model'] }}', values);
				console.log({{ $attributes->get('wire:model') }});
		  },
		  false,
		);
		
		items = {!! $attributes['selected'] !!};
		
		if(Array.isArray(items)){
			items.forEach(function(select) {
					choices.setChoiceByValue((select).toString());
				});
			}
		}
		
		function getSelectValues(select) {
		  var result = [];
		  var options = select && select.options;
		  var opt;
		  for (var i=0, iLen=options.length; i<iLen; i++) {
			opt = options[i];
			if (opt.selected) {
			  result.push(opt.value || opt.text);
			}
		  }
		  return result;
		}
	">
	
    <select {{$attributes->merge(['class' => 'form-control'])}} x-ref="{{ $attributes->get('name') }}" >
		{{ $slot }}
    </select>
	@if($attributes['error'])
        <small class="form-text text-danger"><i
                class="fa fa-exclamation-circle pr-2"></i>{{ $attributes['error'] }}</small>
        @endif
</div>