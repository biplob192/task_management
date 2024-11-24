@php
    $controlClass = isset($attributes['error']) ? 'form-control is-invalid' : 'form-control';
    $modelName = $attributes->wire('model')->value();
@endphp

@if(!$attributes['inline'])
<div 
    wire:ignore 
    x-cloak 
    style="padding-left: 0 !important" 
    class="form-group" 
    x-data="{values: @this.get('{{ $modelName }}')}" 
    x-init="$($refs.select).select2({
        placeholder: '{{ $placeholder }}',
        search: true,
        multiple: '{{ $multiple }}' 
    }).on('select2:select', (e)=>{
        values =  $($refs.select).val();
        @this.set('{{ $modelName }}', values);
    }).on('select2:unselect', (e) =>{
        values =  $($refs.select).val();
        @this.set('{{ $modelName }}', values);
    })" 
>
    @if($attributes['label'])
    <label for="{{ $attributes['id'] ?? null }}" class="form-label">{{ $attributes['label'] ?? null }}
        @if ($attributes['help-text'])
        <span class="hint--top" aria-label="This is helptext">
            <i class="fa fa-question-circle"></i>
        </span>
        @endif
    </label>
    @endif
    <select 
        {{ $attributes->whereDoesntStartWith('wire:model') }} 
        {{ $multiple ? 'multiple' : null }}
        x-ref="select" 
        {{ $attributes->merge(['class'=> $controlClass]) }}
    >
        {{ $slot }}
    </select>
    @if($attributes['error'])
    <small class="form-text text-danger">
        <i class="fa fa-exclamation-circle pr-2"></i>{{ $attributes['error'] }}
    </small>
    @endif
</div>

@else

<div 
    wire:ignore 
    x-cloak 
    class="form-group row" 
    x-data="{values: @this.get('{{ $modelName }}')}" 
    x-init="$($refs.select).select2({
        placeholder: '{{ $placeholder }}',
        search: true,
        multiple: '{{ $multiple }}' 
    }).on('select2:select', (e)=>{
        values =  $($refs.select).val();
        @this.set('{{ $modelName }}', values);
    }).on('select2:unselect', (e) =>{
        values =  $($refs.select).val();
        @this.set('{{ $modelName }}', values);
    })" 
>
    @if($attributes['label'])
    <label for="{{ $attributes['id'] ?? null }}"
        class="{{ $attributes['left-col'] ?? 'col-lg-2 col-md-2 col-sm-12 col-xs-12' }} col-form-label">{{ $attributes['label'] ?? null }}</label>
    @endif

    <div class="{{ $attributes['right-col'] ?? 'col-lg-10 col-md-10 col-sm-12 col-xs-12' }}">
        <select 
            {{ $attributes->whereDoesntStartWith('wire:model') }} 
            {{ $multiple ? 'multiple' : null }} 
            x-ref="select" 
            {{ $attributes->merge(['class'=> $controlClass]) }} 
        >
            {{ $slot }}
        </select>
        @if($attributes['error'])
        <small class="form-text text-danger"><i
                class="fa fa-exclamation-circle pr-2"></i>{{ $attributes['error'] }}</small>
        @endif

        @if ($attributes['help-text'])
        <small class="form-text text-muted">{{ $attributes['help-text'] }}</small>
        @endif
    </div>

</div>

@endif
@once
@push('header')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('footer')
<!-- Select2 -->
<script src="{{ asset('assets/libs/select2/js/select2.full.min.js')}}"></script>
@endpush
@endonce