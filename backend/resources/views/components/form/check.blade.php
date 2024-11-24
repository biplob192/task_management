@props([
    'required' =>'',
    'label' => '',
    'error' => false,
    'helpText' => null,
])
<div class="form-group mb-3">
    <div class="form-check">
        <input type="checkbox" {{ $attributes->merge(['class' => 'form-check-input']) }}>
        <label class="form-check-label {{ $required }}" for="{{ $attributes->get('id') }}">{{ $label }}</label>
        @if($error)
        <div class="invalid-feedback text-danger">
            {{ $error }}
        </div>
        @endif

        @if($helpText)
        <div class="text-muted fw-lighter fst-italic" style="font-size: .9em">
            {{ $helpText }}
        </div>
        @endif

    </div>
</div>
