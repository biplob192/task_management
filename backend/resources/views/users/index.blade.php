@extends('layouts.master_layout')

@section('page-title')
    {{ __('Manage Users') }}
@endsection

@push('header')
    <style>
        .loading-modal {
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url("../../loading.gif") 50% 40% no-repeat;
        }
    </style>
@endpush


@section('header')
    <x-common.header title="{{ __('Manage Users') }}">
        <li class="breadcrumb-item">
            <a href="javascript: void(0);">{{ __('User Management') }}</a>
        </li>
        <li class="breadcrumb-item active">{{ __('Users') }}</li>
    </x-common.header>
@endsection

@section('content')
    <x-action-box>
        <x-slot name="left">
            <button type="button" class="btn waves-effect btn-primary" onclick="createNewButtonClicked()">
                <i class="fa fa-plus me-2"></i> {{ __('New User') }}
            </button>
            @include('users.includes._add_update_user')
        </x-slot>

        <x-slot name="right">
            <div class="d-flex justify-content-between">
                <!-- Pagination Control -->
                <x-form.select id="perPage" onchange="changePerPage(this.value)">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}> 5 </option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}> 10 </option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}> 50 </option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}> 100 </option>
                </x-form.select>

                <div class="ms-2">
                    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvasFilter" aria-controls="offcanvasFilter">
                        <i class="fa fa-filter pe-2"></i> {{ __('Search') }}
                    </button>
                    <x-offcanvas id="offcanvasFilter" size="sm" title="{{ __('Search') }}">
                        <form method="GET" action="{{ route('users.index') }}">
                            <x-form.input id="search_email" name='email' value="{{ old('email', $specialSearch['email'] ?? '') }}" placeholder="{{ __('Search by Email') }}" />
                            <x-form.input id="search_phone" name='phone' value="{{ old('phone', $specialSearch['phone'] ?? '') }}" placeholder="{{ __('Search by Phone') }}" />
                            <x-form.input id="search" name='search' value="{{ old('search', $search ?? '') }}" placeholder="{{ __('Search Everywhere...') }}" />
                            <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            <button type="button" id="reset" name='reset' value="1" class="btn btn-link" onclick="window.location='{{ route('users.index') }}';">{{ __('Reset') }}</button>
                        </form>
                    </x-offcanvas>
                </div>
            </div>
        </x-slot>
    </x-action-box>

    <x-table.table>
        <x-slot name="head">
            <tr>
                <x-table.th>{{ __('#ID') }}</x-table.th>
                <x-table.th>{{ __('Name') }}</x-table.th>
                <x-table.th>{{ __('Email') }}</x-table.th>
                <x-table.th>{{ __('Phone') }}</x-table.th>
                <x-table.th>{{ __('Action') }}</x-table.th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @forelse($users['data'] as $user)
                <tr data-record-id="{{ $user->id }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="editButtonClicked(event)" data-id="{{ $user->id }}"> <i class="fa fa-edit fa-color-primary"></i> </button>
                        <a class="btn btn-primary btn-sm" onclick="deleteButtonClicked({{ $user->id }}, '{{ route('users.ajaxDestroy', $user->id) }}')" id="deleteUser"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Records Found!</td>
                </tr>
            @endforelse
        </x-slot>
    </x-table.table>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            {{ $users['data']->appends(['perPage' => $perPage])->links() }}
        </div>
    </div>

    <!-- Loader Modal -->
    <div class="loading-modal" id="loading-modal"></div>
    <x-notify />
@endsection

@push('footer')
    @include('users.includes._user_page_script')
@endpush
