@props([
    'hasFiles' => false
])

<form 
    {!! $hasFiles ? 'enctype="multipart/form-data"' : '' !!} 
    {{ $attributes->merge(['class' => 'needs-validation']) }}>
    
    @csrf

    {{ $slot }}
</form>
