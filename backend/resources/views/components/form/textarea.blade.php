@props([
    'label' => '',
    'error' => false
])

<div class="mb-3">
    <label class="form-label" for="{{ $attributes['id'] ?? null }}">{{ $label }}</label>
    <textarea 
        {{ $attributes->merge(['class' => 'form-control']) }}
    ></textarea>
    @if($error)
    <div class="invalid-feedback text-danger">
        {{ $error }}
    </div>
    @endif
</div>