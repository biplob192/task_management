@props([
    'hasButton' => true,
    'buttonText' => '',
    'hasFooter' => true,
    'hasForm' => true,
    "modalId" => '',
    'hasHeader' => true,
    'title' => '',
    'on' => '',
    'onHide' => 'hideModal',
    'size' => 'md',
    'modalContentClass' => '',
    'closeFromServer' => false,
    'backDrop' => true
])

@if($hasButton)
<button type="button" {{ $attributes->merge(['class' => 'btn btn-secondary']) }}>{{ $buttonText }}</button>
@endif

<div id="{{ $modalId }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" @if($backDrop) data-bs-backdrop="static" data-bs-keyboard="false" @endif >
    <div class="modal-dialog modal-{{ $size }}" role="document">
        <div class="modal-content {{ $modalContentClass }}">
            @if ($hasHeader)
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">{{ $title }}</h5>
                @if($closeFromServer)
                    <button type="button" class="btn-close" wire:click="hideViewModal" aria-label="Close"></button>
                @else
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            @endif
            <div class="modal-body text-left">
                {{ $slot }}
            </div>
            @if($hasFooter)
                <div class="modal-footer d-flex justify-content-between">
                    {{ $footer ?? null }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('footer')
<script type="text/javascript">
    window.addEventListener('{{ $on }}', (event) => {
        $('#{{ $modalId }}').modal('show');
    } );
    window.addEventListener('{{ $onHide }}', (event) => {
          $('#{{ $modalId }}').modal('hide');
      } );
</script>
@endpush
