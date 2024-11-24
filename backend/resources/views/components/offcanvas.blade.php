@props([
    'id' => '',
    'title' => '',
    'position'=> 'end',
    'size' => 'md',
])

<div wire:ignore>
    <div 
        class="offcanvas offcanvas-{{ $size }} offcanvas-{{ $position }}" 
        tabindex="-1" 
        id="{{ $id }}" 
        aria-labelledby="{{ $id }}Label"
        >
    
        @if($title)
        <div class="offcanvas-header bg-primary" style="margin-left: -1px;">
          <h5 class="offcanvas-title text-white" id="offcanvasExampleLabel">{{ $title }}</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        @endif
    
        <div class="offcanvas-body py-4">
            {{ $slot }}
        </div>
    </div>
</div>

@once
    @push('header')
    <style>
        .offcanvas-sm {
            width:300px !important;
        }
        .offcanvas-md {
            width:400px !important;
        }

        .offcanvas-lg {
            width:600px !important;
        }
    </style>
    @endpush

    @push('footer')
    <script>
        const offCanvasId = document.getElementById('{{ $id }}');
        
        window.addEventListener('hideOffCanvas', (event) => {
            bootstrap.Offcanvas.getInstance(offCanvasId).hide();
        });
    </script>
    @endpush
@endonce