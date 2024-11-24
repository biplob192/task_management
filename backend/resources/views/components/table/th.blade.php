@props([
    'sortable' => null,
    'direction' => null,
])

@php
$th_class = '';
if ( $sortable) {
    $th_class .= "sorting";
}

if ($direction == 'asc') {
    $th_class .= " sorting_asc";
}

if ($direction == 'desc') {
    $th_class .= " sorting_desc";
}
@endphp

<th {{ $attributes->merge(['class' => $th_class]) }}>
    {{ $slot }}
</th>