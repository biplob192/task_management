@props([
    'confirmId'     => '',
    'title'       => '',
    'confirmText' => 'Yes',
    'denyText'    => 'No'
])

<div id="{{ $confirmId }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="justify-content: center">
                <h5 class="modal-title" id="{{ $confirmId }}-title">{{ $title }}</h5>
            </div>
            <div class="modal-body text-center">
                {{ $slot }}
            </div>
            <div class="modal-footer justify-content-center">
                <button {{ $attributes }} class="btn btn-success btn-md">{{ $confirmText }}</button>
                <button class="btn btn-danger btn-md" data-bs-dismiss="modal">{{ $denyText }}</button>
            </div>
        </div>
    </div>
</div>

@push('footer')
<script>
window.addEventListener('showConfirmDialog', (event) => {
    $('#{{ $confirmId }}').modal('show');
});

window.addEventListener('hideConfirmDialog', (event) => {
    $('#{{ $confirmId }}').modal('hide');
});
</script>
@endpush