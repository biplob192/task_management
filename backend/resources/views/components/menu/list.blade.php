@props([
'icon' => '',
'text' => '',
'key' => strtolower($text)
])

<li {{ $attributes }}>
    <a style="cursor: pointer;" class="has-arrow">
        @if($icon)
            <i data-feather="{{ $icon }}"></i>
        @endif
        <span data-key="t-{{ $key }}">{{ $text }}</span>
    </a>
    <ul class="sub-menu" aria-expanded="false">
        {{$slot}}
    </ul>
</li>
