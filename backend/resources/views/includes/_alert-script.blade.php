<script>
    const MESSAGE_TYPE = {
        'success': {
            'type': 'success',
            'icon': 'check-all'
        },
        'error': {
            'type': 'danger',
            'icon': 'block-helper'
        },
        'warning': {
            'type': 'warning',
            'icon': 'alert-outline'
        },
        'info': {
            'type': 'info',
            'icon': 'alert-circle-outline'
        },
    };


    window.addEventListener('alert', event => {
        const details = event.detail[0];

        const type = MESSAGE_TYPE[details.type];
        let messageContent = document.getElementById('message-content');
        messageContent.insertAdjacentHTML('beforeend', `
            <div class="alert alert-${type.type} alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-${type.icon} label-icon"></i><strong>Success</strong> - ${details.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
    });


    window.addEventListener('show-delete-notification', event => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                window.Livewire.dispatch('deleteConfirm');
            } else if (result.dismiss) {
                window.Livewire.dispatch('deleteCancel');
            }
        })
    });


    window.addEventListener('show-reminder-notification', event => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You already sent reminder",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, send it'
        }).then((result) => {
            if (result.isConfirmed) {
                livewire.emit('SendConfirm');
            } else if (result.dismiss) {
                livewire.emit('SendCancel');
            }
        })
    });


    window.addEventListener('deleteNotification', event => {
        const {
            recordId,
            deleteRoute
        } = event.detail;

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, delete it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Make a DELETE request using jQuery's AJAX
                $.ajax({
                    url: deleteRoute,
                    type: 'POST',
                    data: {
                        _method: 'delete',
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', 'The record has been deleted.', 'success');

                        // Use the recordId to find the row and remove it
                        $(`tr[data-record-id="${recordId}"]`).remove();

                        // $(`a[id="deleteUrl"][data-id="${recordId}"]`).closest('tr').remove();
                    },
                    error: function(error) {
                        const errorMessage = error.responseJSON.message;

                        // Show error message
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Swal.fire('Cancelled', 'The record is safe :)', 'error');
            }
        });
    });


    window.addEventListener('editNotification', event => {
        const {
            ajaxShowUrl,
            callback
        } = event.detail; // Extract the record ID and delete route URL


        Swal.fire({
            title: 'Are you sure?',
            text: "You want to edit this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006A4E',
            cancelButtonColor: '#fd625e',
            confirmButtonText: 'Yes, edit it'
        }).then((result) => {
            if (result.isConfirmed) {
                // Make a GET request using jQuery's AJAX
                $.ajax({
                    url: ajaxShowUrl,
                    type: 'GET',
                    success: function(response) {
                        // Call the callback function with the response details
                        if (callback && typeof callback === 'function') {
                            callback({
                                ...event.detail,
                                responseData: response.data
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire('Error!', 'Ajax call failed: ' + error.responseJSON.message, 'error');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // Swal.fire('Cancelled', 'The record is safe :)', 'error');
            }
        });
    });

    window.addEventListener('deleted', event => {
        Swal.fire({
            title: 'Deleted',
            text: event.detail.message,
            icon: 'success',
            // confirmButtonColor: '#C9AC60'
        })
    });

    $(document).ready(function() {
        // Enable Bootstrap tooltips on page load
        $('[data-bs-toggle="tooltip"]').tooltip();
        // Ensure Livewire updates re-instantiate tooltips
        if (typeof window.Livewire !== 'undefined') {
            window.Livewire.hook('message.processed', (message, component) => {
                $('[data-bs-toggle="tooltip"]').tooltip('dispose').tooltip();
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('alert-success');
        if (successMessage) {
            // Add a fade-out effect if desired
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease';
                successMessage.style.opacity = 0;
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 500);
            }, 5000);
        }
    });
</script>
