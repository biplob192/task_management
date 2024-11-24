<div
    wire:ignore
    x-data
    x-init="
        () => {
            const post = FilePond.create($refs.{{ $attributes->get('ref') ?? 'input' }});
            post.setOptions({
                allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
                server: {
                    process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                    },
                },
                allowImagePreview: {{ $attributes->has('allowImagePreview') ? 'true' : 'false' }},
                imagePreviewMaxHeight: {{ $attributes->has('imagePreviewMaxHeight') ? $attributes->get('imagePreviewMaxHeight') : '256' }},
                allowFileTypeValidation: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
                allowImageValidateSize: {{ $attributes->has('allowImageValidateSize') ? 'true' : 'false' }},
                acceptedFileTypes: {!! $attributes->get('acceptedFileTypes') ?? 'null' !!},
                allowFileSizeValidation: {{ $attributes->has('allowFileSizeValidation') ? 'true' : 'false' }},
                maxFileSize: {!! $attributes->has('maxFileSize') ? "'".$attributes->get('maxFileSize')."'" : 'null' !!},
                
            });
        }
    "
>
    <input type="file" x-ref="{{ $attributes->get('ref') ?? 'input' }}" />
</div>
@once
@push('header')
<!-- filepond css --->
<link href="{{ asset('assets/libs/filepond/css/filepond.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/filepond/css/filepond-plugin-image-preview.css')}}" rel="stylesheet">
@endpush

@push('footer')
<!-- filepond css --->
<script src="{{ asset('assets/libs/filepond/js/filepond-plugin-file-validate-type.js')}}"></script>
<script src="{{ asset('assets/libs/filepond/js/filepond-plugin-file-validate-size.js')}}"></script>
<script src="{{ asset('assets/libs/filepond/js/filepond-plugin-image-validate-size.js')}}"></script>
<script src="{{ asset('assets/libs/filepond/js/filepond-plugin-image-preview.js')}}"></script>
<script src="{{ asset('assets/libs/filepond/js/filepond.js')}}"></script>
<script>
	FilePond.registerPlugin(FilePondPluginFileValidateType);
	FilePond.registerPlugin(FilePondPluginFileValidateSize);
	FilePond.registerPlugin(FilePondPluginImagePreview);
    FilePond.registerPlugin(FilePondPluginImageValidateSize);
</script>
@endpush
@endonce