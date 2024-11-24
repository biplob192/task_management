<script>
    // Hide the loader when the page has fully loaded
    window.onload = function() {
        document.getElementById('loading-modal').style.display = 'none';
    };


    // Handle perPage records
    function changePerPage(perPage) {
        // Show the loader
        document.getElementById('loading-modal').style.display = 'block';

        const url = new URL(window.location.href);
        url.searchParams.set('perPage', perPage);

        // Redirect with the new perPage value
        window.location.href = url.toString();
    }


    // Handle create new button clicked
    function createNewButtonClicked() {
        setupAddUpdateUserModal('create');

        // Dispatch the event to show the modal
        window.dispatchEvent(new CustomEvent('openNewUserModal'));
    }


    // Handle edit button clicked
    function editButtonClicked(event) {
        // Collect the target ID
        const button = event.currentTarget;
        recordId = button.getAttribute('data-id');

        if (recordId) {
            const updateUrl = '{{ route('users.update', ':id') }}'.replace(':id', recordId);
            const ajaxShowUrl = '{{ route('users.ajaxShow', ':id') }}'.replace(':id', recordId);

            // Setup the form/modal
            setupAddUpdateUserModal('edit', updateUrl);

            // Dispatch the event with callback
            window.dispatchEvent(new CustomEvent('editNotification', {
                detail: {
                    ajaxShowUrl,
                    callback: confirmedEditRecord,
                }
            }));
        } else {
            console.error('ID is required for updating.');
        }
    }


    // Handle delete button clicked
    function deleteButtonClicked(recordId, deleteRoute) {
        window.dispatchEvent(new CustomEvent('deleteNotification', {
            detail: {
                recordId,
                deleteRoute
            }
        }));
    }


    // Callback on edit confirmation
    function confirmedEditRecord(details) {
        const form = document.getElementById('add_update_user');
        const {
            name,
            email,
            phone
        } = details.responseData;

        // Assign values in the same line
        form.querySelector('#name').value = name;
        form.querySelector('#email').value = email;
        form.querySelector('#phone').value = phone;

        // Dispatch the event to show the modal
        window.dispatchEvent(new CustomEvent('openNewUserModal'));
    }


    // Setup the modal according to operation (edit/create)
    function setupAddUpdateUserModal(operation, updateUrl = null) {
        // Collect modal/form informations
        const form = document.getElementById('add_update_user');
        const modalTitle = document.getElementById('my-modal-title');
        const editElements = form.querySelectorAll('.edit_user');
        const createElements = form.querySelectorAll('.create_user');
        let methodInput = form.querySelector('input[name="_method"]');

        if (operation === 'create') {
            // Set form action and reset all the inputs
            const storeUrl = '{{ route('users.store') }}';
            form.setAttribute('action', storeUrl);
            form.reset();

            // Change the modal title and hide extra inputs
            modalTitle.textContent = 'Create New User';
            editElements.forEach(el => el.style.display = 'none');
            createElements.forEach(el => el.style.display = 'block');

            // Remove method input if it exists
            if (methodInput) {
                form.removeChild(methodInput);
            }
        } else if (operation === 'edit' && updateUrl) {
            // Set form action
            form.setAttribute('action', updateUrl);

            // Change the modal title and hide extra inputs
            modalTitle.textContent = 'Edit User';
            editElements.forEach(el => el.style.display = 'block');
            createElements.forEach(el => el.style.display = 'block');

            // Add method input if not exists
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.setAttribute('type', 'hidden');
                methodInput.setAttribute('name', '_method');
                methodInput.setAttribute('value', 'PUT');
                form.appendChild(methodInput);
            } else {
                methodInput.setAttribute('value', 'PUT');
            }
        }
    }
</script>
