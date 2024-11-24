<div
    wire:ignore 
    x-data
	x-init="() => {
	var choices = new Choices($refs.{{ $attributes['name'] }}, {
		itemSelectText: '',
		removeItems: true,
	    removeItemButton: true,
	});
	choices.passedElement.element.addEventListener(
	  'change',
	  function(event) {
	  		values = getSelectValues($refs.{{ $attributes['name'] }});
		    @this.set('{{ $attributes['wire:model'] }}', values);
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
    <select id="{{ $attributes['name'] }}" wire-model="{{ $attributes['wire:model'] }}" wire:change="{{ $attributes['wire:change'] }}" x-ref="{{ $attributes['name'] }}"  multiple="multiple">
	    <option value="" >{{ $attributes['placeholder'][0] }}</option>
    	@if(count($attributes['options'])>0)
	    	@foreach($attributes['options'] as $key=>$option)
	    		<option value="{{$option['id']}}" >{{$option['name']}}</option>
	    	@endforeach
    	@endif
    </select>
</div>