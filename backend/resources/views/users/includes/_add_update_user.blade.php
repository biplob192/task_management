<form id="add_update_user" method="POST" action="{{ route('users.store') }}">
    @csrf
    <x-modal :has-button="false" modal-id="modalNewUser" on="openNewUserModal" title="">

        {{-- <x-form.input name="phone" required="required" type="text" id="phone" class="edit_user" label="{{ __('Phone') }}" placeholder="{{ __('Place Phone') }}" :error="$errors->first('phone')" /> --}}
        {{-- <x-form.input name="password" required="required" type="password" id="password" class="create_user" label="{{ __('Password') }}" placeholder="{{ __('Password') }}" :error="$errors->first('password')" /> --}}

        <x-form.input name="name" required="required" type="text" id="name" class="" label="{{ __('Name') }}" placeholder="{{ __('Place Name') }}" :error="$errors->first('name')" />
        <x-form.input name="email" required="required" type="text" id="email" class="" label="{{ __('Email') }}" placeholder="{{ __('Place Email') }}" :error="$errors->first('email')" />
        <x-form.input name="phone" required="required" type="text" id="phone" class="" label="{{ __('Phone') }}" placeholder="{{ __('Place Phone') }}" :error="$errors->first('phone')" />
        <x-form.input name="password" required="required" type="password" id="password" class="" label="{{ __('Password') }}" placeholder="{{ __('Password') }}" :error="$errors->first('password')" />
        <x-form.input name="password_confirmation" required="required" type="password" id="password_confirmation" class="" label="{{ __('Confirm Password') }}" placeholder="{{ __('Confirm Password') }}" :error="$errors->first('password_confirmation')" />

        <x-slot name="footer">
            <button type="submit" class="btn btn-primary">
                <div>
                    {{-- <i class="fas fa-spin fa-spinner mr-2"></i> --}}
                </div>
                {{ __('Save') }}
            </button>
        </x-slot>
    </x-modal>
</form>
