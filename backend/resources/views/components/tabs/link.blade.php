@props([
    'icon' => null,
    'href' => '',
    'active' => false
])

<li class="nav-item">
    <a class="nav-link {{ $active ? 'active' : null}}" data-bs-toggle="tab" href="#{{ $href }}" role="tab" aria-selected="true">
        <span class="d-block d-sm-none">
            @if($icon)
            <i class="mdi mdi-{{ $icon }} font-size-16 align-middle1 me-1"></i>
            @endif
        </span>
        <span class="d-none d-sm-block">{{ $slot }}</span> 
    </a>
</li>