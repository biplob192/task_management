@props([
'icon' => '',
'text' => '',
'link' => '#',
'key' => strtolower($text)
])

<li {{ $attributes }}>
    <a href="{{ $link }}" {{ $attributes }}>
        @if($icon)
            <i data-feather="{{ $icon }}"></i>
        @endif
        <span data-key="t-{{ $key }}">{{ $text }}</span>
    </a>
</li>
