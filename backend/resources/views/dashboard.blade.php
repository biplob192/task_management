@extends('layouts.master_layout')

@section('page-title')
    {{ __('Manage Urls') }}
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
    <x-common.header title="{{ __('Dashboard') }}">
        {{-- <li class="breadcrumb-item">
            <a href="javascript: void(0);">{{ __('Url Management') }}</a>
        </li>
        <li class="breadcrumb-item active">{{ __('Urls') }}</li> --}}
    </x-common.header>
@endsection

@section('content')
@endsection

@push('footer')
    <script></script>
@endpush
