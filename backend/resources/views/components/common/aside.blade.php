<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>
                <x-menu.item text="{{ __('Dashboard') }}" link="{{ route('auth.dashboard') }}" icon="home" class="{{ request()->is('dashboard*') ? 'active' : '' }} " />
                <x-menu.item text="{{ __('Manage Users') }}" link="{{ route('users.index') }}" icon="users" class="{{ request()->is('users') ? 'active' : '' }}" />
                <x-menu.item text="{{ __('Developer') }}" link="https://biplob192.github.io/" icon="code" class="" target="_blank" />


                {{-- <x-menu.list text="{{ __('Manage Urls') }}" icon="link" class="{{ request()->is('urls*') ? 'mm-active' : '' }}">
                    <x-menu.item text="{{ __('All Urls') }}" link="{{ route('urls.index') }}" class="{{ request()->is('urls') ? 'active' : '' }}" />
                    <x-menu.item text="{{ __('Create New Url') }}" link="{{ route('urls.create') }}" class="{{ request()->is('urls/create') ? 'active' : '' }}" />
                </x-menu.list> --}}
            </ul>
        </div>
    </div>
</div>
