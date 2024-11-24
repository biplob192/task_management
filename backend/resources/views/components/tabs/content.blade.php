@props([
    'active' => false
])

@php
    $cls = 'tab-pane';
    if ($active) {
        $cls .= ' active';
    }
@endphp

<div {{ $attributes->merge(['class' => $cls ]) }}>
    <p class="mb-0">
        {{ $slot }}
    </p>
</div>