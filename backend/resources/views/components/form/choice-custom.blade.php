<div
   wire:ignore
   x-data
   x-init="() => {
    var choices = new Choices($refs.{{ $attributes['name'] }}, {
        delimiter:',',
        editItems:!0,
        duplicateItemsAllowed:!1,
        maxItemCount:null,
        removeItemButton:!0,
        placeholder: true,
        placeholderValue: '{{ $attributes['placeholder'][0] }}',
	});
    choices.passedElement.element.addEventListener(
	  'change',
	  function(event) {
             //console.log(event.detail);
	  		 //values = getSelectValues(event.detail);
             values = getSelectValues($refs.{{ $attributes['name'] }});
		     @this.set('{{ $attributes['wire:model'] }}', values);
             console.log(values);
	  },
	  false,
	);
    function getSelectValues(select) {
	  var result = [];
      var result = select.value.split(',');
     // result.push(select.value);
	  return result;
	}
   }
">
    <div class="mb-3">
        <label for="{{ $attributes['name'] }}" class="form-label">{{ $attributes['label'] }}</label>
        <input class="form-control" id="{{ $attributes['name'] }}" wire-model="{{ $attributes['wire:model'] }}" x-ref="{{ $attributes['name'] }}"  type="text" />
    </div>
</div>