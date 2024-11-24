@once

@push('header')
<link rel="stylesheet" href="{{ asset('assets/notify/css/simple-notify.min.css') }}">
@endpush

@push('footer')
<script src="{{ asset('assets/notify/js/simple-notify.min.js') }}"></script>
<script>
    window.addEventListener('notify', function(event) {
        const detail = event.detail[0];

        new Notify({
            status: detail.type,
            title: detail.title,
            text: detail.message,
            effect:'slide',
            showIcon: true,
            position:'right top',
            autoclose: true,
            autotimeout: 5000
        });
    });
</script>
@endpush
@endonce
