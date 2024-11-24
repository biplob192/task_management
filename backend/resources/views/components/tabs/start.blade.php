@props([
    'title' => null,
    'desc' => null
])
<div class="card">
    <div class="card-header">
        @if($title)
        <h4 class="card-title">{{ $title }}</h4>
        @endif

        @if($desc)
        <p class="card-title-desc">{{ $desc }}</p>
        @endif
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>