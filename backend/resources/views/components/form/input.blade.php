@props([
    'required' =>'',
    'label' => '',
    'error' => false
])

<div class="mb-3 {{ $attributes['class'] ?? null }}">
    @if($label)
    <label class="form-label for="{{ $attributes['id'] ?? null }}">{{ $label }}</label>
    @endif
    <input
        {{ $attributes->merge(['type' => 'text', 'class' => 'form-control']) }}
        {{ $required }}
    >
    @if($error)
    <div class="invalid-feedback d-block text-danger">
        {{ $error }}
    </div>
    @endif
</div>
